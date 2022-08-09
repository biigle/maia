import os
import sys
import json
import cv2
from pyvips import Image as VipsImage
from pyvips.error import Error as VipsError
import numpy as np
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

        # If this is not empty, scale/knowledge transfer is used.
        self.scale_factors = params.get('kt_scale_factors', {})

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

        if len(images) == 0:
            raise Exception('No images in dataset. All corrupt?')

        # Discard additional channels (e.g. alpha)
        mean_pixel = np.array(mean_pixels).mean(axis = 0).tolist()[:3]

        props = [prop for trainingprops in self.training_proposals.values() for prop in trainingprops]
        classes = {}

        for i, proposal in enumerate(props):
            if(len(proposal) == 4):
              classes[i+1] = f"{proposal[-1]}"
            else:
              classes[i+1] = "Interesting"

        return {
            'training_images_path': self.training_images_path,
            'training_masks_path': self.training_masks_path,
            'training_images': images,
            'training_masks': masks,
            'mean_pixel': mean_pixel,
            'crop_dimension': self.crop_dimension,
            # For now all datasets are binary, i.e. only the classes "Background" and
            # "Interesting" are supported.
            'classes': classes
        }

    def ensure_train_masks_dirs(self):
        if not os.path.exists(self.training_images_path):
           os.makedirs(self.training_images_path)

        if not os.path.exists(self.training_masks_path):
           os.makedirs(self.training_masks_path)

    def process_image(self, imageId, proposals):
        try:
            image = VipsImage.new_from_file(self.images[imageId])

            if bool(self.scale_factors) != False:
                scale_factor = self.scale_factors[imageId]
                image = image.resize(scale_factor)
                proposals = np.round(np.array(proposals, dtype=np.float32) * scale_factor).astype(int)

            masks = []
            classes = []

            for i, proposal in enumerate(proposals):
                mask = np.zeros((image.height, image.width), dtype=np.int32)
                cv2.circle(mask, (proposal[0], proposal[1]), proposal[2], i+1, -1)
                masks.append(mask)
                if(len(proposal) == 4):
                  classes.append(proposal[3])
                else:
                  classes.append("Interesting")

            image_paths = []
            mask_paths = []
            mean_pixels = []

            for i, proposal in enumerate(proposals):
                image_file = '{}_{}.jpg'.format(imageId, i)
                image_crop, mask_crops = self.generate_crop(image, masks, proposal)
                mask_file = self.save_mask(mask_crops, image_file, self.training_masks_path, classes)
                image_crop.write_to_file(os.path.join(self.training_images_path, image_file), strip=True, Q=95)
                image_paths.append(image_file)
                mask_paths.append(mask_file)
                np_crop = np.ndarray(buffer=image_crop.write_to_memory(), shape=[image_crop.height, image_crop.width, image_crop.bands], dtype=np.uint8)
                mean_pixels.append(np_crop.reshape((-1, image.bands)).mean(axis = 0))

        except VipsError as e:
            print('Image #{} is corrupt! Skipping...'.format(imageId))

            return False, False, False

        return image_paths, mask_paths, mean_pixels

    def generate_crop(self, image, masks, proposal):
        width, height = image.width, image.height

        crop_width = min(width, self.crop_dimension)
        crop_height = min(height, self.crop_dimension)
        current_crop_dimension = np.array([crop_width, crop_height])

        center = np.array([proposal[0], proposal[1]])
        topLeft = np.round(center - current_crop_dimension / 2).astype(np.int32)
        bottomRight = np.round(center + current_crop_dimension / 2).astype(np.int32)
        offset = [0, 0]
        if topLeft[0] < 0: offset[0] = abs(topLeft[0])
        if topLeft[1] < 0: offset[1] = abs(topLeft[1])
        if bottomRight[0] > width: offset[0] = width - bottomRight[0]
        if bottomRight[1] > height: offset[1] = height - bottomRight[1]

        topLeft += offset
        bottomRight += offset

        image_crop = image.extract_area(topLeft[0], topLeft[1], current_crop_dimension[0], current_crop_dimension[1])
        mask_crops = [mask[topLeft[1]:bottomRight[1], topLeft[0]:bottomRight[0]] for mask in masks]

        return image_crop, mask_crops

    def save_mask(self, masks, filename, path, classes):
        mask_store = [mask for mask in masks if np.any(mask)]
        mask_file = '{}.npz'.format(filename)
        np.savez_compressed(os.path.join(path, mask_file), masks=mask_store, classes=classes)

        return mask_file

with open(sys.argv[1]) as f:
    params = json.load(f)

runner = DatasetGenerator(params)
output = runner.generate()

with open(params['output_path'], 'w') as f:
    json.dump(output, f)
