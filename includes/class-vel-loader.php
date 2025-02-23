<?php
/**
 * 註冊所有動作和過濾器的加載器
 *
 * @package VEL_Enterprise_System
 * @since 1.0.0
 */

namespace VEL;

if (!defined('ABSPATH')) {
    exit;
}

class Loader {
    /**
     * 存儲所有要註冊的動作
     *
     * @var array
     */
    protected $actions;

    /**
     * 存儲所有要註冊的過濾器
     *
     * @var array
     */
    protected $filters;

    /**
     * 存儲所有要註冊的短代碼
     *
     * @var array
     */
    protected $shortcodes;

    /**
     * 初始化集合
     */
    public function __construct() {
        $this->actions = array();
        $this->filters = array();
        $this->shortcodes = array();
    }

    /**
     * 添加新的動作到集合中
     *
     * @param string $hook          鉤子名稱
     * @param object $component     包含回調的對象
     * @param string $callback      回調函數名稱
     * @param int    $priority      優先級
     * @param int    $accepted_args 接受的參數數量
     */
    public function add_action($hook, $component, $callback, $priority = 10, $accepted_args = 1) {
        $this->actions = $this->add($this->actions, $hook, $component, $callback, $priority, $accepted_args);
    }

    /**
     * 添加新的過濾器到集合中
     *
     * @param string $hook          鉤子名稱
     * @param object $component     包含回調的對象
     * @param string $callback      回調函數名稱
     * @param int    $priority      優先級
     * @param int    $accepted_args 接受的參數數量
     */
    public function add_filter($hook, $component, $callback, $priority = 10, $accepted_args = 1) {
        $this->filters = $this->add($this->filters, $hook, $component, $callback, $priority, $accepted_args);
    }

    /**
     * 添加新的短代碼到集合中
     *
     * @param string $tag      短代碼標籤
     * @param object $component 包含回調的對象
     * @param string $callback  回調函數名稱
     */
    public function add_shortcode($tag, $component, $callback) {
        $this->shortcodes = $this->add($this->shortcodes, $tag, $component, $callback, null, null);
    }

    /**
     * 工具函數，用於向集合中添加新的鉤子/短代碼
     *
     * @param array  $hooks         現有的鉤子集合
     * @param string $hook          鉤子名稱
     * @param object $component     包含回調的對象
     * @param string $callback      回調函數名稱
     * @param int    $priority      優先級
     * @param int    $accepted_args 接受的參數數量
     * @return array
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
     * 註冊存儲在集合中的所有鉤子
     */
    public function run() {
        // 註冊動作
        foreach ($this->actions as $hook) {
            add_action(
                $hook['hook'],
                array($hook['component'], $hook['callback']),
                $hook['priority'],
                $hook['accepted_args']
            );
        }

        // 註冊過濾器
        foreach ($this->filters as $hook) {
            add_filter(
                $hook['hook'],
                array($hook['component'], $hook['callback']),
                $hook['priority'],
                $hook['accepted_args']
            );
        }

        // 註冊短代碼
        foreach ($this->shortcodes as $shortcode) {
            add_shortcode(
                $shortcode['hook'],
                array($shortcode['component'], $shortcode['callback'])
            );
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