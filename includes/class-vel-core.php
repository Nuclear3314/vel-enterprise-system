<?php
/**
 * 核心類檔案
 *
 * @package     VEL_Enterprise_System
 * @author      VEL New Life World
 * @copyright   2025 VEL New Life World
 */

if (!defined('ABSPATH')) {
    exit;
}

namespace VEL\Includes;

class VEL_Core {
    protected $loader;
    protected $plugin_name;
    protected $version;

    /**
     * 初始化 VEL 核心系統
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
     * 載入相依性
     */
    private function load_dependencies() {
        // 載入必要的類別
        require_once VEL_PLUGIN_DIR . 'includes/class-vel-loader.php';
        require_once VEL_PLUGIN_DIR . 'includes/class-vel-i18n.php';
        require_once VEL_PLUGIN_DIR . 'includes/class-vel-security.php';
        require_once VEL_PLUGIN_DIR . 'includes/class-vel-logger.php';
        require_once VEL_PLUGIN_DIR . 'admin/class-vel-admin.php';
        require_once VEL_PLUGIN_DIR . 'public/class-vel-floating-window.php';
        
        $this->loader = new VEL_Loader();
    }

    /**
     * 設定區域化
     */
    private function set_locale() {
        $i18n = new VEL_i18n();
        $this->loader->add_action('plugins_loaded', $i18n, 'load_plugin_textdomain');
    }

    /**
     * 註冊管理後台鉤子
     */
    private function define_admin_hooks() {
        $admin = new VEL_Admin();
        $this->loader->add_action('admin_menu', $admin, 'add_admin_menu');
        $this->loader->add_action('admin_enqueue_scripts', $admin, 'enqueue_admin_assets');
        $this->loader->add_action('admin_init', $admin, 'register_settings');
    }

    /**
     * 註冊前台鉤子
     */
    private function define_public_hooks() {
        // 註冊前台鉤子
    }

    /**
     * 註冊API鉤子
     */
    private function define_api_hooks() {
        // API相關鉤子
    }

    /**
     * 運行已註冊的鉤子
     */
    public function run() {
        $this->loader->run();
    }
}

class Core {
    private $modules = [];
    private $loader;

    public function __construct() {
        $this->loader = new Loader();
        $this->init_modules();
        $this->setup_hooks();
    }

    private function init_modules() {
        $this->modules = [
            'ai' => new AI\System(),
            'security' => new Security\Handler(),
            'storage' => new Storage\Manager(),
            'sync' => new Sync\Controller(),
            'members' => new Members\Handler()
        ];
    }

    private function setup_hooks() {
        $this->loader->add_action('init', $this, 'init_plugin');
        $this->loader->add_action('admin_init', $this, 'admin_init');
        
        foreach ($this->modules as $module) {
            if (method_exists($module, 'register_hooks')) {
                $module->register_hooks($this->loader);
            }
        }
    }

    public function init_plugin() {
        do_action('vel_init');
    }

    public function admin_init() {
        do_action('vel_admin_init');
    }
}