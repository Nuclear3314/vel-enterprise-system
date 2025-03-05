import aiohttp
from typing import List, Dict

class APIDiagnostics:
    def __init__(self, base_url: str):
        self.base_url = base_url
        
    async def check_endpoints(self, endpoints: List[str]) -> Dict:
        results = {}
        async with aiohttp.ClientSession() as session:
            for endpoint in endpoints:
                results[endpoint] = await self._check_endpoint(session, endpoint)
        return results