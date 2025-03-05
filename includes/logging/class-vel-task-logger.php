<?php
namespace VEL\Includes\Logging;

class VEL_Task_Logger
{
    private $table_name;

    public function __construct()
    {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'vel_task_logs';
    }

    public function log($task_id, $message, $type = 'info')
    {
        global $wpdb;

        return $wpdb->insert(
            $this->table_name,
            [
                'task_id' => $task_id,
                'message' => $message,
                'log_type' => $type,
                'created_at' => current_time('mysql')
            ]
        );
    }

    public function get_task_logs($task_id)
    {
        global $wpdb;

        return $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM {$this->table_name} 
            WHERE task_id = %d 
            ORDER BY created_at DESC",
            $task_id
        ));
    }
}