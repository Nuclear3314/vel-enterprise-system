import psutil
from typing import Dict
import asyncio

class MetricsCollector:
    def __init__(self):
        self.metrics_cache = {}
        
    async def collect_metrics(self) -> Dict:
        try:
            return {
                "cpu": await self._get_cpu_metrics(),
                "memory": await self._get_memory_metrics(),
                "disk": await self._get_disk_metrics(),
                "network": await self._get_network_metrics()
            }
        except Exception as e:
            self.logger.error(f"收集指標失敗: {str(e)}")
            return {}