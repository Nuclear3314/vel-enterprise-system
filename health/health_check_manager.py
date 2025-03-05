from typing import Dict
import logging
import asyncio

class HealthCheckManager:
    def __init__(self, config: Dict):
        self.logger = logging.getLogger('health_checker')
        self.config = config
        self.checks = {
            'system': self._check_system_health,
            'database': self._check_database_health,
            'api': self._check_api_health,
            'services': self._check_services_health
        }
    
    async def run_health_check(self) -> Dict:
        results = {
            'status': 'checking',
            'timestamp': datetime.now().isoformat(),
            'checks': {}
        }
        
        for check_name, check_func in self.checks.items():
            try:
                results['checks'][check_name] = await check_func()
            except Exception as e:
                self.logger.error(f"{check_name} 檢查失敗: {str(e)}")
                results['checks'][check_name] = {
                    'status': 'error',
                    'error': str(e)
                }
        
        return results