<?php
namespace VEL\Tests\Unit;

use PHPUnit\Framework\TestCase;
use VEL\Includes\Models\VEL_User_Model;

class UserModelTest extends TestCase
{
    private $model;

    protected function setUp(): void
    {
        parent::setUp();
        $this->model = new VEL_User_Model();
    }

    public function test_can_create_user()
    {
        $data = [
            'email' => 'test@example.com',
            'name' => 'Test User'
        ];

        $result = $this->model->create($data);
        $this->assertTrue($result);
    }
}