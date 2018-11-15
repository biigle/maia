import PIL, sys
import numpy as np
import tensorflow as tf
from scipy.ndimage import zoom
from Autoencoder import Autoencoder
from scipy.misc import imsave

class AutoencoderSaliencyDetector(object):

    def __init__(self, patch_size, stride = 1, hidden = 0.05):
        self.n_input = patch_size**2 * 3
        n_hidden = int(np.round(self.n_input * hidden))
        self.patch_size = patch_size
        self.stride = stride
        self.padding = int(np.floor(self.patch_size / 2.0))

        self.autoencoder = Autoencoder([self.n_input, n_hidden])
        # self.autoencoder = Autoencoder(self.n_input, n_hidden)

        # Load the image file.
        self.x = tf.placeholder(tf.string)
        self.chunk = tf.placeholder(tf.int32, [4])

        self.read_file = tf.read_file(self.x)
        self.decode_image = tf.to_float(tf.image.decode_image(self.read_file, channels=3))
        # Slice image in chunks so really large images can be processed with constant
        # memory consumption.
        self.crop_chunk = tf.image.crop_to_bounding_box(self.decode_image, self.chunk[0], self.chunk[1], self.chunk[2], self.chunk[3])

        # Extract image patches in the correct size and reshape the 2D array of patches
        # to an 1D array that can be fed to the autoencoder.
        self.extract_patches = tf.extract_image_patches(
            images=[self.crop_chunk],
            ksizes=[1, self.patch_size, self.patch_size, 1],
            strides=[1, self.stride, self.stride, 1],
            rates=[1, 1, 1, 1],
            padding='VALID'
        )
        self.patches_shape = tf.shape(self.extract_patches[0])
        self.reshape = tf.reshape(self.extract_patches[0], [-1, self.n_input])

        # Apply the autoencoder.
        self.element_wise_cost = self.autoencoder.element_wise_cost(self.reshape)

        # Reshape the array back to the original image size (minus the invalid border).
        # Collapse the grayscale channel so the result has shape [height, width] instead
        # of [height, width, 1].
        self.reshape_back = tf.reshape(self.element_wise_cost, self.patches_shape[:2])

        self.sess = self.autoencoder.sess

    def train(self, images, number=10000, reset=False, display=True, epochs=30, batch_size=128, display_step=10):
        if reset: self.autoencoder._initialize()

        X_train = images.random_patches(number=number, size=self.patch_size)

        for epoch in range(epochs):
            acc_cost = 0.
            total_batch = int(number / batch_size)
            # Loop over all batches
            for i in range(total_batch):
                batch_xs = self._get_random_block_from_data(X_train, batch_size)

                # Fit training using batch data
                cost = self.autoencoder.partial_fit(batch_xs)
                acc_cost += cost

            # Display logs per epoch step
            if display and epoch % display_step == 0:
                # Compute average loss
                avg_cost = acc_cost / number
                print("Epoch: {:04d} ({:.5f})".format(epoch + 1, avg_cost))

    def _get_random_block_from_data(self, data, batch_size):
        start_index = np.random.randint(0, len(data) - batch_size)

        return data[start_index:(start_index + batch_size)]

    def apply(self, image_path, available_bytes=9e9):
        img = PIL.Image.open(image_path)

        # Determine how many patches fit into memory.
        # *2 because the extracted patches are reshaped witch duplicates the data
        # *4 because the patches are cast to float32 (= 4 Bytes)
        px_per_batch = available_bytes / (self.n_input * 2 * 4)

        chunks = self._get_chunks(img.width, img.height, px_per_batch)
        tmp_result = []

        for chunk in chunks:
            tmp_result.extend(self.sess.run(self.reshape_back, feed_dict={
                self.x: image_path,
                self.chunk: chunk
            }))

        # Upscale the result to the original size again because a stride != 1 shrinks
        # it by factor "stride".
        if self.stride != 1:
            tmp_result = zoom(tmp_result, self.stride, order=0)
            # Cut off any extraneous columns or rows that may be introduced in the
            # scaling due to rounding.
            diff = tmp_result.shape[0] - (img.height - 2*self.padding)
            if diff > 0: tmp_result = tmp_result[:-diff, :]
            diff = tmp_result.shape[1] - (img.width - 2*self.padding)
            if diff > 0: tmp_result = tmp_result[:, :-diff]

        result = np.zeros((img.height, img.width))
        result[self.padding:-self.padding, self.padding:-self.padding] = tmp_result

        return result

    """
    I've called these chunks so they are not confused with batches of mini batch
    training.
    """
    def _get_chunks(self, width, height, px_per_chunk):
        # The effective (reduced) number of columns due to the stride.
        # See: https://github.com/tensorflow/tensorflow/blob/1ad5e692e2fc218ca0b2a9a461c19762fdc9674b/tensorflow/core/framework/common_shape_fns.cc#L37
        effective_width = (width - self.patch_size + self.stride) / self.stride
        double_padding = 2 * self.padding
        # Rows that should be porcessed per chunk. These rows contain exactly
        # px_per_chunk pixels that are evaluated with the current stride.
        rows_per_chunk = int(np.ceil(px_per_chunk / effective_width)) * self.stride
        # Finally add the padding to each chunk.
        rows_per_chunk += double_padding

        if rows_per_chunk <= double_padding:
            raise Exception('Patch dimension of {} too large. Can only process {} rows in one chunk.'.format(double_padding, rows_per_chunk))

        start = 0
        stop = 0
        chunks = []

        while stop < height:
            stop = start + rows_per_chunk
            if stop > height: stop = height
            # Tuple with offset_height, offset_width, target_height, target_width for
            # tf.image.crop_to_bounding_box().
            chunks.append((start, 0, stop - start, width))
            # Subtract padding because each chunk must be padded. So there are 2*padding
            # lines in each chunk that aren't actually processed.
            start = stop - double_padding

        return chunks
