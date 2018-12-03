import numpy as np
import mrcnn.config
import imgaug
import math

class Config(mrcnn.config.Config):
    def __init__(self, trainset, name='no_name'):
        self.NAME = name
        # Add one for the background class (0) which is not explicitly specified in the
        # classes dict.
        self.NUM_CLASSES = 1 + len(trainset['classes'])
        # Disable validation since we do not have ground truth.
        self.VALIDATION_STEPS = 0
        # This is the mean pixel of the training images.
        self.MEAN_PIXEL = np.array(trainset['mean_pixel'])
        self.AUGMENTATION = imgaug.augmenters.Sometimes(0.5, [
            imgaug.augmenters.Fliplr(0.5),
            imgaug.augmenters.Flipud(0.5),
            imgaug.augmenters.Affine(rotate=(-180, 180)),
            imgaug.augmenters.GaussianBlur(sigma=(0.0, 2.0)),
        ])
        super().__init__()

class TrainingConfig(Config):
    def __init__(self, params, trainset):
        self.IMAGE_MIN_DIM = trainset['crop_dimension']
        # Determine the number of images per batch that the GPU can handle. From the
        # Mask R-CNN config: "A 12GB GPU can typically handle 2 images of 1024x1024px."
        # So a 6GB GPU should be able to handle 1024x1024px. I found that one 1024px²
        # image per 12 GB is more accurate.
        factor = 1024**2 / 12 # 1024px²/12GB
        self.IMAGES_PER_GPU = math.floor((params['available_bytes'] / 1e+9) * (factor / trainset['crop_dimension']**2))

        super().__init__(trainset, name = 'maia_training')

class InferenceConfig(Config):
    def __init__(self, name, params, trainset):
        self.IMAGES_PER_GPU = 1
        self.IMAGE_MIN_DIM = 64
        self.IMAGE_RESIZE_MODE = "pad64"
        super().__init__(trainset, name = 'maia_inference')
