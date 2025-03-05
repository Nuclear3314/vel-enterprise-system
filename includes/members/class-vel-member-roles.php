namespace VEL\Includes\Members;

class VEL_Member_Roles {
    const DEFAULT_ROLE = 'atomy_consumer';

    public function __construct() {
        add_action('init', [$this, 'register_member_roles']);
        add_action('user_register', [$this, 'set_default_role'], 10, 1);
    }

    public function register_member_roles() {
        add_role(
            'atomy_consumer',
            '艾多美一般消費者會員',
            [
                'read' => true,
                'view_storage_locations' => true,
                'use_shopping_cart' => true,
                'view_public_map' => true
            ]
        );
    }

    public function set_default_role($user_id) {
        $site_id = get_current_blog_id();
        
        // 只在子站點設定預設角色
        if (!is_main_site()) {
            $user = new \WP_User($user_id);
            $user->set_role(self::DEFAULT_ROLE);
        }
    }
}