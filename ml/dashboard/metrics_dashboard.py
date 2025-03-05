import dash
import dash_core_components as dcc
import dash_html_components as html
from dash.dependencies import Input, Output
import plotly.express as px

class MetricsDashboard:
    def __init__(self):
        self.app = dash.Dash(__name__)
        self.setup_layout()
        
    def setup_layout(self):
        self.app.layout = html.Div([
            html.H1('模型效能監控儀表板'),
            dcc.Graph(id='accuracy-graph'),
            dcc.Graph(id='latency-graph'),
            dcc.Interval(
                id='interval-component',
                interval=5*1000,  # 每5秒更新
                n_intervals=0
            )
        ])