<?php
namespace VEL\Includes\Metrics;

class VEL_Metrics_Collector
{
    private $metrics_storage;

    public function __construct()
    {
        $this->metrics_storage = new \VEL\Includes\Storage\VEL_Metrics_Storage();
    }

    public function collect_system_metrics()
    {
        return [
            'cpu' => $this->collect_cpu_metrics(),
            'memory' => $this->collect_memory_metrics(),
            'disk' => $this->collect_disk_metrics(),
            'network' => $this->collect_network_metrics()
        ];
    }

    private function collect_cpu_metrics()
    {
        $load = sys_getloadavg();
        return [
            '1min' => $load[0],
            '5min' => $load[1],
            '15min' => $load[2]
        ];
    }
}