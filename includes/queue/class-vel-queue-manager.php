<?php
namespace VEL\Includes\Queue;

class VEL_Queue_Manager
{
    private $table_name;

    public function __construct()
    {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'vel_queue';
    }

    public function push($data, $queue = 'default')
    {
        global $wpdb;

        return $wpdb->insert(
            $this->table_name,
            [
                'queue' => $queue,
                'payload' => maybe_serialize($data),
                'attempts' => 0,
                'created_at' => current_time('mysql')
            ]
        );
    }

    public function process($queue = 'default')
    {
        global $wpdb;

        $job = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$this->table_name} 
            WHERE queue = %s AND attempts < 3 
            ORDER BY created_at ASC LIMIT 1",
            $queue
        ));

        if ($job) {
            try {
                $data = maybe_unserialize($job->payload);
                $this->handle_job($data);
                $this->delete_job($job->id);
            } catch (\Exception $e) {
                $this->increment_attempts($job->id);
            }
        }
    }
}