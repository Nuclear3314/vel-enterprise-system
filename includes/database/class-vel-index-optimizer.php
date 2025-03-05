<?php
namespace VEL\Includes\Database;

class VEL_Index_Optimizer
{
    private $wpdb;

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
    }

    public function analyze_indexes()
    {
        $results = [];
        $tables = $this->get_vel_tables();

        foreach ($tables as $table) {
            $results[$table] = [
                'missing_indexes' => $this->find_missing_indexes($table),
                'unused_indexes' => $this->find_unused_indexes($table)
            ];
        }

        return $results;
    }

    private function find_missing_indexes($table)
    {
        return $this->wpdb->get_results(
            "EXPLAIN SELECT * FROM $table"
        );
    }
}