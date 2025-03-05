import mlflow
from datetime import datetime
import json

class ModelVersionControl:
    def __init__(self, tracking_uri: str):
        mlflow.set_tracking_uri(tracking_uri)
        self.experiment_name = f"vel_training_{datetime.now().strftime('%Y%m%d')}"
        
    def log_model_version(self, model, metrics: dict):
        with mlflow.start_run(experiment_name=self.experiment_name):
            mlflow.log_metrics(metrics)
            mlflow.pytorch.log_model(model, "model")