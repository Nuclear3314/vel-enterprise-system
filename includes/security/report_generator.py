import jinja2
from typing import Dict
import pandas as pd

class SecurityReportGenerator:
    def __init__(self, template_path: str):
        self.template_loader = jinja2.FileSystemLoader(searchpath="./templates")
        self.template_env = jinja2.Environment(loader=self.template_loader)
        
    def generate_report(self, scan_results: Dict) -> str:
        template = self.template_env.get_template('security_report.html')
        
        report_data = {
            'timestamp': scan_results['timestamp'],
            'summary': self._generate_summary(scan_results),
            'details': self._generate_details(scan_results),
            'recommendations': self._generate_recommendations(scan_results)
        }
        
        return template.render(**report_data)