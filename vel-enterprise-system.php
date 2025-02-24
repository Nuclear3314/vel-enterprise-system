<?php
/**
 * Plugin Name: VEL Enterprise System
 * Description: A powerful enterprise-level system providing AI analysis, prediction, and model management.
 * Version: 1.0.0
 * Author: Nuclear3314
 * Text Domain: vel-enterprise-system
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Define plugin constants.
define( 'VEL_PLUGIN_VERSION', '1.0.0' );
define( 'VEL_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'VEL_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Include required files.
require_once VEL_PLUGIN_DIR . 'config/constants.php';
require_once VEL_PLUGIN_DIR . 'config/default-settings.php';
require_once VEL_PLUGIN_DIR . 'includes/api/class-vel-rest-controller.php';
require_once VEL_PLUGIN_DIR . 'includes/class-vel-security.php';
require_once VEL_PLUGIN_DIR . 'includes/class-vel-ids.php';
require_once VEL_PLUGIN_DIR . 'includes/class-vel-logger.php';

// Initialize the plugin.
function vel_enterprise_system_init() {
    // Load text domain for translations.
    load_plugin_textdomain( 'vel-enterprise-system', false, basename( dirname( __FILE__ ) ) . '/languages' );

    // Register REST API routes.
    $rest_controller = new VEL_REST_Controller();
    $rest_controller->register_routes();
}
add_action( 'init', 'vel_enterprise_system_init' );

// Enqueue admin scripts and styles.
function vel_enqueue_admin_scripts() {
    wp_enqueue_script( 'vel-admin', VEL_PLUGIN_URL . 'admin/js/vel-admin.js', array( 'jquery' ), VEL_PLUGIN_VERSION, true );
    wp_enqueue_style( 'vel-admin', VEL_PLUGIN_URL . 'admin/css/vel-admin.css', array(), VEL_PLUGIN_VERSION );
}
add_action( 'admin_enqueue_scripts', 'vel_enqueue_admin_scripts' );

// Enqueue public scripts and styles.
function vel_enqueue_public_scripts() {
    wp_enqueue_script( 'vel-public', VEL_PLUGIN_URL . 'public/js/vel-public.js', array( 'jquery' ), VEL_PLUGIN_VERSION, true );
    wp_enqueue_style( 'vel-public', VEL_PLUGIN_URL . 'public/css/vel-public.css', array(), VEL_PLUGIN_VERSION );
}
add_action( 'wp_enqueue_scripts', 'vel_enqueue_public_scripts' );

// Detect and respond to suspicious activity.
function vel_detect_suspicious_activity( $request ) {
    VEL_IDS::detect_intrusion( $request );
}
add_action( 'rest_api_init', 'vel_detect_suspicious_activity' );
