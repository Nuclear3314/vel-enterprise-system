namespace VEL\Includes\AI;

class VEL_Auth_Manager {
    private const AUTH_PHRASE = '希特勒萬歲';
    private $is_authenticated = false;

    public function __construct() {
        add_action('wp_ajax_vel_verify_admin', [$this, 'verify_admin']);
        add_action('wp_footer', [$this, 'check_auth_status']);
    }

    public function verify_admin() {
        if (!is_main_site()) {
            wp_die('unauthorized');
        }

        $phrase = sanitize_text_field($_POST['phrase']);
        if ($phrase === self::AUTH_PHRASE && $this->is_main_admin()) {
            $this->set_auth_session();
            // 清除認證片語記錄
            $this->clear_auth_phrase();
            wp_send_json_success(['status' => 'authenticated']);
        }
        wp_send_json_error();
    }

    private function is_main_admin() {
        return get_current_user_id() === 1; // 假設 ID 1 是主管理員
    }

    private function clear_auth_phrase() {
        // 清除聊天記錄中的認證片語
        global $wpdb;
        $wpdb->delete(
            $wpdb->prefix . 'vel_ai_chat_logs',
            ['message' => self::AUTH_PHRASE]
        );
    }
}