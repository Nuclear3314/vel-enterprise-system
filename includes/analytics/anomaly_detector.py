from sklearn.ensemble import IsolationForest
import numpy as np

class AnomalyDetector:
    def __init__(self, contamination: float = 0.1):
        self.model = IsolationForest(
            contamination=contamination,
            random_state=42
        )
        
    def detect_anomalies(self, data: np.ndarray) -> Dict:
        predictions = self.model.fit_predict(data)
        return {
            'anomalies': np.where(predictions == -1)[0],
            'scores': self.model.score_samples(data)
        }