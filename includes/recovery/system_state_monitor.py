import psutil
import aiohttp
from typing import Dict

class SystemStateMonitor:
    def __init__(self):
        self.critical_services = [
            'database',
            'api_gateway',
            'cache_service'
        ]
    
    async def check_system_state(self) -> Dict:
        state = {
            'services': await self._check_services(),
            'resources': self._check_resources(),
            'connectivity': await self._check_connectivity()
        }
        return self._evaluate_state(state)