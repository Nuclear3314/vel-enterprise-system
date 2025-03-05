import pytest
import logging
from typing import Dict, List

class AutomatedTestManager:
    def __init__(self):
        self.logger = logging.getLogger('test_manager')
        self.test_suites: Dict[str, List[str]] = {
            'model': ['test_accuracy', 'test_performance'],
            'api': ['test_endpoints', 'test_responses'],
            'integration': ['test_pipeline', 'test_workflow']
        }
    
    def run_test_suite(self, suite_name: str) -> Dict:
        try:
            if suite_name not in self.test_suites:
                raise ValueError(f"找不到測試套件: {suite_name}")
                
            results = pytest.main(['-v', f'tests/{suite_name}'])
            return {
                'suite': suite_name,
                'status': 'success' if results == 0 else 'failed',
                'details': self._collect_test_results()
            }
        except Exception as e:
            self.logger.error(f"測試執行失敗: {str(e)}")
            return {'status': 'error', 'message': str(e)}