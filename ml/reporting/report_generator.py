import pandas as pd
import matplotlib.pyplot as plt
from jinja2 import Template

class PerformanceReport:
    def __init__(self, benchmark_data: dict):
        self.data = pd.DataFrame(benchmark_data)
        self.template_path = 'templates/performance_report.html'

    def generate_report(self) -> str:
        figures = self.generate_plots()
        stats = self.calculate_statistics()
        
        with open(self.template_path) as f:
            template = Template(f.read())
        
        return template.render(
            figures=figures,
            stats=stats,
            timestamp=pd.Timestamp.now()
        )