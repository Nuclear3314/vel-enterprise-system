<?php
/**
 * 載入器類別
 *
 * @package     VEL_Enterprise_System
 * @author      VEL New Life World
 * @copyright   2025 VEL New Life World
 */

namespace VEL;

if (!defined('ABSPATH')) {
    exit;
}

class VEL_Loader {
    protected $actions;
    protected $filters;
    protected $shortcodes;

    /**
     * 初始化載入器
     */
    public function __construct() {
        $this->actions = array();
        $this->filters = array();
        $this->shortcodes = array();
    }

    /**
     * 新增動作鉤子
     *
     * @param string   $hook          鉤子名稱
     * @param object   $component     執行鉤子的對象
     * @param string   $callback      回調函數名稱
     * @param int      $priority      優先順序
     * @param int      $accepted_args 接受的參數數量
     */
    public function add_action($hook, $component, $callback, $priority = 10, $accepted_args = 1) {
        $this->actions = $this->add($this->actions, $hook, $component, $callback, $priority, $accepted_args);
    }

    /**
     * 新增篩選鉤子
     *
     * @param string   $hook          鉤子名稱
     * @param object   $component     執行鉤子的對象
     * @param string   $callback      回調函數名稱
     * @param int      $priority      優先順序
     * @param int      $accepted_args 接受的參數數量
     */
    public function add_filter($hook, $component, $callback, $priority = 10, $accepted_args = 1) {
        $this->filters = $this->add($this->filters, $hook, $component, $callback, $priority, $accepted_args);
    }

    /**
     * 新增短碼
     *
     * @param string   $tag           短碼標籤
     * @param object   $component     執行短碼的對象
     * @param string   $callback      回調函數名稱
     */
    public function add_shortcode($tag, $component, $callback) {
        $this->shortcodes = $this->add($this->shortcodes, $tag, $component, $callback, 0, 0);
    }

    /**
     * 通用新增鉤子函數
     */
    private function add($hooks, $hook, $component, $callback, $priority, $accepted_args) {
        $hooks[] = array(
            'hook'          => $hook,
            'component'     => $component,
            'callback'      => $callback,
            'priority'      => $priority,
            'accepted_args' => $accepted_args
        );

        return $hooks;
    }

    /**
     * 執行所有已註冊的鉤子
     */
    public function run() {
        // 註冊動作鉤子
        foreach ($this->actions as $hook) {
            add_action($hook['hook'], array($hook['component'], $hook['callback']), $hook['priority'], $hook['accepted_args']);
        }

        // 註冊篩選鉤子
        foreach ($this->filters as $hook) {
            add_filter($hook['hook'], array($hook['component'], $hook['callback']), $hook['priority'], $hook['accepted_args']);
        }

        // 註冊短碼
        foreach ($this->shortcodes as $shortcode) {
            add_shortcode($shortcode['hook'], array($shortcode['component'], $shortcode['callback']));
        }
    }

    /**
     * 獲取已註冊的動作
     *
     * @return array
     */
    public function get_actions() {
        return $this->actions;
    }

    /**
     * 獲取已註冊的過濾器
     *
     * @return array
     */
    public function get_filters() {
        return $this->filters;
    }

    /**
     * 獲取已註冊的短代碼
     *
     * @return array
     */
    public function get_shortcodes() {
        return $this->shortcodes;
    }
}