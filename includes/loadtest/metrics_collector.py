import psutil
import asyncio
from datetime import datetime

class PerformanceCollector:
    def __init__(self):
        self.metrics = []
        
    async def collect_metrics(self):
        while True:
            metric = {
                'timestamp': datetime.now().isoformat(),
                'cpu': psutil.cpu_percent(),
                'memory': psutil.virtual_memory().percent,
                'network': self._get_network_stats()
            }
            self.metrics.append(metric)
            await asyncio.sleep(1)