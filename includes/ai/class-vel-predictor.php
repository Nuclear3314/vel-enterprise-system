<?php
/**
 * AI 預測器類
 *
 * @package VEL_Enterprise_System
 * @subpackage AI
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class VEL_Predictor {
    /**
     * 預測配置
     *
     * @var array
     */
    private $config;

    /**
     * 初始化預測器
     */
    public function __construct() {
        $this->config = get_option('vel_predictor_config', array(
            'prediction_window' => 30, // 預測窗口（天）
            'min_confidence' => 0.7,   // 最小信心度
            'use_seasonality' => true, // 是否考慮季節性
            'max_iterations' => 1000   // 最大迭代次數
        ));

        add_action('init', array($this, 'init_predictor'));
        add_filter('vel_predict_data', array($this, 'predict_future_values'), 10, 2);
    }

    /**
     * 初始化預測器
     */
    public function init_predictor() {
        if (!wp_next_scheduled('vel_weekly_prediction')) {
            wp_schedule_event(time(), 'weekly', 'vel_weekly_prediction');
        }

        add_action('vel_weekly_prediction', array($this, 'generate_weekly_predictions'));
    }

    /**
     * 預測未來值
     *
     * @param array  $historical_data 歷史數據
     * @param string $target_variable 目標變量
     * @return array
     */
    public function predict_future_values($historical_data, $target_variable) {
        if (!$this->validate_input_data($historical_data, $target_variable)) {
            return array(
                'error' => '輸入數據驗證失敗',
                'code' => 'invalid_input'
            );
        }

        $result = array(
            'predictions' => array(),
            'confidence' => 0,
            'metadata' => array(
                'target_variable' => $target_variable,
                'timestamp' => current_time('mysql'),
                'model_version' => '1.0.0'
            )
        );

        // 數據預處理
        $processed_data = $this->preprocess_data($historical_data);

        // 特徵工程
        $features = $this->extract_features($processed_data);

        // 模型訓練和預測
        $predictions = $this->train_and_predict($features, $target_variable);

        // 後處理預測結果
        $result['predictions'] = $this->postprocess_predictions($predictions);
        $result['confidence'] = $this->calculate_prediction_confidence($result['predictions'], $historical_data);

        // 保存預測結果
        $this->store_prediction_result($result);

        return $result;
    }

    /**
     * 生成每週預測
     */
    public function generate_weekly_predictions() {
        // 獲取歷史數據
        $historical_data = $this->get_historical_data();

        // 對每個關鍵指標進行預測
        $targets = array('sales', 'inventory', 'demand');
        $weekly_predictions = array();

        foreach ($targets as $target) {
            $prediction = $this->predict_future_values($historical_data, $target);
            if (!isset($prediction['error'])) {
                $weekly_predictions[$target] = $prediction;
            }
        }

        // 保存每週預測結果
        update_option('vel_weekly_predictions', $weekly_predictions);

        // 發送預測報告
        $this->send_prediction_report($weekly_predictions);
    }

    /**
     * 驗證輸入數據
     *
     * @param array  $data
     * @param string $target
     * @return bool
     */
    private function validate_input_data($data, $target) {
        if (!is_array($data) || empty($data)) {
            return false;
        }

        if (!isset($data[$target])) {
            return false;
        }

        // 檢查數據點數量
        $min_required_points = 30; // 至少需要30個數據點
        if (count($data[$target]) < $min_required_points) {
            return false;
        }

        return true;
    }

    /**
     * 數據預處理
     *
     * @param array $data
     * @return array
     */
    private function preprocess_data($data) {
        $processed_data = array();

        foreach ($data as $key => $values) {
            // 處理缺失值
            $values = $this->handle_missing_values($values);

            // 標準化
            $values = $this->standardize_values($values);

            // 去除異常值
            $values = $this->remove_outliers($values);

            $processed_data[$key] = $values;
        }

        return $processed_data;
    }

    /**
     * 處理缺失值
     *
     * @param array $values
     * @return array
     */
    private function handle_missing_values($values) {
        // 使用線性插值填充缺失值
        $result = array();
        $last_valid = null;
        $next_valid = null;

        foreach ($values as $index => $value) {
            if ($value === null) {
                if ($last_valid === null) {
                    // 向後找第一個有效值
                    for ($i = $index + 1; $i < count($values); $i++) {
                        if ($values[$i] !== null) {
                            $next_valid = $values[$i];
                            break;
                        }
                    }
                    $result[$index] = $next_valid;
                } else if ($next_valid === null) {
                    $result[$index] = $last_valid;
                } else {
                    // 線性插值
                    $result[$index] = ($last_valid + $next_valid) / 2;
                }
            } else {
                $result[$index] = $value;
                $last_valid = $value;
            }
        }

        return $result;
    }

    /**
     * 標準化數值
     *
     * @param array $values
     * @return array
     */
    private function standardize_values($values) {
        $mean = array_sum($values) / count($values);
        $std = sqrt(array_sum(array_map(function($x) use ($mean) {
            return pow($x - $mean, 2);
        }, $values)) / count($values));

        return array_map(function($x) use ($mean, $std) {
            return ($x - $mean) / ($std ?: 1);
        }, $values);
    }

    /**
     * 去除異常值
     *
     * @param array $values
     * @return array
     */
    private function remove_outliers($values) {
        $q1 = $this->calculate_percentile($values, 25);
        $q3 = $this->calculate_percentile($values, 75);
        $iqr = $q3 - $q1;
        $lower_bound = $q1 - (1.5 * $iqr);
        $upper_bound = $q3 + (1.5 * $iqr);

        return array_filter($values, function($x) use ($lower_bound, $upper_bound) {
            return $x >= $lower_bound && $x <= $upper_bound;
        });
    }

    /**
     * 計算百分位數
     *
     * @param array $values
     * @param int   $percentile
     * @return float
     */
    private function calculate_percentile($values, $percentile) {
        sort($values);
        $index = ($percentile / 100) * (count($values) - 1);
        $floor = floor($index);
        $fraction = $index - $floor;

        if ($fraction == 0) {
            return $values[$floor];
        } else {
            return $values[$floor] + ($values[$floor + 1] - $values[$floor]) * $fraction;
        }
    }

    /**
     * 特徵提取
     *
     * @param array $data
     * @return array
     */
    private function extract_features($data) {
        $features = array();

        // 時間相關特徵
        $features['time_features'] = $this->extract_time_features($data);

        // 統計特徵
        $features['statistical_features'] = $this->extract_statistical_features($data);

        // 趨勢特徵
        $features['trend_features'] = $this->extract_trend_features($data);

        // 季節性特徵
        if ($this->config['use_seasonality']) {
            $features['seasonal_features'] = $this->extract_seasonal_features($data);
        }

        return $features;
    }

    /**
     * 訓練模型並進行預測
     *
     * @param array  $features
     * @param string $target
     * @return array
     */
    private function train_and_predict($features, $target) {
        $model = $this->initialize_model();
        $predictions = array();

        // 訓練模型
        $training_result = $this->train_model($model, $features, $target);
        if ($training_result['success']) {
            // 進行預測
            $predictions = $this->make_predictions($model, $features);
        }

        return $predictions;
    }

    /**
     * 發送預測報告
     *
     * @param array $predictions
     */
    private function send_prediction_report($predictions) {
        $report = $this->generate_prediction_report($predictions);
        
        // 發送郵件通知
        $to = get_option('admin_email');
        $subject = __('Weekly AI Prediction Report', 'vel-enterprise-system');
        $message = $this->format_prediction_report($report);
        
        wp_mail($to, $subject, $message);
    }

    /**
     * 生成預測報告
     *
     * @param array $predictions
     * @return array
     */
    private function generate_prediction_report($predictions) {
        $report = array(
            'summary' => array(),
            'details' => array(),
            'recommendations' => array()
        );

        foreach ($predictions as $target => $prediction) {
            // 添加摘要
            $report['summary'][$target] = array(
                'mean' => array_sum($prediction['predictions']) / count($prediction['predictions']),
                'confidence' => $prediction['confidence'],
                'trend' => $this->detect_prediction_trend($prediction['predictions'])
            );

            // 添加詳細信息
            $report['details'][$target] = $prediction['predictions'];

            // 生成建議
            $report['recommendations'][$target] = $this->generate_recommendations($prediction);
        }

        return $report;
    }

    /**
     * 格式化預測報告
     *
     * @param array $report
     * @return string
     */
    private function format_prediction_report($report) {
        $message = "=== VEL Enterprise System AI Prediction Report ===\n\n";

        // 添加摘要
        $message .= "Summary:\n";
        foreach ($report['summary'] as $target => $summary) {
            $message .= sprintf(
                "%s:\n- Mean: %.2f\n- Confidence: %.2f%%\n- Trend: %s\n\n",
                ucfirst($target),
                $summary['mean'],
                $summary['confidence'] * 100,
                $summary['trend']
            );
        }

        // 添加建議
        $message .= "\nRecommendations:\n";
        foreach ($report['recommendations'] as $target => $recommendations) {
            $message .= sprintf("\n%s:\n", ucfirst($target));
            foreach ($recommendations as $recommendation) {
                $message .= sprintf("- %s\n", $recommendation);
            }
        }

        return $message;
    }

    /**
     * 檢測預測趨勢
     *
     * @param array $predictions
     * @return string
     */
    private function detect_prediction_trend($predictions) {
        $first = reset($predictions);
        $last = end($predictions);
        
        $change = ($last - $first) / $first;
        
        if ($change > 0.05) {
            return 'Upward';
        } elseif ($change < -0.05) {
            return 'Downward';
        } else {
            return 'Stable';
        }
    }
}