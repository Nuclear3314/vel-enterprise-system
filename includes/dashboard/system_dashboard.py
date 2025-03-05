import dash
from dash import html, dcc
import plotly.graph_objs as go
from datetime import datetime, timedelta

class SystemDashboard:
    def __init__(self):
        self.app = dash.Dash(__name__)
        self.setup_layout()
        
    def setup_layout(self):
        self.app.layout = html.Div([
            html.H1('VEL 系統監控儀表板'),
            html.Div([
                dcc.Graph(id='cpu-usage'),
                dcc.Graph(id='memory-usage'),
                dcc.Graph(id='response-time')
            ]),
            dcc.Interval(
                id='interval-component',
                interval=5*1000,  # 每5秒更新一次
                n_intervals=0
            )
        ])