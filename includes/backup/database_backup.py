import mysql.connector
import gzip
import os

class DatabaseBackup:
    def __init__(self, db_config: Dict):
        self.db_config = db_config
        
    async def backup(self, backup_path: str) -> str:
        db_file = os.path.join(backup_path, 'database.sql.gz')
        
        try:
            # 執行 mysqldump
            cmd = f"mysqldump -h {self.db_config['host']} -u {self.db_config['user']} " \
                  f"-p{self.db_config['password']} {self.db_config['database']}"
                  
            with gzip.open(db_file, 'wt') as f:
                result = os.popen(cmd).read()
                f.write(result)
                
            return db_file
        except Exception as e:
            raise BackupError(f"資料庫備份失敗: {str(e)}")