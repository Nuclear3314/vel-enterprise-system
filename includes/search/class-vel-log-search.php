<?php
namespace VEL\Includes\Search;

class VEL_Log_Search
{
    private $table_name;

    public function __construct()
    {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'vel_distributed_logs';
    }

    public function search($query, $filters = [])
    {
        global $wpdb;

        $sql = "SELECT * FROM {$this->table_name} WHERE 1=1";
        $params = [];

        if (!empty($query)) {
            $sql .= " AND message LIKE %s";
            $params[] = '%' . $wpdb->esc_like($query) . '%';
        }

        return $wpdb->get_results($wpdb->prepare($sql, $params));
    }
}