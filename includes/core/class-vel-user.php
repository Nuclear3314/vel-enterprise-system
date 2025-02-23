 /**
     * 更新用戶活動記錄
     *
     * @param int    $user_id
     * @param string $activity_type
     * @param array  $activity_data
     */
    public function log_user_activity($user_id, $activity_type, $activity_data = array()) {
        $activities = get_user_meta($user_id, $this->meta_prefix . 'activities', true);
        if (!is_array($activities)) {
            $activities = array();
        }

        // 添加新活動
        $activities[] = array(
            'type' => $activity_type,
            'data' => $activity_data,
            'timestamp' => current_time('mysql')
        );

        // 限制活動歷史記錄數量
        $max_activities = 100;
        if (count($activities) > $max_activities) {
            $activities = array_slice($activities, -$max_activities);
        }

        update_user_meta($user_id, $this->meta_prefix . 'activities', $activities);
    }

    /**
     * 獲取用戶活動歷史
     *
     * @param int $user_id
     * @param int $limit
     * @return array
     */
    public function get_user_activities($user_id, $limit = 10) {
        $activities = get_user_meta($user_id, $this->meta_prefix . 'activities', true);
        if (!is_array($activities)) {
            return array();
        }

        return array_slice($activities, -$limit);
    }

    /**
     * 更新用戶預測記錄
     *
     * @param int   $user_id
     * @param array $prediction_data
     */
    public function update_prediction_record($user_id, $prediction_data) {
        // 更新最後預測時間
        update_user_meta($user_id, $this->meta_prefix . 'last_prediction', current_time('mysql'));

        // 增加預測計數
        $count = (int) get_user_meta($user_id, $this->meta_prefix . 'prediction_count', true);
        update_user_meta($user_id, $this->meta_prefix . 'prediction_count', $count + 1);

        // 更新準確度分數
        if (isset($prediction_data['accuracy'])) {
            $current_accuracy = (float) get_user_meta($user_id, $this->meta_prefix . 'accuracy_score', true);
            $new_accuracy = ($current_accuracy * $count + $prediction_data['accuracy']) / ($count + 1);
            update_user_meta($user_id, $this->meta_prefix . 'accuracy_score', $new_accuracy);
        }

        // 記錄活動
        $this->log_user_activity($user_id, 'prediction', $prediction_data);
    }

    /**
     * 檢查用戶是否達到每日限制
     *
     * @param int $user_id
     * @return bool
     */
    public function has_reached_daily_limit($user_id) {
        $daily_limit = get_option('vel_daily_prediction_limit', 100);
        $today_count = $this->get_today_predictions_count($user_id);
        return $today_count >= $daily_limit;
    }

    /**
     * 獲取用戶性能指標
     *
     * @param int $user_id
     * @return array
     */
    public function get_performance_metrics($user_id) {
        return array(
            'prediction_count' => (int) get_user_meta($user_id, $this->meta_prefix . 'prediction_count', true),
            'accuracy_score' => (float) get_user_meta($user_id, $this->meta_prefix . 'accuracy_score', true),
            'last_prediction' => get_user_meta($user_id, $this->meta_prefix . 'last_prediction', true),
            'daily_count' => $this->get_today_predictions_count($user_id),
            'daily_limit' => get_option('vel_daily_prediction_limit', 100)
        );
    }

    /**
     * 檢查並更新用戶權限
     *
     * @param int $user_id
     */
    public function update_user_capabilities($user_id) {
        $user = get_user_by('id', $user_id);
        if (!$user) {
            return;
        }

        // 基於性能指標更新權限
        $metrics = $this->get_performance_metrics($user_id);
        
        // 如果用戶預測準確度高於 90%，授予高級分析權限
        if ($metrics['accuracy_score'] > 0.9 && $metrics['prediction_count'] > 100) {
            $user->add_cap('vel_advanced_analysis');
        } else {
            $user->remove_cap('vel_advanced_analysis');
        }

        // 記錄權限更新
        Logger::log('user', sprintf(
            'User capabilities updated for user %d. Metrics: %s',
            $user_id,
            json_encode($metrics)
        ));
    }

    /**
     * 獲取用戶設置
     *
     * @param int    $user_id
     * @param string $setting_name
     * @param mixed  $default
     * @return mixed
     */
    public function get_user_setting($user_id, $setting_name, $default = null) {
        $settings = get_user_meta($user_id, $this->meta_prefix . 'settings', true);
        if (!is_array($settings)) {
            return $default;
        }

        return isset($settings[$setting_name]) ? $settings[$setting_name] : $default;
    }

    /**
     * 更新用戶設置
     *
     * @param int    $user_id
     * @param string $setting_name
     * @param mixed  $value
     */
    public function update_user_setting($user_id, $setting_name, $value) {
        $settings = get_user_meta($user_id, $this->meta_prefix . 'settings', true);
        if (!is_array($settings)) {
            $settings = array();
        }

        $settings[$setting_name] = $value;
        update_user_meta($user_id, $this->meta_prefix . 'settings', $settings);
    }
}