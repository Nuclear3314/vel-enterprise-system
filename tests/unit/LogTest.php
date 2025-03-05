<?php
namespace VEL\Tests\Unit;

use PHPUnit\Framework\TestCase;
use VEL\Includes\Models\VEL_Log;

class LogTest extends TestCase
{
    private $log;

    protected function setUp(): void
    {
        parent::setUp();
        $this->log = new VEL_Log();
    }

    public function test_can_create_log()
    {
        $result = $this->log->add_log(
            'test',
            'Test message'
        );
        $this->assertTrue($result);
    }
}