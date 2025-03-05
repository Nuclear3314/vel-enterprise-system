import unittest
import asyncio
from typing import Dict

class TestManager:
    def __init__(self, config: Dict):
        self.config = config
        self.test_suites = {
            'api': self._run_api_tests,
            'database': self._run_db_tests,
            'integration': self._run_integration_tests
        }
        
    async def run_tests(self, suite: str = 'all') -> Dict:
        results = {
            'passed': [],
            'failed': [],
            'errors': []
        }
        
        if suite == 'all':
            for name, test_suite in self.test_suites.items():
                results.update(await test_suite())
        else:
            results.update(await self.test_suites[suite]())
            
        return results