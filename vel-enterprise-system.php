<?php
/**
 * VEL Enterprise System
 *
 * @package           VEL_Enterprise_System
 * @author            Nuclear3314
 * @copyright         2025 Nuclear3314
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       VEL Enterprise System
 * Plugin URI:        https://github.com/Nuclear3314/vel-enterprise-system
 * Description:       Enterprise level system with security, AI and logistics features
 * Version:          1.0.0
 * Requires at least: 5.8
 * Requires PHP:      7.4
 * Author:           Nuclear3314
 * Author URI:       https://github.com/Nuclear3314
 * Text Domain:      vel-enterprise-system
 * License:          GPL v2 or later
 * License URI:      http://www.gnu.org/licenses/gpl-2.0.txt
 * Update URI:       https://github.com/Nuclear3314/vel-enterprise-system
 */

// 如果直接訪問此文件，則退出
if (!defined('ABSPATH')) {
    exit;
}

// 定義插件常量
define('VEL_VERSION', '1.0.0');
define('VEL_PLUGIN_FILE', __FILE__);
define('VEL_PLUGIN_BASENAME', plugin_basename(__FILE__));
define('VEL_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('VEL_PLUGIN_URL', plugin_dir_url(__FILE__));
define('VEL_PLUGIN_ASSETS_DIR', VEL_PLUGIN_DIR . 'assets/');
define('VEL_PLUGIN_ASSETS_URL', VEL_PLUGIN_URL . 'assets/');
define('VEL_PLUGIN_INCLUDES_DIR', VEL_PLUGIN_DIR . 'includes/');
define('VEL_PLUGIN_ADMIN_DIR', VEL_PLUGIN_DIR . 'admin/');
define('VEL_PLUGIN_PUBLIC_DIR', VEL_PLUGIN_DIR . 'public/');

// 自動加載類
spl_autoload_register(function ($class) {
    $prefix = 'VEL\\';
    $base_dir = VEL_PLUGIN_INCLUDES_DIR;

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// 確保 WordPress 已加載
if (!function_exists('add_action')) {
    exit;
}

// 加載插件功能
require_once VEL_PLUGIN_INCLUDES_DIR . 'class-vel-loader.php';
require_once VEL_PLUGIN_INCLUDES_DIR . 'class-vel-i18n.php';
require_once VEL_PLUGIN_INCLUDES_DIR . 'core/class-vel-base.php';

/**
 * 插件啟動時的處理
 */
function vel_activate() {
    require_once VEL_PLUGIN_INCLUDES_DIR . 'class-vel-installer.php';
    VEL_Installer::install();
}
register_activation_hook(__FILE__, 'vel_activate');

/**
 * 插件停用時的處理
 */
function vel_deactivate() {
    // 清理定時任務
    wp_clear_scheduled_hook('vel_daily_analysis');
    wp_clear_scheduled_hook('vel_weekly_prediction');
}
register_deactivation_hook(__FILE__, 'vel_deactivate');

/**
 * 初始化插件
 */
function vel_init() {
    // 加載文本域
    load_plugin_textdomain(
        'vel-enterprise-system',
        false,
        dirname(VEL_PLUGIN_BASENAME) . '/languages/'
    );

    // 初始化插件
    $plugin = new VEL\Core\Base();
    $plugin->run();
}
add_action('plugins_loaded', 'vel_init');