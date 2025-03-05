from prometheus_client import Counter, Gauge
import logging

class CacheMonitor:
    def __init__(self):
        self.hit_counter = Counter('cache_hits_total', 'Cache hit count')
        self.miss_counter = Counter('cache_misses_total', 'Cache miss count')
        self.size_gauge = Gauge('cache_size_bytes', 'Current cache size in bytes')
        self.logger = logging.getLogger('cache_monitor')
        
    async def record_metrics(self, hit: bool, size: int):
        if hit:
            self.hit_counter.inc()
        else:
            self.miss_counter.inc()
        self.size_gauge.set(size)