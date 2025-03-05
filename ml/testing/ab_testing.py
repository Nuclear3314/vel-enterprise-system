from typing import Dict, Any
import numpy as np
from scipy import stats

class ABTesting:
    def __init__(self):
        self.experiments: Dict[str, Dict[str, Any]] = {}
        
    def create_experiment(self, name: str, control_version: str, test_version: str):
        self.experiments[name] = {
            'control': {'version': control_version, 'metrics': []},
            'test': {'version': test_version, 'metrics': []},
            'start_time': datetime.now()
        }