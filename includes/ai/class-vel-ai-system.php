namespace VEL\Includes\AI;

class VEL_AI_System {
    private $providers = [];
    private $current_provider;

    public function __construct() {
        add_action('init', [$this, 'initialize_ai_system']);
        add_action('rest_api_init', [$this, 'register_endpoints']);
    }

    public function initialize_ai_system() {
        // 註冊 AI 提供者
        $this->register_provider('openai', new VEL_OpenAI_Provider());
        $this->register_provider('claude', new VEL_Claude_Provider());
        
        // 設定預設提供者
        $this->set_current_provider('claude');
    }

    public function register_endpoints() {
        register_rest_route('vel/v1', '/ai/execute', [
            'methods' => 'POST',
            'callback' => [$this, 'handle_ai_request'],
            'permission_callback' => [$this, 'check_permissions']
        ]);
    }

    public function handle_ai_request($request) {
        $command = $request->get_param('command');
        $user_role = wp_get_current_user()->roles[0];
        
        try {
            // 驗證命令格式
            if (!$this->validate_command($command)) {
                throw new \Exception('無效的命令格式');
            }
            
            // 檢查權限
            if (!$this->can_execute_command($user_role, $command)) {
                throw new \Exception('權限不足');
            }
            
            // 執行命令
            $result = $this->execute_command($command);
            
            return new \WP_REST_Response([
                'success' => true,
                'data' => $result
            ], 200);
            
        } catch (\Exception $e) {
            return new \WP_REST_Response([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}