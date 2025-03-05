import aiohttp
from typing import List, Dict

class DeploymentValidator:
    def __init__(self, endpoints: List[str]):
        self.endpoints = endpoints
        
    async def validate_deployment(self) -> Dict:
        results = {}
        async with aiohttp.ClientSession() as session:
            for endpoint in self.endpoints:
                try:
                    async with session.get(endpoint) as response:
                        results[endpoint] = {
                            'status': response.status,
                            'response_time': response.elapsed.total_seconds()
                        }
                except Exception as e:
                    results[endpoint] = {
                        'status': 'error',
                        'message': str(e)
                    }
        return results