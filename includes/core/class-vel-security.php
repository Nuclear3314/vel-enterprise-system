<?php
/**
 * 安全模塊核心類
 *
 * @package VEL_Enterprise_System
 * @subpackage Core
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class VEL_Security {
    /**
     * 日誌目錄
     *
     * @var string
     */
    private $log_dir;

    /**
     * 初始化安全模塊
     */
    public function __construct() {
        $this->log_dir = VEL_PLUGIN_DIR . 'logs';
        $this->init_security();
    }

    /**
     * 初始化安全設置
     */
    public function init_security() {
        // 基本安全措施
        add_action('send_headers', array($this, 'set_security_headers'));
        add_action('admin_init', array($this, 'init_security_logging'));
        
        // 高級安全功能
        add_filter('authenticate', array($this, 'check_login_attempts'), 30, 3);
        add_action('wp_login_failed', array($this, 'log_failed_login'));
        
        // 檔案上傳安全
        add_filter('upload_mimes', array($this, 'restrict_upload_types'));
        add_filter('wp_handle_upload_prefilter', array($this, 'scan_uploads'));
    }

    /**
     * 設置安全headers
     */
    public function set_security_headers() {
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: SAMEORIGIN');
        header('X-XSS-Protection: 1; mode=block');
        header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
        header('Content-Security-Policy: default-src \'self\'');
        header('Referrer-Policy: strict-origin-when-cross-origin');
    }

    /**
     * 初始化安全日誌
     */
    public function init_security_logging() {
        if (!file_exists($this->log_dir)) {
            wp_mkdir_p($this->log_dir);
        }
        
        // 設置日誌文件權限
        $this->secure_log_directory();
        
        // 開始記錄
        $this->log_security_event('Security logging initialized');
    }

    /**
     * 檢查登入嘗試次數
     *
     * @param WP_User|WP_Error|null $user
     * @param string                $username
     * @param string                $password
     * @return WP_User|WP_Error
     */
    public function check_login_attempts($user, $username, $password) {
        if (!empty($username)) {
            $attempts = get_transient('login_attempts_' . $username);
            
            if ($attempts && $attempts >= 5) {
                return new WP_Error(
                    'too_many_attempts',
                    '登入嘗試次數過多，請稍後再試。'
                );
            }
        }
        
        return $user;
    }

    /**
     * 記錄失敗的登入嘗試
     *
     * @param string $username
     */
    public function log_failed_login($username) {