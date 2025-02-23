<?php
/**
 * VEL Enterprise System Uninstaller
 *
 * @package VEL_Enterprise_System
 */

// 如果未經 WordPress 調用，則退出
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// 加載安裝程序類
require_once plugin_dir_path(__FILE__) . 'includes/class-vel-installer.php';

// 運行卸載程序
VEL_Installer::uninstall();

// 清理選項
$options = array(
    'vel_install_date',
    'vel_db_version',
    'vel_ai_config',
    'vel_notification_settings',
    'vel_security_settings',
    'vel_report_settings'
);

foreach ($options as $option) {
    delete_option($option);
}

// 清理定時任務
wp_clear_scheduled_hook('vel_daily_analysis');
wp_clear_scheduled_hook('vel_weekly_prediction');

// 清理角色和權限
$roles = array('vel_ai_analyst', 'vel_ai_admin');
foreach ($roles as $role) {
    remove_role($role);
}

// 清理管理員權限
$admin = get_role('administrator');
if ($admin) {
    $capabilities = array(
        'vel_manage_ai',
        'vel_view_predictions',
        'vel_create_predictions',
        'vel_view_analysis',
        'vel_manage_models',
        'vel_export_data'
    );
    
    foreach ($capabilities as $cap) {
        $admin->remove_cap($cap);
    }
}