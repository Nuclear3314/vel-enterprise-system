import pandas as pd
import matplotlib.pyplot as plt
from typing import List, Dict

class LoadTestReportGenerator:
    def __init__(self, results: Dict):
        self.results = results
        self.report_path = "reports/loadtest"
        
    def generate_report(self) -> str:
        df = pd.DataFrame(self.results)
        
        # 生成圖表
        self._create_response_time_plot(df)
        self._create_throughput_plot(df)
        self._create_error_rate_plot(df)
        
        # 產生HTML報告
        return self._generate_html_report(df)