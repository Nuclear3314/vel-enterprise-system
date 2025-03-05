from typing import Dict, List
import pytest
import asyncio
import logging

class IntegrationTestManager:
    def __init__(self, config: Dict):
        self.logger = logging.getLogger('integration_tests')
        self.config = config
        self.test_suites = {
            'api': self._test_api_integration,
            'database': self._test_database_integration,
            'cache': self._test_cache_integration
        }
    
    async def run_test_suite(self, suite_name: str = 'all') -> Dict:
        results = {
            'passed': [],
            'failed': [],
            'errors': []
        }
        
        if suite_name == 'all':
            for name, suite in self.test_suites.items():
                results.update(await suite())
        else:
            results.update(await self.test_suites[suite_name]())
            
        return results