import pandas as pd
import matplotlib.pyplot as plt
from datetime import datetime
import jinja2

class PerformanceReportGenerator:
    def __init__(self, template_path: str):
        self.template_loader = jinja2.FileSystemLoader(searchpath="./templates")
        self.template_env = jinja2.Environment(loader=self.template_loader)
        
    async def generate_report(self, data: dict) -> str:
        template = self.template_env.get_template('performance_report.html')
        plots = self._generate_plots(data)
        
        return template.render(
            timestamp=datetime.now().strftime("%Y-%m-%d %H:%M:%S"),
            metrics=data,
            plots=plots
        )