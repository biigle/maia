import sys
import os
import json
from Config import InferenceConfig
from Dataset import InferenceDataset
import mrcnn.model as modellib
from concurrent.futures import ThreadPoolExecutor, wait

class InferenceRunner(object):

    def __init__(self, params, trainset):
        # Path to the directory to store temporary files.
        self.tmp_dir = params['tmp_dir']
        # Estimated available GPU memory in bytes.
        self.available_bytes = params['available_bytes']
        # Storage path of the trained Mask R-CNN model.
        self.model_dir = params['model_dir']
        # Path to the trained weights to use for inference.
        self.model_path = params['model_path']

        self.max_workers = params['max_workers']
        self.config = InferenceConfig(params, trainset)

        # Cast image ID to int.
        images = {int(k): v for k, v in params['images'].items()}
        self.dataset = InferenceDataset(images)

    def run(self):
        # self.config.display()
        self.dataset.prepare()

        model = modellib.MaskRCNN(mode="inference", config=self.config, model_dir=self.model_dir)
        model.load_weights(self.model_path, by_name=True)

        executor = ThreadPoolExecutor(max_workers=self.max_workers)
        jobs = []

        image_infos = self.dataset.image_info
        total_images = len(image_infos)

        for i, info in enumerate(image_infos):
            print('Image {} of {} (#{})'.format(i + 1, total_images, info['id']))
            image = self.dataset.load_image(i)
            results = model.detect([image])
            jobs.append(executor.submit(self.process_result, info['id'], results[0]))

        wait(jobs)

    def process_result(self, image_id, result):
        points = []
        for roi, score in zip(result['rois'], result['scores']):
            x = min(roi[0], roi[2])
            y = min(roi[1], roi[3])
            w = abs(roi[0] - roi[2])
            h = abs(roi[1] - roi[3])
            rx = round(w / 2)
            ry = round(h / 2)
            r = max(rx, ry)
            points.append([int(x + rx), int(y + ry), int(r), float(score)])

        with open(os.path.join(self.tmp_dir, '{}.json'.format(image_id)), 'w') as outfile:
            json.dump(points, outfile)

with open(sys.argv[1]) as f:
    params = json.load(f)

with open(sys.argv[2]) as f:
    trainset = json.load(f)

with open(sys.argv[3]) as f:
    train_params = json.load(f)

params['model_dir'] = train_params['model_dir']
params['model_path'] = train_params['model_path']

runner = InferenceRunner(params, trainset)
runner.run()
