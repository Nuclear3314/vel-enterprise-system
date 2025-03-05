from typing import Any, Optional
import redis
import json
import asyncio

class RedisCacheManager:
    def __init__(self, config: dict):
        self.redis = redis.Redis(
            host=config['host'],
            port=config['port'],
            db=config['db']
        )
        self.default_ttl = config.get('default_ttl', 3600)
        
    async def get_or_set(self, key: str, callback, ttl: int = None) -> Any:
        value = await self.get(key)
        if value is None:
            value = await callback()
            await self.set(key, value, ttl or self.default_ttl)
        return value