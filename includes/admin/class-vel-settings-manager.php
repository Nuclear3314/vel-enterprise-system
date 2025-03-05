namespace VEL\Includes\Admin;

class VEL_Settings_Manager {
    private const OPTION_GROUP = 'vel_settings';
    private const SETTINGS_PAGE = 'vel-settings';

    public function __construct() {
        add_action('admin_menu', [$this, 'add_settings_page']);
        add_action('admin_init', [$this, 'register_settings']);
    }

    public function register_settings() {
        // AI 設定區段
        register_setting(self::OPTION_GROUP, 'vel_ai_settings', [
            'type' => 'array',
            'sanitize_callback' => [$this, 'sanitize_ai_settings']
        ]);

        // 安全設定區段
        register_setting(self::OPTION_GROUP, 'vel_security_settings', [
            'type' => 'array',
            'sanitize_callback' => [$this, 'sanitize_security_settings']
        ]);

        // 物流設定區段
        register_setting(self::OPTION_GROUP, 'vel_logistics_settings', [
            'type' => 'array',
            'sanitize_callback' => [$this, 'sanitize_logistics_settings']
        ]);
    }

    public function add_settings_page() {
        add_menu_page(
            'VEL 系統設定',
            'VEL 設定',
            'manage_options',
            self::SETTINGS_PAGE,
            [$this, 'render_settings_page'],
            'dashicons-admin-generic'
        );
    }
}