import git
import json
import hashlib
from datetime import datetime

class ModelVersionManager:
    def __init__(self, repo_path):
        self.repo = git.Repo(repo_path)
        self.version_file = 'model_versions.json'
        
    def create_version(self, model_path, metadata):
        version_hash = self._generate_hash(model_path)
        version_info = {
            'hash': version_hash,
            'timestamp': datetime.now().isoformat(),
            'metadata': metadata
        }
        
        self._save_version_info(version_info)
        self._commit_changes(version_hash)
        return version_hash