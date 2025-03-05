<?php
namespace VEL\Includes\Notifications;

class VEL_Notification_Trigger
{
    private $notification_manager;

    public function __construct()
    {
        $this->notification_manager = new VEL_Notification_Manager();
        $this->register_triggers();
    }

    private function register_triggers()
    {
        add_action('vel_system_alert', [$this, 'handle_system_alert'], 10, 2);
        add_action('vel_performance_warning', [$this, 'handle_performance_warning'], 10, 2);
    }

    public function handle_system_alert($alert_type, $message)
    {
        $formatted_message = $this->format_alert_message($alert_type, $message);
        $this->notification_manager->send($formatted_message, 'line');
    }

    private function format_alert_message($type, $message)
    {
        return sprintf(
            "[%s] %s - %s",
            strtoupper($type),
            get_bloginfo('name'),
            $message
        );
    }
}