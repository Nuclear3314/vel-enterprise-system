<?php
namespace VEL\Includes\Detection;

class VEL_Anomaly_Engine
{
    private $threshold_config;

    public function __construct()
    {
        $this->threshold_config = [
            'error_rate' => 0.05,
            'response_time' => 2000,
            'memory_usage' => 85
        ];
    }

    public function detect_anomalies($metrics)
    {
        $anomalies = [];

        foreach ($metrics as $metric => $value) {
            if ($this->is_anomaly($metric, $value)) {
                $anomalies[] = [
                    'metric' => $metric,
                    'value' => $value,
                    'threshold' => $this->threshold_config[$metric],
                    'timestamp' => current_time('mysql')
                ];
            }
        }

        return $anomalies;
    }
}