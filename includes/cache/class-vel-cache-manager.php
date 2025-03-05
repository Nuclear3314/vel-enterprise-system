<?php
namespace VEL\Includes\Cache;

class VEL_Cache_Manager
{
    private $wp_cache;
    private $cache_prefix = 'vel_';
    private $cache_group = 'vel_cache';

    public function __construct()
    {
        $this->wp_cache = wp_cache_init();
    }

    public function set($key, $value, $expiration = 3600)
    {
        $cache_key = $this->build_key($key);
        return wp_cache_set($cache_key, $value, $this->cache_group, $expiration);
    }

    public function get($key)
    {
        $cache_key = $this->build_key($key);
        return wp_cache_get($cache_key, $this->cache_group);
    }

    private function build_key($key)
    {
        return $this->cache_prefix . $key;
    }
}