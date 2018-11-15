import sys
import os
import json
import cv2
import numpy as np
from scipy.misc import imsave
from ImageCollection import ImageCollection
from AutoencoderSaliencyDetector import AutoencoderSaliencyDetector


class DetectionRunner(object):

    def __init__(self, params):
        # Number of image clusters to use
        self.clusters = params['clusters']
        # Size of the input image patches for the autoencoder.
        self.patch_size = params['patch_size']
        # Percentile to use to determine the dynamic threshold.
        self.threshold = params['threshold']
        # Size of the latent layer of the autoencoder relative to the input size.
        self.latent_size = params['latent_size']
        # Size of the training dataset of patches for the autoencoder.
        self.trainset_size = params['trainset_size']
        # Number of training epochs for the autoencoder.
        self.epochs = params['epochs']

        # Array of file paths to the images to process.
        self.images = params['images']
        # Path to the directory to store temporary files.
        self.tmp_dir = params['tmp_dir']

        self.region_vote_mask = np.ones((self.patch_size, self.patch_size))
        self.detector_stride = 2

    def run(self):
        images = ImageCollection(self.images);
        if self.clusters > 1:
            clusters = images.make_clusters(number=self.clusters)
        else:
            clusters = [images]

        detector = AutoencoderSaliencyDetector(self.patch_size, stride=self.detector_stride, hidden=self.latent_size)

        for i, cluster in enumerate(clusters):
            print("Cluster {}".format(i + 1))
            threshold = self.process_cluster(detector, cluster)
            self.postprocess_map(cluster, threshold)

    def process_cluster(self, detector, cluster):
        print("  Training")
        detector.train(cluster, reset=True, number=self.trainset_size, epochs=self.epochs, display_step=1, batch_size=128)

        total_images = len(cluster)
        thresholds = []

        for i, image in enumerate(cluster):
            print("  Image {} of {}".format(i + 1, total_images))
            saliency_map = detector.apply(image.path)
            cv2.filter2D(saliency_map, -1, self.region_vote_mask)
            saliency_map = saliency_map.astype(np.float32)
            thresholds.append(np.percentile(saliency_map, self.threshold))
            np.save(self.image_path(image, 'npy'), saliency_map)

        return np.mean(thresholds)

    def postprocess_map(self, cluster, threshold):
        for image in cluster:
            saliency_map = np.load(self.image_path(image, 'npy'))
            saliency_map = np.where(saliency_map > threshold, 255, 0)
            imsave(self.image_path(image, 'png'), saliency_map)
            # TODO store bounding circles in a JSON file
            os.remove(self.image_path(image, 'npy'))

    def image_path(self, image, suffix):
        return '{}/{}.{}'.format(self.tmp_dir, image.id, suffix)


with open(sys.argv[1]) as f:
    params = json.load(f)

runner = DetectionRunner(params)
runner.run()
