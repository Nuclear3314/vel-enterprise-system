<?php
namespace VEL\Includes\Dashboard;

class VEL_Realtime_Dashboard
{
    private $websocket_server;
    private $metrics_collector;

    public function __construct()
    {
        $this->metrics_collector = new \VEL\Includes\Monitoring\VEL_Metrics_Collector();
    }

    public function init()
    {
        add_action('admin_menu', [$this, 'add_dashboard_page']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_dashboard_assets']);
        add_action('wp_ajax_get_realtime_metrics', [$this, 'get_realtime_metrics']);
    }

    public function get_realtime_metrics()
    {
        check_ajax_referer('vel_dashboard_nonce');

        wp_send_json([
            'system_stats' => $this->metrics_collector->get_system_stats(),
            'active_tasks' => $this->metrics_collector->get_active_tasks(),
            'resources' => $this->metrics_collector->get_resource_usage()
        ]);
    }
}