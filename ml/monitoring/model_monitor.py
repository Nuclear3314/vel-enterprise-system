from prometheus_client import start_http_server, Gauge
import numpy as np
import time

class ModelMonitor:
    def __init__(self, port=8000):
        self.model_latency = Gauge('model_inference_latency', 'Model inference latency in seconds')
        self.model_accuracy = Gauge('model_accuracy', 'Model accuracy score')
        self.prediction_count = Gauge('prediction_count', 'Number of predictions made')
        start_http_server(port)

    def record_metrics(self, latency, accuracy):
        self.model_latency.set(latency)
        self.model_accuracy.set(accuracy)
        self.prediction_count.inc()