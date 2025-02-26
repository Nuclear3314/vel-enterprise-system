<?php
/**
 * REST API Controller Class
 *
 * @package     VEL
 * @subpackage  VEL/includes/api
 * @author      Nuclear3314
 * @copyright   2025 Nuclear3314
 * @license     GPL v2 or later
 * @version     1.0.0
 * @created     2025-02-25 15:49:40
 */

if (!defined('ABSPATH')) {
    exit('Direct access not allowed.');
}

class VEL_REST_Controller extends WP_REST_Controller {
    /**
     * API 實例
     *
     * @var API
     */
    private $api;

class VEL_REST_Controller extends WP_REST_Controller {
    /**
     * 系統代號常量
     */
    const DEFENSE_SYSTEMS = array(
        'MICHAEL', 'GABRIEL', 'RAPHAEL', 'URIEL',
        'ABADDON', 'SAMAEL', 'AVENGER'
    );

    /**
     * 命名空間
     */
    protected $namespace = 'vel/v1';

    /**
     * 構造函數
     */
    public function __construct() {
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
        // 預測相關端點
        register_rest_route($this->namespace, '/predictions', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_predictions'),
                'permission_callback' => array($this, 'get_predictions_permissions_check'),
                'args' => $this->get_collection_args()
            ),
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'create_prediction'),
                'permission_callback' => array($this, 'create_prediction_permissions_check'),
                'args' => $this->get_prediction_args()
            )
        ));

        // 模型相關端點
        register_rest_route($this->namespace, '/models', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_models'),
                'permission_callback' => array($this, 'get_models_permissions_check'),
                'args' => $this->get_collection_args()
            ),
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'create_model'),
                'permission_callback' => array($this, 'create_model_permissions_check'),
                'args' => $this->get_model_args()
            )
        ));

        register_rest_route($this->namespace, '/models/(?P<id>[\d]+)', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_model'),
                'permission_callback' => array($this, 'get_model_permissions_check')
            ),
            array(
                'methods' => WP_REST_Server::EDITABLE,
                'callback' => array($this, 'update_model'),
                'permission_callback' => array($this, 'update_model_permissions_check'),
                'args' => $this->get_model_args()
            ),
            array(
                'methods' => WP_REST_Server::DELETABLE,
                'callback' => array($this, 'delete_model'),
                'permission_callback' => array($this, 'delete_model_permissions_check')
            )
        ));

        // 分析相關端點
        register_rest_route($this->namespace, '/analysis', array(
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'create_analysis'),
                'permission_callback' => array($this, 'create_analysis_permissions_check'),
                'args' => $this->get_analysis_args()
            )
        ));

        // 指標相關端點
        register_rest_route($this->namespace, '/metrics', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_metrics'),
                'permission_callback' => array($this, 'get_metrics_permissions_check'),
                'args' => $this->get_metrics_args()
            )
        ));

        // 安全相關端點
        register_rest_route($this->namespace, '/security/status', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_security_status'),
                'permission_callback' => array($this, 'get_security_status_permissions_check')
            )
        ));

        // 設置相關端點
        register_rest_route($this->namespace, '/settings', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_settings'),
                'permission_callback' => array($this, 'get_settings_permissions_check')
            ),
            array(
                'methods' => WP_REST_Server::EDITABLE,
                'callback' => array($this, 'update_settings'),
                'permission_callback' => array($this, 'update_settings_permissions_check'),
                'args' => $this->get_settings_args()
            )
        ));
      }

    /**
     * 註冊路由
     */
    public function register_routes() {
        // 威脅偵測與報告端點
        register_rest_route($this->namespace, '/threat/detect', array(
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'detect_threat'),
                'permission_callback' => array($this, 'check_security_permissions'),
                'args' => $this->get_threat_detection_args()
            )
        ));

        // 聯合反擊協調端點
        register_rest_route($this->namespace, '/countermeasure/coordinate', array(
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'coordinate_countermeasure'),
                'permission_callback' => array($this, 'check_security_permissions'),
                'args' => $this->get_countermeasure_args()
            )
        ));

        // 防禦系統狀態端點
        register_rest_route($this->namespace, '/defense/status', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_defense_status'),
                'permission_callback' => array($this, 'check_security_permissions')
            )
        ));

        // 蜜罐系統管理端點
        register_rest_route($this->namespace, '/honeypot/manage', array(
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'manage_honeypot'),
                'permission_callback' => array($this, 'check_security_permissions'),
                'args' => $this->get_honeypot_args()
            )
        ));

        // 全球黑名單同步端點
        register_rest_route($this->namespace, '/blacklist/sync', array(
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'sync_global_blacklist'),
                'permission_callback' => array($this, 'check_security_permissions'),
                'args' => $this->get_blacklist_args()
            )
        ));

        // 威脅情報共享端點
        register_rest_route($this->namespace, '/intelligence/share', array(
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'share_threat_intelligence'),
                'permission_callback' => array($this, 'check_security_permissions'),
                'args' => $this->get_intelligence_args()
            )
        ));

        // Level 7 威脅處理端點
        register_rest_route($this->namespace, '/threat/level-seven', array(
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'handle_level_seven_threat'),
                'permission_callback' => array($this, 'check_security_permissions'),
                'args' => $this->get_level_seven_args()
            )
        ));
    }

    /**
     * 獲取預測列表
     *
     * @param WP_REST_Request $request
     * @return WP_REST_Response|WP_Error
     */
    public function get_predictions($request) {
        $params = $request->get_params();
        $page = $params['page'] ?? 1;
        $per_page = $params['per_page'] ?? 10;

        try {
            global $wpdb;
            $table = $wpdb->prefix . 'vel_predictions';

            $total = $wpdb->get_var("SELECT COUNT(*) FROM $table");
            $offset = ($page - 1) * $per_page;

            $predictions = $wpdb->get_results($wpdb->prepare(
                "SELECT * FROM $table ORDER BY created_at DESC LIMIT %d OFFSET %d",
                $per_page,
                $offset
            ));

            $response = array(
                'predictions' => $predictions,
                'total' => (int) $total,
                'pages' => ceil($total / $per_page)
            );

            return $this->api->format_response($response);
        } catch (\Exception $e) {
            return new WP_Error(
                'fetch_failed',
                $e->getMessage(),
                array('status' => 500)
            );
        }
    }

    /**
     * 獲取模型列表
     *
     * @param WP_REST_Request $request
     * @return WP_REST_Response|WP_Error
     */
    public function get_models($request) {
        $params = $request->get_params();
        $page = $params['page'] ?? 1;
        $per_page = $params['per_page'] ?? 10;

        try {
            global $wpdb;
            $table = $wpdb->prefix . 'vel_ai_models';

            $total = $wpdb->get_var("SELECT COUNT(*) FROM $table");
            $offset = ($page - 1) * $per_page;

            $models = $wpdb->get_results($wpdb->prepare(
                "SELECT * FROM $table ORDER BY created_at DESC LIMIT %d OFFSET %d",
                $per_page,
                $offset
            ));

            foreach ($models as &$model) {
                $model->config = maybe_unserialize($model->config);
                $model->performance_metrics = maybe_unserialize($model->performance_metrics);
            }

            $response = array(
                'models' => $models,
                'total' => (int) $total,
                'pages' => ceil($total / $per_page)
            );

            return $this->api->format_response($response);
        } catch (\Exception $e) {
            return new WP_Error(
                'fetch_failed',
                $e->getMessage(),
                array('status' => 500)
            );
        }
    }

    /**
     * 創建模型
     *
     * @param WP_REST_Request $request
     * @return WP_REST_Response|WP_Error
     */
    public function create_model($request) {
        $params = $request->get_params();
        
        $validation = $this->api->validate_required_params(
            array('name', 'type', 'config'),
            $params
        );
        
        if (is_wp_error($validation)) {
            return $validation;
        }

        try {
            $model = new Model(null, $params);
            $model_id = $model->save();

            if (!$model_id) {
                throw new \Exception('Failed to create model');
            }

            Logger::log('api', 'Model created', Logger::INFO, array(
                'model_id' => $model_id,
                'config' => $params
            ));

            return $this->api->format_response(
                array('model_id' => $model_id),
                201
            );
        } catch (\Exception $e) {
            return new WP_Error(
                'creation_failed',
                $e->getMessage(),
                array('status' => 500)
            );
        }
      }

    /**
     * 處理威脅偵測
     */
    public function detect_threat($request) {
        $params = $request->get_params();
        
        try {
            $result = $this->ids->analyze_threat($params);
            
            if ($result['threat_level'] >= 7) {
                $this->ids->handle_level_seven_threat($result);
            }
            
            return $this->format_response($result);
        } catch (\Exception $e) {
            return new WP_Error(
                'threat_detection_failed',
                $e->getMessage(),
                array('status' => 500)
            );
        }
    }

    /**
     * 協調聯合反擊
     */
    public function coordinate_countermeasure($request) {
        $params = $request->get_params();
        
        try {
            $result = $this->ids->coordinate_joint_countermeasure($params);
            return $this->format_response($result);
        } catch (\Exception $e) {
            return new WP_Error(
                'countermeasure_failed',
                $e->getMessage(),
                array('status' => 500)
            );
        }
    }

    /**
     * 獲取防禦系統狀態
     */
    public function get_defense_status($request) {
        try {
            $status = array();
            foreach (self::DEFENSE_SYSTEMS as $system) {
                $status[$system] = $this->ids->get_system_status($system);
            }
            return $this->format_response($status);
        } catch (\Exception $e) {
            return new WP_Error(
                'status_check_failed',
                $e->getMessage(),
                array('status' => 500)
            );
        }
    }

    /**
     * 管理蜜罐系統
     */
    public function manage_honeypot($request) {
        $params = $request->get_params();
        
        try {
            $result = $this->ids->manage_honeypot_system($params);
            return $this->format_response($result);
        } catch (\Exception $e) {
            return new WP_Error(
                'honeypot_management_failed',
                $e->getMessage(),
                array('status' => 500)
            );
        }
    }
    /**
     * 獲取單個模型
     *
     * @param WP_REST_Request $request
     * @return WP_REST_Response|WP_Error
     */
    public function get_model($request) {
        $model_id = $request->get_param('id');

        try {
            $model = new Model($model_id);
            return $this->api->format_response($model->export());
        } catch (\Exception $e) {
            return new WP_Error(
                'not_found',
                __('Model not found', 'vel-enterprise-system'),
                array('status' => 404)
            );
        }
    }

    /**
     * 更新模型
     *
     * @param WP_REST_Request $request
     * @return WP_REST_Response|WP_Error
     */
    public function update_model($request) {
        $model_id = $request->get_param('id');
        $params = $request->get_params();

        try {
            $model = new Model($model_id);
            $model->import($params);
            $success = $model->save();

            if (!$success) {
                throw new \Exception('Failed to update model');
            }

            Logger::log('api', 'Model updated', Logger::INFO, array(
                'model_id' => $model_id,
                'updates' => $params
            ));

            return $this->api->format_response($model->export());
        } catch (\Exception $e) {
            return new WP_Error(
                'update_failed',
                $e->getMessage(),
                array('status' => 500)
            );
        }
    }

    /**
     * 刪除模型
     *
     * @param WP_REST_Request $request
     * @return WP_REST_Response|WP_Error
     */
    public function delete_model($request) {
        $model_id = $request->get_param('id');

        try {
            global $wpdb;
            $table = $wpdb->prefix . 'vel_ai_models';

            $result = $wpdb->delete(
                $table,
                array('id' => $model_id),
                array('%d')
            );

            if (!$result) {
                throw new \Exception('Failed to delete model');
            }

            Logger::log('api', 'Model deleted', Logger::INFO, array(
                'model_id' => $model_id
            ));

            return $this->api->format_response(
                null,
                204
            );
        } catch (\Exception $e) {
            return new WP_Error(
                'deletion_failed',
                $e->getMessage(),
                array('status' => 500)
            );
        }
    }

    /**
     * 創建分析
     *
     * @param WP_REST_Request $request
     * @return WP_REST_Response|WP_Error
     */
    public function create_analysis($request) {
        $params = $request->get_params();

        $validation = $this->api->validate_required_params(
            array('type', 'data'),
            $params
        );

        if (is_wp_error($validation)) {
            return $validation;
        }

        try {
            $analyzer = new \VEL\AI\Analyzer();
            $result = $analyzer->analyze($params['type'], $params['data']);

            Logger::log('api', 'Analysis created', Logger::INFO, array(
                'type' => $params['type'],
                'result' => $result
            ));

            return $this->api->format_response($result);
        } catch (\Exception $e) {
            return new WP_Error(
                'analysis_failed',
                $e->getMessage(),
                array('status' => 500)
            );
        }
    }

    /**
     * 獲取指標
     *
     * @param WP_REST_Request $request
     * @return WP_REST_Response|WP_Error
     */
    public function get_metrics($request) {
        $params = $request->get_params();
        $start_date = $params['start_date'] ?? date('Y-m-d', strtotime('-30 days'));
        $end_date = $params['end_date'] ?? date('Y-m-d');

        try {
            global $wpdb;
            $predictions_table = $wpdb->prefix . 'vel_predictions';
            $models_table = $wpdb->prefix . 'vel_ai_models';

            // 獲取預測指標
            $prediction_metrics = $wpdb->get_results($wpdb->prepare(
                "SELECT 
                    DATE(created_at) as date,
                    COUNT(*) as total,
                    AVG(accuracy) as avg_accuracy
                FROM $predictions_table
                WHERE created_at BETWEEN %s AND %s
                GROUP BY DATE(created_at)
                ORDER BY date",
                $start_date,
                $end_date
            ));

            // 獲取模型性能指標
            $model_metrics = $wpdb->get_results($wpdb->prepare(
                "SELECT 
                    m.name,
                    m.type,
                    m.performance_metrics
                FROM $models_table m
                WHERE m.status = 'active'
                AND m.created_at <= %s",
                $end_date
            ));

            $response = array(
                'prediction_metrics' => $prediction_metrics,
                'model_metrics' => $model_metrics,
                'period' => array(
                    'start' => $start_date,
                    'end' => $end_date
                )
            );

            return $this->api->format_response($response);
        } catch (\Exception $e) {
            return new WP_Error(
                'metrics_failed',
                $e->getMessage(),
                array('status' => 500)
            );
        }
    }

    /**
     * 獲取安全狀態
     *
     * @param WP_REST_Request $request
     * @return WP_REST_Response|WP_Error
     */
    public function get_security_status($request) {
        try {
            $security = new VEL_Security();
            $status = $security->get_status();

            return $this->api->format_response($status);
        } catch (\Exception $e) {
            return new WP_Error(
                'security_status_failed',
                $e->getMessage(),
                array('status' => 500)
            );
        }
    }

    /**
     * 獲取參數定義
     */
    private function get_collection_args() {
        return array(
            'page' => array(
                'description' => __('Current page of the collection.', 'vel-enterprise-system'),
                'type' => 'integer',
                'default' => 1,
                'minimum' => 1,
            ),
            'per_page' => array(
                'description' => __('Maximum number of items to be returned in result set.', 'vel-enterprise-system'),
                'type' => 'integer',
                'default' => 10,
                'minimum' => 1,
                'maximum' => 100,
            ),
        );
    }

    /**
     * 獲取預測參數定義
     */
    private function get_prediction_args() {
        return array(
            'model_id' => array(
                'required' => true,
                'type' => 'integer',
                'description' => __('Model ID to use for prediction.', 'vel-enterprise-system'),
            ),
            'data' => array(
                'required' => true,
                'type' => 'object',
                'description' => __('Data for prediction.', 'vel-enterprise-system'),
            ),
        );
    }

    /**
     * 獲取模型參數定義
     */
    private function get_model_args() {
        return array(
            'name' => array(
                'required' => true,
                'type' => 'string',
                'description' => __('Model name.', 'vel-enterprise-system'),
            ),
            'type' => array(
                'required' => true,
                'type' => 'string',
                'enum' => array('linear', 'logistic', 'neural_network'),
                'description' => __('Model type.', 'vel-enterprise-system'),
            ),
            'config' => array(
                'required' => true,
                'type' => 'object',
                'description' => __('Model configuration.', 'vel-enterprise-system'),
            ),
        );
    }

    /**
     * 獲取分析參數定義
     */
    private function get_analysis_args() {
        return array(
            'type' => array(
                'required' => true,
                'type' => 'string',
                'enum' => array('trend', 'pattern', 'anomaly'),
                'description' => __('Analysis type.', 'vel-enterprise-system'),
            ),
            'data' => array(
                'required' => true,
                'type' => 'object',
                'description' => __('Data for analysis.', 'vel-enterprise-system'),
            ),
        );
    }

    /**
     * 獲取指標參數定義
     */
    private function get_metrics_args() {
        return array(
            'start_date' => array(
                'type' => 'string',
                'format' => 'date',
                'description' => __('Start date for metrics (YYYY-MM-DD).', 'vel-enterprise-system'),
            ),
            'end_date' => array(
                'type' => 'string',
                'format' => 'date',
                'description' => __('End date for metrics (YYYY-MM-DD).', 'vel-enterprise-system'),
            ),
        );
    }

    /**
     * 獲取設置參數定義
     */
    private function get_settings_args() {
        return array(
            'security' => array(
                'type' => 'object',
                'description' => __('Security settings.', 'vel-enterprise-system'),
            ),
            'ai' => array(
                'type' => 'object',
                'description' => __('AI settings.', 'vel-enterprise-system'),
            ),
            'analytics' => array(
                'type' => 'object',
                'description' => __('Analytics settings.', 'vel-enterprise-system'),
            ),
            'logging' => array(
                'type' => 'object',
                'description' => __('Logging settings.', 'vel-enterprise-system'),
            ),
        );
    }
}
