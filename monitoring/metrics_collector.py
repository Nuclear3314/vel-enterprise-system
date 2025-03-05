import psutil
import asyncio
from typing import Dict

class MetricsCollector:
    def __init__(self):
        self.metrics = {}
        
    async def collect_metrics(self) -> Dict:
        return {
            'cpu': await self._collect_cpu_metrics(),
            'memory': await self._collect_memory_metrics(),
            'disk': await self._collect_disk_metrics(),
            'network': await self._collect_network_metrics()
        }
        
    async def _collect_cpu_metrics(self) -> Dict:
        return {
            'usage': psutil.cpu_percent(interval=1),
            'count': psutil.cpu_count(),
            'frequency': psutil.cpu_freq()._asdict()
        }