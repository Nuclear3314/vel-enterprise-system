<?php
namespace VEL\Includes\Storage;

class VEL_Log_Storage
{
    private $table_name;

    public function __construct()
    {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'vel_distributed_logs';
    }

    public function store($log_entry)
    {
        global $wpdb;

        return $wpdb->insert(
            $this->table_name,
            [
                'message' => $log_entry['message'],
                'level' => $log_entry['level'],
                'context' => maybe_serialize($log_entry['context']),
                'trace_id' => $log_entry['trace_id'],
                'created_at' => $log_entry['timestamp']
            ],
            ['%s', '%s', '%s', '%s', '%s']
        );
    }
}