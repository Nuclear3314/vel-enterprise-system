namespace VEL\Includes\Members;

class VEL_Registration_Handler {
    private $roles;

    public function __construct() {
        $this->roles = new VEL_Member_Roles();
        add_action('user_register', [$this, 'handle_new_registration'], 9, 1);
    }

    public function handle_new_registration($user_id) {
        global $wpdb;

        try {
            // 記錄註冊來源
            $registration_source = [
                'user_id' => $user_id,
                'site_id' => get_current_blog_id(),
                'registration_date' => current_time('mysql'),
                'role' => VEL_Member_Roles::DEFAULT_ROLE
            ];

            $wpdb->insert($wpdb->prefix . 'vel_member_registrations', $registration_source);

        } catch (\Exception $e) {
            $this->log_registration_error($e, $user_id);
        }
    }
}