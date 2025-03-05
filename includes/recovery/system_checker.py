from typing import List, Dict
import aiohttp

class SystemChecker:
    def __init__(self):
        self.check_points = [
            'database_connection',
            'file_integrity',
            'service_status'
        ]
        
    async def verify_system(self) -> Dict:
        results = {}
        for check in self.check_points:
            results[check] = await getattr(self, f'_check_{check}')()
        return results