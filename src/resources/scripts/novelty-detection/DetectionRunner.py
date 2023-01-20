import sys
import os
import json
import cv2
import numpy as np
from concurrent.futures import ThreadPoolExecutor, wait
from ImageCollection import ImageCollection
from NoveltyDetector import NoveltyDetector
from PIL import Image

class DetectionRunner(object):

    def __init__(self, params):
        # Number of image clusters to use
        self.clusters = params['nd_clusters']
        # Size of the input image patches for the autoencoder.
        self.patch_size = params['nd_patch_size']
        # Percentile to use to determine the dynamic threshold.
        self.threshold = params['nd_threshold']
        # Size of the latent layer of the autoencoder relative to the input size.
        self.latent_size = params['nd_latent_size']
        # Size of the training dataset of patches for the autoencoder.
        self.trainset_size = params['nd_trainset_size']
        # Number of training epochs for the autoencoder.
        self.epochs = params['nd_epochs']

        # Dict of image IDs and file paths to the images to process.
        self.images = params['images']
        # Path to the directory to store temporary files.
        self.tmp_dir = params['tmp_dir']

        self.max_workers = params['max_workers']
        # Percentage of worker threads to use for training data preparation. The rest is
        # used for post processing.
        self.train_workers_ratio = 0.5

        self.region_vote_mask = np.ones((self.patch_size, self.patch_size))
        self.detector_stride = params['nd_stride']
        self.ignore_radius = params['nd_ignore_radius']

    def run(self):
        full_executor = ThreadPoolExecutor(max_workers=self.max_workers)

        # Split available worker threads between training data preparation and post
        # processing (if possible).
        if self.max_workers > 1:
            workers = min(self.max_workers - 1, round(self.max_workers * self.train_workers_ratio))
            train_executor = ThreadPoolExecutor(max_workers=workers)
            post_executor = ThreadPoolExecutor(max_workers=self.max_workers - workers)
        else:
            train_executor = full_executor
            post_executor = full_executor

        images = ImageCollection(self.images, executor=full_executor);
        images.prune_corrupt_images()
        total_images = len(images)

        if total_images == 0:
            print('No image files to process.')
            return
        elif total_images < self.clusters:
            print('{} clusters were requested but only {} images are there. Reducing clusters to {}.'.format(self.clusters, total_images, total_images))
            self.clusters = total_images

        if self.clusters > 1:
            clusters = images.make_clusters(number=self.clusters)
        else:
            clusters = [images]

        detector = NoveltyDetector(self.patch_size, stride=self.detector_stride, hidden=self.latent_size)

        jobs = []

        for i, cluster in enumerate(clusters):
            cluster.set_executor(train_executor)
            print("Cluster {} of {}".format(i + 1, self.clusters))
            threshold = self.process_cluster(detector, cluster)
            jobs.extend([post_executor.submit(self.postprocess_map, image, threshold) for image in cluster])

        wait(jobs)

    def process_cluster(self, detector, cluster):
        print("  Training")
        detector.train(cluster, number=self.trainset_size, epochs=self.epochs, display_step=1, batch_size=128, num_workers=self.max_workers)

        total_images = len(cluster)
        thresholds = []

        for i, image in enumerate(cluster):
            print("  Image {} of {} (#{})".format(i + 1, total_images, image.id))
            saliency_map = detector.apply(image.path)
            saliency_map = cv2.filter2D(saliency_map, -1, self.region_vote_mask)
            saliency_map = saliency_map.astype(np.float32)
            thresholds.append(np.percentile(saliency_map, self.threshold))
            np.save(self.image_path(image, 'npy'), saliency_map)

        return np.mean(thresholds)

    def postprocess_map(self, image, threshold):
        saliency_map = np.load(self.image_path(image, 'npy'))
        binary_map = np.where(saliency_map > threshold, 255, 0).astype(np.uint8)
        # Image.fromarray(binary_map).save(self.image_path(image, 'png'))
        mask = np.zeros_like(binary_map)
        contours, _ = cv2.findContours(binary_map, cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_SIMPLE)
        points = []
        for c in contours:
            x, y, w, h = cv2.boundingRect(c)
            rx = round(w / 2)
            ry = round(h / 2)
            r = max(rx, ry)
            if r > self.ignore_radius:
                mask *= 0 # reset mask
                cv2.drawContours(mask, [c], -1, 1, -1)
                saliency = np.sum(saliency_map * mask)
                points.append([int(x + rx), int(y + ry), int(r), float(saliency)])
        with open(self.image_path(image, 'json'), 'w') as outfile:
            json.dump(points, outfile)
        os.remove(self.image_path(image, 'npy'))


    def image_path(self, image, suffix):
        return '{}/{}.{}'.format(self.tmp_dir, image.id, suffix)


with open(sys.argv[1]) as f:
    params = json.load(f)

runner = DetectionRunner(params)
runner.run()
