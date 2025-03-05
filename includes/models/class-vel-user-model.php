<?php
namespace VEL\Includes\Models;

class VEL_User_Model extends VEL_Abstract_Model
{
    protected function get_table_name()
    {
        return $this->wpdb->prefix . 'vel_users';
    }

    public function get_by_email($email)
    {
        return $this->wpdb->get_row(
            $this->wpdb->prepare(
                "SELECT * FROM {$this->table} WHERE email = %s",
                $email
            )
        );
    }
}