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

    def random_patches(self, number, size):
        image = np.array(self.pil_image())

        return extract_patches_2d(image, (size, size), max_patches=number)

    def is_corrupt(self):
        image = self.pil_image()
        try:
            image.load()
        except (IOError, OSError) as e:
            print('Image #{} is corrupt!'.format(self.id))
            return True

        return False

    def pil_image(self):
        image = PilImage.open(self.path)
        if image.mode in ['RGBA', 'L', 'P', 'CMYK']:
            image = image.convert('RGB')
        elif image.mode in ['I', 'I;16']:
            # I images (32 bit signed integer) and I;16 (16 bit unsigned imteger)
            # need to be rescaled manually before converting.
            # image/256 === image/(2**16)*(2**8)
            image = Image.fromarray((np.array(image)/256).astype(np.uint8)).convert('RGB')

        return image

    def _get_resized_image(self):
        image = self.pil_image()
        return image.resize((256, 256), PilImage.BILINEAR)

    def extract_pca_features(self):
        return np.array(self._get_resized_image()).flatten()

    def extract_features(self):
        resized_image = np.array(self._get_resized_image().convert('L'))
        e = np.sum(entropy(resized_image, disk(3)))

        return [e]
