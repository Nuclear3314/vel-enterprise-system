<?php
/**
 * 外掛相容性檢查類別
 */

namespace VEL\Includes;

if (!defined('ABSPATH')) {
    exit;
}

class VEL_Compatibility {
    /**
     * 執行相容性檢查
     */
    public function check_compatibility() {
        return [
            'php_version' => $this->check_php_version(),
            'wp_version' => $this->check_wp_version(),
            'required_extensions' => $this->check_required_extensions(),
            'file_permissions' => $this->check_file_permissions()
        ];
    }

    /**
     * 檢查 PHP 版本
     */
    private function check_php_version() {
        return version_compare(PHP_VERSION, '8.2', '>=');
    }

    /**
     * 檢查 WordPress 版本
     */
    private function check_wp_version() {
        global $wp_version;
        return version_compare($wp_version, '5.8', '>=');
    }

    /**
     * 檢查必要的 PHP 擴充
     */
    private function check_required_extensions() {
        $required = ['mysqli', 'curl', 'json', 'zip'];
        $missing = [];

        foreach ($required as $ext) {
            if (!extension_loaded($ext)) {
                $missing[] = $ext;
            }
        }

        return empty($missing) ? true : $missing;
    }

    /**
     * 檢查檔案權限
     */
    private function check_file_permissions() {
        $upload_dir = wp_upload_dir();
        return [
            'upload_dir_writable' => is_writable($upload_dir['basedir']),
            'plugin_dir_writable' => is_writable(VEL_PLUGIN_DIR)
        ];
    }
}