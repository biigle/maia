import os
from PIL import Image as PilImage
import numpy as np
from sklearn.feature_extraction.image import extract_patches_2d
from skimage.filters.rank import entropy
from skimage.morphology import disk

class Image(object):

    def __init__(self, id, path):
        self.id = id
        self.path = path

    def random_patch(self, size, vectorize=True):
        return self.random_patches(1, size=size, vectorize=vectorize)[0]

    def random_patches(self, number, size, vectorize=True):
        patches = extract_patches_2d(self.image(), (size, size), max_patches=number)

        return np.reshape(patches, (number, size * size * 3)) if vectorize else patches

    def is_corrupt(self):
        image = PilImage.open(self.path)
        try:
            image.load()
        except (IOError, OSError) as e:
            print('Image #{} is corrupt!'.format(self.id))
            return True

        return False

    def image(self):
        image = np.array(PilImage.open(self.path))
        # Remove alpha channel if present.
        if image.shape[2] == 4:
            image = np.delete(image, 3, axis=2)

        return image

    def _get_resized_image(self):
        img = PilImage.open(self.path)
        return img.convert('RGB').resize((256, 256), PilImage.BILINEAR)

    def extract_pca_features(self):
        return np.array(self._get_resized_image()).flatten()

    def extract_features(self):
        resized_image = np.array(self._get_resized_image().convert('L'))
        e = np.sum(entropy(resized_image, disk(3)))

        return [e]
