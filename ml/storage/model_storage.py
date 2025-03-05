import lmdb
import pickle
from pathlib import Path

class OptimizedModelStorage:
    def __init__(self, storage_path: str):
        self.env = lmdb.open(storage_path, 
                           map_size=10*(1024*1024*1024),  # 10GB
                           subdir=True,
                           map_async=True)
        
    def save_model(self, model_id: str, model) -> bool:
        try:
            with self.env.begin(write=True) as txn:
                txn.put(model_id.encode(), 
                       pickle.dumps(model))
            return True
        except Exception as e:
            print(f"儲存失敗: {str(e)}")
            return False