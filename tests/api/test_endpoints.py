import pytest
import aiohttp
from typing import Dict

class APITestCase:
    def __init__(self, base_url: str):
        self.base_url = base_url
        
    @pytest.mark.asyncio
    async def test_health_endpoint(self):
        async with aiohttp.ClientSession() as session:
            async with session.get(f"{self.base_url}/health") as response:
                assert response.status == 200
                data = await response.json()
                assert data['status'] == 'healthy'