<?php
namespace VEL\Includes\Cache;

class VEL_Cache_Integration
{
    private $cache_manager;
    private $default_expiry = 3600;

    public function __construct()
    {
        $this->cache_manager = new VEL_Cache_Manager();
    }

    public function cache_query($query_key, $callback, $expiry = null)
    {
        $cached_result = $this->cache_manager->get($query_key);

        if ($cached_result !== false) {
            return $cached_result;
        }

        $result = call_user_func($callback);
        $this->cache_manager->set(
            $query_key,
            $result,
            $expiry ?? $this->default_expiry
        );

        return $result;
    }
}