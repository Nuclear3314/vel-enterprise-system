<?php
/**
 * API 基礎類
 *
 * @package VEL_Enterprise_System
 * @subpackage API
 * @since 1.0.0
 * @version 1.0.0
 * @author Nuclear3314
 * @copyright 2025 Nuclear3314
 */

namespace VEL\API;

use VEL\Core\Logger;
use WP_Error;
use WP_REST_Response;

if (!defined('ABSPATH')) {
    exit;
}

class API {
    /**
     * API 版本
     *
     * @var string
     */
    protected $version = '1.0.0';

    /**
     * API 命名空間
     *
     * @var string
     */
    protected $namespace = 'vel/v1';

    /**
     * 請求限制配置
     *
     * @var array
     */
    protected $rate_limit = array(
        'window' => 3600, // 1小時
        'max_requests' => 1000
    );

    /**
     * 構造函數
     */
    public function __construct() {
        $this->init();
    }

    /**
     * 初始化 API
     */
    protected function init() {
        add_action('rest_api_init', array($this, 'register_routes'));
        add_filter('rest_pre_dispatch', array($this, 'check_rate_limit'), 10, 3);
    }

    /**
     * 驗證請求
     *
     * @param \WP_REST_Request $request
     * @return bool|WP_Error
     */
    protected function validate_request($request) {
        // 檢查 API 密鑰
        $api_key = $request->get_header('X-VEL-API-Key');
        if (!$this->validate_api_key($api_key)) {
            return new WP_Error(
                'invalid_api_key',
                __('Invalid API key', 'vel-enterprise-system'),
                array('status' => 401)
            );
        }

        // 檢查簽名
        $signature = $request->get_header('X-VEL-Signature');
        if (!$this->verify_signature($request, $signature)) {
            return new WP_Error(
                'invalid_signature',
                __('Invalid request signature', 'vel-enterprise-system'),
                array('status' => 401)
            );
        }

        return true;
    }

    /**
     * 檢查請求限制
     *
     * @param mixed           $response
     * @param \WP_REST_Server $server
     * @param \WP_REST_Request $request
     * @return mixed
     */
    public function check_rate_limit($response, $server, $request) {
        if (strpos($request->get_route(), $this->namespace) !== false) {
            $ip = $this->get_client_ip();
            $api_key = $request->get_header('X-VEL-API-Key');
            
            $cache_key = "vel_rate_limit_{$api_key}_{$ip}";
            $requests = get_transient($cache_key);

            if ($requests === false) {
                $requests = array(
                    'count' => 1,
                    'timestamp' => time()
                );
            } else {
                // 檢查是否在時間窗口內
                if (time() - $requests['timestamp'] > $this->rate_limit['window']) {
                    $requests = array(
                        'count' => 1,
                        'timestamp' => time()
                    );
                } else if ($requests['count'] >= $this->rate_limit['max_requests']) {
                    return new WP_Error(
                        'rate_limit_exceeded',
                        __('API rate limit exceeded', 'vel-enterprise-system'),
                        array('status' => 429)
                    );
                } else {
                    $requests['count']++;
                }
            }

            set_transient($cache_key, $requests, $this->rate_limit['window']);
        }

        return $response;
    }

    /**
     * 驗證 API 密鑰
     *
     * @param string $api_key
     * @return bool
     */
    protected function validate_api_key($api_key) {
        if (empty($api_key)) {
            return false;
        }

        $valid_keys = get_option('vel_api_keys', array());
        return isset($valid_keys[$api_key]) && $valid_keys[$api_key]['active'];
    }

    /**
     * 驗證請求簽名
     *
     * @param \WP_REST_Request $request
     * @param string           $signature
     * @return bool
     */
    protected function verify_signature($request, $signature) {
        if (empty($signature)) {
            return false;
        }

        $api_key = $request->get_header('X-VEL-API-Key');
        $timestamp = $request->get_header('X-VEL-Timestamp');
        $body = $request->get_body();

        $data = $api_key . $timestamp . $body;
        $expected_signature = hash_hmac('sha256', $data, $this->get_secret_key());

        return hash_equals($expected_signature, $signature);
    }

    /**
     * 獲取客戶端 IP
     *
     * @return string
     */
    protected function get_client_ip() {
        $ip = '';
        
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return sanitize_text_field($ip);
    }

    /**
     * 獲取密鑰
     *
     * @return string
     */
    protected function get_secret_key() {
        return get_option('vel_api_secret_key', '');
    }

    /**
     * 記錄 API 請求
     *
     * @param \WP_REST_Request $request
     * @param mixed            $response
     */
    protected function log_request($request, $response) {
        $log_data = array(
            'method' => $request->get_method(),
            'route' => $request->get_route(),
            'params' => $request->get_params(),
            'headers' => $request->get_headers(),
            'ip' => $this->get_client_ip(),
            'api_key' => $request->get_header('X-VEL-API-Key'),
            'timestamp' => current_time('mysql'),
            'response_code' => $response->get_status(),
            'response_data' => $response->get_data()
        );

        Logger::log('api', 'API Request', Logger::INFO, $log_data);
    }

    /**
     * 格式化響應
     *
     * @param mixed $data
     * @param int   $status
     * @return WP_REST_Response
     */
    protected function format_response($data, $status = 200) {
        $response = new WP_REST_Response($data, $status);
        $response->set_headers(array(
            'X-VEL-Version' => $this->version,
            'X-VEL-Rate-Limit-Remaining' => $this->get_remaining_requests()
        ));

        return $response;
    }

    /**
     * 獲取剩餘請求次數
     *
     * @return int
     */
    protected function get_remaining_requests() {
        $ip = $this->get_client_ip();
        $api_key = $_SERVER['HTTP_X_VEL_API_KEY'] ?? '';
        $cache_key = "vel_rate_limit_{$api_key}_{$ip}";
        $requests = get_transient($cache_key);

        if ($requests === false) {
            return $this->rate_limit['max_requests'];
        }

        return max(0, $this->rate_limit['max_requests'] - $requests['count']);
    }

    /**
     * 驗證必要參數
     *
     * @param array $required
     * @param array $provided
     * @return bool|WP_Error
     */
    protected function validate_required_params($required, $provided) {
        $missing = array();

        foreach ($required as $param) {
            if (!isset($provided[$param])) {
                $missing[] = $param;
            }
        }

        if (!empty($missing)) {
            return new WP_Error(
                'missing_params',
                sprintf(
                    __('Missing required parameters: %s', 'vel-enterprise-system'),
                    implode(', ', $missing)
                ),
                array('status' => 400)
            );
        }

        return true;
    }
}