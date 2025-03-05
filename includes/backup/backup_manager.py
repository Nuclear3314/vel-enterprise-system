from typing import Dict
import shutil
import os
from datetime import datetime

class BackupManager:
    def __init__(self, config: Dict):
        self.backup_dir = config['backup_dir']
        self.retention_days = config.get('retention_days', 30)
        os.makedirs(self.backup_dir, exist_ok=True)
        
    async def create_backup(self) -> Dict:
        timestamp = datetime.now().strftime('%Y%m%d_%H%M%S')
        backup_path = os.path.join(self.backup_dir, f'backup_{timestamp}')
        
        try:
            # 建立資料庫備份
            await self._backup_database(backup_path)
            # 建立檔案備份
            await self._backup_files(backup_path)
            # 清理舊備份
            self._cleanup_old_backups()
            
            return {
                'status': 'success',
                'backup_path': backup_path,
                'timestamp': timestamp
            }
        except Exception as e:
            return {
                'status': 'error',
                'error': str(e)
            }