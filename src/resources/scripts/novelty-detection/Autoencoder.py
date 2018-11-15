# See https://github.com/tensorflow/models/blob/628b970a3d7c59a3b65220e24972f9987e879bca/research/autoencoder/autoencoder_models/Autoencoder.py
import numpy as np
import tensorflow as tf

class Autoencoder(object):

    def __init__(self, n_layers, transfer_function=tf.nn.softplus, optimizer=tf.train.AdamOptimizer()):
        self.n_layers = n_layers
        self.transfer = transfer_function

        self.weights = self._initialize_weights()

        # model
        self.x = tf.placeholder(tf.float32, [None, self.n_layers[0]])
        self.hidden_encode, self.hidden_recon = self._plug(self.x)
        self.reconstruction = self.hidden_recon[-1]

        # cost
        self.cost = tf.losses.mean_squared_error(self.reconstruction, self.x)
        self.optimizer = optimizer.minimize(self.cost)

        self.sess = tf.Session()
        self._initialize()

    def _initialize_weights(self):
        all_weights = dict()
        initializer = tf.contrib.layers.xavier_initializer()
        # Encoding network weights
        encoder_weights = []
        for layer in range(len(self.n_layers)-1):
            kind = initializer if layer == 0 else tf.zeros
            w = tf.Variable(
                kind((self.n_layers[layer], self.n_layers[layer + 1]),
                            dtype=tf.float32))
            b = tf.Variable(
                tf.zeros([self.n_layers[layer + 1]], dtype=tf.float32))
            encoder_weights.append({'w': w, 'b': b})
        # Recon network weights
        recon_weights = []
        for layer in range(len(self.n_layers)-1, 0, -1):
            w = tf.Variable(
                tf.zeros((self.n_layers[layer], self.n_layers[layer - 1]),
                            dtype=tf.float32))
            b = tf.Variable(
                tf.zeros([self.n_layers[layer - 1]], dtype=tf.float32))
            recon_weights.append({'w': w, 'b': b})
        all_weights['encode'] = encoder_weights
        all_weights['recon'] = recon_weights
        return all_weights

    '''
    Takes an input tensor and returns the encoding and reconstruction layers.
    '''
    def _plug(self, input_tensor):
        hidden_encode = []
        h = input_tensor
        for layer in range(len(self.n_layers)-1):
            h = self.transfer(
                tf.add(tf.matmul(h, self.weights['encode'][layer]['w']),
                       self.weights['encode'][layer]['b']))
            hidden_encode.append(h)

        hidden_recon = []
        for layer in range(len(self.n_layers)-1):
            h = self.transfer(
                tf.add(tf.matmul(h, self.weights['recon'][layer]['w']),
                       self.weights['recon'][layer]['b']))
            hidden_recon.append(h)

        return hidden_encode, hidden_recon

    def _initialize(self):
        self.sess.run(tf.global_variables_initializer())

    '''
    Takes an input tensor (an array of flattened images) and returns the tensor that
    calculates the reconstruction error for each inpput element. These method can be
    used to apply the trained autoencoder in an external TF pipeline.
    '''
    def element_wise_cost(self, input_tensor):
        encode, recon = self._plug(input_tensor)
        return tf.reduce_mean(tf.square(recon[-1] - input_tensor), 1)

    def partial_fit(self, X):
        cost, opt = self.sess.run((self.cost, self.optimizer), feed_dict={self.x: X})
        return cost

    def calc_total_cost(self, X):
        return self.sess.run(self.cost, feed_dict={self.x: X})

    def transform(self, X):
        return self.sess.run(self.hidden_encode[-1], feed_dict={self.x: X})

    def generate(self, hidden=None):
        if hidden is None:
            hidden = np.random.normal(size=self.weights['encode'][-1]['b'])
        return self.sess.run(self.reconstruction, feed_dict={self.hidden_encode[-1]: hidden})

    def reconstruct(self, X):
        return self.sess.run(self.reconstruction, feed_dict={self.x: X})

    def getWeights(self):
        raise NotImplementedError
        return self.sess.run(self.weights)

    def getBiases(self):
        raise NotImplementedError
        return self.sess.run(self.weights)
