<?php
/**
 * 自動載入器
 *
 * @package     VEL_Enterprise_System
 * @author      VEL New Life World
 * @copyright   2025 VEL New Life World
 */

if (!defined('ABSPATH')) {
    exit;
}

namespace VEL\Includes;

class VEL_Autoloader {
    private static $instance = null;

    public static function init() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->register();
    }

    /**
     * 註冊自動載入
     */
    public function register() {
        spl_autoload_register([$this, 'autoload']);
    }

    /**
     * 自動載入函式
     *
     * @param string $class 類別名稱
     */
    private function autoload($class) {
        // 檢查是否屬於我們的命名空間
        $prefix = 'VEL\\';
        if (strpos($class, $prefix) !== 0) {
            return;
        }

        // 移除命名空間前綴
        $relative_class = substr($class, strlen($prefix));
        $relative_class = strtolower(str_replace('_', '-', $relative_class));
        
        // 建構檔案路徑
        $file = VEL_PLUGIN_DIR . str_replace('\\', '/', $relative_class) . '.php';
        
        if (file_exists($file)) {
            require_once $file;
        }
    }
}