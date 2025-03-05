from typing import Dict, List
import psutil
import logging
import asyncio

class SystemDiagnostics:
    def __init__(self):
        self.logger = logging.getLogger('diagnostics')
        self.check_items = {
            'system': self._check_system,
            'database': self._check_database,
            'cache': self._check_cache,
            'api': self._check_api
        }
    
    async def run_diagnostics(self) -> Dict:
        results = {}
        for name, check in self.check_items.items():
            try:
                results[name] = await check()
            except Exception as e:
                self.logger.error(f"診斷失敗 {name}: {str(e)}")
                results[name] = {'status': 'error', 'message': str(e)}
        return results