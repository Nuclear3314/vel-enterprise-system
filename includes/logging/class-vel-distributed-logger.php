<?php
namespace VEL\Includes\Logging;

class VEL_Distributed_Logger
{
    private $log_storage;
    private $log_levels = ['debug', 'info', 'warning', 'error', 'critical'];

    public function __construct()
    {
        $this->log_storage = new VEL_Log_Storage();
    }

    public function log($message, $level = 'info', $context = [])
    {
        if (!in_array($level, $this->log_levels)) {
            throw new \InvalidArgumentException('無效的日誌等級');
        }

        $log_entry = [
            'message' => $message,
            'level' => $level,
            'context' => $context,
            'timestamp' => date('Y-m-d H:i:s'),
            'trace_id' => $this->generate_trace_id()
        ];

        return $this->log_storage->store($log_entry);
    }
}