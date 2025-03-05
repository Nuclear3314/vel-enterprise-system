import mysql.connector
from typing import Dict

class DatabaseHealthChecker:
    def __init__(self, config: Dict):
        self.config = config
        
    async def check_connection(self) -> Dict:
        try:
            conn = mysql.connector.connect(**self.config)
            cursor = conn.cursor()
            cursor.execute("SELECT 1")
            return {
                'status': 'healthy',
                'latency': self._measure_query_latency()
            }
        except Exception as e:
            return {
                'status': 'unhealthy',
                'error': str(e)
            }