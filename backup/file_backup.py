import shutil
import os
from typing import Dict
import aiofiles

class FileBackup:
    def __init__(self, config: Dict):
        self.config = config
        
    async def backup_files(self, source_path: str, backup_path: str) -> Dict:
        try:
            stats = {
                'files_processed': 0,
                'bytes_copied': 0,
                'errors': []
            }
            
            for root, dirs, files in os.walk(source_path):
                for file in files:
                    src_file = os.path.join(root, file)
                    rel_path = os.path.relpath(src_file, source_path)
                    dst_file = os.path.join(backup_path, rel_path)
                    
                    try:
                        os.makedirs(os.path.dirname(dst_file), exist_ok=True)
                        async with aiofiles.open(src_file, 'rb') as fsrc:
                            async with aiofiles.open(dst_file, 'wb') as fdst:
                                while chunk := await fsrc.read(8192):
                                    await fdst.write(chunk)
                                    stats['bytes_copied'] += len(chunk)
                        stats['files_processed'] += 1
                    except Exception as e:
                        stats['errors'].append({
                            'file': src_file,
                            'error': str(e)
                        })
                        
            return stats
        except Exception as e:
            raise BackupError(f"檔案備份失敗: {str(e)}")