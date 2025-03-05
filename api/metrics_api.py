from fastapi import FastAPI, HTTPException
from typing import Dict
import asyncio

app = FastAPI()

@app.get("/api/metrics")
async def get_metrics() -> Dict:
    try:
        return {
            "system": await collect_system_metrics(),
            "performance": await collect_performance_metrics(),
            "timestamp": datetime.now().isoformat()
        }
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))