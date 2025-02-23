<?php
/**
 * 設置頁面視圖
 *
 * @package VEL_Enterprise_System
 * @subpackage Admin
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap vel-admin-wrap">
    <h1><?php echo esc_html__('VEL Enterprise System Settings', 'vel-enterprise-system'); ?></h1>
    <div class="vel-admin-content">
        <form method="post" action="options.php">
            <?php settings_fields('vel_options'); ?>
            <?php do_settings_sections('vel_settings'); ?>
            <?php submit_button(); ?>
        </form>
    </div>
</div>