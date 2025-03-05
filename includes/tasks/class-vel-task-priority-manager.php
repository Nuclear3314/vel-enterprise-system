<?php
namespace VEL\Includes\Tasks;

class VEL_Task_Priority_Manager
{
    const PRIORITY_HIGH = 'high';
    const PRIORITY_NORMAL = 'normal';
    const PRIORITY_LOW = 'low';

    private $priorities = [
        self::PRIORITY_HIGH => 1,
        self::PRIORITY_NORMAL => 2,
        self::PRIORITY_LOW => 3
    ];

    public function get_priority_level($priority)
    {
        return $this->priorities[$priority] ?? $this->priorities[self::PRIORITY_NORMAL];
    }

    public function get_next_task()
    {
        global $wpdb;

        return $wpdb->get_row(
            "SELECT * FROM {$wpdb->prefix}vel_tasks 
            WHERE status = 'pending' 
            ORDER BY priority ASC, created_at ASC 
            LIMIT 1"
        );
    }
}