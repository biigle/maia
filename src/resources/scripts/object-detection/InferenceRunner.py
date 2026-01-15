import sys
import os
import json
from concurrent.futures import ThreadPoolExecutor

import torch
from PIL import Image
import numpy as np
from TrainingRunner import get_model
from torchvision.transforms import functional as F
from torchvision.ops import nms

class InferenceRunner(object):

    def __init__(self, params):
        # Path to the directory to store temporary files.
        self.tmp_dir = params['tmp_dir']
        # Path to the trained model to use for inference.
        self.checkpoint_path = params['checkpoint_path']
        # We need at least one worker thread here.
        self.max_workers = max(int(params['max_workers']), 1)

        self.num_classes = params['num_classes']

        self.images = {k: v for k, v in params['images'].items()}

        # Sliding window parameters
        self.window_size = 512
        self.step_size = 256  # 50% overlap
        self.nms_iou_threshold = 0.5  # IoU threshold for merging overlapping detections

    def generate_windows(self, image_width, image_height):
        """Generate sliding window positions with overlap."""
        windows = []

        # Generate window positions
        y_positions = list(range(0, image_height - self.window_size + 1, self.step_size))
        x_positions = list(range(0, image_width - self.window_size + 1, self.step_size))

        # Add final positions to cover the entire image
        if y_positions[-1] + self.window_size < image_height:
            y_positions.append(image_height - self.window_size)
        if x_positions[-1] + self.window_size < image_width:
            x_positions.append(image_width - self.window_size)

        # Handle edge case where image is smaller than window size
        if image_height < self.window_size:
            y_positions = [0]
        if image_width < self.window_size:
            x_positions = [0]

        for y in y_positions:
            for x in x_positions:
                # Crop dimensions (handle images smaller than window_size)
                crop_width = min(self.window_size, image_width - x)
                crop_height = min(self.window_size, image_height - y)
                windows.append((x, y, crop_width, crop_height))

        return windows

    def run(self):
        model = get_model(
            self.num_classes,
            weights='DEFAULT',
            # Set min/max size to match training
            max_size=512,
            min_size=512,
        )
        model.load_state_dict(torch.load(self.checkpoint_path))

        device = torch.accelerator.current_accelerator() if torch.accelerator.is_available() else torch.device('cpu')
        model.to(device)
        model.eval()

        executor = ThreadPoolExecutor(max_workers=self.max_workers)

        total_images = len(self.images)
        with torch.inference_mode():
            for index, (image_id, image_path) in enumerate(self.images.items()):
                print('Image {} of {} (#{})'.format(index + 1, total_images, image_id))
                image = Image.open(image_path).convert('RGB')

                # Perform sliding window inference
                result = self.sliding_window_inference(model, image, device)

                executor.submit(self.process_result, image_id, result)

        # Wait for pending jobs of the postprocessing.
        executor.shutdown(True)

    def sliding_window_inference(self, model, image, device):
        """Perform inference on sliding windows and merge results."""
        image_width, image_height = image.size
        windows = self.generate_windows(image_width, image_height)

        all_boxes = []
        all_scores = []
        all_labels = []

        print(f'  Processing {len(windows)} windows for {image_width}x{image_height} image')

        for window_idx, (x, y, crop_width, crop_height) in enumerate(windows):
            # Extract window
            crop = image.crop((x, y, x + crop_width, y + crop_height))

            # Convert to tensor
            crop_tensor = F.to_tensor(crop).unsqueeze(0).to(device)

            # Run inference on this window
            window_result = model(crop_tensor)[0]

            # Adjust bounding boxes to global image coordinates
            boxes = window_result['boxes'].cpu()
            scores = window_result['scores'].cpu()
            labels = window_result['labels'].cpu()

            if len(boxes) > 0:
                # Offset boxes by window position
                boxes[:, [0, 2]] += x  # x coordinates
                boxes[:, [1, 3]] += y  # y coordinates

                # Clip boxes to image boundaries
                boxes[:, [0, 2]] = torch.clamp(boxes[:, [0, 2]], 0, image_width)
                boxes[:, [1, 3]] = torch.clamp(boxes[:, [1, 3]], 0, image_height)

                all_boxes.append(boxes)
                all_scores.append(scores)
                all_labels.append(labels)

        # Merge all detections
        if len(all_boxes) == 0:
            # No detections found
            return {
                'boxes': torch.empty((0, 4)),
                'scores': torch.empty(0),
                'labels': torch.empty(0, dtype=torch.int64)
            }

        all_boxes = torch.cat(all_boxes, dim=0)
        all_scores = torch.cat(all_scores, dim=0)
        all_labels = torch.cat(all_labels, dim=0)

        print(f'  Total detections before NMS: {len(all_boxes)}')

        # Apply NMS to merge overlapping detections
        # NMS is applied per class, so we need to handle each class separately
        keep_indices = []
        unique_labels = torch.unique(all_labels)

        for label in unique_labels:
            label_mask = all_labels == label
            label_boxes = all_boxes[label_mask]
            label_scores = all_scores[label_mask]
            label_indices = torch.where(label_mask)[0]

            # Apply NMS for this class
            keep = nms(label_boxes, label_scores, self.nms_iou_threshold)
            keep_indices.append(label_indices[keep])

        if len(keep_indices) > 0:
            keep_indices = torch.cat(keep_indices)
            final_boxes = all_boxes[keep_indices]
            final_scores = all_scores[keep_indices]
            final_labels = all_labels[keep_indices]
        else:
            final_boxes = torch.empty((0, 4))
            final_scores = torch.empty(0)
            final_labels = torch.empty(0, dtype=torch.int64)

        print(f'  Final detections after NMS: {len(final_boxes)}')

        return {
            'boxes': final_boxes,
            'scores': final_scores,
            'labels': final_labels
        }

    def process_result(self, image_id, pred):
        points = []
        boxes = pred['boxes'].detach().cpu().numpy()
        for bbox, score, label in zip(boxes, pred['scores'], pred['labels']):
            x1, y1, x2, y2 = bbox
            r = round(max(x2 - x1, y2 - y1) / 2, 2)
            x = round((x1 + x2) / 2, 2)
            y = round((y1 + y2) / 2, 2)
            points.append([float(x), float(y), float(r), float(score)])

        path = os.path.join(self.tmp_dir, '{}.json'.format(image_id))
        with open(path, 'w') as outfile:
            json.dump(points, outfile)

if __name__ == '__main__':
    with open(sys.argv[1]) as f:
        params = json.load(f)

    with open(sys.argv[2]) as f:
        train_params = json.load(f)

    params['checkpoint_path'] = train_params['checkpoint_path']
    params['num_classes'] = train_params['num_classes']

    runner = InferenceRunner(params)
    runner.run()
