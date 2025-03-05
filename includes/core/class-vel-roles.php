<?php
/**
 * 角色權限管理類
 *
 * @package VEL_Enterprise_System
 * @subpackage VEL/includes/core
 * @since 1.0.0
 * @version 1.0.0
 * @author Nuclear3314
 * @copyright 2025 Nuclear3314
 */

namespace VEL\Includes\Core;

// 如果沒有正確載入 WordPress，則退出
if (!defined('ABSPATH')) {
    exit('Direct access not allowed.');
}

class VEL_Roles {
    const ROLES = [
        'CREATOR' => [
            'level' => 100,
            'capabilities' => [
                'manage_network',
                'create_sites',
                'manage_ai',
                'use_defense_protocols',
                'view_all_analytics'
            ]
        ],
        'OWNER' => [
            'level' => 90,
            'capabilities' => [
                'manage_site',
                'use_ai_limited',
                'view_site_analytics',
                'manage_storage'
            ]
        ],
        'EXECUTIVE' => [
            'level' => 80,
            'capabilities' => [
                'manage_staff',
                'use_ai_basic',
                'view_reports'
            ]
        ],
        'MANAGER' => [
            'level' => 70,
            'capabilities' => [
                'manage_content',
                'use_ai_assistant',
                'view_basic_analytics'
            ]
        ]
    ];

    /**
     * 初始化角色權限
     */
    public function __construct() {
        add_action('init', [$this, 'register_roles']);
    }

    /**
     * 註冊角色
     */
    public function register_roles() {
        foreach (self::ROLES as $role_name => $role_data) {
            $this->register_single_role($role_name, $role_data);
        }
    }

    /**
     * 註冊單一角色
     */
    private function register_single_role($role_name, $role_data) {
        // 檢查角色是否存在
        $role_slug = strtolower($role_name);
        $existing_role = get_role($role_slug);
        
        if (!$existing_role) {
            add_role(
                $role_slug,
                $role_name,
                $this->get_capabilities($role_data['capabilities'])
            );
        }
    }
}