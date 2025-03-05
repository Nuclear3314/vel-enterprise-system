<?php
namespace VEL\Includes;

class VEL_Error_Handler
{
    private static $instance = null;
    private $errors = [];

    public static function get_instance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function log_error($message, $code = '')
    {
        $error = [
            'message' => $message,
            'code' => $code,
            'time' => current_time('mysql')
        ];

        $this->errors[] = $error;
        error_log(sprintf('[VEL] %s: %s', $code, $message));
    }
}