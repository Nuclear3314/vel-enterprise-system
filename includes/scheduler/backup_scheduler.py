from apscheduler.schedulers.asyncio import AsyncIOScheduler
from datetime import datetime

class BackupScheduler:
    def __init__(self):
        self.scheduler = AsyncIOScheduler()
        
    def schedule_backup(self, backup_manager, cron_expression: str):
        self.scheduler.add_job(
            backup_manager.create_backup,
            'cron',
            **self._parse_cron(cron_expression)
        )
        
    def start(self):
        self.scheduler.start()