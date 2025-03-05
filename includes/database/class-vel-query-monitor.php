<?php
namespace VEL\Includes\Database;

class VEL_Query_Monitor
{
    private $slow_query_log = [];
    private $threshold = 1.0; // 慢查詢閾值（秒）

    public function start_monitoring()
    {
        add_filter('query', [$this, 'log_query'], 10, 2);
    }

    public function log_query($query)
    {
        $start_time = microtime(true);
        $result = $this->wpdb->query($query);
        $execution_time = microtime(true) - $start_time;

        if ($execution_time > $this->threshold) {
            $this->slow_query_log[] = [
                'query' => $query,
                'time' => $execution_time,
                'timestamp' => current_time('mysql')
            ];
        }

        return $result;
    }
}