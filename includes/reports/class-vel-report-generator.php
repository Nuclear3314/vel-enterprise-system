<?php
namespace VEL\Includes\Reports;

class VEL_Report_Generator
{
    private $formats = ['pdf', 'csv', 'json'];

    public function generate_report($type = 'performance', $format = 'pdf')
    {
        if (!in_array($format, $this->formats)) {
            throw new \Exception('不支援的報告格式');
        }

        $data = $this->collect_report_data($type);
        return $this->format_report($data, $format);
    }

    private function collect_report_data($type)
    {
        switch ($type) {
            case 'performance':
                return $this->collect_performance_data();
            case 'security':
                return $this->collect_security_data();
            default:
                throw new \Exception('不支援的報告類型');
        }
    }
}