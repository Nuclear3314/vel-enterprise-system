import torch
import ray
from typing import List, Dict

@ray.remote
class InferenceWorker:
    def __init__(self, model_path: str):
        self.model = torch.load(model_path)
        self.model.eval()
        
    def predict(self, data: torch.Tensor) -> torch.Tensor:
        with torch.no_grad():
            return self.model(data)

class DistributedInference:
    def __init__(self, num_workers: int = 4):
        ray.init()
        self.workers = [InferenceWorker.remote(model_path) for _ in range(num_workers)]