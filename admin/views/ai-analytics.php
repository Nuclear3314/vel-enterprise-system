<?php
/**
 * AI 分析儀表板
 *
 * @package VEL_Enterprise_System
 * @subpackage Admin
 */

if (!defined('ABSPATH')) {
    exit;
}

// 獲取最新的分析報告
$daily_report = get_option('vel_daily_ai_report', array());
?>

<div class="wrap vel-admin-wrap">
    <h1><?php echo esc_html__('AI Analytics Dashboard', 'vel-enterprise-system'); ?></h1>

    <div class="vel-admin-content">
        <div class="vel-analytics-summary">
            <h2><?php echo esc_html__('Analysis Summary', 'vel-enterprise-system'); ?></h2>
            <?php if (!empty($daily_report)) : ?>
                <div class="vel-summary-cards">
                    <!-- 趨勢卡片 -->
                    <div class="vel-card">
                        <h3><?php echo esc_html__('Trend Analysis', 'vel-enterprise-system'); ?></h3>
                        <?php
                        $trend = $daily_report['analysis']['metrics']['trend'];
                        $trend_class = 'vel-trend-' . $trend['direction'];
                        ?>
                        <div class="vel-trend <?php echo esc_attr($trend_class); ?>">
                            <span class="trend-direction">
                                <?php echo esc_html(ucfirst($trend['direction'])); ?>
                            </span>
                            <span class="trend-strength">
                                <?php echo esc_html(number_format($trend['strength'] * 100, 1)); ?>%
                            </span>
                        </div>
                    </div>

                    <!-- 異常卡片 -->
                    <div class="vel-card">
                        <h3><?php echo esc_html__('Anomalies', 'vel-enterprise-system'); ?></h3>
                        <?php
                        $anomalies = $daily_report['analysis']['metrics']['anomalies'];
                        $anomaly_count = count($anomalies);
                        ?>
                        <div class="vel-anomalies">
                            <span class="anomaly-count"><?php echo esc_html($anomaly_count); ?></span>
                            <span class="anomaly-label">
                                <?php echo esc_html(_n('Anomaly', 'Anomalies', $anomaly_count, 'vel-enterprise-system')); ?>
                            </span>
                            <?php if ($anomaly_count > 0) : ?>
                                <div class="anomaly-details">
                                    <ul>
                                        <?php foreach ($anomalies as $key => $anomaly) : ?>
                                            <li>
                                                <?php echo esc_html(sprintf(
                                                    __('Value %s (deviation: %s)', 'vel-enterprise-system'),
                                                    $anomaly['value'],
                                                    number_format($anomaly['deviation'], 2)
                                                )); ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- 模式卡片 -->
                    <div class="vel-card">
                        <h3><?php echo esc_html__('Patterns', 'vel-enterprise-system'); ?></h3>
                        <?php
                        $patterns = $daily_report['analysis']['metrics']['patterns'];
                        ?>
                        <div class="vel-patterns">
                            <?php if ($patterns['seasonal']['detected']) : ?>
                                <div class="pattern-item">
                                    <span class="pattern-label"><?php echo esc_html__('Seasonal', 'vel-enterprise-system'); ?></span>
                                    <span class="pattern-value">
                                        <?php echo esc_html($patterns['seasonal']['period']); ?>
                                        <?php echo esc_html__('days', 'vel-enterprise-system'); ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($patterns['cyclic']['detected']) : ?>
                                <div class="pattern-item">
                                    <span class="pattern-label"><?php echo esc_html__('Cyclic', 'vel-enterprise-system'); ?></span>
                                    <span class="pattern-value">
                                        <?php echo esc_html($patterns['cyclic']['length']); ?>
                                        <?php echo esc_html__('units', 'vel-enterprise-system'); ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($patterns['recurring']['patterns'])) : ?>
                                <div class="pattern-item">
                                    <span class="pattern-label"><?php echo esc_html__('Recurring', 'vel-enterprise-system'); ?></span>
                                    <span class="pattern-value">
                                        <?php echo esc_html(count($patterns['recurring']['patterns'])); ?>
                                        <?php echo esc_html__('patterns found', 'vel-enterprise-system'); ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- 建議部分 -->
                <div class="vel-recommendations">
                    <h3><?php echo esc_html__('AI Recommendations', 'vel-enterprise-system'); ?></h3>
                    <?php if (!empty($daily_report['recommendations'])) : ?>
                        <ul class="vel-recommendation-list">
                            <?php foreach ($daily_report['recommendations'] as $recommendation) : ?>
                                <li class="vel-recommendation-item vel-recommendation-<?php echo esc_attr($recommendation['type']); ?>">
                                    <div class="recommendation-content">
                                        <span class="recommendation-message">
                                            <?php echo esc_html($recommendation['message']); ?>
                                        </span>
                                        <span class="recommendation-confidence">
                                            <?php echo esc_html(sprintf(
                                                __('Confidence: %s%%', 'vel-enterprise-system'),
                                                number_format($recommendation['confidence'] * 100, 1)
                                            )); ?>
                                        </span>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else : ?>
                        <p class="vel-no-recommendations">
                            <?php echo esc_html__('No recommendations available at this time.', 'vel-enterprise-system'); ?>
                        </p>
                    <?php endif; ?>
                </div>

                <!-- 時間序列圖表 -->
                <div class="vel-time-series">
                    <h3><?php echo esc_html__('Time Series Analysis', 'vel-enterprise-system'); ?></h3>
                    <div id="vel-time-series-chart"></div>
                    <script>
                        jQuery(document).ready(function($) {
                            if (typeof velTimeSeriesData !== 'undefined') {
                                // 使用 Chart.js 繪製圖表
                                var ctx = document.getElementById('vel-time-series-chart').getContext('2d');
                                new Chart(ctx, {
                                    type: 'line',
                                    data: velTimeSeriesData,
                                    options: {
                                        responsive: true,
                                        scales: {
                                            x: {
                                                type: 'time',
                                                time: {
                                                    unit: 'day'
                                                }
                                            }
                                        }
                                    }
                                });
                            }
                        });
                    </script>
                </div>
            <?php else : ?>
                <div class="vel-no-data">
                    <p><?php echo esc_html__('No analysis data available yet.', 'vel-enterprise-system'); ?></p>
                    <button class="button button-primary" id="vel-run-analysis">
                        <?php echo esc_html__('Run Analysis Now', 'vel-enterprise-system'); ?>
                    </button>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>