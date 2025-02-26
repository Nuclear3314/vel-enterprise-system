<?php
/**
 * Security Class
 *
 * @package     VEL
 * @subpackage  VEL/includes
 * @author      Nuclear3314
 * @copyright   2025 Nuclear3314
 * @license     GPL v2 or later
 * @version     1.0.0
 * @created     2025-02-26 12:21:08
 */

if (!defined('ABSPATH')) {
    exit('Direct access not allowed.');
}

class VEL_Security {
    /**
     * IDS 實例
     *
     * @var VEL_IDS
     */
    private $ids;

    /**
     * Logger 實例
     *
     * @var VEL_Logger
     */
    private $logger;

    /**
     * 構造函數
     */
    public function __construct() {
        $this->ids = new VEL_IDS();
        $this->logger = new VEL_Logger();
    }

    /**
     * 獲取系統安全狀態
     *
     * @return array
     */
    public function get_status() {
        $status = array(
            'overall' => $this->get_overall_status(),
            'firewall' => $this->get_firewall_status(),
            'intrusions' => $this->get_intrusion_attempts(),
            'malware' => $this->get_malware_status(),
            'updates' => $this->get_updates_status(),
            'permissions' => $this->get_permissions_status(),
            'ssl' => $this->get_ssl_status(),
            'last_scan' => $this->get_last_scan_info()
        );

        $this->logger->log('security', 'Security status checked', Logger::INFO);
        return $status;
    }

    /**
     * 獲取總體安全狀態
     *
     * @return array
     */
    private function get_overall_status() {
        $threats = $this->get_active_threats();
        $critical_updates = $this->get_critical_updates();
        
        $status = 'secure';
        if ($threats > 0 || $critical_updates > 0) {
            $status = 'warning';
        }
        if ($threats > 5 || $critical_updates > 3) {
            $status = 'critical';
        }

        return array(
            'status' => $status,
            'threats' => $threats,
            'updates' => $critical_updates,
            'last_check' => current_time('mysql')
        );
    }

    /**
     * 獲取防火牆狀態
     *
     * @return array
     */
    private function get_firewall_status() {
        $blocked_ips = $this->get_blocked_ips();
        $rules = $this->get_firewall_rules();

        return array(
            'enabled' => get_option('vel_firewall_enabled', true),
            'blocked_ips' => count($blocked_ips),
            'rules' => count($rules),
            'last_attack' => $this->get_last_attack_time()
        );
    }

    /**
     * 獲取入侵檢測狀態
     *
     * @return array
     */
    private function get_intrusion_attempts() {
        global $wpdb;
        $table = $wpdb->prefix . 'vel_security_log';

        $last_24h = date('Y-m-d H:i:s', strtotime('-24 hours'));
        
        $attempts = $wpdb->get_results($wpdb->prepare(
            "SELECT attack_type, COUNT(*) as count
            FROM $table
            WHERE created_at > %s
            AND severity = 'high'
            GROUP BY attack_type",
            $last_24h
        ));

        return array(
            'total_24h' => array_sum(array_column($attempts, 'count')),
            'by_type' => $attempts,
            'blocked' => $this->ids->get_blocked_count()
        );
    }

    /**
     * 獲取惡意軟件狀態
     *
     * @return array
     */
    private function get_malware_status() {
        $scan_results = get_option('vel_last_malware_scan', array());
        
        return array(
            'last_scan' => $scan_results['time'] ?? null,
            'infected_files' => $scan_results['infected'] ?? 0,
            'quarantined' => $scan_results['quarantined'] ?? 0,
            'clean_files' => $scan_results['clean'] ?? 0
        );
    }

    /**
     * 獲取更新狀態
     *
     * @return array
     */
    private function get_updates_status() {
        $update_status = array(
            'wordpress' => $this->check_wordpress_updates(),
            'plugins' => $this->check_plugin_updates(),
            'themes' => $this->check_theme_updates()
        );

        return array(
            'needs_update' => array_sum($update_status),
            'details' => $update_status
        );
    }

    /**
     * 獲取權限狀態
     *
     * @return array
     */
    private function get_permissions_status() {
        $critical_files = array(
            ABSPATH . 'wp-config.php',
            ABSPATH . '.htaccess',
            WP_CONTENT_DIR . '/uploads',
            WP_CONTENT_DIR . '/plugins',
            WP_CONTENT_DIR . '/themes'
        );

        $permissions = array();
        foreach ($critical_files as $file) {
            $permissions[$file] = $this->check_file_permissions($file);
        }

        return array(
            'secure' => !in_array(false, array_column($permissions, 'secure')),
            'files' => $permissions
        );
    }

    /**
     * 獲取 SSL 狀態
     *
     * @return array
     */
    private function get_ssl_status() {
        $ssl_info = array();
        
        if (is_ssl()) {
            $host = $_SERVER['HTTP_HOST'];
            $cert_info = openssl_x509_parse(
                openssl_x509_read(
                    file_get_contents("ssl://{$host}:443")
                )
            );

            $ssl_info = array(
                'enabled' => true,
                'issuer' => $cert_info['issuer']['O'] ?? '',
                'expires' => date('Y-m-d H:i:s', $cert_info['validTo_time_t']),
                'days_remaining' => ceil(($cert_info['validTo_time_t'] - time()) / 86400)
            );
        } else {
            $ssl_info = array(
                'enabled' => false,
                'message' => 'SSL is not enabled'
            );
        }

        return $ssl_info;
    }

    /**
     * 獲取最後掃描信息
     *
     * @return array
     */
    private function get_last_scan_info() {
        $last_scan = get_option('vel_last_security_scan', array());
        
        return array(
            'time' => $last_scan['time'] ?? null,
            'duration' => $last_scan['duration'] ?? 0,
            'issues_found' => $last_scan['issues'] ?? 0,
            'issues_fixed' => $last_scan['fixed'] ?? 0
        );
    }

    /**
     * 檢查文件權限
     *
     * @param string $file
     * @return array
     */
    private function check_file_permissions($file) {
        if (!file_exists($file)) {
            return array(
                'exists' => false,
                'secure' => false,
                'message' => 'File does not exist'
            );
        }

        $perms = fileperms($file);
        $mode = decoct($perms & 0777);

        $is_secure = false;
        if (is_dir($file)) {
            $is_secure = $mode === '755';
        } else {
            $is_secure = $mode === '644';
        }

        return array(
            'exists' => true,
            'secure' => $is_secure,
            'mode' => $mode,
            'recommended' => is_dir($file) ? '755' : '644'
        );
    }

    /**
     * 獲取活躍威脅數量
     *
     * @return int
     */
    private function get_active_threats() {
        global $wpdb;
        $table = $wpdb->prefix . 'vel_security_log';

        return (int) $wpdb->get_var(
            "SELECT COUNT(*)
            FROM $table
            WHERE status = 'active'
            AND severity IN ('high', 'critical')"
        );
    }

    /**
     * 獲取關鍵更新數量
     *
     * @return int
     */
    private function get_critical_updates() {
        return array_sum(array(
            $this->check_wordpress_updates(),
            $this->check_plugin_updates(),
            $this->check_theme_updates()
        ));
    }

    /**
     * 檢查 WordPress 更新
     *
     * @return int
     */
    private function check_wordpress_updates() {
        global $wp_version;
        
        if (!function_exists('get_core_updates')) {
            require_once(ABSPATH . 'wp-admin/includes/update.php');
        }

        $core_updates = get_core_updates();
        if (empty($core_updates) || $core_updates[0]->response === 'latest') {
            return 0;
        }

        return 1;
    }

    /**
     * 檢查插件更新
     *
     * @return int
     */
    private function check_plugin_updates() {
        if (!function_exists('get_plugin_updates')) {
            require_once(ABSPATH . 'wp-admin/includes/update.php');
        }

        $plugin_updates = get_plugin_updates();
        return count($plugin_updates);
    }

    /**
     * 檢查主題更新
     *
     * @return int
     */
    private function check_theme_updates() {
        if (!function_exists('get_theme_updates')) {
            require_once(ABSPATH . 'wp-admin/includes/update.php');
        }

        $theme_updates = get_theme_updates();
        return count($theme_updates);
    }

    /**
     * 獲取被封禁的 IP
     *
     * @return array
     */
    private function get_blocked_ips() {
        return get_option('vel_blocked_ips', array());
    }

    /**
     * 獲取防火牆規則
     *
     * @return array
     */
    private function get_firewall_rules() {
        return get_option('vel_firewall_rules', array());
    }

    /**
     * 獲取最後攻擊時間
     *
     * @return string|null
     */
    private function get_last_attack_time() {
        global $wpdb;
        $table = $wpdb->prefix . 'vel_security_log';

        return $wpdb->get_var(
            "SELECT created_at
            FROM $table
            WHERE severity IN ('high', 'critical')
            ORDER BY created_at DESC
            LIMIT 1"
        );
    }
}
