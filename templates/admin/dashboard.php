<?php
/**
 * Admin Dashboard Template
 *
 * @package     VEL
 * @author      Nuclear3314
 * @copyright   2025 Nuclear3314
 * @license     GPL v2 or later
 * @version     1.0.0
 * @created     2025-02-25 09:06:52
 */

if (!defined('ABSPATH')) {
    exit('Direct access not allowed.');
}

// 獲取必要的數據
$security_status = VEL()->security->get_status();
$ai_predictions = VEL()->ai->get_recent_predictions();
$system_stats = VEL()->system->get_statistics();
$recent_activities = VEL()->logger->get_recent_activities();
?>

<div class="wrap vel-admin-wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

    <?php settings_errors(); ?>

    <div class="vel-admin-header">
        <div class="vel-stats">
            <div class="vel-stat-card">
                <h3><?php esc_html_e('Security Status', 'vel-enterprise-system'); ?></h3>
                <div class="vel-stat-value">
                    <?php echo esc_html($security_status['level']); ?>
                </div>
                <div class="vel-stat-description">
                    <?php echo esc_html($security_status['description']); ?>
                </div>
            </div>

            <div class="vel-stat-card">
                <h3><?php esc_html_e('AI Insights', 'vel-enterprise-system'); ?></h3>
                <div class="vel-stat-value">
                    <?php echo esc_html($ai_predictions['count']); ?>
                </div>
                <div class="vel-stat-description">
                    <?php echo esc_html($ai_predictions['summary']); ?>
                </div>
            </div>

            <div class="vel-stat-card">
                <h3><?php esc_html_e('System Health', 'vel-enterprise-system'); ?></h3>
                <div class="vel-stat-value">
                    <?php echo esc_html($system_stats['health_score']); ?>%
                </div>
                <div class="vel-stat-description">
                    <?php echo esc_html($system_stats['status']); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="vel-grid">
        <div class="vel-card">
            <div class="vel-widget" id="vel-security-widget">
                <div class="vel-widget-header">
                    <h2><?php esc_html_e('Security Overview', 'vel-enterprise-system'); ?></h2>
                    <button class="vel-button vel-button-secondary" onclick="velRefreshWidget('security')">
                        <?php esc_html_e('Refresh', 'vel-enterprise-system'); ?>
                    </button>
                </div>
                <div class="vel-widget-content">
                    <?php VEL()->admin->render_security_widget(); ?>
                </div>
            </div>
        </div>

        <div class="vel-card">
            <div class="vel-widget" id="vel-ai-widget">
                <div class="vel-widget-header">
                    <h2><?php esc_html_e('AI Analytics', 'vel-enterprise-system'); ?></h2>
                    <button class="vel-button vel-button-secondary" onclick="velRefreshWidget('ai')">
                        <?php esc_html_e('Refresh', 'vel-enterprise-system'); ?>
                    </button>
                </div>
                <div class="vel-widget-content">
                    <?php VEL()->admin->render_ai_widget(); ?>
                </div>
            </div>
        </div>

        <div class="vel-card">
            <div class="vel-widget" id="vel-logistics-widget">
                <div class="vel-widget-header">
                    <h2><?php esc_html_e('Logistics Status', 'vel-enterprise-system'); ?></h2>
                    <button class="vel-button vel-button-secondary" onclick="velRefreshWidget('logistics')">
                        <?php esc_html_e('Refresh', 'vel-enterprise-system'); ?>
                    </button>
                </div>
                <div class="vel-widget-content">
                    <?php VEL()->admin->render_logistics_widget(); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="vel-grid">
        <div class="vel-card">
            <div class="vel-widget" id="vel-activity-widget">
                <div class="vel-widget-header">
                    <h2><?php esc_html_e('Recent Activity', 'vel-enterprise-system'); ?></h2>
                </div>
                <div class="vel-widget-content">
                    <div class="vel-activity-log">
                        <?php foreach ($recent_activities as $activity): ?>
                            <div class="vel-activity-item">
                                <span class="vel-activity-time">
                                    <?php echo esc_html($activity['time']); ?>
                                </span>
                                <span class="vel-activity-type <?php echo esc_attr($activity['type']); ?>">
                                    <?php echo esc_html($activity['type']); ?>
                                </span>
                                <span class="vel-activity-message">
                                    <?php echo esc_html($activity['message']); ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="vel-card">
            <div class="vel-widget" id="vel-system-widget">
                <div class="vel-widget-header">
                    <h2><?php esc_html_e('System Information', 'vel-enterprise-system'); ?></h2>
                </div>
                <div class="vel-widget-content">
                    <?php VEL()->admin->render_system_info(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
