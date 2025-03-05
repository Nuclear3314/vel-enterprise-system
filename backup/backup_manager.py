from typing import Dict, List
import logging
import asyncio
from datetime import datetime

class BackupManager:
    def __init__(self, config: Dict):
        self.logger = logging.getLogger('backup')
        self.config = config
        self.backup_types = {
            'full': self._create_full_backup,
            'incremental': self._create_incremental_backup,
            'differential': self._create_differential_backup
        }
        
    async def start_backup(self, backup_type: str) -> Dict:
        start_time = datetime.now()
        try:
            if backup_type not in self.backup_types:
                raise ValueError(f"不支援的備份類型: {backup_type}")
                
            result = await self.backup_types[backup_type]()
            return {
                'status': 'success',
                'type': backup_type,
                'start_time': start_time,
                'end_time': datetime.now(),
                'details': result
            }
        except Exception as e:
            self.logger.error(f"備份失敗: {str(e)}")
            return {'status': 'error', 'message': str(e)}