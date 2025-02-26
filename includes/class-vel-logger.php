<?php
/**
 * Logger Class
 *
 * @package     VEL
 * @subpackage  VEL/includes
 * @author      Nuclear3314
 * @copyright   2025 Nuclear3314
 * @license     GPL v2 or later
 * @version     1.0.0
 * @created     2025-02-26 12:31:33
 */

if (!defined('ABSPATH')) {
    exit('Direct access not allowed.');
}

class VEL_Logger {
    /**
     * 日誌等級常量
     */
    const DEBUG = 'debug';
    const INFO = 'info';
    const WARNING = 'warning';
    const ERROR = 'error';
    const CRITICAL = 'critical';

    /**
     * 日誌目錄
     *
     * @var string
     */
    private $log_dir;

    /**
     * 日誌檔案映射
     *
     * @var array
     */
    private $log_files = array(
        'security' => 'security.log',
        'api' => 'api.log',
        'performance' => 'performance.log',
        'error' => 'error.log',
        'debug' => 'debug.log'
    );

    /**
     * 日誌等級映射
     *
     * @var array
     */
    private $level_colors = array(
        self::DEBUG => '0;37', // 灰色
        self::INFO => '0;32',  // 綠色
        self::WARNING => '1;33', // 黃色
        self::ERROR => '0;31',   // 紅色
        self::CRITICAL => '1;31' // 亮紅色
    );

    /**
     * 構造函數
     */
    public function __construct() {
        $this->log_dir = WP_CONTENT_DIR . '/vel-logs';
        $this->ensure_log_directory();
        $this->rotate_logs();
    }

    /**
     * 記錄日誌
     *
     * @param string $type 日誌類型
     * @param string $message 日誌信息
     * @param string $level 日誌等級
     * @param array $context 上下文數據
     * @return bool
     */
    public function log($type, $message, $level = self::INFO, $context = array()) {
        if (!$this->validate_type($type)) {
            return false;
        }

        $log_entry = $this->format_log_entry($message, $level, $context);
        $log_file = $this->get_log_file($type);

        if (!$log_file) {
            return false;
        }

        // 寫入日誌
        $success = file_put_contents(
            $log_file,
            $log_entry . PHP_EOL,
            FILE_APPEND | LOCK_EX
        );

        // 如果是錯誤或嚴重等級，同時寫入錯誤日誌
        if (in_array($level, array(self::ERROR, self::CRITICAL))) {
            $error_log = $this->get_log_file('error');
            file_put_contents(
                $error_log,
                $log_entry . PHP_EOL,
                FILE_APPEND | LOCK_EX
            );
        }

        // 如果是調試模式，同時寫入調試日誌
        if (defined('WP_DEBUG') && WP_DEBUG) {
            $debug_log = $this->get_log_file('debug');
            file_put_contents(
                $debug_log,
                $log_entry . PHP_EOL,
                FILE_APPEND | LOCK_EX
            );
        }

        return $success !== false;
    }

    /**
     * 格式化日誌條目
     *
     * @param string $message
     * @param string $level
     * @param array $context
     * @return string
     */
    private function format_log_entry($message, $level, $context) {
        $timestamp = current_time('Y-m-d H:i:s');
        $color_code = $this->level_colors[$level] ?? '0';
        
        $entry = sprintf(
            "[%s] [\033[%sm%s\033[0m] %s",
            $timestamp,
            $color_code,
            strtoupper($level),
            $message
        );

        if (!empty($context)) {
            $entry .= PHP_EOL . json_encode(
                $context,
                JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
            );
        }

        return $entry;
    }

    /**
     * 確保日誌目錄存在
     */
    private function ensure_log_directory() {
        if (!file_exists($this->log_dir)) {
            wp_mkdir_p($this->log_dir);
            
            // 創建 .htaccess 文件防止直接訪問
            $htaccess = $this->log_dir . '/.htaccess';
            if (!file_exists($htaccess)) {
                file_put_contents($htaccess, 'Deny from all');
            }

            // 創建 index.php 文件
            $index = $this->log_dir . '/index.php';
            if (!file_exists($index)) {
                file_put_contents($index, '<?php // Silence is golden');
            }
        }
    }

    /**
     * 獲取日誌文件路徑
     *
     * @param string $type
     * @return string|false
     */
    private function get_log_file($type) {
        if (!isset($this->log_files[$type])) {
            return false;
        }

        return $this->log_dir . '/' . $this->log_files[$type];
    }

    /**
     * 驗證日誌類型
     *
     * @param string $type
     * @return bool
     */
    private function validate_type($type) {
        return isset($this->log_files[$type]);
    }

    /**
     * 輪換日誌文件
     */
    private function rotate_logs() {
        foreach ($this->log_files as $type => $file) {
            $log_file = $this->get_log_file($type);
            
            if (file_exists($log_file) && filesize($log_file) > 5 * 1024 * 1024) { // 5MB
                $backup = $log_file . '.' . date('Y-m-d-H-i-s');
                rename($log_file, $backup);

                // 壓縮舊日誌
                if (class_exists('ZipArchive')) {
                    $zip = new ZipArchive();
                    $zip_file = $backup . '.zip';
                    
                    if ($zip->open($zip_file, ZipArchive::CREATE) === true) {
                        $zip->addFile($backup, basename($backup));
                        $zip->close();
                        unlink($backup);
                    }
                }
            }
        }

        // 清理舊日誌
        $this->cleanup_old_logs();
    }

    /**
     * 清理舊日誌
     */
    private function cleanup_old_logs() {
        $files = glob($this->log_dir . '/*.{log,zip}', GLOB_BRACE);
        $now = time();

        foreach ($files as $file) {
            // 保留30天的日誌
            if ($now - filemtime($file) > 30 * 24 * 60 * 60) {
                unlink($file);
            }
        }
    }

    /**
     * 獲取日誌內容
     *
     * @param string $type
     * @param int $lines
     * @return array
     */
    public function get_logs($type, $lines = 100) {
        $log_file = $this->get_log_file($type);
        
        if (!file_exists($log_file)) {
            return array();
        }

        $logs = array();
        $handle = fopen($log_file, 'r');

        if ($handle) {
            $position = filesize($log_file);
            $line_count = 0;

            while ($position >= 0 && $line_count < $lines) {
                $position--;
                fseek($handle, $position);
                $char = fgetc($handle);

                if ($char === "\n" || $position === 0) {
                    $line = fgets($handle);
                    if ($position === 0) {
                        $line = $char . $line;
                    }
                    $logs[] = trim($line);
                    $line_count++;
                }
            }

            fclose($handle);
        }

        return array_reverse($logs);
    }

    /**
     * 清除特定類型的日誌
     *
     * @param string $type
     * @return bool
     */
    public function clear_logs($type) {
        $log_file = $this->get_log_file($type);
        
        if (!$log_file || !file_exists($log_file)) {
            return false;
        }

        return file_put_contents($log_file, '') !== false;
    }

    /**
     * 獲取日誌統計信息
     *
     * @return array
     */
    public function get_stats() {
        $stats = array();

        foreach ($this->log_files as $type => $file) {
            $log_file = $this->get_log_file($type);
            
            if (file_exists($log_file)) {
                $stats[$type] = array(
                    'size' => filesize($log_file),
                    'modified' => filemtime($log_file),
                    'lines' => count(file($log_file))
                );
            }
        }

        return $stats;
    }
}
