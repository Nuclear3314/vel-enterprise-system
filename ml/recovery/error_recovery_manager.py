from typing import Dict, Any
import logging
import traceback

class ErrorRecoveryManager:
    def __init__(self):
        self.logger = logging.getLogger('error_recovery')
        self.recovery_strategies = {
            'model_error': self._handle_model_error,
            'memory_error': self._handle_memory_error,
            'network_error': self._handle_network_error
        }
        
    async def handle_error(self, error_type: str, error_context: Dict[str, Any]):
        try:
            if error_type in self.recovery_strategies:
                await self.recovery_strategies[error_type](error_context)
            else:
                await self._handle_unknown_error(error_context)
        except Exception as e:
            self.logger.error(f"錯誤恢復失敗: {str(e)}")
            self.logger.error(traceback.format_exc())