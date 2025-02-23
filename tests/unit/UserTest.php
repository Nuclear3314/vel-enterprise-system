<?php
namespace VEL\Tests\Unit;

use VEL_User;
use WP_UnitTestCase;

class UserTest extends WP_UnitTestCase {
    private $user_manager;
    private $test_user_id;

    public function setUp(): void {
        parent::setUp();
        $this->user_manager = new VEL_User();
        
        // 創建測試用戶
        $this->test_user_id = wp_create_user('test_user', 'test_pass', 'test@example.com');
    }

    public function tearDown(): void {
        // 清理測試用戶
        wp_delete_user($this->test_user_id);
        parent::tearDown();
    }

    public function test_setup_new_user() {
        $this->user_manager->setup_new_user($this->test_user_id);
        
        // 檢查用戶元數據
        $user_meta = get_user_meta($this->test_user_id, 'vel_user_setup', true);
        $this->assertTrue(!empty($user_meta));
    }

    public function test_user_capabilities() {
        $user = new WP_User($this->test_user_id);
        
        // 檢查基本權限
        $this->assertFalse($user->has_cap('vel_admin'));
        
        // 添加權限
        $this->user_manager->add_user_capability($this->test_user_id, 'vel_admin');
        
        // 重新檢查權限
        $user = new WP_User($this->test_user_id);
        $this->assertTrue($user->has_cap('vel_admin'));
    }
}