import sys
import os
import json
from concurrent.futures import ThreadPoolExecutor

import torch
from sahi import AutoDetectionModel
from sahi.predict import get_sliced_prediction
from TrainingRunner import get_model

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

        # Sliding window parameters for SAHI
        self.slice_size = 512
        self.overlap_ratio = 0.5

    def run(self):
        # Load the custom model
        model = get_model(
            self.num_classes,
            weights='DEFAULT',
            max_size=512,
            min_size=512,
        )
        model.load_state_dict(torch.load(self.checkpoint_path))

        device = torch.accelerator.current_accelerator() if torch.accelerator.is_available() else torch.device('cpu')

        # Create SAHI detection model from pre-loaded model
        # Category mapping: SAHI expects string keys for category IDs
        category_mapping = {str(i): str(i) for i in range(1, self.num_classes + 1)}

        detection_model = AutoDetectionModel.from_pretrained(
            model_type='torchvision',
            model=model,
            device=device,
            confidence_threshold=0.05,
            category_mapping=category_mapping,
            image_size=512,
        )

        executor = ThreadPoolExecutor(max_workers=self.max_workers)

        total_images = len(self.images)
        for index, (image_id, image_path) in enumerate(self.images.items()):
            print('Image {} of {} (#{})'.format(index + 1, total_images, image_id))

            # Perform sliced prediction using SAHI
            result = get_sliced_prediction(
                image_path,
                detection_model,
                slice_height=self.slice_size,
                slice_width=self.slice_size,
                overlap_height_ratio=self.overlap_ratio,
                overlap_width_ratio=self.overlap_ratio,
                verbose=0,
            )

            executor.submit(self.process_result, image_id, result)

        # Wait for pending jobs of the postprocessing.
        executor.shutdown(True)

    def process_result(self, image_id, result):
        points = []

        for pred in result.object_prediction_list:
            bbox = pred.bbox  # SAHI BoundingBox object
            x1, y1, x2, y2 = bbox.minx, bbox.miny, bbox.maxx, bbox.maxy
            score = pred.score.value

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
