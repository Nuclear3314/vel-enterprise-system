<?php
namespace VEL\Includes\Monitoring;

class VEL_Schedule_Monitor
{
    private $table_name;

    public function __construct()
    {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'vel_schedule_monitor';
    }

    public function record_execution($schedule_id, $status, $execution_time = 0)
    {
        global $wpdb;

        return $wpdb->insert(
            $this->table_name,
            [
                'schedule_id' => $schedule_id,
                'status' => $status,
                'execution_time' => $execution_time,
                'memory_usage' => memory_get_peak_usage(true),
                'created_at' => current_time('mysql')
            ]
        );
    }

    public function get_statistics($schedule_id)
    {
        global $wpdb;

        return $wpdb->get_row($wpdb->prepare(
            "SELECT 
                COUNT(*) as total_executions,
                AVG(execution_time) as avg_execution_time,
                MAX(execution_time) as max_execution_time,
                SUM(CASE WHEN status = 'success' THEN 1 ELSE 0 END) as successful_executions
            FROM {$this->table_name}
            WHERE schedule_id = %d",
            $schedule_id
        ));
    }
}