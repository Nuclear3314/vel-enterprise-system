namespace VEL\Includes\API;

class VEL_API_Endpoints {
    private $namespace = 'vel/v1';

    public function __construct() {
        add_action('rest_api_init', [$this, 'register_endpoints']);
    }

    public function register_endpoints() {
        // AI 相關端點
        $this->register_ai_endpoints();
        
        // 安全相關端點
        $this->register_security_endpoints();
        
        // 物流相關端點
        $this->register_logistics_endpoints();
        
        // 會員相關端點
        $this->register_member_endpoints();
    }

    private function register_ai_endpoints() {
        register_rest_route($this->namespace, '/ai/generate', [
            'methods' => 'POST',
            'callback' => [$this, 'handle_ai_generation'],
            'permission_callback' => [$this, 'check_ai_permissions'],
            'args' => [
                'prompt' => [
                    'required' => true,
                    'type' => 'string'
                ],
                'type' => [
                    'required' => true,
                    'enum' => ['content', 'image', 'code']
                ]
            ]
        ]);
    }

    public function handle_ai_generation($request) {
        $prompt = $request->get_param('prompt');
        $type = $request->get_param('type');
        
        try {
            $result = $this->generate_ai_content($type, $prompt);
            return new \WP_REST_Response($result, 200);
        } catch (\Exception $e) {
            return new \WP_REST_Response([
                'error' => $e->getMessage()
            ], 400);
        }
    }
}