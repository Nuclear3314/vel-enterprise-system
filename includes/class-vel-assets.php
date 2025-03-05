<?php
namespace VEL\Includes;

if (!defined('ABSPATH')) {
    exit;
}

class VEL_Assets {
    /**
     * 註冊樣式和腳本
     */
    public function register_assets() {
        add_action('wp_enqueue_scripts', [$this, 'register_public_assets']);
        add_action('admin_enqueue_scripts', [$this, 'register_admin_assets']);
    }

    /**
     * 註冊前台資源
     */
    public function register_public_assets() {
        // CSS
        wp_register_style(
            'vel-public-style',
            VEL_PLUGIN_URL . 'public/css/vel-public.css',
            [],
            VEL_VERSION
        );

        // JavaScript
        wp_register_script(
            'vel-public-script',
            VEL_PLUGIN_URL . 'public/js/vel-public.js',
            ['jquery'],
            VEL_VERSION,
            true
        );

        // 本地化腳本
        wp_localize_script('vel-public-script', 'velPublicData', [
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('vel-public-nonce')
        ]);
    }

    /**
     * 註冊後台資源
     */
    public function register_admin_assets() {
        // CSS
        wp_register_style(
            'vel-admin-style',
            VEL_PLUGIN_URL . 'admin/css/vel-admin.css',
            [],
            VEL_VERSION
        );

        // JavaScript
        wp_register_script(
            'vel-admin-script',
            VEL_PLUGIN_URL . 'admin/js/vel-admin.js',
            ['jquery'],
            VEL_VERSION,
            true
        );

        // 本地化腳本
        wp_localize_script('vel-admin-script', 'velAdminData', [
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('vel-admin-nonce')
        ]);
    }
}