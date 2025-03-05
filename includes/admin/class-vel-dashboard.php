<?php
namespace VEL\Includes\Admin;

class VEL_Dashboard
{
    private $performance_monitor;
    private $health_checker;

    public function __construct()
    {
        $this->performance_monitor = new \VEL\Includes\Monitoring\VEL_Performance_Monitor();
        $this->health_checker = new \VEL\Includes\Monitoring\VEL_Health_Checker();
    }

    public function init()
    {
        add_action('admin_menu', [$this, 'add_dashboard_page']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_dashboard_assets']);
    }

    public function add_dashboard_page()
    {
        add_menu_page(
            'VEL 系統儀表板',
            'VEL 儀表板',
            'manage_options',
            'vel-dashboard',
            [$this, 'render_dashboard'],
            'dashicons-chart-area'
        );
    }
}