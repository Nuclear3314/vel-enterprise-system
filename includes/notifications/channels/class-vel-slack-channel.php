<?php
namespace VEL\Includes\Notifications\Channels;

use VEL\Includes\Notifications\Interfaces\VEL_Notification_Channel;

class VEL_Slack_Channel implements VEL_Notification_Channel
{
    private $webhook_url;

    public function __construct()
    {
        $this->webhook_url = get_option('vel_slack_webhook_url');
    }

    public function send($message)
    {
        $payload = json_encode(['text' => $message]);

        $ch = curl_init($this->webhook_url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($payload)
        ]);

        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }
}