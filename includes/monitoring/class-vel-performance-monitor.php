<?php
namespace VEL\Includes\Monitoring;

class VEL_Performance_Monitor
{
    private $start_time;
    private $metrics = [];

    public function start_monitoring()
    {
        $this->start_time = microtime(true);
        add_action('shutdown', [$this, 'collect_metrics']);
    }

    public function collect_metrics()
    {
        $this->metrics = [
            'execution_time' => microtime(true) - $this->start_time,
            'memory_usage' => memory_get_peak_usage(true),
            'queries' => get_num_queries(),
            'loaded_classes' => count(get_declared_classes())
        ];

        $this->save_metrics();
    }

    private function save_metrics()
    {
        global $wpdb;

        $wpdb->insert(
            $wpdb->prefix . 'vel_performance_logs',
            [
                'metrics' => json_encode($this->metrics),
                'created_at' => current_time('mysql')
            ]
        );
    }
}