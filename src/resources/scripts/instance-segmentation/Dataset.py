import mrcnn.utils
from skimage.io import imread
import cv2
import numpy as np
import os.path

class Dataset(mrcnn.utils.Dataset):
    def __init__(self, images, name='no_name', masks=[], classes={}):
        super().__init__()
        self.images = images
        self.masks = masks
        self.name = name
        self.classes = classes
        # Ignore the background class.
        self.ignore_classes = set([0])

    def prepare(self):
        for class_id, class_name in self.classes.items():
            self.add_class(self.name, class_id, class_name)

        for image_id, image_file in self.images.items():
            self.add_image(self.name, image_id, image_file)

        super().prepare()

    def load_mask(self, image_index):
        file = self.masks[image_index]
        mask = imread(file)
        _, contours, _ = cv2.findContours(np.copy(mask), cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_SIMPLE)

        # The class to which a contour belongs is defined by the values of its pixels
        # (class_id).
        class_ids = []
        class_masks = []
        for c in contours:
            c = c.squeeze(axis=1)
            source_class_id = mask[c[0][1], c[0][0]]
            # Map the source ID to the internal continuous IDs.
            class_id = self.map_source_class_id('{}.{}'.format(self.name, source_class_id))

            if source_class_id not in self.ignore_classes:
                class_mask = np.zeros((mask.shape[0], mask.shape[1]))
                cv2.drawContours(class_mask, [c], -1, 1, -1)
                class_ids.append(class_id)
                class_masks.append(class_mask)

        if len(class_ids) == 0:
            shape = mask.shape
            class_masks = np.zeros((shape[0], shape[1], 0), dtype=np.bool)
        else:
            class_masks = np.stack(class_masks, axis = 2).astype(np.bool)

        return class_masks, np.array(class_ids).astype(np.int32)

class TrainingDataset(Dataset):
    def __init__(self, trainset):
        images = self.join_paths(trainset['training_images_path'], trainset['training_images'])
        # Convert to the required dict with image IDs.
        images = {k: v for k, v in enumerate(images)}
        masks = self.join_paths(trainset['training_masks_path'], trainset['training_masks'])
        classes = {int(k): v for k, v in trainset['classes'].items()}
        super().__init__(images=images, masks=masks, classes=classes, name='maia_generic_training')

    def join_paths(self, prefix, suffixes):
        return [os.path.join(prefix, s) for s in suffixes]

class InferenceDataset(Dataset):
    def __init__(self, images):
        super().__init__(images=images, name='maia_generic_inference')
