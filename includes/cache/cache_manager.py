import redis
from typing import Any, Optional

class CacheManager:
    def __init__(self, redis_config: Dict):
        self.redis = redis.Redis(**redis_config)
        self.default_ttl = 3600  # 1小時
        
    async def get_cached(self, key: str) -> Optional[Any]:
        try:
            value = self.redis.get(key)
            return value.decode() if value else None
        except Exception as e:
            self.logger.error(f"快取讀取失敗: {str(e)}")
            return None