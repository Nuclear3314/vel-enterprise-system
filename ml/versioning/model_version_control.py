import mlflow
import mlflow.tensorflow

class ModelVersionControl:
    def __init__(self, experiment_name):
        mlflow.set_experiment(experiment_name)
        self.model_registry = {}

    def log_model(self, model, metrics, version):
        with mlflow.start_run():
            mlflow.log_metrics(metrics)
            mlflow.tensorflow.log_model(model, f"model-v{version}")
            self.model_registry[version] = {
                'metrics': metrics,
                'timestamp': mlflow.active_run().info.start_time
            }