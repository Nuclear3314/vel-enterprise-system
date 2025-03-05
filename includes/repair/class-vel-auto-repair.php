<?php
namespace VEL\Includes\Repair;

class VEL_Auto_Repair
{
    private $issues = [];
    private $repair_log = [];

    public function scan_for_issues()
    {
        $this->issues = [
            'database' => $this->scan_database_issues(),
            'files' => $this->scan_file_issues(),
            'performance' => $this->scan_performance_issues()
        ];
        return $this->issues;
    }

    public function auto_repair()
    {
        foreach ($this->issues as $type => $issues) {
            foreach ($issues as $issue) {
                if ($issue['auto_fixable']) {
                    $this->repair_issue($type, $issue);
                }
            }
        }
        return $this->repair_log;
    }
}