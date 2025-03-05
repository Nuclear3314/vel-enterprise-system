<?php
namespace VEL\Includes\Monitoring;

class VEL_Model_Monitor
{
    private $python_bridge;
    private $metrics_storage;

    public function __construct()
    {
        $this->python_bridge = new \VEL\Includes\ML\VEL_Python_Bridge();
        $this->metrics_storage = new \VEL\Includes\Storage\VEL_Metrics_Storage();
    }

    public function monitor_performance()
    {
        $metrics = $this->python_bridge->execute_monitoring();
        $this->store_metrics($metrics);
        return $this->analyze_metrics($metrics);
    }
}