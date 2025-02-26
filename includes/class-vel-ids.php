<?php
/**
 * Intrusion Detection System Class
 *
 * @package     VEL
 * @subpackage  VEL/includes
 * @author      Nuclear3314
 * @copyright   2025 Nuclear3314
 * @license     GPL v2 or later
 * @version     1.0.0
 * @created     2025-02-26 12:26:22
 */

if (!defined('ABSPATH')) {
    exit('Direct access not allowed.');
}

class VEL_IDS {
    /**
     * Logger 實例
     *
     * @var VEL_Logger
     */
    private $logger;

    /**
     * 規則集
     *
     * @var array
     */
    private $rules;

    /**
     * 構造函數
     */
    public function __construct() {
        $this->logger = new VEL_Logger();
        $this->rules = $this->load_rules();
    }

    /**
     * 檢測入侵
     *
     * @param array $request
     * @return bool
     */
    public function detect_intrusion($request) {
        $threats = array();

        // 檢查請求方法
        if (!in_array($request['REQUEST_METHOD'], array('GET', 'POST', 'PUT', 'DELETE'))) {
            $threats[] = array(
                'type' => 'invalid_method',
                'severity' => 'medium',
                'details' => $request['REQUEST_METHOD']
            );
        }

        // 檢查 SQL 注入
        $sql_threats = $this->detect_sql_injection($request);
        if (!empty($sql_threats)) {
            $threats = array_merge($threats, $sql_threats);
        }

        // 檢查 XSS
        $xss_threats = $this->detect_xss($request);
        if (!empty($xss_threats)) {
            $threats = array_merge($threats, $xss_threats);
        }

        // 檢查文件包含漏洞
        $file_threats = $this->detect_file_inclusion($request);
        if (!empty($file_threats)) {
            $threats = array_merge($threats, $file_threats);
        }

        // 記錄威脅
        if (!empty($threats)) {
            foreach ($threats as $threat) {
                $this->log_threat($threat, $request);
            }
            
            // 如果發現高危威脅，則阻止請求
            if ($this->has_high_severity_threat($threats)) {
                $this->block_request($request['REMOTE_ADDR']);
                return true;
            }
        }

        return false;
    }

    /**
     * 檢測 SQL 注入
     *
     * @param array $request
     * @return array
     */
    private function detect_sql_injection($request) {
        $threats = array();
        $patterns = $this->rules['sql_injection'];

        foreach ($request as $key => $value) {
            if (is_string($value)) {
                foreach ($patterns as $pattern) {
                    if (preg_match($pattern, $value)) {
                        $threats[] = array(
                            'type' => 'sql_injection',
                            'severity' => 'high',
                            'pattern' => $pattern,
                            'value' => $value
                        );
                    }
                }
            }
        }

        return $threats;
    }

    /**
     * 檢測 XSS
     *
     * @param array $request
     * @return array
     */
    private function detect_xss($request) {
        $threats = array();
        $patterns = $this->rules['xss'];

        foreach ($request as $key => $value) {
            if (is_string($value)) {
                foreach ($patterns as $pattern) {
                    if (preg_match($pattern, $value)) {
                        $threats[] = array(
                            'type' => 'xss',
                            'severity' => 'high',
                            'pattern' => $pattern,
                            'value' => $value
                        );
                    }
                }
            }
        }

        return $threats;
    }

    /**
     * 檢測文件包含漏洞
     *
     * @param array $request
     * @return array
     */
    private function detect_file_inclusion($request) {
        $threats = array();
        $patterns = $this->rules['file_inclusion'];

        foreach ($request as $key => $value) {
            if (is_string($value)) {
                foreach ($patterns as $pattern) {
                    if (preg_match($pattern, $value)) {
                        $threats[] = array(
                            'type' => 'file_inclusion',
                            'severity' => 'critical',
                            'pattern' => $pattern,
                            'value' => $value
                        );
                    }
                }
            }
        }

        return $threats;
    }

    /**
     * 記錄威脅
     *
     * @param array $threat
     * @param array $request
     */
    private function log_threat($threat, $request) {
        global $wpdb;
        $table = $wpdb->prefix . 'vel_security_log';

        $data = array(
            'type' => $threat['type'],
            'severity' => $threat['severity'],
            'ip_address' => $request['REMOTE_ADDR'],
            'user_agent' => $request['HTTP_USER_AGENT'],
            'request_uri' => $request['REQUEST_URI'],
            'request_method' => $request['REQUEST_METHOD'],
            'details' => json_encode(array(
                'threat' => $threat,
                'request' => $this->sanitize_request($request)
            )),
            'created_at' => current_time('mysql')
        );

        $wpdb->insert($table, $data);

        $this->logger->log(
            'security',
            sprintf('Threat detected: %s (Severity: %s)', $threat['type'], $threat['severity']),
            Logger::WARNING,
            $data
        );
    }

    /**
     * 阻止請求
     *
     * @param string $ip
     */
    private function block_request($ip) {
        $blocked_ips = get_option('vel_blocked_ips', array());
        
        if (!in_array($ip, $blocked_ips)) {
            $blocked_ips[] = $ip;
            update_option('vel_blocked_ips', $blocked_ips);
        }

        $this->logger->log(
            'security',
            sprintf('IP blocked: %s', $ip),
            Logger::WARNING
        );

        wp_die(
            __('Access denied. This incident has been logged.', 'vel-enterprise-system'),
            __('Security Alert', 'vel-enterprise-system'),
            array('response' => 403)
        );
    }

    /**
     * 檢查是否有高危威脅
     *
     * @param array $threats
     * @return bool
     */
    private function has_high_severity_threat($threats) {
        foreach ($threats as $threat) {
            if (in_array($threat['severity'], array('high', 'critical'))) {
                return true;
            }
        }
        return false;
    }

    /**
     * 加載規則集
     *
     * @return array
     */
    private function load_rules() {
        return array(
            'sql_injection' => array(
                '/(\W)*(SELECT|INSERT|UPDATE|DELETE|DROP|UNION|INTO|LOAD_FILE)(\W)+/i',
                '/(\W)*(CONCAT|CHAR|SUBSTRING|ASCII|BIN|HEX|UNHEX|BASE64)(\W)+/i',
                '/(\W)*(BENCHMARK|SLEEP|WAITFOR|DELAY)(\W)+/i'
            ),
            'xss' => array(
                '/<script[^>]*>.*?<\/script>/is',
                '/<[^>]*javascript:[^>]*>/is',
                '/<[^>]*onload=[^>]*>/is',
                '/<[^>]*onerror=[^>]*>/is'
            ),
            'file_inclusion' => array(
                '/(\W)*(include|require|include_once|require_once)(\W)+/i',
                '/\.\.(\/|\\\)/',
                '/(\W)*(file|fopen|fread|fwrite|fgets)(\W)+/i'
            )
        );
    }

    /**
     * 清理請求數據
     *
     * @param array $request
     * @return array
     */
    private function sanitize_request($request) {
        $sensitive_keys = array(
            'pwd', 'pass', 'password',
            'auth', 'key', 'secret',
            'token', 'api_key'
        );

        $clean_request = array();
        foreach ($request as $key => $value) {
            if (is_string($value)) {
                foreach ($sensitive_keys as $sensitive) {
                    if (stripos($key, $sensitive) !== false) {
                        $value = '******';
                        break;
                    }
                }
            }
            $clean_request[$key] = $value;
        }

        return $clean_request;
    }

    /**
     * 獲取被阻止的請求數量
     *
     * @return int
     */
    public function get_blocked_count() {
        global $wpdb;
        $table = $wpdb->prefix . 'vel_security_log';

        return (int) $wpdb->get_var(
            "SELECT COUNT(DISTINCT ip_address)
            FROM $table
            WHERE severity IN ('high', 'critical')"
        );
    }

    /**
     * 清理過期的日誌
     */
    public function cleanup_logs() {
        global $wpdb;
        $table = $wpdb->prefix . 'vel_security_log';
        
        // 刪除30天前的日誌
        $wpdb->query($wpdb->prepare(
            "DELETE FROM $table WHERE created_at < %s",
            date('Y-m-d H:i:s', strtotime('-30 days'))
        ));
    }
}
