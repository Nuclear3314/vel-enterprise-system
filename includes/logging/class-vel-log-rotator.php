<?php
namespace VEL\Includes\Logging;

class VEL_Log_Rotator
{
    private $max_size = 5242880; // 5MB
    private $max_files = 10;
    private $log_dir;

    public function __construct()
    {
        $this->log_dir = wp_upload_dir()['basedir'] . '/vel-logs/';
    }

    public function check_rotation()
    {
        $current_log = $this->log_dir . date('Y-m-d') . '.log';

        if (file_exists($current_log) && filesize($current_log) > $this->max_size) {
            $this->rotate_log($current_log);
        }

        $this->cleanup_old_logs();
    }

    private function rotate_log($log_file)
    {
        $backup_name = $log_file . '.' . time();
        rename($log_file, $backup_name);
        file_put_contents($log_file, '');
    }
}