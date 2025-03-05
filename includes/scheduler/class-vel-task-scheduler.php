<?php
namespace VEL\Includes\Scheduler;

class VEL_Task_Scheduler
{
    private $table_name;

    public function __construct()
    {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'vel_scheduled_tasks';
    }

    public function schedule_task($task_data)
    {
        global $wpdb;

        return $wpdb->insert(
            $this->table_name,
            array_merge($task_data, [
                'created_at' => current_time('mysql')
            ])
        );
    }

    public function get_pending_tasks()
    {
        global $wpdb;

        return $wpdb->get_results(
            "SELECT * FROM {$this->table_name} 
            WHERE next_run <= NOW() 
            AND status = 'active'"
        );
    }
}