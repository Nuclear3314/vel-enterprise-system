<?php
namespace VEL\Includes\Scheduler;

class VEL_Optimization_Scheduler
{
    private $tasks = [
        'daily' => [
            'optimize_tables',
            'clear_expired_cache'
        ],
        'weekly' => [
            'analyze_indexes',
            'backup_database'
        ]
    ];

    public function register_tasks()
    {
        foreach ($this->tasks['daily'] as $task) {
            if (!wp_next_scheduled('vel_daily_' . $task)) {
                wp_schedule_event(time(), 'daily', 'vel_daily_' . $task);
            }
        }

        foreach ($this->tasks['weekly'] as $task) {
            if (!wp_next_scheduled('vel_weekly_' . $task)) {
                wp_schedule_event(time(), 'weekly', 'vel_weekly_' . $task);
            }
        }
    }
}