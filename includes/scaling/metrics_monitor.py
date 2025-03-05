import psutil
import asyncio
from prometheus_client import Gauge

class MetricsMonitor:
    def __init__(self):
        self.cpu_usage = Gauge('cpu_usage', 'CPU usage percentage')
        self.memory_usage = Gauge('memory_usage', 'Memory usage percentage')
        self.request_count = Gauge('request_count', 'Number of requests')
        
    async def collect_metrics(self) -> Dict:
        return {
            'cpu': psutil.cpu_percent(),
            'memory': psutil.virtual_memory().percent,
            'load': await self._get_load_average()
        }