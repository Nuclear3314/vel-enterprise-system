from kubernetes import client, config
import numpy as np

class AutoScaler:
    def __init__(self):
        config.load_kube_config()
        self.v1 = client.AppsV1Api()
        self.thresholds = {
            'cpu': 80,  # 80% CPU 使用率
            'memory': 85,  # 85% 記憶體使用率
            'latency': 100  # 100ms 延遲閾值
        }