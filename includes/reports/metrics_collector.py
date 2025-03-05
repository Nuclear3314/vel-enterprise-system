import asyncio
from typing import Dict, List
import aiohttp

class MetricsCollector:
    def __init__(self, endpoints: List[str]):
        self.endpoints = endpoints
        
    async def collect_metrics(self) -> Dict:
        async with aiohttp.ClientSession() as session:
            tasks = [self._fetch_metrics(session, endpoint) 
                    for endpoint in self.endpoints]
            results = await asyncio.gather(*tasks)
            
        return self._process_results(results)