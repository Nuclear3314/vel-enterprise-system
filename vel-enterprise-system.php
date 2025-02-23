<?php
/**
 * Plugin Name: VEL Enterprise System
 * Plugin URI: https://github.com/Nuclear3314/vel-enterprise-system
 * Description: Enterprise level system with security, AI and logistics
 * Version: 1.0.0
 * Author: Nuclear3314
 * Author URI: https://github.com/Nuclear3314
 * License: GPL v2 or later
 * Text Domain: vel-enterprise-system
 * Domain Path: /languages
 *
 * @package VEL_Enterprise_System
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

// Plugin version
define('VEL_VERSION', '1.0.0');
define('VEL_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('VEL_PLUGIN_URL', plugin_dir_url(__FILE__));
define('VEL_INSTALL_DATE', '2025-02-23 11:56:25');

// Autoloader
require_once VEL_PLUGIN_DIR . 'includes/core/class-vel-loader.php';

// Initialize plugin
function vel_init() {
    $loader = new VEL_Loader();
    $loader->run();
}
add_action('plugins_loaded', 'vel_init');
