ㄏBRIEL', 'RAPHAEL', 'URIEL',
        'ABADDON', 'SAMAEL', 'AVENGER'
    );

    /**
     * API 實例
     */
    private $api;

    /**
     * IDS 實例
     */
    private $ids;

    /**
     * 命名空間
     */
    protected $namespace = 'vel/v1';

    /**
     * 構造函數
     */
    public function __construct() {
        $this->api = new API();
        $this->ids = new VEL_IDS();
        add_action('rest_api_init', array($this, 'register_routes'));
    }
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->namespace = VEL_API_NAMESPACE;
        $this->rest_base = 'v1';
        $this->api = new API();
    }

    /**
     * Register routes
     */
    public function register_routes() {
        // 合併兩個 register_routes 方法的所有端點註冊
        $this->register_prediction_routes();
        $this->register_model_routes();
        $this->register_analysis_routes();
        $this->register_security_routes();
        $this->register_defense_routes();
    }

    /**
     * 註冊預測相關路由
     */
    private function register_prediction_routes() {
        // 原有的預測相關端點註冊代碼
    }
}
