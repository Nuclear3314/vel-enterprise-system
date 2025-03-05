import tensorflow as tf
import horovod.tensorflow as hvd
import torch.distributed as dist
from torch.nn.parallel import DistributedDataParallel
import horovod.torch as hvd

class DistributedTrainingManager:
    def __init__(self, num_workers: int = 4):
        self.num_workers = num_workers
        hvd.init()
        
        # 設定 GPU 使用
        gpus = tf.config.experimental.list_physical_devices('GPU')
        if gpus:
            tf.config.experimental.set_visible_devices(gpus[hvd.local_rank()], 'GPU')

    def distribute_training(self, model, dataset):
        # 調整學習率根據工作節點數
        optimizer = tf.keras.optimizers.Adam(learning_rate=0.001 * hvd.size())
        optimizer = hvd.DistributedOptimizer(optimizer)
        
        return self.train_distributed(model, dataset, optimizer)

    def setup_distributed(self):
        torch.cuda.set_device(hvd.local_rank())
        dist.init_process_group(backend='nccl')
        return hvd.local_rank()