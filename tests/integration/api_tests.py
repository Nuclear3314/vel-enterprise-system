import aiohttp
import pytest
from typing import Dict

class APIIntegrationTests:
    def __init__(self, base_url: str):
        self.base_url = base_url
        
    @pytest.mark.asyncio
    async def test_api_endpoints(self):
        async with aiohttp.ClientSession() as session:
            endpoints = ['/status', '/metrics', '/health']
            results = []
            
            for endpoint in endpoints:
                result = await self._test_endpoint(session, endpoint)
                results.append(result)
                
            return results