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
     * 子站點列表
     *
     * @var array
     */
    private $subsites = array();

    /**
     * 防禦網路狀態
     *
     * @var array
     */
    private $defense_network = array();
    
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
        $this->init_defense_network();
    }

    /**
     * 初始化防禦網路
     */
    private function init_defense_network() {
        $this->subsites = $this->get_registered_subsites();
        $this->setup_defense_channels();
    }

    /**
     * 處理攻擊事件
     *
     * @param array $attack_data
     * @return bool
     */
    public function handle_attack($attack_data) {
        // 記錄攻擊事件
        $this->logger->log('security', 'Attack detected', Logger::WARNING, $attack_data);

        // 評估威脅等級
        $threat_level = $this->assess_threat_level($attack_data);

        // 如果威脅等級高，廣播到所有子站點
        if ($threat_level >= 3) {
            $this->broadcast_defense_alert($attack_data);
        }

        // 執行防禦措施
        $defense_result = $this->execute_defense_measures($attack_data, $threat_level);

        // 根據威脅等級決定是否需要反制
        if ($threat_level >= 4 && $this->should_counter_attack($attack_data)) {
            $this->initiate_counter_measures($attack_data);
        }

        return $defense_result;
    }

/**
 * 獲取註冊的子站點列表
 *
 * @return array
 */
private function get_registered_subsites() {
    global $wpdb;
    $table = $wpdb->prefix . 'vel_subsites';
    
    $sites = $wpdb->get_results("SELECT * FROM {$table} WHERE status = 'active'", ARRAY_A);
    return $sites ?: array();
}

/**
 * 設置防禦通道
 */
private function setup_defense_channels() {
    foreach ($this->subsites as $subsite) {
        $this->defense_network[$subsite['id']] = array(
            'status' => 'connected',
            'last_sync' => current_time('mysql'),
            'channel' => $this->create_defense_channel($subsite)
        );
    }
}

/**
 * 創建防禦通道
 *
 * @param array $subsite
 * @return array
 */
private function create_defense_channel($subsite) {
    return array(
        'token' => $this->generate_channel_token($subsite),
        'endpoint' => $subsite['api_url'] . '/security/channel',
        'encryption_key' => $this->generate_encryption_key($subsite)
    );
}

/**
 * 生成安全令牌
 *
 * @param array $subsite
 * @return string
 */
private function generate_security_token($subsite) {
    return wp_hash($subsite['secret_key'] . time(), 'secure_auth');
}

/**
 * 判斷是否允許反制措施
 *
 * @return bool
 */
private function is_counter_measure_allowed() {
    return get_option('vel_counter_measures_enabled', true);
}

/**
 * 評估DDoS威脅等級
 *
 * @param array $attack_data
 * @return int
 */
private function assess_ddos_threat($attack_data) {
    $requests_per_second = $attack_data['requests_per_second'] ?? 0;
    $concurrent_connections = $attack_data['concurrent_connections'] ?? 0;
    
    if ($requests_per_second > 1000 || $concurrent_connections > 500) {
        return 5;
    } elseif ($requests_per_second > 500 || $concurrent_connections > 200) {
        return 4;
    } elseif ($requests_per_second > 200 || $concurrent_connections > 100) {
        return 3;
    }
    
    return 2;
}

/**
 * 評估爬蟲威脅等級
 *
 * @param array $attack_data
 * @return int
 */
private function assess_crawler_threat($attack_data) {
    $request_frequency = $attack_data['request_frequency'] ?? 0;
    $pattern_match = $attack_data['pattern_match'] ?? false;
    
    if ($request_frequency > 100 && $pattern_match) {
        return 4;
    } elseif ($request_frequency > 50) {
        return 3;
    }
    
    return 2;
}

/**
 * 判斷是否需要反制
 *
 * @param array $attack_data
 * @return bool
 */
private function should_counter_attack($attack_data) {
    // 檢查白名單
    if ($this->is_whitelisted($attack_data['source_ip'])) {
        return false;
    }
    
    // 檢查攻擊持續時間
    $duration = $attack_data['duration'] ?? 0;
    if ($duration < 300) { // 5分鐘
        return false;
    }
    
    return true;
}
    
    /**
     * 評估威脅等級
     *
     * @param array $attack_data
     * @return int
     */
    private function assess_threat_level($attack_data) {
        $level = 1;

        // 根據攻擊類型評估
        switch ($attack_data['type']) {
            case 'ddos':
                $level = $this->assess_ddos_threat($attack_data);
                break;
            case 'injection':
                $level = $this->assess_injection_threat($attack_data);
                break;
            case 'crawler':
                $level = $this->assess_crawler_threat($attack_data);
                break;
            default:
                $level = $this->assess_general_threat($attack_data);
        }

        return $level;
    }

    /**
     * 執行防禦措施
     *
     * @param array $attack_data
     * @param int $threat_level
     * @return bool
     */
    private function execute_defense_measures($attack_data, $threat_level) {
        $measures = array();

        // 根據威脅等級採取不同防禦措施
        switch ($threat_level) {
            case 5: // 最高級別
                $measures = array(
                    'block_ip' => true,
                    'rate_limit' => true,
                    'challenge' => true,
                    'notify_admin' => true
                );
                break;
            case 4:
                $measures = array(
                    'rate_limit' => true,
                    'challenge' => true
                );
                break;
            case 3:
                $measures = array(
                    'rate_limit' => true
                );
                break;
            default:
                $measures = array(
                    'monitor' => true
                );
        }

        return $this->apply_defense_measures($attack_data['source_ip'], $measures);
    }

    /**
     * 執行反制措施
     *
     * @param array $attack_data
     * @return bool
     */
    private function initiate_counter_measures($attack_data) {
        // 檢查是否允許反制
        if (!$this->is_counter_measure_allowed()) {
            return false;
        }

        $counter_measures = array(
            'delay_response' => true,    // 延遲響應
            'resource_consumption' => true, // 資源消耗
            'confusion_data' => true      // 混淆數據
        );

        return $this->apply_counter_measures($attack_data['source_ip'], $counter_measures);
    }

    /**
     * 應用防禦措施
     *
     * @param string $ip
     * @param array $measures
     * @return bool
     */
    private function apply_defense_measures($ip, $measures) {
        foreach ($measures as $measure => $enabled) {
            if (!$enabled) continue;

            switch ($measure) {
                case 'block_ip':
                    $this->block_ip($ip);
                    break;
                case 'rate_limit':
                    $this->apply_rate_limiting($ip);
                    break;
                case 'challenge':
                    $this->issue_challenge($ip);
                    break;
                case 'notify_admin':
                    $this->notify_admin($ip);
                    break;
            }
        }

        return true;
    }

    /**
     * 應用反制措施
     *
     * @param string $ip
     * @param array $measures
     * @return bool
     */
    private function apply_counter_measures($ip, $measures) {
        foreach ($measures as $measure => $enabled) {
            if (!$enabled) continue;

            switch ($measure) {
                case 'delay_response':
                    $this->apply_response_delay($ip);
                    break;
                case 'resource_consumption':
                    $this->increase_resource_usage($ip);
                    break;
                case 'confusion_data':
                    $this->send_confusion_data($ip);
                    break;
            }
        }

        return true;
    }

    /**
     * 廣播防禦警報
     *
     * @param array $attack_data
     * @return bool
     */
    private function broadcast_defense_alert($attack_data) {
        foreach ($this->subsites as $subsite) {
            $this->send_defense_alert($subsite, $attack_data);
        }
        return true;
    }

    /**
     * 應用響應延遲
     *
     * @param string $ip
     */
    private function apply_response_delay($ip) {
        sleep(rand(1, 5));
     }

    /**
     * 增加資源使用
     *
     * @param string $ip
     */
private function increase_resource_usage($ip) {
    // 實現CPU密集運算
    for ($i = 0; $i < 1000000; $i++) {
        hash('sha256', $ip . $i);
    }
}

/**
 * 發送混淆數據
 *
 * @param string $ip
 */
private function send_confusion_data($ip) {
    // 生成隨機混淆數據
    $data = random_bytes(1024 * 1024); // 1MB
    echo base64_encode($data);
}
    
    /**
     * 發送防禦警報
     *
     * @param array $subsite
     * @param array $attack_data
     * @return bool
     */
private function send_defense_alert($subsite, $attack_data) {
    // 添加加密處理
    $encrypted_data = $this->encrypt_alert_data($attack_data, $subsite);
    
    $response = wp_remote_post($subsite['api_url'] . '/security/alert', array(
        'body' => $encrypted_data,
        'headers' => array(
            'Content-Type' => 'application/json',
            'X-VEL-Security-Token' => $this->generate_security_token($subsite),
            'X-VEL-Timestamp' => time(),
            'X-VEL-Signature' => $this->generate_request_signature($encrypted_data, $subsite)
        ),
        'timeout' => 15
    ));

        if (is_wp_error($response)) {
            $this->logger->log('security', 'Failed to send alert', Logger::ERROR, array(
                'subsite' => $subsite['name'],
                'error' => $response->get_error_message()
            ));
            return false;
        }

        return true;
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
