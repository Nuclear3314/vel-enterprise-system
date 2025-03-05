from sklearn.ensemble import IsolationForest
import numpy as np
import pandas as pd

class LogAnomalyDetector:
    def __init__(self, config: Dict):
        self.model = IsolationForest(
            contamination=config.get('contamination', 0.1),
            random_state=42
        )
        
    async def detect_anomalies(self, log_data: pd.DataFrame) -> Dict:
        try:
            features = self._extract_features(log_data)
            predictions = self.model.fit_predict(features)
            
            return {
                'anomalies': self._process_anomalies(log_data[predictions == -1]),
                'total_records': len(log_data),
                'anomaly_count': len(predictions[predictions == -1])
            }
        except Exception as e:
            raise AnomalyDetectionError(f"異常檢測失敗: {str(e)}")