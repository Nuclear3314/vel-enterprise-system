from typing import Dict, List
import asyncio
import logging

class AlertManager:
    def __init__(self):
        self.alert_levels = {
            'critical': 0,
            'warning': 1,
            'info': 2
        }
        self.channels = []
        self.logger = logging.getLogger('alert_manager')
    
    async def trigger_alert(self, level: str, message: str, context: Dict = None):
        if level not in self.alert_levels:
            raise ValueError(f"無效的警報級別: {level}")
            
        alert = {
            'level': level,
            'message': message,
            'context': context,
            'timestamp': datetime.now().isoformat()
        }
        
        await self._notify_all_channels(alert)