<?php
namespace VEL\Includes\Controllers;

abstract class VEL_Base_Controller
{
    protected $service;

    public function __construct()
    {
        $this->register_hooks();
    }

    abstract protected function register_hooks();

    protected function verify_nonce($nonce_name, $action = -1)
    {
        if (
            !isset($_REQUEST['_wpnonce']) ||
            !wp_verify_nonce($_REQUEST['_wpnonce'], $action)
        ) {
            wp_die('安全性檢查失敗');
        }
        return true;
    }

    protected function json_response($data, $status = 200)
    {
        wp_send_json($data, $status);
    }
}