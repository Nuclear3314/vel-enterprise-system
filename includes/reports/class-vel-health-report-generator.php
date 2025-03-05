<?php
namespace VEL\Includes\Reports;

class VEL_Health_Report_Generator
{
    private $advisor;

    public function __construct()
    {
        $this->advisor = new \VEL\Includes\Optimization\VEL_Optimization_Advisor();
    }

    public function generate_health_report()
    {
        $suggestions = $this->advisor->get_optimization_suggestions();
        $system_status = $this->get_system_status();

        return [
            'timestamp' => current_time('mysql'),
            'status' => $system_status,
            'suggestions' => $suggestions,
            'metrics' => $this->collect_metrics()
        ];
    }

    private function collect_metrics()
    {
        return [
            'memory_usage' => memory_get_usage(true),
            'disk_space' => disk_free_space(ABSPATH),
            'php_version' => PHP_VERSION,
            'wordpress_version' => get_bloginfo('version')
        ];
    }
}