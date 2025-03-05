import os
import shutil
from typing import List

class SystemCleanup:
    def __init__(self, cleanup_config: Dict):
        self.config = cleanup_config
        
    async def cleanup_old_files(self) -> Dict:
        results = {
            'files_removed': 0,
            'space_freed': 0
        }
        
        for path in self.config['cleanup_paths']:
            try:
                cleaned = await self._cleanup_directory(path)
                results['files_removed'] += cleaned['files']
                results['space_freed'] += cleaned['space']
            except Exception as e:
                self.logger.error(f"清理失敗 {path}: {str(e)}")
                
        return results