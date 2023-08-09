import os
import sys
import json
from PIL import Image as PilImage
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

        # If this is not empty, scale/knowledge transfer is used.
        self.scale_factors = params.get('kt_scale_factors', {})

    def generate(self):
        if not os.path.exists(self.training_images_path):
           os.makedirs(self.training_images_path)

        executor = ThreadPoolExecutor(max_workers=self.max_workers)
        jobs = []

        for imageId, proposals in self.training_proposals.items():
            jobs.append(executor.submit(self.process_image, imageId, proposals))

        image_list = []

        for job in as_completed(jobs):
            a = job.result()
            if a is not False:
                image_list.append(a)

        annotation_list = []
        annotation_id = 0
        for image in image_list:
            annotations = image.pop('annotations')
            for annotation in annotations:
                annotation['id'] = annotation_id
                annotation_id += 1
                annotation_list.append(annotation)

        if len(image_list) == 0:
            raise Exception('No images in dataset. All corrupt?')

        data_dict = {
            'img_prefix': self.training_images_path,
            'ann_file': os.path.join(self.tmp_dir, 'train_dataset.json'),
            # For now all datasets are binary, i.e. only the class "Interesting"
            # is distinguished from the background.
            'classes': ('interesting', )
        }

        # COCO format.
        # See: https://mmdetection.readthedocs.io/en/latest/user_guides/train.html#coco-annotation-format
        ann_file = {
            'images': image_list,
            'annotations': annotation_list,
            'categories': [{
                'id': 0,
                'name': 'interesting',
                'supercategory': 'interesting',
            }],
        }

        return data_dict, ann_file

    def process_image(self, imageId, proposals):
        try:
            source_path = os.path.abspath(self.images[imageId])
            target_name = os.path.basename(self.images[imageId])
            target_path = os.path.join(self.training_images_path, target_name)
            image = PilImage.open(source_path)

            if bool(self.scale_factors) != False:
                # If scale transfer should be performed, scale the image and proposals.
                scale_factor = self.scale_factors[imageId]
                width = int(round(image.width * scale_factor))
                height = int(round(image.height * scale_factor))
                image_format = image.format
                image = image.resize((width, height))
                proposals = np.round(np.array(proposals, dtype=np.float32) * scale_factor).astype(int)
                image.save(target_path, format=image_format, quality=95)
            else:
                # Load image to check if it is corrupt.
                image.load()
                # Without scale transfer, just make a link to the original image in the cache.
                os.symlink(source_path, target_path)

            image.close()

            annotations = []

            for p in proposals:
                annotations.append({
                    'id': 0, # Placeholder, will be updated to an uniwue ID later.
                    'image_id': int(imageId),
                    'category_id': 0, # There is only one category.
                    'bbox': [
                        p[0] - p[2], # px
                        p[1] - p[2], # py
                        p[2] * 2, # width
                        p[2] * 2, # height
                    ],
                    'area': (p[2] * 2)**2,
                })


        except (IOError, OSError) as e:
            print('Image #{} is corrupt! Skipping...'.format(imageId))

            return False

        return {
            'id': int(imageId),
            'width': image.width,
            'height': image.height,
            'file_name': target_name,
            'annotations': annotations,
        }

if __name__ == '__main__':
    with open(sys.argv[1]) as f:
        params = json.load(f)

    runner = DatasetGenerator(params)
    data_dict, ann_file = runner.generate()
    with open(params['output_path'], 'w') as f:
        json.dump(data_dict, f)
    with open(data_dict['ann_file'], 'w') as f:
        json.dump(ann_file, f)
