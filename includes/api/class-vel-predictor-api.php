<?php
/**
 * AI 預測器 API 類
 *
 * @package VEL_Enterprise_System
 * @subpackage API
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class VEL_Predictor_API {
    /**
     * 註冊 API 路由
     */
    public function register_routes() {
        register_rest_route('vel/v1', '/predict', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_predictions'),
                'permission_callback' => array($this, 'check_permission'),
                'args' => $this->get_prediction_args()
            ),
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'create_prediction'),
                'permission_callback' => array($this, 'check_permission'),
                'args' => $this->get_prediction_args()
            )
        ));

        register_rest_route('vel/v1', '/predict/history', array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => array($this, 'get_prediction_history'),
            'permission_callback' => array($this, 'check_permission'),
            'args' => array(
                'limit' => array(
                    'type' => 'integer',
                    'default' => 10,
                    'minimum' => 1,
                    'maximum' => 100
                )
            )
        ));

        register_rest_route('vel/v1', '/predict/accuracy', array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => array($this, 'get_prediction_accuracy'),
            'permission_callback' => array($this, 'check_permission')
        ));
    }

    /**
     * 權限檢查
     *
     * @param WP_REST_Request $request
     * @return bool
     */
    public function check_permission($request) {
        return current_user_can('manage_options');
    }

    /**
     * 獲取預測參數定義
     *
     * @return array
     */
    private function get_prediction_args() {
        return array(
            'target' => array(
                'required' => true,
                'type' => 'string',
                'enum' => array('sales', 'inventory', 'demand'),
                'description' => '預測目標'
            ),
            'window' => array(
                'required' => false,
                'type' => 'integer',
                'default' => 30,
                'minimum' => 1,
                'maximum' => 365,
                'description' => '預測窗口（天）'
            ),
            'confidence_threshold' => array(
                'required' => false,
                'type' => 'number',
                'default' => 0.7,
                'minimum' => 0,
                'maximum' => 1,
                'description' => '信心度閾值'
            )
        );
    }

    /**
     * 獲取預測結果
     *
     * @param WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function get_predictions($request) {
        $target = $request->get_param('target');
        $window = $request->get_param('window');
        
        try {
            $predictor = new VEL_Predictor();
            $historical_data = $this->get_historical_data($target);
            $predictions = $predictor->predict_future_values($historical_data, $target);

            if (isset($predictions['error'])) {
                return new WP_REST_Response(array(
                    'success' => false,
                    'message' => $predictions['error']
                ), 400);
            }

            return new WP_REST_Response(array(
                'success' => true,
                'data' => $predictions
            ), 200);

        } catch (Exception $e) {
            return new WP_REST_Response(array(
                'success' => false,
                'message' => $e->getMessage()
            ), 500);
        }
    }

    /**
     * 創建新的預測
     *
     * @param WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function create_prediction($request) {
        $target = $request->get_param('target');
        $window = $request->get_param('window');
        $confidence_threshold = $request->get_param('confidence_threshold');

        try {
            $predictor = new VEL_Predictor();
            $historical_data = $this->get_historical_data($target);
            
            // 設置預測配置
            $config = array(
                'prediction_window' => $window,
                'min_confidence' => $confidence_threshold
            );
            $predictor->set_config($config);

            // 執行預測
            $prediction = $predictor->predict_future_values($historical_data, $target);

            if (isset($prediction['error'])) {
                return new WP_REST_Response(array(
                    'success' => false,
                    'message' => $prediction['error']
                ), 400);
            }

            // 保存預測結果
            $saved = $this->save_prediction_result($prediction, $target);

            if (!$saved) {
                return new WP_REST_Response(array(
                    'success' => false,
                    'message' => '保存預測結果失敗'
                ), 500);
            }

            return new WP_REST_Response(array(
                'success' => true,
                'data' => $prediction
            ), 201);

        } catch (Exception $e) {
            return new WP_REST_Response(array(
                'success' => false,
                'message' => $e->getMessage()
            ), 500);
        }
    }

    /**
     * 獲取預測歷史
     *
     * @param WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function get_prediction_history($request) {
        $limit = $request->get_param('limit');

        try {
            global $wpdb;
            $table_name = $wpdb->prefix . 'vel_predictions';

            $results = $wpdb->get_results($wpdb->prepare(
                "SELECT * FROM {$table_name} ORDER BY created_at DESC LIMIT %d",
                $limit
            ));

            return new WP_REST_Response(array(
                'success' => true,
                'data' => array_map(function($result) {
                    return array(
                        'id' => $result->id,
                        'target' => $result->target,
                        'predictions' => maybe_unserialize($result->predictions),
                        'confidence' => $result->confidence,
                        'created_at' => $result->created_at
                    );
                }, $results)
            ), 200);

        } catch (Exception $e) {
            return new WP_REST_Response(array(
                'success' => false,
                'message' => $e->getMessage()
            ), 500);
        }
    }

    /**
     * 獲取預測準確度
     *
     * @param WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function get_prediction_accuracy($request) {
        try {
            global $wpdb;
            $table_name = $wpdb->prefix . 'vel_predictions';

            // 獲取最近30天的預測結果
            $results = $wpdb->get_results(
                "SELECT * FROM {$table_name} 
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                ORDER BY created_at DESC"
            );

            $accuracy_stats = array();
            foreach ($results as $result) {
                $predictions = maybe_unserialize($result->predictions);
                $actual_values = $this->get_actual_values($result->target, $result->created_at);
                
                $accuracy = $this->calculate_prediction_accuracy(
                    $predictions['predictions'],
                    $actual_values
                );

                if (!isset($accuracy_stats[$result->target])) {
                    $accuracy_stats[$result->target] = array();
                }
                $accuracy_stats[$result->target][] = $accuracy;
            }

            // 計算每個目標的平均準確度
            $average_accuracy = array();
            foreach ($accuracy_stats as $target => $accuracies) {
                $average_accuracy[$target] = array_sum($accuracies) / count($accuracies);
            }

            return new WP_REST_Response(array(
                'success' => true,
                'data' => array(
                    'average_accuracy' => $average_accuracy,
                    'details' => $accuracy_stats
                )
            ), 200);

        } catch (Exception $e) {
            return new WP_REST_Response(array(
                'success' => false,
                'message' => $e->getMessage()
            ), 500);
        }
    }

    /**
     * 獲取歷史數據
     *
     * @param string $target
     * @return array
     */
    private function get_historical_data($target) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'vel_historical_data';

        $results = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM {$table_name} WHERE target = %s ORDER BY date DESC LIMIT 365",
            $target
        ));

        return array_map(function($row) {
            return array(
                'date' => $row->date,
                'value' => $row->value
            );
        }, $results);
    }

    /**
     * 保存預測結果
     *
     * @param array  $prediction
     * @param string $target
     * @return bool
     */
    private function save_prediction_result($prediction, $target) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'vel_predictions';

        return $wpdb->insert(
            $table_name,
            array(
                'target' => $target,
                'predictions' => maybe_serialize($prediction['predictions']),
                'confidence' => $prediction['confidence'],
                'created_at' => current_time('mysql')
            ),
            array('%s', '%s', '%f', '%s')
        );
    }

    /**
     * 獲取實際值
     *
     * @param string $target
     * @param string $date
     * @return array
     */
    private function get_actual_values($target, $date) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'vel_historical_data';

        return $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM {$table_name} 
            WHERE target = %s 
            AND date >= %s 
            ORDER BY date ASC",
            $target,
            $date
        ));
    }

    /**
     * 計算預測準確度
     *
     * @param array $predictions
     * @param array $actual_values
     * @return float
     */
    private function calculate_prediction_accuracy($predictions, $actual_values) {
        if (empty($predictions) || empty($actual_values)) {
            return 0;
        }

        $total_error = 0;
        $count = min(count($predictions), count($actual_values));

        for ($i = 0; $i < $count; $i++) {
            $predicted = $predictions[$i];
            $actual = $actual_values[$i]->value;
            $error = abs($predicted - $actual) / $actual;
            $total_error += $error;
        }

        return 1 - ($total_error / $count);
    }
}