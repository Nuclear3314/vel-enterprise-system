from typing import Dict, List
import logging
import traceback
from datetime import datetime

class ErrorTrackingManager:
    def __init__(self, config: Dict):
        self.logger = logging.getLogger('error_tracking')
        self.config = config
        self.error_store = []
        
    async def track_error(self, error: Exception, context: Dict = None) -> Dict:
        error_data = {
            'timestamp': datetime.utcnow().isoformat(),
            'error_type': type(error).__name__,
            'message': str(error),
            'stack_trace': traceback.format_exc(),
            'context': context or {}
        }
        
        await self._store_error(error_data)
        await self._notify_if_critical(error_data)
        
        return error_data