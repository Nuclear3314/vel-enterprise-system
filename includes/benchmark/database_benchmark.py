import aiomysql
from typing import List

class DatabaseBenchmark:
    def __init__(self, db_config: Dict):
        self.db_config = db_config
        
    async def measure_query_performance(self, queries: List[str]) -> Dict:
        results = {}
        async with aiomysql.create_pool(**self.db_config) as pool:
            async with pool.acquire() as conn:
                for query in queries:
                    start_time = time.perf_counter()
                    await conn.execute(query)
                    execution_time = time.perf_counter() - start_time
                    results[query] = execution_time
        return results