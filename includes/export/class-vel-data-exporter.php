<?php
namespace VEL\Includes\Export;

class VEL_Data_Exporter
{
    public function export_performance_data($start_date, $end_date, $format = 'csv')
    {
        global $wpdb;

        $data = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}vel_performance_logs 
            WHERE created_at BETWEEN %s AND %s",
            $start_date,
            $end_date
        ));

        switch ($format) {
            case 'csv':
                return $this->export_to_csv($data);
            case 'json':
                return $this->export_to_json($data);
            default:
                throw new \Exception('不支援的匯出格式');
        }
    }

    private function export_to_csv($data)
    {
        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', '指標', '建立時間']);

        foreach ($data as $row) {
            fputcsv($output, [
                $row->id,
                $row->metrics,
                $row->created_at
            ]);
        }

        return $output;
    }
}