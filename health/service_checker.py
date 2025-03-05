import aiohttp
from typing import List, Dict

class ServiceHealthChecker:
    def __init__(self, service_config: Dict):
        self.config = service_config
        
    async def check_services(self) -> Dict:
        results = {}
        async with aiohttp.ClientSession() as session:
            for service in self.config['services']:
                try:
                    status = await self._check_service(session, service)
                    results[service['name']] = status
                except Exception as e:
                    results[service['name']] = {
                        'status': 'error',
                        'message': str(e)
                    }
        return results