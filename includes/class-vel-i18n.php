<?php
/**
 * 定義插件的國際化功能
 *
 * @package VEL_Enterprise_System
 * @since 1.0.0
 */

namespace VEL\Includes;

if (!defined('ABSPATH')) {
    exit;
}

class VEL_i18n {
    /**
     * 載入翻譯檔案
     */
    public function load_plugin_textdomain() {
        load_plugin_textdomain(
            'vel-enterprise-system',
            false,
            dirname(plugin_basename(VEL_PLUGIN_FILE)) . '/languages/'
        );
    }

    /**
     * 翻譯字串
     */
    public static function translate($text) {
        return __($text, 'vel-enterprise-system');
    }
}