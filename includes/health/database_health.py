import mysql.connector
from typing import Dict

class DatabaseHealthCheck:
    def __init__(self, db_config: Dict):
        self.config = db_config
        
    async def check_health(self) -> Dict:
        try:
            start_time = time.time()
            conn = mysql.connector.connect(**self.config)
            connection_time = time.time() - start_time
            
            return {
                'status': 'healthy',
                'connection_time': connection_time,
                'active_connections': self._get_active_connections(conn)
            }
        except Exception as e:
            return {
                'status': 'unhealthy',
                'error': str(e)
            }