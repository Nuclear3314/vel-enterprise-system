<?php
namespace VEL\Includes;

if (!defined('ABSPATH')) {
    exit;
}

class VEL_Database_Manager
{
    protected $wpdb;
    protected $tables = [];

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;

        // 定義資料表
        $this->tables = [
            'logs' => $wpdb->prefix . 'vel_logs',
            'settings' => $wpdb->prefix . 'vel_settings',
            'users' => $wpdb->prefix . 'vel_users',
            'analytics' => $wpdb->prefix . 'vel_analytics'
        ];
    }

    public function create_tables()
    {
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        $this->create_logs_table();
        $this->create_settings_table();
        $this->create_users_table();
        $this->create_analytics_table();
    }

    private function create_logs_table()
    {
        $sql = "CREATE TABLE IF NOT EXISTS {$this->tables['logs']} (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            log_type varchar(50) NOT NULL,
            log_message text NOT NULL,
            created_at datetime NOT NULL,
            PRIMARY KEY (id)
        ) {$this->wpdb->get_charset_collate()};";

        dbDelta($sql);
    }

    // ... 其他表格創建方法
}