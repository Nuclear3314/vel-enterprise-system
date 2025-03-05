<?php
/**
 * Plugin Name: VEL Enterprise System
 * Plugin URI: https://v-e-l-newlifeworld.com
 * Description: 企業級 WordPress 系統整合方案
 * Version: 1.0.0
 * Requires at least: 5.8
 * Requires PHP: 8.2
 * Author: VEL New Life World
 * Author URI: https://v-e-l-newlifeworld.com
 * Text Domain: vel-enterprise-system
 * Domain Path: /languages
 */

namespace VEL;

if (!defined('ABSPATH')) {
    exit;
}

// 定義常數
define('VEL_VERSION', '1.0.0');
define('VEL_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('VEL_PLUGIN_URL', plugin_dir_url(__FILE__));
define('VEL_PLUGIN_FILE', __FILE__);

// 自動載入
require_once VEL_PLUGIN_DIR . 'includes/class-vel-autoloader.php';
$autoloader = new Includes\VEL_Autoloader();
$autoloader->register();

// 初始化核心元件
$core = new Includes\VEL_Core();
$compatibility = new Includes\VEL_Compatibility();
$assets = new Includes\VEL_Assets();
$security = new Includes\VEL_Security_Validator();

// 註冊啟用/停用鉤子
register_activation_hook(__FILE__, [$core, 'activate']);
register_deactivation_hook(__FILE__, [$core, 'deactivate']);

// 載入翻譯
add_action('plugins_loaded', function() use ($core) {
    load_plugin_textdomain(
        'vel-enterprise-system',
        false,
        dirname(plugin_basename(__FILE__)) . '/languages/'
    );
    
    // 執行相容性檢查
    if (!$core->check_compatibility()) {
        return;
    }
    
    // 初始化外掛
    $core->init();
});

// 註冊資源
add_action('init', function() use ($assets) {
    $assets->register_assets();
});