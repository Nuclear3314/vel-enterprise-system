<?php
namespace VEL\Includes\Monitoring;

class VEL_Health_Checker
{
    public function check_system_health()
    {
        return [
            'database' => $this->check_database(),
            'filesystem' => $this->check_filesystem(),
            'cache' => $this->check_cache(),
            'memory' => $this->check_memory_usage()
        ];
    }

    private function check_database()
    {
        global $wpdb;

        try {
            $wpdb->get_var("SELECT 1");
            return ['status' => 'ok', 'message' => '資料庫連接正常'];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }
}