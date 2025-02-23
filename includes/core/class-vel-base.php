<?php
/**
 * 插件基礎類
 *
 * @package VEL_Enterprise_System
 * @subpackage Core
 */

namespace VEL\Core;

if (!defined('ABSPATH')) {
    exit;
}

class Base {
    /**
     * 加載器實例
     *
     * @var \VEL\Loader
     */
    protected $loader;

    /**
     * 插件名稱
     *
     * @var string
     */
    protected $plugin_name;

    /**
     * 插件版本
     *
     * @var string
     */
    protected $version;

    /**
     * 初始化插件
     */
    public function __construct() {
        $this->plugin_name = 'vel-enterprise-system';
        $this->version = VEL_VERSION;

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
        $this->define_api_hooks();
    }

    /**
     * 加載依賴
     */
    private function load_dependencies() {
        $this->loader = new \VEL\Loader();

        // 核心類
        require_once VEL_PLUGIN_INCLUDES_DIR . 'core/class-vel-security.php';
        require_once VEL_PLUGIN_INCLUDES_DIR . 'core/class-vel-user.php';
        require_once VEL_PLUGIN_INCLUDES_DIR . 'core/class-vel-logger.php';

        // AI 類
        require_once VEL_PLUGIN_INCLUDES_DIR . 'ai/class-vel-analyzer.php';
        require_once VEL_PLUGIN_INCLUDES_DIR . 'ai/class-vel-predictor.php';
        require_once VEL_PLUGIN_INCLUDES_DIR . 'ai/class-vel-model.php';
        require_once VEL_PLUGIN_INCLUDES_DIR . 'ai/class-vel-trainer.php';

        // API 類
        require_once VEL_PLUGIN_INCLUDES_DIR . 'api/class-vel-api.php';
        require_once VEL_PLUGIN_INCLUDES_DIR . 'api/class-vel-predictor-api.php';
        require_once VEL_PLUGIN_INCLUDES_DIR . 'api/class-vel-rest-controller.php';

        // 管理界面類
        require_once VEL_PLUGIN_ADMIN_DIR . 'class-vel-admin.php';
    }

    /**
     * 設置本地化
     */
    private function set_locale() {
        $plugin_i18n = new \VEL\I18n();
        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }

    /**
     * 註冊管理界面鉤子
     */
    private function define_admin_hooks() {
        $plugin_admin = new \VEL\Admin\Admin($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
        $this->loader->add_action('admin_menu', $plugin_admin, 'add_plugin_admin_menu');
    }

    /**
     * 註冊公共界面鉤子
     */
    private function define_public_hooks() {
        // 添加公共界面的鉤子
    }

    /**
     * 註冊 API 鉤子
     */
    private function define_api_hooks() {
        $plugin_api = new \VEL\API\REST_Controller();
        $this->loader->add_action('rest_api_init', $plugin_api, 'register_routes');
    }

    /**
     * 運行加載器
     */
    public function run() {
        $this->loader->run();
    }

    /**
     * 獲取插件名稱
     *
     * @return string
     */
    public function get_plugin_name() {
        return $this->plugin_name;
    }

    /**
     * 獲取加載器
     *
     * @return \VEL\Loader
     */
    public function get_loader() {
        return $this->loader;
    }

    /**
     * 獲取版本號
     *
     * @return string
     */
    public function get_version() {
        return $this->version;
    }
}