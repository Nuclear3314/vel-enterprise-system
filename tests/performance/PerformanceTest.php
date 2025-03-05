<?php
namespace VEL\Tests\Performance;

use PHPUnit\Framework\TestCase;
use VEL\Includes\Monitoring\VEL_Performance_Monitor;

class PerformanceTest extends TestCase
{
    private $monitor;

    protected function setUp(): void
    {
        parent::setUp();
        $this->monitor = new VEL_Performance_Monitor();
    }

    public function test_performance_monitoring()
    {
        $this->monitor->start_monitoring();
        // 模擬工作負載
        sleep(1);
        $metrics = $this->monitor->collect_metrics();

        $this->assertArrayHasKey('execution_time', $metrics);
        $this->assertArrayHasKey('memory_usage', $metrics);
    }
}