<?php
namespace VEL\Includes\Cache;

class VEL_Query_Cache
{
    private $cache_key_prefix = 'vel_query_';
    private $cache_expiration = 3600; // 1小時

    public function get_cached_query($query, $args = [])
    {
        $cache_key = $this->generate_cache_key($query, $args);
        $cached_result = wp_cache_get($cache_key);

        if ($cached_result !== false) {
            return $cached_result;
        }

        return false;
    }

    public function set_cached_query($query, $result, $args = [])
    {
        $cache_key = $this->generate_cache_key($query, $args);
        return wp_cache_set($cache_key, $result, '', $this->cache_expiration);
    }

    private function generate_cache_key($query, $args)
    {
        return $this->cache_key_prefix . md5($query . serialize($args));
    }
}