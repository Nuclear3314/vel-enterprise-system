from cryptography.fernet import Fernet
from typing import Dict

class EncryptionService:
    def __init__(self, key_path: str):
        self.key = self._load_or_generate_key(key_path)
        self.fernet = Fernet(self.key)
        
    def encrypt_data(self, data: str) -> bytes:
        return self.fernet.encrypt(data.encode())
        
    def decrypt_data(self, encrypted_data: bytes) -> str:
        return self.fernet.decrypt(encrypted_data).decode()