from typing import Dict, List
import jwt
import logging
from datetime import datetime, timedelta

class PermissionManager:
    def __init__(self, config: Dict):
        self.logger = logging.getLogger('auth')
        self.config = config
        self.secret_key = config['jwt_secret']
        self.permissions_cache = {}
        
    async def verify_permissions(self, token: str, required_permission: str) -> bool:
        try:
            payload = jwt.decode(token, self.secret_key, algorithms=["HS256"])
            user_permissions = await self._get_user_permissions(payload['user_id'])
            return required_permission in user_permissions
        except Exception as e:
            self.logger.error(f"權限驗證失敗: {str(e)}")
            return False