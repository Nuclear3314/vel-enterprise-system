<?php
namespace VEL\Includes\Cron;

class VEL_Task_Runner
{
    private $queue_manager;

    public function __construct()
    {
        $this->queue_manager = new \VEL\Includes\Queue\VEL_Queue_Manager();
    }

    public function run()
    {
        add_action('vel_process_queue', [$this, 'process_queue']);
        add_action('vel_cleanup_old_data', [$this, 'cleanup_old_data']);
    }

    public function process_queue()
    {
        $this->queue_manager->process();
    }

    public function cleanup_old_data()
    {
        global $wpdb;

        // 清理30天前的數據
        $wpdb->query(
            "DELETE FROM {$wpdb->prefix}vel_queue 
            WHERE created_at < DATE_SUB(NOW(), INTERVAL 30 DAY)"
        );
    }
}