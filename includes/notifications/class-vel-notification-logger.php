<?php
namespace VEL\Includes\Notifications;

class VEL_Notification_Logger
{
    private $table_name;

    public function __construct()
    {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'vel_notification_logs';
    }

    public function log($message, $channel, $status)
    {
        global $wpdb;

        return $wpdb->insert(
            $this->table_name,
            [
                'message' => $message,
                'channel' => $channel,
                'status' => $status,
                'created_at' => current_time('mysql')
            ],
            ['%s', '%s', '%s', '%s']
        );
    }
}