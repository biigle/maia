import os
import sys
import json
import pickle
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

        ann_list = []

        for job in as_completed(jobs):
            a = job.result()
            if a is not False:
                ann_list.append(a)

        if len(ann_list) == 0:
            raise Exception('No images in dataset. All corrupt?')

        data_dict = {
            'img_prefix': self.training_images_path,
            'ann_file': os.path.join(self.tmp_dir, 'train_dataset.pkl'),
            # For now all datasets are binary, i.e. only the class "Interesting"
            # is distinguished from the background.
            'classes': ('interesting', )
        }

        return data_dict, ann_list

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

            bboxes = []
            labels = []

            for p in proposals:
                bboxes.append([
                    p[0] - p[2], # px1
                    p[1] - p[2], # py1
                    p[0] + p[2], # px2
                    p[1] + p[2], # py2
                ])
                labels.append(0)


        except (IOError, OSError) as e:
            print('Image #{} is corrupt! Skipping...'.format(imageId))

            return False

        return {
            'filename': target_name,
            'width': image.width,
            'height': image.height,
            'ann': {
                # These must be np.ndarray with the correct data type.
                # See: https://mmdetection.readthedocs.io/en/latest/tutorials/customize_dataset.html#reorganize-new-data-format-to-middle-format
                'bboxes': np.array(bboxes, dtype=np.float32),
                'labels': np.array(labels, dtype=np.int64),
            },
        }

if __name__ == '__main__':
    with open(sys.argv[1]) as f:
        params = json.load(f)

    runner = DatasetGenerator(params)
    data_dict, ann_list = runner.generate()
    with open(params['output_path'], 'w') as f:
        json.dump(data_dict, f)
    with open(data_dict['ann_file'], 'wb') as f:
        pickle.dump(ann_list, f)
