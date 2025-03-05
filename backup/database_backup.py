import aiomysql
from typing import Dict
import gzip
import os

class DatabaseBackup:
    def __init__(self, db_config: Dict):
        self.config = db_config
        
    async def backup_database(self) -> Dict:
        backup_path = os.path.join(
            self.config['backup_dir'],
            f"db_backup_{datetime.now().strftime('%Y%m%d_%H%M%S')}.sql.gz"
        )
        
        try:
            async with aiomysql.create_pool(**self.config) as pool:
                async with pool.acquire() as conn:
                    async with conn.cursor() as cursor:
                        await self._dump_database(cursor, backup_path)
                        
            return {
                'status': 'success',
                'path': backup_path,
                'size': os.path.getsize(backup_path)
            }
        except Exception as e:
            return {'status': 'error', 'message': str(e)}