<?php
/**
 * Public Analytics Template
 *
 * @package     VEL
 * @author      Nuclear3314
 * @copyright   2025 Nuclear3314
 * @license     GPL v2 or later
 * @version     1.0.0
 * @created     2025-02-25 14:23:24
 */

if (!defined('ABSPATH')) {
    exit('Direct access not allowed.');
}

$user_id = get_current_user_id();
$analytics_data = VEL()->analytics->get_user_analytics($user_id);
?>

<div class="vel-public-wrap">
    <div class="vel-analytics-header">
        <h1><?php esc_html_e('Analytics Dashboard', 'vel-enterprise-system'); ?></h1>
        <div class="vel-analytics-actions">
            <button type="button" class="vel-button vel-button-primary" id="vel-refresh-analytics">
                <?php esc_html_e('Refresh Data', 'vel-enterprise-system'); ?>
            </button>
            <button type="button" class="vel-button vel-button-secondary" id="vel-export-analytics">
                <?php esc_html_e('Export Report', 'vel-enterprise-system'); ?>
            </button>
        </div>
    </div>

    <div class="vel-analytics-content">
        <div class="vel-analytics-summary">
            <div class="vel-grid">
                <?php foreach ($analytics_data['summary'] as $metric): ?>
                    <div class="vel-metric-card">
                        <h3><?php echo esc_html($metric['label']); ?></h3>
                        <div class="vel-metric-value">
                            <?php echo esc_html($metric['value']); ?>
                            <?php if (!empty($metric['trend'])): ?>
                                <span class="vel-trend <?php echo esc_attr($metric['trend']['direction']); ?>">
                                    <?php echo esc_html($metric['trend']['value']); ?>%
                                </span>
                            <?php endif; ?>
                        </div>
                        <div class="vel-metric-description">
                            <?php echo esc_html($metric['description']); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="vel-analytics-charts">
            <div class="vel-grid">
                <div class="vel-chart-card">
                    <h3><?php esc_html_e('Performance Trends', 'vel-enterprise-system'); ?></h3>
                    <div class="vel-chart-container">
                        <canvas id="velPerformanceChart"></canvas>
                    </div>
                </div>
                
                <div class="vel-chart-card">
                    <h3><?php esc_html_e('Usage Statistics', 'vel-enterprise-system'); ?></h3>
                    <div class="vel-chart-container">
                        <canvas id="velUsageChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="vel-analytics-details">
            <h2><?php esc_html_e('Detailed Analysis', 'vel-enterprise-system'); ?></h2>
            <div class="vel-tabs">
                <ul class="vel-tab-nav">
                    <li class="active">
                        <a href="#performance"><?php esc_html_e('Performance', 'vel-enterprise-system'); ?></a>
                    </li>
                    <li>
                        <a href="#security"><?php esc_html_e('Security', 'vel-enterprise-system'); ?></a>
                    </li>
                    <li>
                        <a href="#usage"><?php esc_html_e('Usage', 'vel-enterprise-system'); ?></a>
                    </li>
                </ul>

                <div class="vel-tab-content">
                    <div id="performance" class="vel-tab-pane active">
                        <?php if (!empty($analytics_data['performance'])): ?>
                            <table class="vel-table">
                                <thead>
                                    <tr>
                                        <th><?php esc_html_e('Metric', 'vel-enterprise-system'); ?></th>
                                        <th><?php esc_html_e('Value', 'vel-enterprise-system'); ?></th>
                                        <th><?php esc_html_e('Change', 'vel-enterprise-system'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($analytics_data['performance'] as $metric): ?>
                                        <tr>
                                            <td><?php echo esc_html($metric['label']); ?></td>
                                            <td><?php echo esc_html($metric['value']); ?></td>
                                            <td>
                                                <span class="vel-change <?php echo esc_attr($metric['change']['type']); ?>">
                                                    <?php echo esc_html($metric['change']['value']); ?>%
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p class="vel-no-data">
                                <?php esc_html_e('No performance data available.', 'vel-enterprise-system'); ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <div id="security" class="vel-tab-pane">
                        <!-- Security content -->
                    </div>

                    <div id="usage" class="vel-tab-pane">
                        <!-- Usage content -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
