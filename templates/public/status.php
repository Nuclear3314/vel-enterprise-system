<?php
/**
 * Public Status Template
 *
 * @package     VEL
 * @author      Nuclear3314
 * @copyright   2025 Nuclear3314
 * @license     GPL v2 or later
 * @version     1.0.0
 * @created     2025-02-25 14:36:17
 */

if (!defined('ABSPATH')) {
    exit('Direct access not allowed.');
}

$system_status = VEL()->system->get_public_status();
$user_status = VEL()->users->get_user_status(get_current_user_id());
?>

<div class="vel-public-wrap">
    <div class="vel-status-header">
        <h1><?php esc_html_e('System Status', 'vel-enterprise-system'); ?></h1>
        <div class="vel-status-actions">
            <button type="button" class="vel-button vel-button-primary" id="vel-refresh-status">
                <?php esc_html_e('Refresh Status', 'vel-enterprise-system'); ?>
            </button>
        </div>
    </div>

    <div class="vel-status-content">
        <!-- System Status Overview -->
        <div class="vel-status-overview">
            <div class="vel-grid">
                <div class="vel-status-card">
                    <h3><?php esc_html_e('System Health', 'vel-enterprise-system'); ?></h3>
                    <div class="vel-status-indicator <?php echo esc_attr($system_status['health']['class']); ?>">
                        <?php echo esc_html($system_status['health']['status']); ?>
                    </div>
                    <div class="vel-status-details">
                        <?php echo esc_html($system_status['health']['message']); ?>
                    </div>
                </div>

                <div class="vel-status-card">
                    <h3><?php esc_html_e('Security Status', 'vel-enterprise-system'); ?></h3>
                    <div class="vel-status-indicator <?php echo esc_attr($system_status['security']['class']); ?>">
                        <?php echo esc_html($system_status['security']['status']); ?>
                    </div>
                    <div class="vel-status-details">
                        <?php echo esc_html($system_status['security']['message']); ?>
                    </div>
                </div>

                <div class="vel-status-card">
                    <h3><?php esc_html_e('AI System Status', 'vel-enterprise-system'); ?></h3>
                    <div class="vel-status-indicator <?php echo esc_attr($system_status['ai']['class']); ?>">
                        <?php echo esc_html($system_status['ai']['status']); ?>
                    </div>
                    <div class="vel-status-details">
                        <?php echo esc_html($system_status['ai']['message']); ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Status Section -->
        <div class="vel-user-status">
            <h2><?php esc_html_e('Your Account Status', 'vel-enterprise-system'); ?></h2>
            <div class="vel-grid">
                <div class="vel-status-card">
                    <h3><?php esc_html_e('Access Level', 'vel-enterprise-system'); ?></h3>
                    <div class="vel-status-value">
                        <?php echo esc_html($user_status['access_level']); ?>
                    </div>
                </div>

                <div class="vel-status-card">
                    <h3><?php esc_html_e('Account Security', 'vel-enterprise-system'); ?></h3>
                    <div class="vel-status-value">
                        <?php echo esc_html($user_status['security_status']); ?>
                    </div>
                    <?php if (!empty($user_status['security_recommendations'])): ?>
                        <div class="vel-recommendations">
                            <h4><?php esc_html_e('Recommendations', 'vel-enterprise-system'); ?></h4>
                            <ul>
                                <?php foreach ($user_status['security_recommendations'] as $recommendation): ?>
                                    <li><?php echo esc_html($recommendation); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="vel-status-card">
                    <h3><?php esc_html_e('Recent Activity', 'vel-enterprise-system'); ?></h3>
                    <div class="vel-activity-list">
                        <?php if (!empty($user_status['recent_activity'])): ?>
                            <?php foreach ($user_status['recent_activity'] as $activity): ?>
                                <div class="vel-activity-item">
                                    <span class="vel-activity-time">
                                        <?php echo esc_html($activity['time']); ?>
                                    </span>
                                    <span class="vel-activity-type">
                                        <?php echo esc_html($activity['type']); ?>
                                    </span>
                                    <span class="vel-activity-description">
                                        <?php echo esc_html($activity['description']); ?>
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="vel-no-activity">
                                <?php esc_html_e('No recent activity found.', 'vel-enterprise-system'); ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Notifications -->
        <?php if (!empty($system_status['notifications'])): ?>
            <div class="vel-notifications">
                <h2><?php esc_html_e('System Notifications', 'vel-enterprise-system'); ?></h2>
                <div class="vel-notification-list">
                    <?php foreach ($system_status['notifications'] as $notification): ?>
                        <div class="vel-notification-item <?php echo esc_attr($notification['type']); ?>">
                            <div class="vel-notification-header">
                                <span class="vel-notification-title">
                                    <?php echo esc_html($notification['title']); ?>
                                </span>
                                <span class="vel-notification-time">
                                    <?php echo esc_html($notification['time']); ?>
                                </span>
                            </div>
                            <div class="vel-notification-content">
                                <?php echo wp_kses_post($notification['message']); ?>
                            </div>
                            <?php if (!empty($notification['action'])): ?>
                                <div class="vel-notification-action">
                                    <a href="<?php echo esc_url($notification['action']['url']); ?>" 
                                       class="vel-button vel-button-small">
                                        <?php echo esc_html($notification['action']['label']); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
