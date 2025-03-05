namespace VEL\Includes\Compatibility;

class VEL_PWA_Handler {
    private static $instance = null;
    
    public function __construct() {
        // PWA 功能處理器初始化
    }

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function init() {
        // 實現 PWA 功能邏輯

        // Remove conflicting PWA hooks
        remove_action('wp_ajax_wp_error_template', 'pwa_serve_admin_error_template');
        remove_action('wp_ajax_nopriv_wp_error_template', 'pwa_serve_admin_error_template');
        
        // Add our custom error handler
        add_action('wp_ajax_wp_error_template', [$this, 'handle_error_template']);
        add_action('wp_ajax_nopriv_wp_error_template', [$this, 'handle_error_template']);
        
        // Modify PWA service worker
        add_filter('wp_service_worker_integrations', [$this, 'modify_service_worker']);
    }

    public function handle_error_template() {
        wp_send_json_success([
            'message' => '連線中斷，請稍後重試',
            'retry' => true
        ]);
    }

    public function modify_service_worker($integrations) {
        if (isset($integrations['wp-admin'])) {
            $integrations['wp-admin']['script'] = function() {
                $this->generate_admin_service_worker();
            };
        }
        return $integrations;
    }

    private function generate_admin_service_worker() {
        ?>
        workbox.routing.registerRoute(
            new RegExp('/wp-admin/.*'),
            new workbox.strategies.NetworkOnly()
        );
        <?php
    }
}