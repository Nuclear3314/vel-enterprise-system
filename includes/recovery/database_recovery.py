import mysql.connector
import gzip
import os

class DatabaseRecovery:
    def __init__(self, db_config: Dict):
        self.db_config = db_config
        
    async def restore_database(self, backup_file: str) -> bool:
        try:
            # 解壓縮備份檔案
            if backup_file.endswith('.gz'):
                with gzip.open(backup_file, 'rt') as f:
                    sql_content = f.read()
            else:
                with open(backup_file, 'r') as f:
                    sql_content = f.read()
                    
            # 執行還原
            conn = mysql.connector.connect(**self.db_config)
            cursor = conn.cursor()
            
            for statement in sql_content.split(';'):
                if statement.strip():
                    cursor.execute(statement)
                    
            conn.commit()
            return True
            
        except Exception as e:
            raise RecoveryError(f"資料庫還原失敗: {str(e)}")