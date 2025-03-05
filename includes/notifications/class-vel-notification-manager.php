<?php
namespace VEL\Includes\Notifications;

class VEL_Notification_Manager
{
    private $channels = [];

    public function __construct()
    {
        $this->register_default_channels();
    }

    public function register_channel($name, $handler)
    {
        $this->channels[$name] = $handler;
    }

    public function send($message, $channel = 'email')
    {
        if (isset($this->channels[$channel])) {
            return $this->channels[$channel]->send($message);
        }
        throw new \Exception("未知的通知頻道: $channel");
    }

    private function register_default_channels()
    {
        $this->register_channel('email', new EmailChannel());
        $this->register_channel('slack', new SlackChannel());
        $this->register_channel('line', new LineChannel());
    }
}