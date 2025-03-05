<?php
namespace VEL\Includes\Monitoring;

class VEL_Model_Metrics
{
    private $prometheus_client;
    private $metrics_storage;

    public function __construct()
    {
        $this->metrics_storage = new \VEL\Includes\Storage\VEL_Metrics_Storage();
    }

    public function collect_metrics(): array
    {
        $current_metrics = [
            'latency' => $this->measure_latency(),
            'memory_usage' => $this->get_memory_usage(),
            'prediction_count' => $this->get_prediction_count()
        ];

        $this->store_metrics($current_metrics);
        return $current_metrics;
    }
}