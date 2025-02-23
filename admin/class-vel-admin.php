<?php
/**
 * 管理界面類
 *
 * @package VEL_Enterprise_System
 * @subpackage Admin
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class VEL_Admin {
    /**
     * 初始化管理界面
     */
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
        add_action('admin_init', array($this, 'register_settings'));
    }

    /**
     * 添加管理菜單
     */
    public function add_admin_menu() {
        add_menu_page(
            __('VEL Enterprise', 'vel-enterprise-system'),
            __('VEL Enterprise', 'vel-enterprise-system'),
            'manage_options',
            'vel-enterprise',
            array($this, 'render_dashboard'),
            'dashicons-shield',
            30
        );

        add_submenu_page(
            'vel-enterprise',
            __('Security Settings', 'vel-enterprise-system'),
            __('Security', 'vel-enterprise-system'),
            'manage_options',
            'vel-security',
            array($this, 'render_security_page')
        );
    }

    /**
     * 加載管理界面資源
     */
    public function enqueue_admin_assets() {
        wp_enqueue_style(
            'vel-admin-style',
            VEL_PLUGIN_URL . 'admin/css/vel-admin.css',
            array(),
            VEL_VERSION
        );

        wp_enqueue_script(
            'vel-admin-script',
            VEL_PLUGIN_URL . 'admin/js/vel-admin.js',
            array('jquery'),
            VEL_VERSION,
            true
        );

        wp_localize_script('vel-admin-script', 'velAdmin', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('vel_admin_nonce'),
        ));
    }

    /**
     * 註冊設置
     */
    public function register_settings() {
        register_setting('vel_security_options', 'vel_security_settings');

        add_settings_section(
            'vel_security_section',
            __('Security Settings', 'vel-enterprise-system'),
            array($this, 'render_security_section'),
            'vel_security_settings'
        );

        add_settings_field(
            'vel_enable_logging',
            __('Enable Security Logging', 'vel-enterprise-system'),
            array($this, 'render_checkbox_field'),
            'vel_security_settings',
            'vel_security_section',
            array(
                'label_for' => 'vel_enable_logging',
                'option_name' => 'vel_security_settings',
                'option_value' => 'enable_logging'
            )
        );
    }

    /**
     * 渲染儀表板
     */
    public function render_dashboard() {
        include VEL_PLUGIN_DIR . 'admin/views/dashboard.php';
    }

    /**
     * 渲染安全設置頁面
     */
    public function render_security_page() {
        include VEL_PLUGIN_DIR . 'admin/views/security.php';
    }

    /**
     * 渲染安全設置區段
     */
    public function render_security_section() {
        echo '<p>' . esc_html__('Configure security settings for your enterprise system.', 'vel-enterprise-system') . '</p>';
    }

    /**
     * 渲染複選框字段
     *
     * @param array $args
     */
    public function render_checkbox_field($args) {
        $options = get_option($args['option_name']);
        $value = isset($options[$args['option_value']]) ? $options[$args['option_value']] : 0;
        ?>
        <input type="checkbox"
               id="<?php echo esc_attr($args['label_for']); ?>"
               name="<?php echo esc_attr($args['option_name']); ?>[<?php echo esc_attr($args['option_value']); ?>]"
               value="1"
               <?php checked(1, $value); ?>>
        <?php
    }
}