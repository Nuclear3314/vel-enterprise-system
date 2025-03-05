from typing import Dict, List
import logging
import asyncio

class AlertManager:
    def __init__(self, config: Dict):
        self.logger = logging.getLogger('alert_manager')
        self.config = config
        self.alert_types = {
            'critical': self._handle_critical_alert,
            'warning': self._handle_warning_alert,
            'info': self._handle_info_alert
        }
        
    async def process_alert(self, alert_data: Dict) -> Dict:
        alert_type = alert_data.get('severity', 'info')
        
        try:
            if alert_type in self.alert_types:
                return await self.alert_types[alert_type](alert_data)
            else:
                return {'status': 'error', 'message': '未知的警報類型'}
        except Exception as e:
            self.logger.error(f"警報處理失敗: {str(e)}")
            return {'status': 'error', 'message': str(e)}