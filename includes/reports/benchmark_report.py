import pandas as pd
import matplotlib.pyplot as plt

class BenchmarkReport:
    def __init__(self, results: Dict):
        self.results = results
        self.report_path = "reports/benchmark"
        
    def generate_report(self) -> str:
        df = pd.DataFrame(self.results)
        
        # 生成圖表
        fig, axes = plt.subplots(2, 2, figsize=(15, 10))
        self._plot_metrics(df, axes)
        
        # 儲存報告
        report_file = f"{self.report_path}/report_{time.strftime('%Y%m%d_%H%M%S')}.html"
        self._save_report(df, report_file)
        
        return report_file