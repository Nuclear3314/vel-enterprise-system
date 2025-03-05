<?php
namespace VEL\Tests\Auth;

use PHPUnit\Framework\TestCase;
use VEL\Includes\Auth\VEL_Auth_Manager;

class VEL_Auth_Test extends TestCase
{
    private $auth_manager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->auth_manager = new VEL_Auth_Manager();
    }

    public function test_authentication_with_valid_credentials()
    {
        $credentials = [
            'username' => 'test@example.com',
            'password' => 'password123'
        ];

        $result = $this->auth_manager->authenticate($credentials);
        $this->assertTrue($result);
    }
}