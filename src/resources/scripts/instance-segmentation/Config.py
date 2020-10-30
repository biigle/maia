import numpy as np
import mrcnn.config
import imgaug.augmenters as iaa
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
        self.AUGMENTATION = iaa.SomeOf((0, None), [
            iaa.Fliplr(1.0),
            iaa.Flipud(1.0),
            iaa.Affine(rotate=[90, 180, 270]),
            iaa.GaussianBlur(sigma=(1.0, 2.0)),
            iaa.JpegCompression(compression=(25, 50)),
        ], random_order=True)
        super().__init__()

class TrainingConfig(Config):
    def __init__(self, params, trainset):
        self.RPN_NMS_THRESHOLD = 0.85
        self.IMAGE_MAX_DIM = trainset['crop_dimension']
        # Determine the number of images per batch that the GPU can handle. From the
        # Mask R-CNN config: "A 12GB GPU can typically handle 2 images of 1024x1024px."
        # But I found that one image is more realistic.
        # see: https://github.com/biigle/maia/issues/15
        factor = 1024**2 / 12 # 1024px²/12GB
        self.IMAGES_PER_GPU = math.floor(params['available_bytes'] * 1e-9 * factor / self.IMAGE_MAX_DIM**2)

        # In total, we want to train with about 2000 images per epoch. So we adjust the
        # number of steps according to the batch size determined above.
        self.STEPS_PER_EPOCH = round(2000 / self.IMAGES_PER_GPU)

        super().__init__(trainset, name = 'maia_training')

class InferenceConfig(Config):
    def __init__(self, params, trainset):
        self.IMAGES_PER_GPU = 1
        self.IMAGE_MIN_DIM = 64
        self.IMAGE_RESIZE_MODE = "pad64"
        super().__init__(trainset, name = 'maia_inference')
