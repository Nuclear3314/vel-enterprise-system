<?php
/**
 * 主儀表板視圖
 *
 * @package VEL_Enterprise_System
 * @since 1.0.0
 * @version 1.0.0
 * @author Nuclear3314
 * @copyright 2025 Nuclear3314
 * @last_modified 2025-02-23 15:25:39
 */

defined('ABSPATH') || exit;
?>

<div class="wrap vel-admin-wrap">
    <div class="vel-header">
        <h1><?php esc_html_e('VEL Enterprise System 儀表板', 'vel-enterprise-system'); ?></h1>
        <div class="vel-header-actions">
            <button class="vel-button vel-button-primary" id="vel-refresh-analytics">
                <span class="dashicons dashicons-update"></span>
                <?php esc_html_e('刷新數據', 'vel-enterprise-system'); ?>
            </button>
            <label class="vel-toggle">
                <input type="checkbox" id="vel-auto-refresh">
                <?php esc_html_e('自動刷新', 'vel-enterprise-system'); ?>
            </label>
        </div>
    </div>

    <!-- 日期範圍選擇器 -->
    <div class="vel-card">
        <div class="vel-card-content">
            <div class="vel-form-group">
                <label for="vel-date-range" class="vel-form-label">
                    <?php esc_html_e('選擇日期範圍', 'vel-enterprise-system'); ?>
                </label>
                <input type="text" id="vel-date-range" class="vel-form-control">
            </div>
        </div>
    </div>

    <!-- 統計卡片 -->
    <div class="vel-grid">
        <div class="vel-card vel-stats-card">
            <span class="dashicons dashicons-chart-area"></span>
            <div class="vel-stats-value" id="vel-total-predictions">0</div>
            <div class="vel-stats-label">
                <?php esc_html_e('總預測次數', 'vel-enterprise-system'); ?>
                <span class="vel-trend" id="vel-prediction-trend">+0%</span>
            </div>
        </div>

        <div class="vel-card vel-stats-card">
            <span class="dashicons dashicons-performance"></span>
            <div class="vel-stats-value" id="vel-avg-accuracy">0%</div>
            <div class="vel-stats-label">
                <?php esc_html_e('平均準確率', 'vel-enterprise-system'); ?>
                <span class="vel-trend" id="vel-accuracy-trend">+0%</span>
            </div>
        </div>

        <div class="vel-card vel-stats-card">
            <span class="dashicons dashicons-desktop"></span>
            <div class="vel-stats-value" id="vel-active-models">0</div>
            <div class="vel-stats-label">
                <?php esc_html_e('活躍模型', 'vel-enterprise-system'); ?>
            </div>
        </div>
    </div>

    <!-- 圖表區域 -->
    <div class="vel-grid">
        <!-- 準確率趨勢圖 -->
        <div class="vel-card">
            <div class="vel-card-header">
                <h2 class="vel-card-title">
                    <?php esc_html_e('預測準確率趨勢', 'vel-enterprise-system'); ?>
                </h2>
            </div>
            <div class="vel-card-content">
                <div class="vel-chart-container">
                    <canvas id="vel-accuracy-trend-chart"></canvas>
                </div>
            </div>
        </div>

        <!-- 模型性能對比圖 -->
        <div class="vel-card">
            <div class="vel-card-header">
                <h2 class="vel-card-title">
                    <?php esc_html_e('模型性能對比', 'vel-enterprise-system'); ?>
                </h2>
            </div>
            <div class="vel-card-content">
                <div class="vel-chart-container">
                    <canvas id="vel-model-performance-chart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- 預測分佈圖 -->
    <div class="vel-card">
        <div class="vel-card-header">
            <h2 class="vel-card-title">
                <?php esc_html_e('預測分佈', 'vel-enterprise-system'); ?>
            </h2>
            <div class="vel-card-actions">
                <button class="vel-button vel-button-secondary" id="vel-export-csv">
                    <?php esc_html_e('導出 CSV', 'vel-enterprise-system'); ?>
                </button>
                <button class="vel-button vel-button-secondary" id="vel-export-pdf">
                    <?php esc_html_e('導出 PDF', 'vel-enterprise-system'); ?>
                </button>
            </div>
        </div>
        <div class="vel-card-content">
            <div class="vel-chart-container">
                <canvas id="vel-prediction-distribution-chart"></canvas>
            </div>
        </div>
    </div>

    <!-- 加載器 -->
    <div class="vel-loader" style="display: none;">
        <div class="vel-loader-content"></div>
    </div>

    <!-- 通知容器 -->
    <div class="vel-notifications-container"></div>
</div>

<?php
// 添加必要的腳本和樣式
wp_enqueue_script('moment');
wp_enqueue_script('chart-js');
wp_enqueue_script('daterangepicker');
wp_enqueue_script('vel-analytics');

wp_enqueue_style('vel-admin');
wp_enqueue_style('daterangepicker');

// 本地化腳本
wp_localize_script('vel-analytics', 'velAnalyticsData', array(
    'apiBase' => rest_url('vel/v1'),
    'nonce' => wp_create_nonce('wp_rest'),
    'dateFormat' => get_option('date_format'),
    'refreshInterval' => 300000, // 5分鐘
    'i18n' => array(
        'noData' => __('暫無數據', 'vel-enterprise-system'),
        'loading' => __('加載中...', 'vel-enterprise-system'),
        'error' => __('加載失敗', 'vel-enterprise-system')
    )
));