<?php
namespace VEL\Includes\Reports;

class VEL_Report_Exporter
{
    public function export_to_csv($data, $filename = null)
    {
        if (!$filename) {
            $filename = 'performance-report-' . date('Y-m-d') . '.csv';
        }

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');

        // 寫入 CSV 標頭
        fputcsv($output, ['日期', '平均記憶體使用', '最高記憶體使用', '任務總數']);

        // 寫入資料
        foreach ($data['data'] as $row) {
            fputcsv($output, [
                $row->date,
                $row->avg_memory,
                $row->peak_memory,
                $row->total_tasks
            ]);
        }

        fclose($output);
        exit;
    }
}