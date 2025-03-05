import asyncpg
from typing import Dict, List

class ErrorStorage:
    def __init__(self, db_config: Dict):
        self.config = db_config
        
    async def store_error(self, error_data: Dict):
        async with asyncpg.create_pool(**self.config) as pool:
            async with pool.acquire() as conn:
                await conn.execute('''
                    INSERT INTO error_logs 
                    (error_type, message, stack_trace, context, created_at)
                    VALUES ($1, $2, $3, $4, $5)
                ''', error_data['error_type'],
                     error_data['message'],
                     error_data['stack_trace'],
                     error_data['context'],
                     error_data['timestamp'])