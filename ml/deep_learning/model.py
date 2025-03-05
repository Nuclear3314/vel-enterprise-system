import tensorflow as tf
from tensorflow.keras.models import Sequential
from tensorflow.keras.layers import Dense, LSTM, Dropout

class VELDeepLearning:
    def __init__(self):
        self.model = self.build_model()
        
    def build_model(self):
        model = Sequential([
            LSTM(64, input_shape=(None, 1), return_sequences=True),
            Dropout(0.2),
            LSTM(32),
            Dense(16, activation='relu'),
            Dense(1)
        ])
        model.compile(optimizer='adam', loss='mse')
        return model