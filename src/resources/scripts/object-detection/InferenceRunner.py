import sys
import os
import json
from concurrent.futures import ThreadPoolExecutor

import torch
from PIL import Image
import albumentations as A
import numpy as np
from torch_utils import get_model
from albumentations.pytorch import ToTensorV2

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

        self.transforms = A.Compose([
            A.ToFloat(),
            ToTensorV2(),
        ])

    def run(self):
        model = get_model(self.num_classes)
        model.load_state_dict(torch.load(self.checkpoint_path))
        model.eval()

        device = torch.accelerator.current_accelerator() if torch.accelerator.is_available() else torch.device('cpu')
        model.to(device)

        executor = ThreadPoolExecutor(max_workers=self.max_workers)

        total_images = len(self.images)
        with torch.inference_mode():
            for index, (image_id, image_path) in enumerate(self.images.items()):
                print('Image {} of {} (#{})'.format(index + 1, total_images, image_id))
                image = np.array(Image.open(image_path).convert('RGB'))
                image = self.transforms(image=image)['image'].to(device)
                result = model([image])[0]

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
