import shutil
from datetime import datetime
import logging

class ModelRollbackManager:
    def __init__(self, version_path: str):
        self.version_path = version_path
        self.logger = logging.getLogger('rollback_manager')
        
    def rollback_to_version(self, version_id: str) -> bool:
        try:
            backup_path = f"{self.version_path}/backup_{datetime.now().strftime('%Y%m%d_%H%M%S')}"
            current_model_path = f"{self.version_path}/current"
            target_version_path = f"{self.version_path}/{version_id}"
            
            # 備份當前版本
            shutil.copytree(current_model_path, backup_path)
            
            # 執行回滾
            shutil.rmtree(current_model_path)
            shutil.copytree(target_version_path, current_model_path)
            
            self.logger.info(f"成功回滾到版本: {version_id}")
            return True
            
        except Exception as e:
            self.logger.error(f"回滾失敗: {str(e)}")
            return False