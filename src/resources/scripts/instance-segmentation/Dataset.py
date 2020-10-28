import mrcnn.utils
import cv2
import numpy as np
import os.path
from pyvips import Image as VipsImage
import skimage.color

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

    def load_image(self, image_id):
        """Load the specified image and return a [H,W,3] Numpy array.
        """
        # Load image with Vips because if ignores EXIF rotation (as it should).
        image = self.vips_image_to_numpy_array(VipsImage.new_from_file(self.images[imageId]))
        # image = skimage.io.imread(self.image_info[image_id]['path'])
        # If grayscale. Convert to RGB for consistency.
        if image.ndim != 3:
            image = skimage.color.gray2rgb(image)
        # If has an alpha channel, remove it for consistency
        if image.shape[-1] == 4:
            image = image[..., :3]

        return image

    def load_mask(self, image_index):
        file = self.masks[image_index]
        data = np.load(file, allow_pickle=True)
        classes = []
        masks = []

        for mask in data['masks']:
            # Only one class "Interesting" is supported for now.
            source_class_id = 1
            if source_class_id not in self.ignore_classes:
                classes.append(self.map_source_class_id('{}.{}'.format(self.name, source_class_id)))
                masks.append(mask)

        if len(classes) == 0:
            return super().load_mask(image_index)

        classes = np.array(classes, dtype=np.int32)
        masks = np.stack(masks, axis = 2).astype(np.bool)

        return masks, classes

    def vips_image_to_numpy_array(self, image):
        # https://libvips.github.io/pyvips/intro.html#numpy-and-pil
        format_to_dtype = {
            'uchar': np.uint8,
            'char': np.int8,
            'ushort': np.uint16,
            'short': np.int16,
            'uint': np.uint32,
            'int': np.int32,
            'float': np.float32,
            'double': np.float64,
            'complex': np.complex64,
            'dpcomplex': np.complex128,
        }

        return np.ndarray(buffer=image.write_to_memory(),
                   dtype=format_to_dtype[image.format],
                   shape=[image.height, image.width, image.bands])

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
