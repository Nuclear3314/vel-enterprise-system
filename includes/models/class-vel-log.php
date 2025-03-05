<?php
namespace VEL\Includes\Models;

class VEL_Log extends VEL_Model
{
    protected $table;

    public function __construct()
    {
        parent::__construct();
        $this->table = $this->wpdb->prefix . 'vel_logs';
    }

    public function add_log($type, $message)
    {
        return $this->create([
            'log_type' => $type,
            'log_message' => $message,
            'created_at' => current_time('mysql')
        ]);
    }
}