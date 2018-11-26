import numpy as np
from Image import Image
from sklearn.cluster import KMeans
from sklearn.decomposition import PCA
from sklearn.preprocessing import minmax_scale
from multiprocessing import Pool
from concurrent.futures import ProcessPoolExecutor, as_completed

def extract_image_pca_features(image):
    return image.extract_pca_features()

def extract_image_features(image):
    return image.extract_features()

def random_image_patches(image, size, vectorize):
    return image.random_patches(per_image, size=size, vectorize=vectorize)

class ImageCollection(object):
    def __init__(self, items, executor=None):
        if type(items) == dict:
            self.images = [Image(id, path) for id, path in items.items()]
        else:
            self.images = items

        self.executor = executor if executor != None else ProcessPoolExecutor()

    def __getitem__(self, key):
        return self.images[key]

    def __len__(self):
        return len(self.images)

    def random_patches(self, number=10000, size=29, vectorize=True):
        per_image = int(np.ceil(float(number) / len(self.images)))

        jobs = [self.executor.submit(image.random_patches, per_image, size, vectorize) for image in self.images]

        patches = []
        for job in as_completed(jobs):
            patches.extend(job.result())
        patches = np.array(patches)

        np.random.shuffle(patches)
        # Remove random elements so the exact number is returned.
        return patches[:number]

    def make_clusters(self, number=5):
        pca_features = self.executor.map(extract_image_pca_features, self.images)
        # Call list() to resolve the map object.
        pca_features = np.array(list(pca_features))
        pca_features = PCA(n_components=2, copy=False).fit_transform(pca_features)

        features = self.executor.map(extract_image_features, self.images)
        # Call list() to resolve the map object.
        features = np.array(list(features))
        features = np.append(features, pca_features, axis=1)

        # axis=0 to scale each feature and not each sample.
        new_features = minmax_scale(features, axis=0)

        labels = KMeans(n_clusters=number).fit_predict(new_features)
        clusters = [[] for i in range(number)]
        for label, image in zip(labels, self.images):
            clusters[label].append(image)

        return [ImageCollection(cluster, self.executor) for cluster in clusters]
