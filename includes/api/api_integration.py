from fastapi import FastAPI, HTTPException
from typing import Dict

class APIIntegration:
    def __init__(self):
        self.app = FastAPI(title="VEL System API")
        self.register_routes()
        
    def register_routes(self):
        @self.app.get("/health")
        async def health_check():
            return {"status": "healthy"}
            
        @self.app.get("/metrics")
        async def get_metrics():
            return await self.collect_system_metrics()