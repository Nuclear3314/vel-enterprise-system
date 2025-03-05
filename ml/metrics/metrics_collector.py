import asyncio
from typing import Dict, List
import aiohttp
import time

class MetricsCollector:
    def __init__(self):
        self.metrics_cache = {}
        self.cache_ttl = 60  # 60 秒快取時間
        
    async def collect_metrics(self) -> Dict[str, Any]:
        current_time = time.time()
        
        if self._is_cache_valid(current_time):
            return self.metrics_cache
            
        metrics = await self._gather_all_metrics()
        self.metrics_cache = {
            'data': metrics,
            'timestamp': current_time
        }
        
        return metrics