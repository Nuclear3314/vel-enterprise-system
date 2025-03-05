namespace VEL\Includes\Members;

class VEL_Role_Controller {
    public function __construct() {
        add_action('set_user_role', [$this, 'validate_role_change'], 10, 3);
    }

    public function validate_role_change($user_id, $new_role, $old_roles) {
        // 檢查是否為主站點操作
        if (!is_main_site() && !$this->is_self_requested_change()) {
            // 如果不是用戶自己要求的變更，阻止角色變更
            if ($new_role !== VEL_Member_Roles::DEFAULT_ROLE) {
                wp_die('會員角色變更需要主站點授權。');
            }
        }
    }

    private function is_self_requested_change() {
        // 檢查是否為用戶自己的請求
        return (isset($_POST['user_role_change']) && 
                wp_verify_nonce($_POST['_wpnonce'], 'change_role'));
    }
}