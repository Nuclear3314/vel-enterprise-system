namespace VEL\Includes\Members;

class VEL_Member_Manager {
    private $user_meta_prefix = 'vel_';
    
    public function __construct() {
        add_action('init', [$this, 'initialize_member_system']);
        add_action('user_register', [$this, 'setup_new_member']);
        add_action('vel_sync_member_data', [$this, 'sync_member_data']);
    }

    public function initialize_member_system() {
        // 註冊會員類型
        $this->register_member_types();
        
        // 初始化會員功能
        $this->initialize_member_features();
    }

    private function register_member_types() {
        register_taxonomy('member_type', 'user', [
            'labels' => [
                'name' => '會員類型',
                'singular_name' => '會員類型'
            ],
            'hierarchical' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'member-type']
        ]);
    }

    public function setup_new_member($user_id) {
        // 設置預設會員類型
        wp_set_object_terms($user_id, 'general', 'member_type');
        
        // 初始化會員元數據
        $this->initialize_member_meta($user_id);
    }
}