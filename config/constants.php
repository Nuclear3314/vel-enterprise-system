<?php
/**
 * Constants Configuration
 *
 * @package     VEL
 * @author      Nuclear3314
 * @copyright   2025 Nuclear3314
 * @license     GPL v2 or later
 * @version     1.0.0
 * @created     2025-02-25 14:58:29
 */

if (!defined('ABSPATH')) {
    exit('Direct access not allowed.');
}

// Plugin Information
define('VEL_NAME', 'VEL Enterprise System');
define('VEL_SLUG', 'vel-enterprise-system');
define('VEL_VERSION', '1.0.0');
define('VEL_DB_VERSION', '1.0.0');
define('VEL_REQUIRED_WP_VERSION', '5.8');
define('VEL_REQUIRED_PHP_VERSION', '7.4');

// File and Directory Paths
define('VEL_FILE', __FILE__);
define('VEL_BASENAME', plugin_basename(VEL_FILE));
define('VEL_PATH', dirname(VEL_FILE));
define('VEL_INCLUDES_PATH', VEL_PATH . '/includes');
define('VEL_TEMPLATES_PATH', VEL_PATH . '/templates');
define('VEL_ASSETS_PATH', VEL_PATH . '/assets');
define('VEL_LANGUAGES_PATH', VEL_PATH . '/languages');
define('VEL_VENDOR_PATH', VEL_PATH . '/vendor');

// URLs
define('VEL_URL', plugins_url('', VEL_FILE));
define('VEL_ASSETS_URL', VEL_URL . '/assets');
define('VEL_JS_URL', VEL_ASSETS_URL . '/js');
define('VEL_CSS_URL', VEL_ASSETS_URL . '/css');
define('VEL_IMAGES_URL', VEL_ASSETS_URL . '/images');

// Security Constants
define('VEL_MAX_LOGIN_ATTEMPTS', 5);
define('VEL_LOGIN_LOCKOUT_DURATION', 900); // 15 minutes in seconds
define('VEL_SECURITY_SALT', 'vf@K8#mP9$qL2*nX');
define('VEL_SESSION_TIMEOUT', 3600); // 1 hour in seconds
define('VEL_TOKEN_EXPIRATION', 86400); // 24 hours in seconds

// AI Model Constants
define('VEL_DEFAULT_MODEL', 'gpt-3');
define('VEL_MODEL_VERSION', '1.0.0');
define('VEL_AI_BATCH_SIZE', 32);
define('VEL_MAX_TOKENS', 2048);
define('VEL_MIN_CONFIDENCE', 0.75);

// Cache Constants
define('VEL_CACHE_ENABLED', true);
define('VEL_CACHE_EXPIRATION', 3600); // 1 hour in seconds
define('VEL_CACHE_PREFIX', 'vel_');

// API Constants
define('VEL_API_VERSION', 'v1');
define('VEL_API_NAMESPACE', 'vel/v1');
define('VEL_API_TIMEOUT', 30); // seconds
define('VEL_MAX_API_REQUESTS', 1000);

// Logging Constants
define('VEL_LOG_ENABLED', true);
define('VEL_LOG_LEVEL', 'info'); // debug, info, warning, error
define('VEL_LOG_FILE', WP_CONTENT_DIR . '/vel-logs/system.log');
define('VEL_ERROR_LOG', WP_CONTENT_DIR . '/vel-logs/error.log');

// Database Tables
define('VEL_TABLE_SECURITY', $wpdb->prefix . 'vel_security_log');
define('VEL_TABLE_AI_MODELS', $wpdb->prefix . 'vel_ai_models');
define('VEL_TABLE_PREDICTIONS', $wpdb->prefix . 'vel_predictions');
define('VEL_TABLE_ANALYSIS', $wpdb->prefix . 'vel_analysis');
define('VEL_TABLE_SETTINGS', $wpdb->prefix . 'vel_settings');

// Feature Flags
define('VEL_FEATURE_AI_ENABLED', true);
define('VEL_FEATURE_IDS_ENABLED', true);
define('VEL_FEATURE_ANALYTICS_ENABLED', true);
define('VEL_FEATURE_REPORTS_ENABLED', true);
define('VEL_FEATURE_API_ENABLED', true);

// System Limits
define('VEL_MAX_FILE_SIZE', 10 * 1024 * 1024); // 10MB
define('VEL_MAX_UPLOAD_FILES', 10);
define('VEL_MAX_BATCH_PROCESS', 100);
define('VEL_CRON_INTERVAL', 300); // 5 minutes in seconds
