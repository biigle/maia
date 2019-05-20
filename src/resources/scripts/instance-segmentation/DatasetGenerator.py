import os
import sys
import json
import cv2
import numpy as np
from PIL import Image
from concurrent.futures import ThreadPoolExecutor, as_completed

class DatasetGenerator(object):
    def __init__(self, params):
        # Dict of image IDs and file paths to the images to process.
        self.images = params['images']
        # Path to the directory to store temporary files.
        self.tmp_dir = params['tmp_dir']
        # Estimated available GPU memory in bytes.
        self.available_bytes = params['available_bytes']
        # Dict of image IDs and arrays of selected training proposal coordinates.
        self.training_proposals = params['training_proposals']

        self.max_workers = params['max_workers']

        self.training_images_path = '{}/training_images'.format(self.tmp_dir)
        self.training_masks_path = '{}/training_masks'.format(self.tmp_dir)
        self.crop_dimension = 512

    def generate(self):
        self.ensure_train_masks_dirs()
        executor = ThreadPoolExecutor(max_workers=self.max_workers)
        jobs = []

        for imageId, proposals in self.training_proposals.items():
            jobs.append(executor.submit(self.process_image, imageId, proposals))

        images = []
        masks = []
        mean_pixels = []

        for job in as_completed(jobs):
            i, m, p = job.result()
            if i is not False:
                images.extend(i)
                masks.extend(m)
                mean_pixels.extend(p)

        mean_pixel = np.array(mean_pixels).mean(axis = 0).tolist()

        return {
            'training_images_path': self.training_images_path,
            'training_masks_path': self.training_masks_path,
            'training_images': images,
            'training_masks': masks,
            'mean_pixel': mean_pixel,
            'crop_dimension': self.crop_dimension,
            # For now all datasets are binary, i.e. only the classes "Background" and
            # "Interesting" are supported.
            'classes': {
                1: 'Interesting',
            }
        }

    def ensure_train_masks_dirs(self):
        if not os.path.exists(self.training_images_path):
           os.makedirs(self.training_images_path)

        if not os.path.exists(self.training_masks_path):
           os.makedirs(self.training_masks_path)

    def process_image(self, imageId, proposals):
        image = Image.open(self.images[imageId])
        try:
            image.load()
        except IOError as e:
            print('Image #{} is corrupt! Skipping...'.format(imageId))

            return False, False, False

        mask = np.zeros((image.height, image.width), dtype=np.uint8)
        for proposal in proposals:
            # 1 is the class ID of 'Interesting'.
            # Observe order of drawing the circles if multiple classes should be trained.
            # One solution might be to draw the circle of the current proposal again when
            # its crop is generated in the loop below, so the current proposal is always
            # "on top".
            cv2.circle(mask, (proposal[0], proposal[1]), proposal[2], 1, -1)

        mask = Image.fromarray(mask)
        images = []
        masks = []
        mean_pixels = []

        for i, proposal in enumerate(proposals):
            image_file = '{}_{}.jpg'.format(imageId, i)
            mask_file = '{}.png'.format(image_file)
            image_crop, mask_crop = self.generate_crop(image, mask, proposal)
            image_crop.save('{}/{}'.format(self.training_images_path, image_file))
            mask_crop.save('{}/{}'.format(self.training_masks_path, mask_file))
            images.append(image_file)
            masks.append(mask_file)
            mean_pixels.append(np.array(image_crop).reshape((-1, 3)).mean(axis = 0))

        return images, masks, mean_pixels

    def generate_crop(self, image, mask, proposal):
        width, height = image.size
        if width < self.crop_dimension or height < self.crop_dimension:
            raise Exception('Image is smaller than the crop dimension!')

        center = np.array([proposal[0], proposal[1]])
        topLeft = np.round(center - self.crop_dimension / 2).astype(np.int32)
        bottomRight = np.round(center + self.crop_dimension / 2).astype(np.int32)
        offset = [0, 0]
        if topLeft[0] < 0: offset[0] = abs(topLeft[0])
        if topLeft[1] < 0: offset[1] = abs(topLeft[1])
        if bottomRight[0] > width: offset[0] = width - bottomRight[0]
        if bottomRight[1] > height: offset[1] = height - bottomRight[1]

        topLeft += offset
        bottomRight += offset

        image_crop = image.crop((topLeft[0], topLeft[1], bottomRight[0], bottomRight[1]))
        mask_crop = mask.crop((topLeft[0], topLeft[1], bottomRight[0], bottomRight[1]))

        return image_crop, mask_crop

with open(sys.argv[1]) as f:
    params = json.load(f)

runner = DatasetGenerator(params)
output = runner.generate()

with open(params['output_path'], 'w') as f:
    json.dump(output, f)
