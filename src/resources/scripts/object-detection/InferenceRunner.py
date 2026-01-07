import sys
import os
import json
from concurrent.futures import ThreadPoolExecutor

import torch
from PIL import Image
import numpy as np
from TrainingRunner import get_model
from torchvision.transforms import functional as F

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

    def run(self):
        model = get_model(
            self.num_classes,
            weights=None,
            # # The original config had rpn_nms_thresh=0.7 and box_nms_thresh=0.5.
            # # Lowered, because of many overlapping boxes for the same objects in tests.
            # rpn_nms_thresh=0.2,
            # box_nms_thresh=0.2,
            # Increase default max_size of 1333.
            # max_size=8192,
            # # Use same min_size enforced by BIIGLE.
            # min_size=512,
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
                image_tensor = F.to_tensor(image).unsqueeze(0).to(device)
                result = model(image_tensor)[0]

                executor.submit(self.process_result, image_id, result)

        # Wait for pending jobs of the postprocessing.
        executor.shutdown(True)

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
