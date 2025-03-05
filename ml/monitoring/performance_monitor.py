import numpy as np
from sklearn.metrics import mean_squared_error, r2_score
import json
import logging
import psutil
import asyncio
from prometheus_client import start_http_server, Gauge
from typing import Dict, Any

class ModelPerformanceMonitor:
    def __init__(self):
        self.metrics = {}
        self.logger = logging.getLogger('vel_model_monitor')
        
    def evaluate_model(self, y_true, y_pred):
        self.metrics = {
            'mse': float(mean_squared_error(y_true, y_pred)),
            'r2': float(r2_score(y_true, y_pred)),
            'mae': float(np.mean(np.abs(y_true - y_pred)))
        }
        return self.metrics

class PerformanceMonitor:
    def __init__(self, port: int = 8000):
        self.metrics = {
            'inference_latency': Gauge('inference_latency', 'Model inference latency in ms'),
            'throughput': Gauge('throughput', 'Requests per second'),
            'memory_usage': Gauge('memory_usage', 'Memory usage percentage'),
            'gpu_utilization': Gauge('gpu_utilization', 'GPU utilization percentage')
        }
        start_http_server(port)
        
    async def start_monitoring(self):
        while True:
            self._collect_metrics()
            await asyncio.sleep(1)