import yaml
import docker
from typing import Optional

class DeploymentPipeline:
    def __init__(self, config_path: str):
        with open(config_path, 'r') as f:
            self.config = yaml.safe_load(f)
        self.docker_client = docker.from_env()
        
    def deploy_model(self, version: str) -> bool:
        try:
            self._build_container(version)
            self._run_tests()
            self._update_service(version)
            return True
        except Exception as e:
            self._rollback()
            return False