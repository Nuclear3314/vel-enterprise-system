import asyncio
from datetime import datetime

class RepairMonitor:
    def __init__(self):
        self.repair_history = []
        
    async def monitor_repair(self, repair_action: Dict):
        start_time = datetime.now()
        
        try:
            result = await self._execute_repair(repair_action)
            end_time = datetime.now()
            
            self.repair_history.append({
                'action': repair_action,
                'result': result,
                'duration': (end_time - start_time).total_seconds()
            })
            
            return result
        except Exception as e:
            self._log_repair_failure(repair_action, str(e))
            raise