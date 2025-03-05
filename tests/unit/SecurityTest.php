<?php
namespace VEL\Tests\Unit;

use VEL\Tests\TestCase;
use VEL\Includes\Security\VEL_Security;

class SecurityTest extends TestCase {
    private $security;

    protected function setUp(): void {
        parent::setUp();
        $this->security = new VEL_Security();
    }

    public function test_security_headers() {
        $this->security->set_security_headers();
        
        // 檢查是否設置了必要的安全標頭
        $headers = xdebug_get_headers();
        
        $this->assertContains('X-Content-Type-Options: nosniff', $headers);
        $this->assertContains('X-Frame-Options: SAMEORIGIN', $headers);
        $this->assertContains('X-XSS-Protection: 1; mode=block', $headers);
    }

    public function test_init_security_logging() {
        $this->security->init_security_logging();
        
        // 檢查是否創建了日誌目錄
        $this->assertTrue(is_dir(VEL_PLUGIN_DIR . 'logs'));
        
        // 檢查是否有適當的權限
        $this->assertEquals('0755', substr(sprintf('%o', fileperms(VEL_PLUGIN_DIR . 'logs')), -4));
    }

    public function test_安全性檢查功能()
    {
        $result = $this->security->checkSecurityStatus();
        $this->assertTrue($result);
    }
}