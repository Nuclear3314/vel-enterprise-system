import pandas as pd
import numpy as np
from typing import Dict, List

class ModelComparator:
    def __init__(self):
        self.metrics = ['accuracy', 'latency', 'memory_usage']
        
    def compare_models(self, model_results: Dict[str, Dict]) -> pd.DataFrame:
        comparison_data = []
        
        for model_id, results in model_results.items():
            metrics = {
                'model_id': model_id,
                'total_score': self._calculate_score(results)
            }
            metrics.update(results)
            comparison_data.append(metrics)
            
        return pd.DataFrame(comparison_data)