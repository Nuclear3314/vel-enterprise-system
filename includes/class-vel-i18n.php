<?php
/**
 * 定義插件的國際化功能
 *
 * @package VEL_Enterprise_System
 * @since 1.0.0
 */

namespace VEL;

if (!defined('ABSPATH')) {
    exit;
}

class I18n {
    /**
     * 加載插件的文本域
     */
    public function load_plugin_textdomain() {
        load_plugin_textdomain(
            'vel-enterprise-system',
            false,
            dirname(dirname(plugin_basename(__FILE__))) . '/languages/'
        );
    }

    /**
     * 獲取翻譯字符串
     *
     * @param string $text    要翻譯的文本
     * @param string $context 上下文（可選）
     * @return string
     */
    public static function get_text($text, $context = '') {
        if (!empty($context)) {
            return _x($text, $context, 'vel-enterprise-system');
        }
        return __($text, 'vel-enterprise-system');
    }

    /**
     * 獲取並輸出翻譯字符串
     *
     * @param string $text    要翻譯的文本
     * @param string $context 上下文（可選）
     */
    public static function echo_text($text, $context = '') {
        if (!empty($context)) {
            echo esc_html(_x($text, $context, 'vel-enterprise-system'));
        } else {
            echo esc_html__($text, 'vel-enterprise-system');
        }
    }

    /**
     * 獲取帶有數量的翻譯字符串
     *
     * @param string $single 單數形式
     * @param string $plural 複數形式
     * @param int    $number 數量
     * @return string
     */
    public static function get_plural_text($single, $plural, $number) {
        return sprintf(
            _n($single, $plural, $number, 'vel-enterprise-system'),
            number_format_i18n($number)
        );
    }

    /**
     * 輸出帶有數量的翻譯字符串
     *
     * @param string $single 單數形式
     * @param string $plural 複數形式
     * @param int    $number 數量
     */
    public static function echo_plural_text($single, $plural, $number) {
        echo esc_html(sprintf(
            _n($single, $plural, $number, 'vel-enterprise-system'),
            number_format_i18n($number)
        ));
    }

    /**
     * 獲取格式化的日期
     *
     * @param string $date   日期字符串
     * @param string $format 格式（可選）
     * @return string
     */
    public static function get_formatted_date($date, $format = '') {
        if (empty($format)) {
            $format = get_option('date_format');
        }
        return date_i18n($format, strtotime($date));
    }

    /**
     * 輸出格式化的日期
     *
     * @param string $date   日期字符串
     * @param string $format 格式（可選）
     */
    public static function echo_formatted_date($date, $format = '') {
        echo esc_html(self::get_formatted_date($date, $format));
    }
}