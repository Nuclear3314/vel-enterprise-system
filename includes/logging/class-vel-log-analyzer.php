<?php
namespace VEL\Includes\Logging;

class VEL_Log_Analyzer
{
    private $log_dir;
    private $patterns = [
        'error' => '/\[ERROR\]/i',
        'warning' => '/\[WARNING\]/i',
        'info' => '/\[INFO\]/i'
    ];

    public function __construct()
    {
        $this->log_dir = wp_upload_dir()['basedir'] . '/vel-logs/';
    }

    public function analyze_logs($days = 7)
    {
        $summary = [
            'error_count' => 0,
            'warning_count' => 0,
            'info_count' => 0,
            'top_errors' => []
        ];

        $files = $this->get_recent_logs($days);
        foreach ($files as $file) {
            $this->process_log_file($file, $summary);
        }

        return $summary;
    }

    private function process_log_file($file, &$summary)
    {
        $content = file_get_contents($file);
        $lines = explode("\n", $content);

        foreach ($lines as $line) {
            foreach ($this->patterns as $type => $pattern) {
                if (preg_match($pattern, $line)) {
                    $summary[$type . '_count']++;
                    if ($type === 'error') {
                        $this->process_error($line, $summary);
                    }
                }
            }
        }
    }
}