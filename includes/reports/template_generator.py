import jinja2
from datetime import datetime

class ReportTemplateGenerator:
    def __init__(self, template_dir: str):
        self.env = jinja2.Environment(
            loader=jinja2.FileSystemLoader(template_dir)
        )
        
    def generate_html(self, template_name: str, data: Dict) -> str:
        template = self.env.get_template(template_name)
        return template.render(
            data=data,
            timestamp=datetime.now().strftime("%Y-%m-%d %H:%M:%S")
        )