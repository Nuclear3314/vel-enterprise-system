import torch
from typing import Dict, Optional

class ModelHealthChecker:
    def __init__(self, model_path: str):
        self.model_path = model_path
        
    async def check_model_health(self) -> Dict:
        try:
            model = torch.load(self.model_path)
            return {
                'status': 'healthy',
                'memory_usage': self._get_model_memory_usage(model),
                'device': str(next(model.parameters()).device)
            }
        except Exception as e:
            return {
                'status': 'unhealthy',
                'error': str(e)
            }