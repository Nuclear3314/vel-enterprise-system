import numpy as np
import psutil
import torch
from typing import Dict, Any

class ResourceOptimizer:
    def __init__(self):
        self.device = torch.device('cuda' if torch.cuda.is_available() else 'cpu')
        self.memory_threshold = 0.85  # 85% 記憶體使用率閾值
        self.cpu_threshold = 0.90

    def optimize_resources(self) -> Dict:
        cpu_usage = psutil.cpu_percent(interval=1)
        memory_usage = psutil.virtual_memory().percent
        
        return {
            'should_scale': self._check_scaling_needs(cpu_usage, memory_usage),
            'recommended_workers': self._calculate_optimal_workers()
        }