<?php
/**
 * AI 分析器類
 *
 * @package VEL_Enterprise_System
 * @subpackage AI
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class VEL_Analyzer {
    /**
     * 模型配置
     *
     * @var array
     */
    private $config;

    /**
     * 初始化分析器
     */
    public function __construct() {
        $this->config = get_option('vel_ai_config', array(
            'min_data_points' => 10,
            'confidence_threshold' => 0.75,
            'max_predictions' => 5
        ));

        add_action('init', array($this, 'init_analyzer'));
        add_filter('vel_analyze_data', array($this, 'analyze_data'), 10, 2);
    }

    /**
     * 初始化 AI 分析器
     */
    public function init_analyzer() {
        if (!wp_next_scheduled('vel_daily_analysis')) {
            wp_schedule_event(time(), 'daily', 'vel_daily_analysis');
        }

        add_action('vel_daily_analysis', array($this, 'perform_daily_analysis'));
    }

    /**
     * 分析數據
     *
     * @param array  $data    要分析的數據
     * @param string $context 分析上下文
     * @return array
     */
    public function analyze_data($data, $context = 'general') {
        if (!$this->validate_data($data)) {
            return array(
                'error' => '數據驗證失敗',
                'code' => 'invalid_data'
            );
        }

        $result = array(
            'context' => $context,
            'timestamp' => current_time('mysql'),
            'metrics' => array()
        );

        // 基礎統計分析
        $result['metrics']['basic'] = $this->perform_basic_analysis($data);

        // 趨勢分析
        $result['metrics']['trend'] = $this->analyze_trends($data);

        // 異常檢測
        $result['metrics']['anomalies'] = $this->detect_anomalies($data);

        // 模式識別
        $result['metrics']['patterns'] = $this->identify_patterns($data);

        // 保存分析結果
        $this->store_analysis_result($result);

        return $result;
    }

    /**
     * 執行每日分析
     */
    public function perform_daily_analysis() {
        $data = $this->collect_daily_data();
        $analysis = $this->analyze_data($data, 'daily');
        
        do_action('vel_daily_analysis_complete', $analysis);
        
        $this->generate_daily_report($analysis);
    }

    /**
     * 驗證輸入數據
     *
     * @param array $data
     * @return bool
     */
    private function validate_data($data) {
        if (!is_array($data) || empty($data)) {
            return false;
        }

        // 檢查數據點數量
        if (count($data) < $this->config['min_data_points']) {
            return false;
        }

        return true;
    }

    /**
     * 執行基礎統計分析
     *
     * @param array $data
     * @return array
     */
    private function perform_basic_analysis($data) {
        $numeric_data = array_filter($data, 'is_numeric');
        
        return array(
            'count' => count($numeric_data),
            'sum' => array_sum($numeric_data),
            'average' => array_sum($numeric_data) / count($numeric_data),
            'min' => min($numeric_data),
            'max' => max($numeric_data),
            'median' => $this->calculate_median($numeric_data)
        );
    }

    /**
     * 分析趨勢
     *
     * @param array $data
     * @return array
     */
    private function analyze_trends($data) {
        $trend = array(
            'direction' => 'stable',
            'strength' => 0,
            'confidence' => 0
        );

        // 計算變化率
        $changes = array();
        $values = array_values($data);
        for ($i = 1; $i < count($values); $i++) {
            $changes[] = $values[$i] - $values[$i - 1];
        }

        // 確定趨勢方向
        $positive_changes = array_filter($changes, function($v) { return $v > 0; });
        $trend_strength = count($positive_changes) / count($changes);

        if ($trend_strength > 0.6) {
            $trend['direction'] = 'upward';
            $trend['strength'] = $trend_strength;
        } elseif ($trend_strength < 0.4) {
            $trend['direction'] = 'downward';
            $trend['strength'] = 1 - $trend_strength;
        }

        // 計算信心度
        $trend['confidence'] = $this->calculate_confidence($changes);

        return $trend;
    }

    /**
     * 檢測異常
     *
     * @param array $data
     * @return array
     */
    private function detect_anomalies($data) {
        $anomalies = array();
        $values = array_values($data);
        
        // 計算標準差
        $mean = array_sum($values) / count($values);
        $variance = array_sum(array_map(function($x) use ($mean) {
            return pow($x - $mean, 2);
        }, $values)) / count($values);
        $std_dev = sqrt($variance);

        // 檢測異常值（超過 2 個標準差）
        foreach ($data as $key => $value) {
            if (abs($value - $mean) > 2 * $std_dev) {
                $anomalies[$key] = array(
                    'value' => $value,
                    'deviation' => ($value - $mean) / $std_dev
                );
            }
        }

        return $anomalies;
    }

    /**
     * 識別模式
     *
     * @param array $data
     * @return array
     */
    private function identify_patterns($data) {
        $patterns = array(
            'seasonal' => $this->detect_seasonality($data),
            'cyclic' => $this->detect_cycles($data),
            'recurring' => $this->find_recurring_patterns($data)
        );

        return $patterns;
    }

    /**
     * 檢測季節性
     *
     * @param array $data
     * @return array
     */
    private function detect_seasonality($data) {
        // 實現季節性檢測邏輯
        return array(
            'detected' => false,
            'period' => 0,
            'confidence' => 0
        );
    }

    /**
     * 檢測循環
     *
     * @param array $data
     * @return array
     */
    private function detect_cycles($data) {
        // 實現循環檢測邏輯
        return array(
            'detected' => false,
            'length' => 0,
            'confidence' => 0
        );
    }

    /**
     * 尋找重複模式
     *
     * @param array $data
     * @return array
     */
    private function find_recurring_patterns($data) {
        // 實現重複模式檢測邏輯
        return array(
            'patterns' => array(),
            'confidence' => 0
        );
    }

    /**
     * 計算中位數
     *
     * @param array $data
     * @return float
     */
    private function calculate_median($data) {
        sort($data);
        $count = count($data);
        $middle = floor($count / 2);

        if ($count % 2 == 0) {
            return ($data[$middle - 1] + $data[$middle]) / 2;
        }

        return $data[$middle];
    }

    /**
     * 計算信心度
     *
     * @param array $data
     * @return float
     */
    private function calculate_confidence($data) {
        // 實現信心度計算邏輯
        return 0.85; // 示例值
    }

    /**
     * 收集每日數據
     *
     * @return array
     */
    private function collect_daily_data() {
        // 實現數據收集邏輯
        return array();
    }

    /**
     * 生成每日報告
     *
     * @param array $analysis
     */
    private function generate_daily_report($analysis) {
        $report = array(
            'date' => current_time('mysql'),
            'analysis' => $analysis,
            'recommendations' => $this->generate_recommendations($analysis)
        );

        // 保存報告
        update_option('vel_daily_ai_report', $report);
        
        // 發送通知
        do_action('vel_daily_report_generated', $report);
    }

    /**
     * 生成建議
     *
     * @param array $analysis
     * @return array
     */
    private function generate_recommendations($analysis) {
        $recommendations = array();

        // 基於趨勢的建議
        if (isset($analysis['metrics']['trend'])) {
            $trend = $analysis['metrics']['trend'];
            if ($trend['direction'] === 'upward' && $trend['confidence'] > $this->config['confidence_threshold']) {
                $recommendations[] = array(
                    'type' => 'trend',
                    'message' => '檢測到上升趨勢，建議增加庫存',
                    'confidence' => $trend['confidence']
                );
            }
        }

        // 基於異常的建議
        if (isset($analysis['metrics']['anomalies']) && !empty($analysis['metrics']['anomalies'])) {
            $recommendations[] = array(
                'type' => 'anomaly',
                'message' => '檢測到異常值，建議調查原因',
                'confidence' => 0.9
            );
        }

        return $recommendations;
    }

    /**
     * 存儲分析結果
     *
     * @param array $result
     */
    private function store_analysis_result($result) {
        global $wpdb;

        $table_name = $wpdb->prefix . 'vel_analysis_results';

        $wpdb->insert(
            $table_name,
            array(
                'context' => $result['context'],
                'timestamp' => $result['timestamp'],
                'metrics' => maybe_serialize($result['metrics'])
            ),
            array('%s', '%s', '%s')
        );
    }
}