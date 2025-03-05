<?php
namespace VEL\Includes\Analysis;

class VEL_Trend_Analyzer
{
    private $metrics_history;
    private $analysis_period = 30; // 天數

    public function analyze_trends()
    {
        return [
            'performance_trend' => $this->analyze_performance_trend(),
            'resource_usage_trend' => $this->analyze_resource_usage_trend(),
            'error_rate_trend' => $this->analyze_error_rate_trend()
        ];
    }

    private function analyze_performance_trend()
    {
        global $wpdb;

        $results = $wpdb->get_results($wpdb->prepare(
            "SELECT DATE(created_at) as date, AVG(response_time) as avg_time
            FROM {$wpdb->prefix}vel_performance_logs
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL %d DAY)
            GROUP BY DATE(created_at)
            ORDER BY date ASC",
            $this->analysis_period
        ));

        return $this->calculate_trend($results);
    }
}