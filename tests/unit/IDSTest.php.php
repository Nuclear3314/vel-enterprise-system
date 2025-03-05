<?php
/**
 * 入侵檢測與防護測試
 *
 * @package VEL_Enterprise_System
 * @since 1.0.0
 * @version 1.0.0
 * @last_modified 2025-02-24 04:12:53
 */

use PHPUnit\Framework\TestCase;

class IDSTest extends TestCase {

    public function testDetectIntrusion() {
        $request = new \WP_REST_Request('GET', '/test-endpoint');
        $request->set_header('X-Forwarded-For', '192.168.0.1');
        $request->set_header('User-Agent', 'sqlmap');
        
        VEL_IDS::detect_intrusion( $request );

        $blocked_ips = get_option( 'vel_blocked_ips', array() );
        $this->assertContains( '192.168.0.1', $blocked_ips );
    }

    public function testBlockRequest() {
        $ip_address = '192.168.0.2';
        VEL_IDS::block_request( $ip_address );

        $blocked_ips = get_option( 'vel_blocked_ips', array() );
        $this->assertContains( $ip_address, $blocked_ips );
    }

    public function testNotifySites() {
        $ip_address = '192.168.0.3';
        update_option('vel_other_sites', array('http://example.com', 'http://example2.com'));

        VEL_IDS::notify_sites($ip_address);
        // 無法直接測試外部 API 通知，但可以通過日誌或其他方式驗證
    }

    public function testLaunchCounterattack() {
        $ip_address = '192.168.0.4';
        VEL_IDS::launch_counterattack($ip_address);
        // 無法直接測試反擊行為，但可以通過日誌或其他方式驗證
    }
}