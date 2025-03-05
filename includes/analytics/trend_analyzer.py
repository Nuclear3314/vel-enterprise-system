from statsmodels.tsa.seasonal import seasonal_decompose
import plotly.graph_objects as go

class TrendAnalyzer:
    def __init__(self):
        self.decomposition = None
        
    def analyze_trends(self, data: pd.Series) -> Dict:
        self.decomposition = seasonal_decompose(
            data, 
            period=self._detect_period(data)
        )
        
        return {
            'trend': self.decomposition.trend,
            'seasonal': self.decomposition.seasonal,
            'residual': self.decomposition.resid
        }