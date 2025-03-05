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

 namespace VEL\Includes;

if (!defined('ABSPATH')) {
    exit('Direct access not allowed.');
}

class VEL_Security {
     /**
     * 系統版本
     */
    const VERSION = '1.0.0';

    /**
     * 威脅等級常量
     */
    const THREAT_LEVELS = [
        'LOW' => 1,
        'MEDIUM' => 2,
        'HIGH' => 3,
        'CRITICAL' => 4,
        'EMERGENCY' => 5
    ];

    /**
     * 攻擊類型常量
     */
    const ATTACK_TYPES = [
        'DDOS' => 'ddos',
        'INJECTION' => 'injection',
        'CRAWLER' => 'crawler',
        'XSS' => 'xss',
        'BRUTE_FORCE' => 'brute_force'
    ];

    // 添加日誌級別常量
    const LOG_LEVELS = [
        'DEBUG' => 0,
        'INFO' => 1,
        'WARNING' => 2,
        'ERROR' => 3,
        'CRITICAL' => 4
    ];

    // 添加防護模式常量
    const PROTECTION_MODES = [
        'PASSIVE' => 'passive',
        'ACTIVE' => 'active',
        'AGGRESSIVE' => 'aggressive'
    ];
 
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
     * 防禦系統等級常量
     */
    const DEFENSE_LEVELS = [
        'LEVEL_1' => 'MICHAEL',    // 基礎防禦
        'LEVEL_2' => 'GABRIEL',    // 預警系統
        'LEVEL_3' => 'RAPHAEL',    // 系統恢復
        'LEVEL_4' => 'URIEL',      // 智能分析
        'LEVEL_5' => 'ABADDON',    // 蜜罐誘捕
        'LEVEL_6' => 'SAMAEL',     // 全球封鎖
        'LEVEL_7' => 'AVENGER'     // 終極反制
    ];

    /**
     * 初始化防禦網路
     */
    private function init_defense_network() {
        $this->subsites = $this->get_registered_subsites();
        $this->setup_defense_channels();
    }

    private function get_blocked_ips(): array {
        return get_option('vel_blocked_ips', array());
    }
    
    private function assess_threat_level(array $attack_data): int {
        // ...existing code...
    }
    
    private function handle_attack(array $attack_data): bool {
        // ...existing code...
    }
    
    private function block_ip(string $ip): void {
        // ...existing code...
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

    private function handle_level_seven_threat($attack_data) {
        $this->logger->log('security', 'Level 7 threat detected!', Logger::CRITICAL, $attack_data);
        
        // 啟動終極防禦協議
        $this->activate_avenger_protocol($attack_data);
        
        // 發送緊急通知
        $this->notify_admin_emergency($attack_data);
        
        // 廣播到所有子站點
        $this->broadcast_level_seven_alert($attack_data);
        
        return $this->coordinate_joint_counter_attack($attack_data);
    }
    
    private function get_active_connections() {
        // 實現獲取活躍連接數的邏輯
        return array(
            'total' => $this->count_active_sessions(),
            'suspicious' => $this->count_suspicious_connections()
        );
    }
    
    private function handle_error($error, $context = array()) {
        $this->logger->log('security', $error, Logger::ERROR, $context);
        
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log(sprintf('[VEL Security] %s: %s', $error, json_encode($context)));
        }
    }

    private function measure_response_time() {
        $start = microtime(true);
        // 執行測試請求
        $end = microtime(true);
        return $end - $start;
    }

    private function assess_injection_threat($attack_data) {
        $patterns_matched = $attack_data['patterns_matched'] ?? 0;
        $severity = $attack_data['severity'] ?? 'low';
        
        if ($patterns_matched > 5 || $severity === 'critical') {
            return 5;
        } elseif ($patterns_matched > 3 || $severity === 'high') {
            return 4;
        }
        
        return 3;
    }
    
    private function block_ip($ip) {
        $blocked_ips = get_option('vel_blocked_ips', array());
        if (!in_array($ip, $blocked_ips)) {
            $blocked_ips[] = $ip;
            update_option('vel_blocked_ips', $blocked_ips);
            
            $this->logger->log('security', "IP {$ip} has been blocked", Logger::WARNING);
        }
    }
    
    private function apply_rate_limiting($ip) {
        $rate_limits = get_transient('vel_rate_limits') ?: array();
        $rate_limits[$ip] = ($rate_limits[$ip] ?? 0) + 1;
        set_transient('vel_rate_limits', $rate_limits, 3600);
    }
    
    private function issue_challenge($ip) {
        // 實現驗證碼或其他挑戰機制
        $challenge = wp_create_nonce('vel_security_challenge');
        set_transient("vel_challenge_{$ip}", $challenge, 300);
        return $challenge;
    }

    private function emergency_contact_list() {
        return [
            ['email' => 'gns19450616@gmail.com', 'priority' => 1],
            // 可以添加更多緊急聯絡人
        ];
    }
    
    private function report_security_incident($incident_data) {
        $report = [
            'timestamp' => current_time('mysql'),
            'incident_type' => $incident_data['type'],
            'severity' => $incident_data['severity'],
            'details' => $incident_data['details'],
            'action_taken' => $incident_data['action'],
            'success_rate' => $incident_data['success_rate']
        ];
        
        // 記錄到資料庫
        $this->log_security_incident($report);
        
        // 如果是高風險事件，立即通知管理員
        if ($incident_data['severity'] >= 4) {
            $this->notify_admin_emergency($report);
        }
        
        return $report;
    }

    private function sync_defense_status() {
        $status = [
            'site_id' => get_current_blog_id(),
            'defense_level' => $this->current_defense_level,
            'active_threats' => $this->get_active_threats(),
            'blocked_ips' => $this->get_blocked_ips(),
            'system_status' => $this->get_system_status(),
            'last_sync' => current_time('mysql')
        ];
        
        // 同步到主站點
        $this->sync_to_main_site($status);
        
        // 更新本地狀態
        update_option('vel_defense_status', $status);
        
        return $status;
    }

    private function auto_recovery_protocol($system_failure) {
        $recovery_steps = [
            'backup_check' => $this->verify_backup_integrity(),
            'system_restore' => $this->restore_critical_systems(),
            'permissions_reset' => $this->reset_security_permissions(),
            'cache_clear' => $this->clear_security_cache(),
            'defense_reinit' => $this->reinitialize_defense_systems()
        ];
        
        foreach ($recovery_steps as $step => $result) {
            if (!$result) {
                $this->log_recovery_failure($step);
                return false;
            }
        }
        
        return true;
    }

    private function monitor_system_performance() {
        $metrics = [
            'memory_usage' => memory_get_usage(true),
            'cpu_load' => sys_getloadavg(),
            'disk_usage' => disk_free_space('/'),
            'active_connections' => $this->get_active_connections(),
            'response_time' => $this->measure_response_time()
        ];
        
        if ($this->detect_performance_issues($metrics)) {
            $this->trigger_performance_alert($metrics);
        }
        
        return $metrics;
    }

    private function broadcast_emergency_alert($attack_data) {
        foreach ($this->emergency_contact_list() as $contact) {
            $this->notify_emergency_contact($contact, $attack_data);
        }
    }

    private function encrypt_alert_data($data, $subsite) {
        $key = base64_decode($subsite['encryption_key']);
        $iv = random_bytes(16);
        
        $encrypted = openssl_encrypt(
            json_encode($data),
            'AES-256-CBC',
            $key,
            OPENSSL_RAW_DATA,
            $iv
        );
        
        return base64_encode($iv . $encrypted);
    }
    
    private function generate_request_signature($data, $subsite) {
        return hash_hmac(
            'sha256',
            $data,
            $subsite['secret_key']
        );
    }

    private function assess_general_threat($attack_data) {
        $frequency = $attack_data['frequency'] ?? 0;
        $impact = $attack_data['impact'] ?? 'low';
        
        if ($frequency > 100 || $impact === 'critical') {
            return 4;
        } elseif ($frequency > 50 || $impact === 'high') {
            return 3;
        }
        
        return 2;
    }
    
    private function is_whitelisted($ip) {
        $whitelisted_ips = get_option('vel_whitelisted_ips', array());
        return in_array($ip, $whitelisted_ips);
    }

    private function activate_avenger_protocol($attack_data) {
        // 部署高級蜜罐
        $this->deploy_advanced_honeypot();
        
        // 啟動全球封鎖
        $this->activate_global_blacklist($attack_data['source_ip']);
        
        // 開始反向追蹤
        $this->initiate_reverse_tracking($attack_data);
        
        // 準備反制資源
        $this->prepare_counter_resources();
    }
    
    private function coordinate_joint_counter_attack($attack_data) {
        // 發送到所有子站點
        foreach ($this->subsites as $subsite) {
            $this->send_counter_attack_signal($subsite, $attack_data);
        }
        
        // 開始協同反制
        return $this->execute_joint_counter_measures($attack_data);
    }

    private function notify_admin_emergency($attack_data) {
        $admin_email = 'gns19450616@gmail.com';
        $subject = '[緊急] Level 7 威脅警報';
        
        $message = sprintf(
            "檢測到 Level 7 威脅！\n" .
            "攻擊來源: %s\n" .
            "攻擊類型: %s\n" .
            "時間: %s\n" .
            "威脅等級: %d\n" .
            "已啟動 AVENGER 協議",
            $attack_data['source_ip'],
            $attack_data['type'],
            current_time('mysql'),
            $attack_data['threat_level']
        );
        
        wp_mail($admin_email, $subject, $message);
    }
    
    private function log_defense_action($action, $data) {
        global $wpdb;
        $table = $wpdb->prefix . 'vel_defense_log';
        
        return $wpdb->insert($table, array(
            'action_type' => $action,
            'details' => json_encode($data),
            'created_at' => current_time('mysql')
        ));
    }

    private function sync_defense_network($attack_data) {
        $sync_data = array(
            'attack_info' => $attack_data,
            'defense_level' => 'LEVEL_7',
            'protocol' => 'AVENGER',
            'timestamp' => time()
        );
        
        foreach ($this->defense_network as $node) {
            if ($node['status'] === 'connected') {
                $this->send_sync_command($node['channel'], $sync_data);
            }
        }
    }
    
    private function send_sync_command($channel, $data) {
        $encrypted_data = $this->encrypt_defense_data($data, $channel['encryption_key']);
        
        return wp_remote_post($channel['endpoint'], array(
            'body' => $encrypted_data,
            'headers' => array(
                'X-VEL-Defense-Token' => $channel['token'],
                'X-VEL-Timestamp' => time()
            ),
            'timeout' => 30
        ));
    }

    private function deploy_advanced_honeypot() {
        $honeypot_config = array(
            'emulation_level' => 'maximum',
            'response_delay' => 'random',
            'data_fabrication' => true,
            'resource_consumption' => 'adaptive',
            'tracking_enabled' => true
        );
        
        $this->honeypot->deploy($honeypot_config);
        $this->log_defense_action('honeypot_deployed', $honeypot_config);
        
        return true;
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
