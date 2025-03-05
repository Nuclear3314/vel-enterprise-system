namespace VEL\Includes\Logistics;

class VEL_Logistics_System {
    private $storage_locations = [];
    private $delivery_providers = [];

    public function __construct() {
        add_action('init', [$this, 'initialize_logistics']);
        add_action('rest_api_init', [$this, 'register_api_endpoints']);
    }

    public function initialize_logistics() {
        $this->register_post_types();
        $this->register_taxonomies();
        $this->load_storage_locations();
    }

    private function register_post_types() {
        register_post_type('storage_location', [
            'labels' => [
                'name' => '寄存點管理',
                'singular_name' => '寄存點'
            ],
            'public' => true,
            'has_archive' => true,
            'supports' => ['title', 'editor', 'custom-fields'],
            'show_in_rest' => true
        ]);
    }

    public function register_api_endpoints() {
        register_rest_route('vel/v1', '/logistics/locations', [
            'methods' => 'GET',
            'callback' => [$this, 'get_storage_locations'],
            'permission_callback' => [$this, 'check_permissions']
        ]);
    }
}