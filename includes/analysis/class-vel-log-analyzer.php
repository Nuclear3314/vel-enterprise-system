<?php
namespace VEL\Includes\Analysis;

class VEL_Log_Analyzer
{
    private $log_repository;

    public function __construct()
    {
        $this->log_repository = new \VEL\Includes\Repository\VEL_Log_Repository();
    }

    public function analyze_logs($timeframe = '24h')
    {
        return [
            'error_frequency' => $this->analyze_error_frequency($timeframe),
            'pattern_detection' => $this->detect_patterns($timeframe),
            'performance_impact' => $this->analyze_performance_impact($timeframe)
        ];
    }

    private function analyze_error_frequency($timeframe)
    {
        return $this->log_repository->get_error_frequency($timeframe);
    }
}