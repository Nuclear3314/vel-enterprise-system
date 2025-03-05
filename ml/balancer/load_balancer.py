import asyncio
from collections import deque
from typing import List, Dict

class ModelLoadBalancer:
    def __init__(self, worker_nodes: List[str]):
        self.workers = deque(worker_nodes)
        self.worker_loads: Dict[str, int] = {worker: 0 for worker in worker_nodes}
        
    async def get_next_worker(self) -> str:
        worker = self.workers[0]
        self.workers.rotate(-1)
        return worker