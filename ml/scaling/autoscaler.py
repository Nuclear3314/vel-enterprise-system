from kubernetes import client, config
import numpy as np

class ModelAutoscaler:
    def __init__(self):
        config.load_kube_config()
        self.k8s_apps_v1 = client.AppsV1Api()
        
    def scale_deployment(self, deployment_name, namespace, target_replicas):
        try:
            body = {
                "spec": {
                    "replicas": target_replicas
                }
            }
            self.k8s_apps_v1.patch_namespaced_deployment_scale(
                name=deployment_name,
                namespace=namespace,
                body=body
            )
            return True
        except Exception as e:
            print(f"擴展失敗: {str(e)}")
            return False