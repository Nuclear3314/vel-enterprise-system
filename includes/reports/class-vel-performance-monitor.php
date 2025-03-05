<?php
namespace VEL\Includes\Reports;

class VEL_Performance_Monitor
{
    private $metrics = [];

    public function collect_metrics()
    {
        $this->metrics = [
            'memory_usage' => $this->get_memory_usage(),
            'query_stats' => $this->get_query_statistics(),
            'cache_stats' => $this->get_cache_statistics(),
            'system_load' => sys_getloadavg()
        ];

        return $this->metrics;
    }

    private function get_memory_usage()
    {
        return [
            'current' => memory_get_usage(true),
            'peak' => memory_get_peak_usage(true)
        ];
    }
}