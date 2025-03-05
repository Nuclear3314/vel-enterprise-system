<?php
namespace VEL\Includes\Notifications\Interfaces;

interface VEL_Notification_Channel
{
    public function send($message);
    public function isEnabled();
    public function getConfig();
}