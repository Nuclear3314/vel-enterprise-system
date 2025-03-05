from fastapi import FastAPI, HTTPException
from typing import Dict
import asyncio

app = FastAPI()

@app.get("/api/dashboard/metrics")
async def get_metrics() -> Dict:
    try:
        metrics = await collect_system_metrics()
        alerts = await check_system_alerts()
        
        return {
            "status": "success",
            "metrics": metrics,
            "alerts": alerts,
            "timestamp": datetime.now().isoformat()
        }
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))