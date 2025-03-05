namespace VEL\Includes\Members;

class VEL_Member_Validator {
    public function validate_user_login($user_login, $user) {
        // 檢查是否為子站點
        if (!is_main_site()) {
            // 檢查主站點會員關聯
            if (!$this->has_main_site_relation($user->ID)) {
                wp_die('您需要完成主站點會員註冊程序。');
            }
        }
        return $user;
    }

    private function has_main_site_relation($user_id) {
        global $wpdb;
        
        $relation = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}vel_user_relations 
            WHERE local_user_id = %d AND site_id = %d",
            $user_id,
            get_current_blog_id()
        ));

        return !empty($relation);
    }
}