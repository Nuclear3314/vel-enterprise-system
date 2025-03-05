from typing import Dict, List
import pandas as pd
import plotly.express as px
import asyncio

class PerformanceReportManager:
    def __init__(self, config: Dict):
        self.config = config
        self.report_types = {
            'daily': self._generate_daily_report,
            'weekly': self._generate_weekly_report,
            'monthly': self._generate_monthly_report
        }
    
    async def generate_report(self, report_type: str) -> Dict:
        if report_type not in self.report_types:
            raise ValueError(f"不支援的報告類型: {report_type}")
            
        return await self.report_types[report_type]()