import os
import sys
import json
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
        # Dict of image IDs and arrays of selected training proposal coordinates.
        self.training_proposals = params['training_proposals']

        self.max_workers = params['max_workers']

        self.training_images_path = os.path.join(self.tmp_dir, 'training_images')
        self.crop_dimension = 512

        # If this is not empty, scale/knowledge transfer is used.
        self.scale_factors = params.get('kt_scale_factors', {})

    def generate(self):
        if not os.path.exists(self.training_images_path):
           os.makedirs(self.training_images_path)

        executor = ThreadPoolExecutor(max_workers=self.max_workers)
        jobs = []

        for imageId, proposals in self.training_proposals.items():
            jobs.append(executor.submit(self.process_image, imageId, proposals))

        ann_list = []

        for job in as_completed(jobs):
            a = job.result()
            if a is not False:
                ann_list.extend(a)

        if len(ann_list) == 0:
            raise Exception('No images in dataset. All corrupt?')

        data_dict = {
            'img_prefix': self.training_images_path,
            'ann_file': os.path.join(self.tmp_dir, 'train_dataset.json'),
            # For now all datasets are binary, i.e. only the class "Interesting"
            # is distinguished from the background.
            'classes': ('interesting', )
        }

        return data_dict, ann_list

    def process_image(self, imageId, proposals):
        try:
            image = VipsImage.new_from_file(self.images[imageId])

            if bool(self.scale_factors) != False:
                scale_factor = self.scale_factors[imageId]
                image = image.resize(scale_factor)
                proposals = np.round(np.array(proposals, dtype=np.float32) * scale_factor).astype(int)

            output = []

            for i, proposal in enumerate(proposals):
                image_crop, bboxes, labels = self.generate_crop(image, proposal, proposals)
                image_file = '{}_{}.jpg'.format(imageId, i)
                path = os.path.join(self.training_images_path, image_file)
                image_crop.write_to_file(path, strip=True, Q=95)

                output.append({
                    'filename': image_file,
                    'width': image_crop.width,
                    'height': image_crop.height,
                    'ann': {
                        'bboxes': bboxes,
                        'labels': labels,
                    },
                })

        except VipsError as e:
            print('Image #{} is corrupt! Skipping...'.format(imageId))

            return False

        return output

    def generate_crop(self, image, proposal, proposals):
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

        # Convert to regular Python data types so the computed values will be JSON serializable.
        topLeft = topLeft.tolist()
        current_crop_dimension = current_crop_dimension.tolist()

        bboxes = self.determine_crop_bboxes(topLeft, current_crop_dimension, proposals)
        # Only label "interesting""
        labels = [0 for b in bboxes]

        image_crop = image.extract_area(topLeft[0], topLeft[1], current_crop_dimension[0], current_crop_dimension[1])

        return image_crop, bboxes, labels

    def determine_crop_bboxes(self, topLeft, dim, proposals):
        x1, y1 = topLeft
        w, h = dim
        x2 = x1 + w
        y2 = y1 + h
        bboxes = []
        for p in proposals:
            px1 = p[0] - p[2]
            px2 = p[0] + p[2]
            py1 = p[1] - p[2]
            py2 = p[1] + p[2]
            # See: https://stackoverflow.com/a/20925869/1796523
            if x2 > px1 and px2 > x1 and y2 > py1 and py2 > y1:
                bboxes.append([
                    # Limit the proposal dimensions to the crop bbox.
                    max(x1, px1),
                    max(y1, py1),
                    min(x2, px2),
                    min(y2, py2),
                ])

        return bboxes


if __name__ == '__main__':
    with open(sys.argv[1]) as f:
        params = json.load(f)

    runner = DatasetGenerator(params)
    data_dict, ann_list = runner.generate()
    with open(params['output_path'], 'w') as f:
        json.dump(output, f)

    with open(data_dict['ann_file'], 'w') as f:
        json.dump(ann_list, f)
