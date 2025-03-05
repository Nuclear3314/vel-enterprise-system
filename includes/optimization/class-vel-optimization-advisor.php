<?php
namespace VEL\Includes\Optimization;

class VEL_Optimization_Advisor
{
    private $analyzer;

    public function __construct()
    {
        $this->analyzer = new \VEL\Includes\Analysis\VEL_System_Analyzer();
    }

    public function get_optimization_suggestions()
    {
        $health_data = $this->analyzer->analyze_system_health();
        $suggestions = [];

        if ($health_data['memory']['percentage'] > 80) {
            $suggestions[] = [
                'type' => 'memory',
                'severity' => 'high',
                'message' => '記憶體使用率過高，建議增加 PHP 記憶體限制或優化程式碼',
                'action' => 'increase_memory_limit'
            ];
        }

        return $suggestions;
    }
}