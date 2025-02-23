<?php
/**
 * 安裝程序類
 *
 * @package VEL_Enterprise_System
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class VEL_Installer {
    /**
     * 數據庫版本
     */
    const DB_VERSION = '1.0.0';

    /**
     * 執行安裝
     */
    public static function install() {
        self::create_tables();
        self::create_options();
        self::create_roles();
        
        // 記錄安裝時間
        add_option('vel_install_date', current_time('mysql'));
        add_option('vel_db_version', self::DB_VERSION);
    }

    /**
     * 創建數據表
     */
    private static function create_tables() {
        global $wpdb;
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        $charset_collate = $wpdb->get_charset_collate();

        // 歷史數據表
        $historical_table = $wpdb->prefix . 'vel_historical_data';
        $sql = "CREATE TABLE IF NOT EXISTS $historical_table (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            target varchar(50) NOT NULL,
            date datetime NOT NULL,
            value decimal(15,4) NOT NULL,
            metadata longtext,
            created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            KEY target (target),
            KEY date (date)
        ) $charset_collate;";
        dbDelta($sql);

        // 預測結果表
        $predictions_table = $wpdb->prefix . 'vel_predictions';
        $sql = "CREATE TABLE IF NOT EXISTS $predictions_table (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            target varchar(50) NOT NULL,
            predictions longtext NOT NULL,
            confidence decimal(5,4) NOT NULL,
            metadata longtext,
            created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            KEY target (target),
            KEY created_at (created_at)
        ) $charset_collate;";
        dbDelta($sql);

        // AI 模型表
        $models_table = $wpdb->prefix . 'vel_ai_models';
        $sql = "CREATE TABLE IF NOT EXISTS $models_table (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            name varchar(100) NOT NULL,
            type varchar(50) NOT NULL,
            version varchar(20) NOT NULL,
            config longtext NOT NULL,
            status varchar(20) NOT NULL DEFAULT 'active',
            performance_metrics longtext,
            created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            UNIQUE KEY name_version (name, version),
            KEY status (status)
        ) $charset_collate;";
        dbDelta($sql);

        // 分析日誌表
        $analysis_logs_table = $wpdb->prefix . 'vel_analysis_logs';
        $sql = "CREATE TABLE IF NOT EXISTS $analysis_logs_table (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            type varchar(50) NOT NULL,
            description text NOT NULL,
            details longtext,
            status varchar(20) NOT NULL,
            execution_time decimal(10,4),
            created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            KEY type (type),
            KEY status (status),
            KEY created_at (created_at)
        ) $charset_collate;";
        dbDelta($sql);

        // 模型評估表
        $evaluations_table = $wpdb->prefix . 'vel_model_evaluations';
        $sql = "CREATE TABLE IF NOT EXISTS $evaluations_table (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            model_id bigint(20) NOT NULL,
            metric_name varchar(50) NOT NULL,
            metric_value decimal(10,4) NOT NULL,
            details longtext,
            created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            KEY model_id (model_id),
            KEY metric_name (metric_name)
        ) $charset_collate;";
        dbDelta($sql);
    }

    /**
     * 創建選項
     */
    private static function create_options() {
        // AI 配置
        add_option('vel_ai_config', array(
            'prediction_window' => 30,
            'min_confidence' => 0.7,
            'use_seasonality' => true,
            'max_iterations' => 1000,
            'learning_rate' => 0.01,
            'validation_split' => 0.2,
            'early_stopping_patience' => 5
        ));

        // 通知設置
        add_option('vel_notification_settings', array(
            'email_notifications' => true,
            'prediction_alerts' => true,
            'accuracy_threshold' => 0.8,
            'alert_frequency' => 'daily'
        ));

        // 安全設置
        add_option('vel_security_settings', array(
            'api_rate_limit' => 100,
            'max_predictions_per_day' => 1000,
            'log_retention_days' => 30
        ));

        // 報告設置
        add_option('vel_report_settings', array(
            'auto_generate' => true,
            'report_frequency' => 'weekly',
            'include_charts' => true,
            'export_format' => 'pdf'
        ));
    }

    /**
     * 創建角色和權限
     */
    private static function create_roles() {
        // AI 分析師角色
        add_role('vel_ai_analyst', __('AI Analyst', 'vel-enterprise-system'), array(
            'read' => true,
            'vel_view_predictions' => true,
            'vel_create_predictions' => true,
            'vel_view_analysis' => true
        ));

        // AI 管理員角色
        add_role('vel_ai_admin', __('AI Administrator', 'vel-enterprise-system'), array(
            'read' => true,
            'vel_manage_ai' => true,
            'vel_view_predictions' => true,
            'vel_create_predictions' => true,
            'vel_view_analysis' => true,
            'vel_manage_models' => true,
            'vel_export_data' => true
        ));

        // 添加自定義功能到管理員角色
        $admin_role = get_role('administrator');
        if ($admin_role) {
            $admin_role->add_cap('vel_manage_ai');
            $admin_role->add_cap('vel_view_predictions');
            $admin_role->add_cap('vel_create_predictions');
            $admin_role->add_cap('vel_view_analysis');
            $admin_role->add_cap('vel_manage_models');
            $admin_role->add_cap('vel_export_data');
        }
    }

    /**
     * 升級數據庫
     */
    public static function update_db_check() {
        if (get_option('vel_db_version') != self::DB_VERSION) {
            self::create_tables();
            update_option('vel_db_version', self::DB_VERSION);
        }
    }

    /**
     * 卸載插件
     */
    public static function uninstall() {
        self::remove_tables();
        self::remove_options();
        self::remove_roles();
    }

    /**
     * 移除數據表
     */
    private static function remove_tables() {
        global $wpdb;

        $tables = array(
            'vel_historical_data',
            'vel_predictions',
            'vel_ai_models',
            'vel_analysis_logs',
            'vel_model_evaluations'
        );

        foreach ($tables as $table) {
            $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}$table");
        }
    }

    /**
     * 移除選項
     */
    private static function remove_options() {
        delete_option('vel_install_date');
        delete_option('vel_db_version');
        delete_option('vel_ai_config');
        delete_option('vel_notification_settings');
        delete_option('vel_security_settings');
        delete_option('vel_report_settings');
    }

    /**
     * 移除角色和權限
     */
    private static function remove_roles() {
        remove_role('vel_ai_analyst');
        remove_role('vel_ai_admin');

        $admin_role = get_role('administrator');
        if ($admin_role) {
            $admin_role->remove_cap('vel_manage_ai');
            $admin_role->remove_cap('vel_view_predictions');
            $admin_role->remove_cap('vel_create_predictions');
            $admin_role->remove_cap('vel_view_analysis');
            $admin_role->remove_cap('vel_manage_models');
            $admin_role->remove_cap('vel_export_data');
        }
    }
}