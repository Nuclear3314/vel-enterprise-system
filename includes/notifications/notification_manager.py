import smtplib
from email.message import EmailMessage
import requests

class NotificationManager:
    def __init__(self, config: Dict):
        self.config = config
        self.channels = {
            'email': self._send_email,
            'slack': self._send_slack,
            'line': self._send_line
        }
        
    async def send_alert(self, alert: Dict, channels: List[str] = None):
        if not channels:
            channels = ['email']  # 預設使用電子郵件
            
        for channel in channels:
            if channel in self.channels:
                await self.channels[channel](alert)