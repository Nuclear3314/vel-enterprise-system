from fastapi import FastAPI, Depends, HTTPException
from typing import Dict

app = FastAPI()

@app.post("/auth/login")
async def login(credentials: Dict):
    try:
        token = await auth_manager.authenticate(
            credentials['username'],
            credentials['password']
        )
        return {"access_token": token}
    except Exception as e:
        raise HTTPException(status_code=401, detail=str(e))