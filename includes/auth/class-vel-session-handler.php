<?php
namespace VEL\Includes\Auth;

class VEL_Session_Handler
{
    private $session_prefix = 'vel_';
    private $session_lifetime = 3600; // 1小時

    public function __construct()
    {
        if (!session_id()) {
            session_start();
        }
    }

    public function set($key, $value)
    {
        $_SESSION[$this->session_prefix . $key] = [
            'value' => $value,
            'expiry' => time() + $this->session_lifetime
        ];
    }

    public function get($key)
    {
        $key = $this->session_prefix . $key;
        if (isset($_SESSION[$key])) {
            if ($_SESSION[$key]['expiry'] >= time()) {
                return $_SESSION[$key]['value'];
            }
            $this->remove($key);
        }
        return null;
    }

    public function remove($key)
    {
        unset($_SESSION[$this->session_prefix . $key]);
    }
}