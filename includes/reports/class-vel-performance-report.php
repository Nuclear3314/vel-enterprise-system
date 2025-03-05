<?php
namespace VEL\Includes\Reports;

class VEL_Performance_Report
{
    private $metrics_collector;
    private $date_range;

    public function __construct()
    {
        $this->metrics_collector = new \VEL\Includes\Monitoring\VEL_Metrics_Collector();
        $this->date_range = [
            'start' => date('Y-m-d', strtotime('-30 days')),
            'end' => date('Y-m-d')
        ];
    }

    public function generate_report($type = 'daily')
    {
        switch ($type) {
            case 'daily':
                return $this->generate_daily_report();
            case 'weekly':
                return $this->generate_weekly_report();
            case 'monthly':
                return $this->generate_monthly_report();
            default:
                throw new \Exception('無效的報表類型');
        }
    }

    private function generate_daily_report()
    {
        global $wpdb;

        $results = $wpdb->get_results($wpdb->prepare(
            "SELECT 
                DATE(created_at) as date,
                AVG(memory_usage) as avg_memory,
                MAX(memory_usage) as peak_memory,
                COUNT(*) as total_tasks
            FROM {$wpdb->prefix}vel_task_performance
            WHERE created_at BETWEEN %s AND %s
            GROUP BY DATE(created_at)
            ORDER BY date DESC",
            $this->date_range['start'],
            $this->date_range['end']
        ));

        return [
            'type' => 'daily',
            'data' => $results,
            'summary' => $this->calculate_summary($results)
        ];
    }
}