<?php
namespace VEL\Includes\Alerts;

class VEL_Alert_Manager
{
    private $channels = [];
    private $threshold_levels = [
        'critical' => 90,
        'warning' => 75,
        'notice' => 60
    ];

    public function register_channels()
    {
        $this->channels = [
            'email' => new EmailChannel(),
            'slack' => new SlackChannel(),
            'line' => new LineChannel()
        ];
    }

    public function send_alert($message, $level, $channels = ['email'])
    {
        foreach ($channels as $channel) {
            if (isset($this->channels[$channel])) {
                $this->channels[$channel]->send($message, $level);
            }
        }
    }
}