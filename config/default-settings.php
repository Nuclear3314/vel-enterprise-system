<?php
/**
 * Default Settings Configuration
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

return array(
    // General Settings
    'general' => array(
        'enable_features' => array(
            'ai' => true,
            'security' => true,
            'analytics' => true,
            'reports' => true,
            'api' => true
        ),
        'debug_mode' => false,
        'log_level' => 'info',
        'maintenance_mode' => false,
        'timezone' => 'UTC',
        'date_format' => 'Y-m-d H:i:s',
        'language' => 'en_US'
    ),

    // Security Settings
    'security' => array(
        'max_login_attempts' => VEL_MAX_LOGIN_ATTEMPTS,
        'lockout_duration' => VEL_LOGIN_LOCKOUT_DURATION,
        'enable_2fa' => true,
        'password_expiration' => 90, // days
        'min_password_length' => 12,
        'require_special_chars' => true,
        'session_timeout' => VEL_SESSION_TIMEOUT,
        'ip_whitelist' => array(),
        'ip_blacklist' => array(),
        'enable_ids' => true,
        'scan_frequency' => 'daily',
        'file_monitoring' => true
    ),

    // AI Settings
    'ai' => array(
        'default_model' => VEL_DEFAULT_MODEL,
        'model_version' => VEL_MODEL_VERSION,
        'batch_size' => VEL_AI_BATCH_SIZE,
        'max_tokens' => VEL_MAX_TOKENS,
        'min_confidence' => VEL_MIN_CONFIDENCE,
        'training_schedule' => 'weekly',
        'auto_update_models' => true,
        'retention_period' => 30, // days
        'enable_predictions' => true,
        'prediction_threshold' => 0.75
    ),

    // API Settings
    'api' => array(
        'version' => VEL_API_VERSION,
        'namespace' => VEL_API_NAMESPACE,
        'timeout' => VEL_API_TIMEOUT,
        'max_requests' => VEL_MAX_API_REQUESTS,
        'require_authentication' => true,
        'allow_remote_access' => false,
        'rate_limiting' => true,
        'cache_enabled' => true,
        'cache_ttl' => 3600
    ),

    // Logging Settings
    'logging' => array(
        'enabled' => VEL_LOG_ENABLED,
        'level' => VEL_LOG_LEVEL,
        'file' => VEL_LOG_FILE,
        'error_file' => VEL_ERROR_LOG,
        'max_file_size' => 10 * 1024 * 1024, // 10MB
        'rotation_enabled' => true,
        'rotation_size' => 5 * 1024 * 1024, // 5MB
        'retention_days' => 30
    ),

    // Cache Settings
    'cache' => array(
        'enabled' => VEL_CACHE_ENABLED,
        'expiration' => VEL_CACHE_EXPIRATION,
        'prefix' => VEL_CACHE_PREFIX,
        'driver' => 'file', // file, redis, memcached
        'compression' => true,
        'exclude_urls' => array(),
        'exclude_cookies' => array(),
        'exclude_query_strings' => array()
    ),

    // Email Settings
    'email' => array(
        'from_name' => get_bloginfo('name'),
        'from_email' => get_bloginfo('admin_email'),
        'template_path' => VEL_TEMPLATES_PATH . '/emails',
        'notification_events' => array(
            'security_alert' => true,
            'model_update' => true,
            'prediction_complete' => true,
            'system_error' => true
        )
    ),

    // Report Settings
    'reports' => array(
        'enabled_reports' => array(
            'security' => true,
            'performance' => true,
            'predictions' => true,
            'usage' => true
        ),
        'schedule' => array(
            'security' => 'daily',
            'performance' => 'weekly',
            'predictions' => 'monthly',
            'usage' => 'monthly'
        ),
        'retention' => array(
            'security' => 90, // days
            'performance' => 180,
            'predictions' => 365,
            'usage' => 365
        ),
        'export_formats' => array('csv', 'pdf', 'json')
    ),

    // Integration Settings
    'integrations' => array(
        'slack' => array(
            'enabled' => false,
            'webhook_url' => '',
            'channel' => '#vel-alerts',
            'username' => 'VEL Bot',
            'icon' => ':robot_face:'
        ),
        'sentry' => array(
            'enabled' => false,
            'dsn' => '',
            'environment' => 'production'
        ),
        'elasticsearch' => array(
            'enabled' => false,
            'host' => 'localhost',
            'port' => 9200,
            'index_prefix' => 'vel_'
        )
    )
);
