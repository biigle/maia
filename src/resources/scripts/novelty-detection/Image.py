import os
from PIL import Image as PilImage, ImageFile
import numpy as np
from sklearn.feature_extraction.image import extract_patches_2d
from skimage.filters.rank import entropy
from skimage.morphology import disk

# Attempt to load truncated images, see: https://github.com/biigle/maia/issues/30
ImageFile.LOAD_TRUNCATED_IMAGES = True

class Image(object):

    def __init__(self, id, path):
        self.id = id
        self.path = path

    def random_patch(self, size, vectorize=True):
        return self.random_patches(1, size=size, vectorize=vectorize)[0]

    def random_patches(self, number, size, vectorize=True):
        patches = extract_patches_2d(self.image(), (size, size), max_patches=number)

        return np.reshape(patches, (number, size * size * 3)) if vectorize else patches

    def image(self):
        return np.array(PilImage.open(self.path))

    def _get_resized_image(self):
        img = PilImage.open(self.path)
        return img.resize((500, 500), PilImage.BILINEAR)

    def extract_pca_features(self):
        return np.array(self._get_resized_image()).flatten()

    def extract_features(self):
        resized_image = np.array(self._get_resized_image().convert('L'))
        e = np.sum(entropy(resized_image, disk(3)))

        return [e]
