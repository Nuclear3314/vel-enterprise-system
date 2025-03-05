<?php
namespace VEL\Includes\Logging;

class VEL_Logger
{
    private $log_path;
    private $log_levels = ['info', 'warning', 'error', 'debug'];

    public function __construct()
    {
        $upload_dir = wp_upload_dir();
        $this->log_path = $upload_dir['basedir'] . '/vel-logs/';

        if (!file_exists($this->log_path)) {
            wp_mkdir_p($this->log_path);
        }
    }

    public function log($message, $level = 'info')
    {
        if (!in_array($level, $this->log_levels)) {
            $level = 'info';
        }

        $log_entry = sprintf(
            "[%s] %s: %s\n",
            current_time('mysql'),
            strtoupper($level),
            $message
        );

        $log_file = $this->log_path . date('Y-m-d') . '.log';
        file_put_contents($log_file, $log_entry, FILE_APPEND);
    }
}