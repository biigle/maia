import numpy as np
from Image import Image
from sklearn.cluster import KMeans
from sklearn.decomposition import PCA
from sklearn.preprocessing import minmax_scale
from multiprocessing import Pool

def extract_image_pca_features(image):
    return image.extract_pca_features()

def extract_image_features(image):
    return image.extract_features()

class ImageCollection(object):
    def __init__(self, items):
        self.images = list(map(self._create_image, enumerate(items)))

    def __getitem__(self, key):
        return self.images[key]

    def __len__(self):
        return len(self.images)

    def _create_image(self, args):
        i, item = args
        if type(item) == Image:
            return item
        else:
            return Image(item, id=i)

    def random_patches(self, number=10000, size=29, vectorize=True):
        per_image = int(np.ceil(float(number) / len(self.images)))
        patches = []
        for image in self.images:
            patches.extend(image.random_patches(per_image, size=size, vectorize=vectorize))
        patches = np.array(patches)

        np.random.shuffle(patches)
        # Remove random elements so the exact number is returned.
        return patches[:number]

    def make_clusters(self, number=5):
        pca_features = np.array(Pool().map(extract_image_pca_features, self.images))
        pca_features = PCA(n_components=2, copy=False).fit_transform(pca_features)

        features = np.array(Pool().map(extract_image_features, self.images))
        features = np.append(features, pca_features, axis=1)

        # axis=0 to scale each feature and not each sample.
        new_features = minmax_scale(features, axis=0)

        labels = KMeans(n_clusters=number).fit_predict(new_features)
        clusters = [[] for i in range(number)]
        for label, image in zip(labels, self.images):
            clusters[label].append(image)

        return [ImageCollection(cluster) for cluster in clusters]
