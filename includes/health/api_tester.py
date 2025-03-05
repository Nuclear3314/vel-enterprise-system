import aiohttp
from typing import List, Dict

class APIHealthCheck:
    def __init__(self, endpoints: List[str]):
        self.endpoints = endpoints
        
    async def check_endpoints(self) -> Dict:
        results = {}
        async with aiohttp.ClientSession() as session:
            for endpoint in self.endpoints:
                results[endpoint] = await self._test_endpoint(session, endpoint)
        return results