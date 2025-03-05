namespace VEL\Tests\Unit;

use VEL\Includes\Security\VEL_Defense_System;
use WP_UnitTestCase;

class VEL_Security_Test extends WP_UnitTestCase {
    private $defense_system;

    public function setUp(): void {
        parent::setUp();
        $this->defense_system = new VEL_Defense_System();
    }

    public function test_michael_protocol_activation() {
        $threat_data = [
            'ip' => '192.168.1.1',
            'type' => 'ddos',
            'intensity' => 'high'
        ];

        $result = $this->defense_system->activate_protocol('MICHAEL', $threat_data);
        
        $this->assertTrue($result['status'] === 'active');
        $this->assertEquals(7, $result['level']);
        $this->assertNotEmpty($result['measures']);
    }
}