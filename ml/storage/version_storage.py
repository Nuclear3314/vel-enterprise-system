import lmdb
import pickle
from typing import Any, Optional

class VersionStorage:
    def __init__(self, path: str):
        self.env = lmdb.open(path, map_size=1099511627776)  # 1TB
        
    def store_version(self, version_id: str, data: Any) -> bool:
        try:
            with self.env.begin(write=True) as txn:
                txn.put(
                    key=version_id.encode(),
                    value=pickle.dumps(data)
                )
            return True
        except Exception as e:
            print(f"存儲失敗: {str(e)}")
            return False