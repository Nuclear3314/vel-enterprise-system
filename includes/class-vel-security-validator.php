<?php
namespace VEL\Includes;

if (!defined('ABSPATH')) {
    exit;
}

class VEL_Security_Validator {
    /**
     * 驗證請求
     */
    public static function validate_request($nonce_key = '', $action = -1) {
        if (empty($nonce_key)) {
            return false;
        }

        $nonce = $_REQUEST[$nonce_key] ?? '';
        if (!wp_verify_nonce($nonce, $action)) {
            wp_die(__('安全檢查失敗', 'vel-enterprise-system'));
        }

        return true;
    }

    /**
     * 清理輸入資料
     */
    public static function sanitize_data($data, $type = 'text') {
        switch ($type) {
            case 'email':
                return sanitize_email($data);
            case 'url':
                return esc_url_raw($data);
            case 'html':
                return wp_kses_post($data);
            case 'int':
                return intval($data);
            default:
                return sanitize_text_field($data);
        }
    }

    /**
     * 驗證權限
     */
    public static function check_capability($capability = 'manage_options') {
        if (!current_user_can($capability)) {
            wp_die(__('權限不足', 'vel-enterprise-system'));
        }
        return true;
    }
}