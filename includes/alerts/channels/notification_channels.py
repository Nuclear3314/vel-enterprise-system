from abc import ABC, abstractmethod
import requests
import smtplib
from email.message import EmailMessage

class NotificationChannel(ABC):
    @abstractmethod
    async def send(self, alert: dict):
        pass

class EmailChannel(NotificationChannel):
    def __init__(self, config: dict):
        self.smtp_config = config
        
    async def send(self, alert: dict):
        msg = EmailMessage()
        msg.set_content(alert['message'])
        msg['Subject'] = f"VEL Alert: {alert['level']}"
        msg['From'] = self.smtp_config['from']
        msg['To'] = self.smtp_config['to']