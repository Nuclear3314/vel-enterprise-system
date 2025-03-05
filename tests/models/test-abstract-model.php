<?php
namespace VEL\Tests\Models;

use PHPUnit\Framework\TestCase;
use VEL\Includes\Models\VEL_Abstract_Model;

class Test_Abstract_Model extends TestCase
{
    private $model;

    protected function setUp(): void
    {
        parent::setUp();
        $this->model = $this->getMockForAbstractClass(VEL_Abstract_Model::class);
    }

    public function test_can_create_model()
    {
        $this->assertInstanceOf(VEL_Abstract_Model::class, $this->model);
    }
}