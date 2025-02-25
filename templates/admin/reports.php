<?php
/**
 * Admin Reports Template
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

$report_type = isset($_GET['type']) ? sanitize_text_field($_GET['type']) : 'security';
$date_range = isset($_GET['range']) ? sanitize_text_field($_GET['range']) : 'week';
$custom_start = isset($_GET['start']) ? sanitize_text_field($_GET['start']) : '';
$custom_end = isset($_GET['end']) ? sanitize_text_field($_GET['end']) : '';

$report_data = VEL()->reports->get_report_data($report_type, $date_range, $custom_start, $custom_end);
?>

<div class="wrap vel-admin-wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

    <div class="vel-report-filters">
        <form method="get" action="" class="vel-filters-form">
            <input type="hidden" name="page" value="vel-reports">
            
            <select name="type" id="vel-report-type">
                <option value="security" <?php selected($report_type, 'security'); ?>>
                    <?php esc_html_e('Security Report', 'vel-enterprise-system'); ?>
                </option>
                <option value="ai" <?php selected($report_type, 'ai'); ?>>
                    <?php esc_html_e('AI Performance Report', 'vel-enterprise-system'); ?>
                </option>
                <option value="logistics" <?php selected($report_type, 'logistics'); ?>>
                    <?php esc_html_e('Logistics Report', 'vel-enterprise-system'); ?>
                </option>
                <option value="system" <?php selected($report_type, 'system'); ?>>
                    <?php esc_html_e('System Health Report', 'vel-enterprise-system'); ?>
                </option>
            </select>

            <select name="range" id="vel-date-range">
                <option value="day" <?php selected($date_range, 'day'); ?>>
                    <?php esc_html_e('Today', 'vel-enterprise-system'); ?>
                </option>
                <option value="week" <?php selected($date_range, 'week'); ?>>
                    <?php esc_html_e('This Week', 'vel-enterprise-system'); ?>
                </option>
                <option value="month" <?php selected($date_range, 'month'); ?>>
                    <?php esc_html_e('This Month', 'vel-enterprise-system'); ?>
                </option>
                <option value="custom" <?php selected($date_range, 'custom'); ?>>
                    <?php esc_html_e('Custom Range', 'vel-enterprise-system'); ?>
                </option>
            </select>

            <div class="vel-custom-range" style="display: <?php echo $date_range === 'custom' ? 'inline' : 'none'; ?>">
                <input type="date" name="start" value="<?php echo esc_attr($custom_start); ?>">
                <input type="date" name="end" value="<?php echo esc_attr($custom_end); ?>">
            </div>

            <button type="submit" class="button button-primary">
                <?php esc_html_e('Generate Report', 'vel-enterprise-system'); ?>
            </button>

            <button type="button" class="button button-secondary" id="vel-export-report">
                <?php esc_html_e('Export Report', 'vel-enterprise-system'); ?>
            </button>
        </form>
    </div>

    <div class="vel-report-content">
        <?php if (!empty($report_data)): ?>
            <div class="vel-report-summary">
                <h2><?php esc_html_e('Report Summary', 'vel-enterprise-system'); ?></h2>
                <div class="vel-summary-stats">
                    <?php foreach ($report_data['summary'] as $key => $value): ?>
                        <div class="vel-stat-card">
                            <h3><?php echo esc_html($value['label']); ?></h3>
                            <div class="vel-stat-value">
                                <?php echo esc_html($value['value']); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="vel-report-details">
                <h2><?php esc_html_e('Detailed Report', 'vel-enterprise-system'); ?></h2>
                <div class="vel-report-chart">
                    <!-- Chart will be rendered here by JavaScript -->
                    <canvas id="velReportChart"></canvas>
                </div>

                <div class="vel-report-table">
                    <table class="wp-list-table widefat fixed striped">
                        <thead>
                            <tr>
                                <?php foreach ($report_data['headers'] as $header): ?>
                                    <th><?php echo esc_html($header); ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($report_data['rows'] as $row): ?>
                                <tr>
                                    <?php foreach ($row as $cell): ?>
                                        <td><?php echo esc_html($cell); ?></td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php else: ?>
            <div class="vel-no-data">
                <p><?php esc_html_e('No data available for the selected parameters.', 'vel-enterprise-system'); ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>
