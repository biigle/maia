import sys
import os
import json
from Config import InferenceConfig
from Dataset import InferenceDataset
import mrcnn.model as modellib
from concurrent.futures import ThreadPoolExecutor, wait, FIRST_COMPLETED

class InferenceRunner(object):

    def __init__(self, params, trainset):
        # Path to the directory to store temporary files.
        self.tmp_dir = params['tmp_dir']
        # Storage path of the trained Mask R-CNN model.
        self.model_dir = params['model_dir']
        # Path to the trained weights to use for inference.
        self.model_path = params['model_path']
        # We need at least one worker thread here.
        self.max_workers = max(params['max_workers'], 1)

        self.config = InferenceConfig(params, trainset)
        # Cast image ID to int.
        images = {int(k): v for k, v in params['images'].items()}
        classes = {int(k): v for k, v in trainset['classes'].items()}
        self.dataset = InferenceDataset(images, classes)

    def run(self):
        self.config.display()
        self.dataset.prepare()

        model = modellib.MaskRCNN(mode="inference", config=self.config, model_dir=self.model_dir)
        model.load_weights(self.model_path, by_name=True)

        executor = ThreadPoolExecutor(max_workers=self.max_workers)

        """
        The following is a queue-like implementation where a number of worker threads
        load the images so the GPU is constantly fed with data. New jobs are submitted
        only when another job finished so there is only a fixed nuber of loaded images
        held in memory and waiting to be processed.
        """

        # Current image index of images that were pushed to the "queue".
        pushed_index = 0
        # Current image index of images that have been popped from the "queue" and
        # that have been processed.
        popped_index = 0
        total_images = len(self.dataset.image_info)

        jobs = set()

        # Initially kick off as many threads as were configured.
        for i in range(min(self.max_workers, total_images)):
            jobs.add(executor.submit(self.load_image, pushed_index))
            pushed_index += 1

        while popped_index < total_images:
            # Wait for the first job that is finished.
            done, _ = wait(jobs, return_when=FIRST_COMPLETED)
            popped_index += 1
            job = done.pop()
            # Remove the job from the list so it is not returned by wait() again.
            jobs.remove(job)
            image, info =  job.result()
            if image is not False:
                print('Image {} of {} (#{})'.format(popped_index, total_images, info['id']))
                results = model.detect([image])
                # Postprocess ansynchronously but independent of the "queue".
                executor.submit(self.process_result, info['id'], results[0])

            # If there are still images left, push a new job to the "queue".
            if pushed_index < total_images:
                jobs.add(executor.submit(self.load_image, pushed_index))
                pushed_index += 1

        # Wait for pending jobs (of the postprocessing).
        executor.shutdown(True)

    def load_image(self, i):
        info = self.dataset.image_info[i]

        try:
            image = self.dataset.load_image(i)
        except (IOError, OSError, ValueError) as e:
            print('Error while loading image #{}! Skipping...'.format(info['id']))
            return False, False

        return image, info

    def process_result(self, image_id, result):
        points = []
        for roi, score, class_id in zip(result['rois'], result['scores'], result['class_ids']):
            # ROIs are stored as (y1, x1, y2, x2).
            y = min(roi[0], roi[2])
            x = min(roi[1], roi[3])
            h = abs(roi[0] - roi[2])
            w = abs(roi[1] - roi[3])
            rx = round(w / 2)
            ry = round(h / 2)
            r = max(rx, ry)
            points.append([int(x + rx), int(y + ry), int(r), float(score), int(class_id)])

        path = os.path.join(self.tmp_dir, '{}.json'.format(image_id))
        with open(path, 'w') as outfile:
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
