import tensorflow as tf
import numpy as np
from sklearn.preprocessing import MinMaxScaler

class VELPredictor:
    def __init__(self):
        self.model = None
        self.scaler = MinMaxScaler()
        self.sequence_length = 10
        
    def build_model(self):
        self.model = tf.keras.Sequential([
            tf.keras.layers.LSTM(50, input_shape=(self.sequence_length, 1)),
            tf.keras.layers.Dense(1)
        ])
        self.model.compile(optimizer='adam', loss='mse')