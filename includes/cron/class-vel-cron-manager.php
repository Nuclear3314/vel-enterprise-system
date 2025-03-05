<?php
namespace VEL\Includes\Cron;

class VEL_Cron_Manager
{
    public function register_crons()
    {
        if (!wp_next_scheduled('vel_process_queue')) {
            wp_schedule_event(time(), 'every_minute', 'vel_process_queue');
        }

        if (!wp_next_scheduled('vel_cleanup_old_data')) {
            wp_schedule_event(time(), 'daily', 'vel_cleanup_old_data');
        }
    }

    public function deregister_crons()
    {
        wp_clear_scheduled_hook('vel_process_queue');
        wp_clear_scheduled_hook('vel_cleanup_old_data');
    }
}