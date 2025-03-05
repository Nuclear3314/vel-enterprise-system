<?php
namespace VEL\Includes\Admin;

class VEL_Log_Viewer
{
    private $log_dir;
    private $per_page = 50;

    public function __construct()
    {
        $upload_dir = wp_upload_dir();
        $this->log_dir = $upload_dir['basedir'] . '/vel-logs/';
    }

    public function init()
    {
        add_action('admin_menu', [$this, 'add_menu_page']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
    }

    public function add_menu_page()
    {
        add_submenu_page(
            'vel-dashboard',
            '系統日誌',
            '系統日誌',
            'manage_options',
            'vel-logs',
            [$this, 'render_page']
        );
    }

    public function render_page()
    {
        $logs = $this->get_logs();
        include VEL_PLUGIN_DIR . 'admin/views/log-viewer.php';
    }

    private function get_logs()
    {
        $files = glob($this->log_dir . '*.log');
        $logs = [];

        foreach ($files as $file) {
            $logs[basename($file)] = file_get_contents($file);
        }

        return $logs;
    }
}