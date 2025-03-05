<?php
namespace VEL\Includes\Notifications\Channels;

use VEL\Includes\Notifications\Interfaces\VEL_Notification_Channel;

class VEL_Line_Channel implements VEL_Notification_Channel
{
    private $api_token;
    private $endpoint = 'https://notify-api.line.me/api/notify';

    public function __construct()
    {
        $this->api_token = get_option('vel_line_token');
    }

    public function send($message)
    {
        $headers = [
            'Authorization: Bearer ' . $this->api_token,
            'Content-Type: application/x-www-form-urlencoded'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->endpoint);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "message=" . urlencode($message));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }

    public function isEnabled()
    {
        return !empty($this->api_token);
    }

    public function getConfig()
    {
        return [
            'token' => $this->api_token,
            'endpoint' => $this->endpoint
        ];
    }
}