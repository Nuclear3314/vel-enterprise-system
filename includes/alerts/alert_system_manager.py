from typing import Dict, List
import asyncio
import logging

class AlertSystemManager:
    def __init__(self):
        self.logger = logging.getLogger('alert_system')
        self.thresholds = {
            'cpu_usage': 80,  # CPU 使用率 > 80%
            'memory_usage': 85,  # 記憶體使用率 > 85%
            'response_time': 2000  # 回應時間 > 2秒
        }
        
    async def check_metrics(self, metrics: Dict) -> List[Dict]:
        alerts = []
        for metric_name, value in metrics.items():
            if metric_name in self.thresholds:
                if value > self.thresholds[metric_name]:
                    alerts.append(self._create_alert(metric_name, value))
        return alerts