<?php
/**
 * 安全設置頁面模板
 *
 * @package VEL_Enterprise_System
 * @subpackage Admin
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap vel-admin-wrap">
    <h1><?php echo esc_html__('Security Settings', 'vel-enterprise-system'); ?></h1>
    
    <?php settings_errors(); ?>
    
    <div class="vel-admin-content">
        <form method="post" action="options.php">
            <?php
            settings_fields('vel_security_options');
            do_settings_sections('vel_security_settings');
            submit_button();
            ?>
        </form>
        
        <div class="vel-security-status">
            <h2><?php echo esc_html__('Security Status', 'vel-enterprise-system'); ?></h2>
            <?php
            $security = new VEL_Security();
            $status = $security->get_security_status();
            ?>
            <table class="widefat">
                <thead>
                    <tr>
                        <th><?php echo esc_html__('Check', 'vel-enterprise-system'); ?></th>
                        <th><?php echo esc_html__('Status', 'vel-enterprise-system'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($status as $check => $result) : ?>
                    <tr>
                        <td><?php echo esc_html($check); ?></td>
                        <td>
                            <span class="vel-status-<?php echo esc_attr($result['status']); ?>">
                                <?php echo esc_html($result['message']); ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>