from typing import Dict, List
import asyncio
import logging

class HealthCheckManager:
    def __init__(self, config: Dict):
        self.logger = logging.getLogger('health_check')
        self.checks = {
            'system': self._check_system_health,
            'database': self._check_database_health,
            'api': self._check_api_health,
            'cache': self._check_cache_health
        }
        self.config = config
        
    async def run_health_checks(self) -> Dict:
        results = {}
        for check_name, check_func in self.checks.items():
            try:
                results[check_name] = await check_func()
            except Exception as e:
                self.logger.error(f"健康檢查失敗 {check_name}: {str(e)}")
                results[check_name] = {'status': 'error', 'error': str(e)}
        return results