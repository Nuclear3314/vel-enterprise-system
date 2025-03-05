<?php
namespace VEL\Includes\Alerts;

class VEL_Realtime_Alerts
{
    private $channels = [];
    private $ml_bridge;

    public function __construct()
    {
        $this->ml_bridge = new \VEL\Includes\ML\VEL_ML_Bridge();
        $this->init_channels();
    }

    public function monitor()
    {
        $anomalies = $this->ml_bridge->analyze_logs($this->collect_current_data());

        if (!empty($anomalies)) {
            $this->send_alerts($anomalies);
        }
    }
}