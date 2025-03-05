import smtplib
import requests
from typing import Dict

class NotificationSender:
    def __init__(self, config: Dict):
        self.config = config
        self.channels = {
            'email': self._send_email,
            'slack': self._send_slack,
            'teams': self._send_teams
        }
        
    async def send_notification(self, alert: Dict, channels: List[str] = None) -> Dict:
        if not channels:
            channels = ['email']
            
        results = {}
        for channel in channels:
            try:
                results[channel] = await self.channels[channel](alert)
            except Exception as e:
                results[channel] = {'status': 'error', 'error': str(e)}
                
        return results