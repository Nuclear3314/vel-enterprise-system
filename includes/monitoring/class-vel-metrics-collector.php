<?php
namespace VEL\Includes\Monitoring;

class VEL_Metrics_Collector
{
    private $cache;
    private $cache_expiry = 60; // 快取時間（秒）

    public function __construct()
    {
        $this->cache = new \VEL\Includes\Cache\VEL_Cache_Manager();
    }

    public function get_system_stats()
    {
        $cache_key = 'vel_system_stats';
        $stats = $this->cache->get($cache_key);

        if (!$stats) {
            $stats = [
                'memory_usage' => $this->get_memory_usage(),
                'disk_usage' => $this->get_disk_usage(),
                'cpu_load' => $this->get_cpu_load(),
                'php_version' => PHP_VERSION,
                'wordpress_version' => get_bloginfo('version')
            ];

            $this->cache->set($cache_key, $stats, $this->cache_expiry);
        }

        return $stats;
    }
}