from locust import HttpUser, task, between
from typing import Dict, List
import asyncio

class LoadTestManager:
    def __init__(self, config: Dict):
        self.target_url = config['target_url']
        self.num_users = config['num_users']
        self.spawn_rate = config['spawn_rate']
        self.test_duration = config['duration']
        
    class VELUser(HttpUser):
        wait_time = between(1, 3)
        
        @task
        def test_endpoint(self):
            self.client.get("/api/status")
            self.client.post("/api/data", json={"test": "data"})