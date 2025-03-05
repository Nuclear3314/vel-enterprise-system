import jinja2
from typing import Dict
import plotly.express as px

class AnalysisReportGenerator:
    def __init__(self, template_dir: str):
        self.env = jinja2.Environment(
            loader=jinja2.FileSystemLoader(template_dir)
        )
        
    async def generate_report(self, analysis_results: Dict) -> str:
        template = self.env.get_template('analysis_report.html')
        plots = self._generate_plots(analysis_results)
        return template.render(
            results=analysis_results,
            plots=plots
        )