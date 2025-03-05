<?php
namespace VEL\Includes\Auth;

class VEL_Auth_Manager
{
    private $user_service;
    private $session_handler;

    public function __construct()
    {
        $this->user_service = new \VEL\Includes\Services\VEL_User_Service();
        $this->session_handler = new \VEL\Includes\Auth\VEL_Session_Handler();
    }

    public function authenticate($credentials)
    {
        try {
            $user = $this->user_service->validate_credentials($credentials);
            if ($user) {
                $this->session_handler->create_session($user);
                return true;
            }
            return false;
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }
}