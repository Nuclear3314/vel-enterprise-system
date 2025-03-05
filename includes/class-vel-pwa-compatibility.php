namespace VEL\Includes;

class VEL_PWA_Compatibility {
    public function __construct() {
        // PWA 相容性初始化
    }
    
    public function init() {
        // 實現 PWA 相容性邏輯
    }

    public function register_offline_handlers() {
        add_action('wp_ajax_vel_offline_handler', [$this, 'handle_offline_requests']);
        add_action('wp_ajax_nopriv_vel_offline_handler', [$this, 'handle_offline_requests']);
    }

    public function register_service_worker($integrations) {
        if (!isset($integrations['vel-offline'])) {
            $integrations['vel-offline'] = [
                'scope' => 'admin',
                'script' => function() {
                    $this->generate_sw_script();
                },
            ];
        }
        return $integrations;
    }

    private function generate_sw_script() {
        $offline_page = home_url('/offline/');
        ?>
        workbox.routing.registerRoute(
            new RegExp('/wp-admin/.*'),
            new workbox.strategies.NetworkFirst({
                cacheName: 'vel-admin',
                plugins: [
                    new workbox.expiration.ExpirationPlugin({
                        maxEntries: 50,
                        maxAgeSeconds: 24 * 60 * 60
                    })
                ]
            })
        );
        <?php
    }
}