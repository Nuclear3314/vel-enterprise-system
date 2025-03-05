import psutil
from typing import Dict
import asyncio
import time

class ResourceMonitor:
    def __init__(self):
        self.thresholds = {
            'cpu': 80,
            'memory': 85,
            'disk': 90
        }
        self.metrics = {}
        
    async def monitor_resources(self) -> Dict:
        return {
            'cpu': await self._monitor_cpu(),
            'memory': await self._monitor_memory(),
            'disk': await self._monitor_disk(),
            'network': await self._monitor_network()
        }
        
    async def collect_metrics(self) -> Dict:
        return {
            'cpu': self._get_cpu_metrics(),
            'memory': self._get_memory_metrics(),
            'disk': self._get_disk_metrics(),
            'network': self._get_network_metrics()
        }
        
    def _get_cpu_metrics(self) -> Dict:
        return {
            'usage_percent': psutil.cpu_percent(interval=1),
            'frequency': psutil.cpu_freq()._asdict(),
            'cores': psutil.cpu_count()
        }