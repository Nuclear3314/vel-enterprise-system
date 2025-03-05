from sklearn.model_selection import GridSearchCV
import tensorflow as tf

class ModelTuner:
    def __init__(self):
        self.param_grid = {
            'batch_size': [32, 64, 128],
            'epochs': [50, 100, 150],
            'optimizer': ['adam', 'rmsprop'],
            'learning_rate': [0.001, 0.0001]
        }
        
    def tune_model(self, model, X_train, y_train):
        tuner = tf.keras.tuner.RandomSearch(
            model,
            objective='val_loss',
            max_trials=5,
            directory='tuning_logs'
        )
        return tuner.search(X_train, y_train, epochs=50)