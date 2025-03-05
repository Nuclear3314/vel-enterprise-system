namespace VEL\Includes\Members;

class VEL_Member_Registration {
    private $main_site_id;

    public function __construct() {
        $this->main_site_id = defined('MAIN_SITE_ID') ? MAIN_SITE_ID : 1;
        add_action('user_register', [$this, 'sync_to_main_site'], 10, 1);
    }

    public function sync_to_main_site($user_id) {
        // 檢查是否為子站點
        if (!is_main_site()) {
            $user_data = get_userdata($user_id);
            
            // 切換到主站點
            switch_to_blog($this->main_site_id);
            
            try {
                // 在主站點建立會員
                $main_user_id = $this->create_main_site_user($user_data);
                
                // 建立關聯
                $this->create_user_relation($main_user_id, $user_id, get_current_blog_id());
                
                // 設定得勝者會員身份
                $this->set_victory_member_role($main_user_id);
                
            } catch (\Exception $e) {
                $this->log_sync_error($e, $user_id);
            }
            
            // 還原到原站點
            restore_current_blog();
        }
    }

    private function create_main_site_user($user_data) {
        // 檢查主站點是否已存在此郵箱
        $existing_user = get_user_by('email', $user_data->user_email);
        if ($existing_user) {
            return $existing_user->ID;
        }

        // 建立新使用者
        return wp_insert_user([
            'user_login' => $user_data->user_login,
            'user_pass' => wp_generate_password(),
            'user_email' => $user_data->user_email,
            'first_name' => $user_data->first_name,
            'last_name' => $user_data->last_name,
            'role' => 'victory_member'
        ]);
    }

    private function create_user_relation($main_user_id, $local_user_id, $site_id) {
        global $wpdb;
        
        return $wpdb->insert(
            $wpdb->prefix . 'vel_user_relations',
            [
                'main_user_id' => $main_user_id,
                'local_user_id' => $local_user_id,
                'site_id' => $site_id,
                'created_at' => current_time('mysql')
            ]
        );
    }
}