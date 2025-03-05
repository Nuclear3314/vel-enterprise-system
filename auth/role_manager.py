from typing import Dict, List
import asyncpg

class RoleManager:
    def __init__(self, db_config: Dict):
        self.db_config = db_config
        
    async def assign_role(self, user_id: int, role_name: str) -> Dict:
        async with asyncpg.create_pool(**self.db_config) as pool:
            async with pool.acquire() as conn:
                try:
                    await conn.execute('''
                        INSERT INTO user_roles (user_id, role_name)
                        VALUES ($1, $2)
                    ''', user_id, role_name)
                    return {'status': 'success'}
                except Exception as e:
                    return {'status': 'error', 'message': str(e)}