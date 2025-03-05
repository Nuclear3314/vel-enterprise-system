from typing import Dict, Optional

class ScalingStrategy:
    def __init__(self, config: Dict):
        self.thresholds = config['thresholds']
        self.cooldown_period = config['cooldown_period']
        
    def calculate_new_replicas(self, current: int, metrics: Dict) -> Optional[int]:
        if self._is_in_cooldown():
            return None
            
        if metrics['cpu'] > self.thresholds['cpu_high']:
            return current + 1
        elif metrics['cpu'] < self.thresholds['cpu_low']:
            return max(1, current - 1)
        
        return None