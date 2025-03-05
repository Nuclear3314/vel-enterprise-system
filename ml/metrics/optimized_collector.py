import asyncio
from typing import Dict, Any
import aiohttp
import time

class OptimizedMetricsCollector:
    def __init__(self):
        self.metrics_cache = {}
        self.cache_ttl = 60  # 快取時效60秒
        
    async def collect_metrics(self) -> Dict[str, Any]:
        if self._is_cache_valid():
            return self.metrics_cache
            
        async with aiohttp.ClientSession() as session:
            tasks = [
                self._collect_model_metrics(session),
                self._collect_system_metrics(session),
                self._collect_api_metrics(session)
            ]
            results = await asyncio.gather(*tasks)
            
        self.metrics_cache = self._merge_results(results)
        return self.metrics_cache