<?php
namespace VEL\Includes\Cache;

class VEL_Cache_Cleaner
{
    private $cache_manager;
    private $threshold = 1000; // 快取項目數量閾值
    private $cache_types = [
        'query',
        'object',
        'transient'
    ];

    public function __construct()
    {
        $this->cache_manager = new VEL_Cache_Manager();
    }

    public function clean_expired()
    {
        global $wpdb;

        $expired = $wpdb->get_results(
            "SELECT option_name FROM $wpdb->options 
            WHERE option_name LIKE '_transient_timeout_vel_%' 
            AND option_value < " . time()
        );

        foreach ($expired as $transient) {
            $key = str_replace('_transient_timeout_', '', $transient->option_name);
            delete_transient($key);
        }
    }

    public function clean_all_caches()
    {
        $results = [];
        foreach ($this->cache_types as $type) {
            $method = 'clean_' . $type . '_cache';
            if (method_exists($this, $method)) {
                $results[$type] = $this->$method();
            }
        }
        return $results;
    }

    private function clean_query_cache()
    {
        global $wpdb;
        return $wpdb->query("TRUNCATE TABLE {$wpdb->prefix}vel_query_cache");
    }
}