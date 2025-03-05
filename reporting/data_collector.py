import pandas as pd
from typing import List, Dict

class DataCollector:
    def __init__(self, sources: List[str]):
        self.sources = sources
        
    async def collect_data(self) -> Dict:
        collected_data = {}
        for source in self.sources:
            try:
                data = await self._fetch_source_data(source)
                collected_data[source] = data
            except Exception as e:
                self.logger.error(f"資料收集失敗 {source}: {str(e)}")
                
        return collected_data