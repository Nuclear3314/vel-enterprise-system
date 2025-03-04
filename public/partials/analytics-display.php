<?php
/**
 * 分析顯示部分
 *
 * @package VEL_Enterprise_System
 * @since 1.0.0
 * @version 1.0.0
 * @author Nuclear3314
 * @copyright 2025 Nuclear3314
 * @last_modified 2025-02-23 15:40:12
 */

defined('ABSPATH') || exit;
?>

<div class="vel-container">
    <h1><?php esc_html_e('分析儀表板', 'vel-enterprise-system'); ?></h1>

    <!-- 準確率趨勢圖 -->
    <div class="vel-chart-container">
        <canvas id="vel-accuracy-trend-chart"></canvas>
    </div>

    <!-- 模型性能對比圖 -->
    <div class="vel-chart-container">
        <canvas id="vel-model-performance-chart"></canvas>
    </div>

    <!-- 預測分佈圖 -->
    <div class="vel-chart-container">
        <canvas id="vel-prediction-distribution-chart"></canvas>
    </div>
</div>

<?php
// 添加必要的腳本和樣式
wp_enqueue_script('chart-js');
wp_enqueue_script('vel-public');
wp_enqueue_style('vel-public');

// 本地化腳本
wp_localize_script('vel-public', 'velPublicData', array(
    'apiBase' => rest_url('vel/v1'),
    'i18n' => array(
        'error' => __('加載分析數據失敗', 'vel-enterprise-system')
    )
));