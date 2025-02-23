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
}