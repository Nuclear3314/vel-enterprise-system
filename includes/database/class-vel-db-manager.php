<?php
namespace VEL\Includes\Database;

if (!defined('ABSPATH')) {
    exit;
}

class VEL_DB_Manager {
    private $wpdb;
    private $charset_collate;
    private $tables;

    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->charset_collate = $wpdb->get_charset_collate();
        
        $this->tables = [
            'logs' => $wpdb->prefix . 'vel_logs',
            'settings' => $wpdb->prefix . 'vel_settings'
        ];
    }

    public function install() {
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        
        $schema = file_get_contents(VEL_PLUGIN_DIR . 'includes/database/schema.sql');
        $schema = str_replace('{prefix}', $this->wpdb->prefix, $schema);
        
        dbDelta($schema);
    }

    public function uninstall() {
        foreach ($this->tables as $table) {
            $this->wpdb->query("DROP TABLE IF EXISTS {$table}");
        }
    }
}