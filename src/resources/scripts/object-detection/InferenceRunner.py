import sys
import os
import json
from torch import cuda
from concurrent.futures import ThreadPoolExecutor
from mmdet.apis import init_detector, inference_detector

class InferenceRunner(object):

    def __init__(self, params, trainset):
        # Path to the directory to store temporary files.
        self.tmp_dir = params['tmp_dir']
        # Path to the trained model to use for inference.
        self.checkpoint_path = params['checkpoint_path']
        # Path to the MMDetection config.
        self.config_path = params['config_path']
        # We need at least one worker thread here.
        self.max_workers = max(int(params['max_workers']), 1)

        self.cfg_options = {
            'gpu_ids': [0],
        }

        self.images = {k: v for k, v in params['images'].items()}

    def run(self):
        device = 'cuda:0' if cuda.is_available() else 'cpu'
        model = init_detector(self.config_path, checkpoint=self.checkpoint_path, device=device, cfg_options=self.cfg_options)

        executor = ThreadPoolExecutor(max_workers=self.max_workers)

        total_images = len(self.images)
        for index, (image_id, image_path) in enumerate(self.images.items()):
            print('Image {} of {} (#{})'.format(index + 1, total_images, image_id))
            result = inference_detector(model, image_path)
            executor.submit(self.process_result, image_id, result.pred_instances)

        # Wait for pending jobs of the postprocessing.
        executor.shutdown(True)

    def process_result(self, image_id, pred):
        points = []
        for bbox, score, label in zip(pred.bboxes, pred.scores, pred.labels):
            x1, y1, x2, y2 = bbox.detach().cpu().numpy()
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
        trainset = json.load(f)

    with open(sys.argv[3]) as f:
        train_params = json.load(f)

    params['checkpoint_path'] = train_params['checkpoint_path']
    params['config_path'] = train_params['config_path']

    runner = InferenceRunner(params, trainset)
    runner.run()
