<?php
namespace VEL\Includes\Services;

use VEL\Includes\Models\VEL_User_Model;

class VEL_User_Service
{
    private $user_model;

    public function __construct()
    {
        $this->user_model = new VEL_User_Model();
    }

    public function create_user($data)
    {
        if (!$this->validate_user_data($data)) {
            return false;
        }

        return $this->user_model->create([
            'email' => sanitize_email($data['email']),
            'name' => sanitize_text_field($data['name']),
            'created_at' => current_time('mysql')
        ]);
    }

    private function validate_user_data($data)
    {
        return !empty($data['email']) && is_email($data['email']);
    }
}