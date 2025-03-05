<?php
namespace VEL\Includes\Database;

class VEL_DB_Optimizer
{
    private $wpdb;
    private $tables = [];

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->init_tables();
    }

    private function init_tables()
    {
        $this->tables = [
            $this->wpdb->prefix . 'vel_tasks',
            $this->wpdb->prefix . 'vel_task_logs',
            $this->wpdb->prefix . 'vel_performance_logs'
        ];
    }

    public function optimize_tables()
    {
        $results = [];
        foreach ($this->tables as $table) {
            $results[$table] = $this->wpdb->query("OPTIMIZE TABLE $table");
        }
        return $results;
    }

    public function analyze_table_structure()
    {
        $issues = [];
        foreach ($this->tables as $table) {
            $analysis = $this->wpdb->get_results("ANALYZE TABLE $table");
            if ($analysis[0]->Msg_text !== 'OK') {
                $issues[$table] = $analysis[0]->Msg_text;
            }
        }
        return $issues;
    }
}