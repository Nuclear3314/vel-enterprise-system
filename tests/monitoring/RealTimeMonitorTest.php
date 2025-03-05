<?php
namespace VEL\Tests\Monitoring;

use PHPUnit\Framework\TestCase;
use VEL\Includes\Monitoring\VEL_Realtime_Monitor;

class RealTimeMonitorTest extends TestCase
{
    private $monitor;

    protected function setUp(): void
    {
        parent::setUp();
        $this->monitor = new VEL_Realtime_Monitor();
    }

    public function test_metric_collection()
    {
        $metrics = $this->monitor->collect_realtime_metrics();

        $this->assertArrayHasKey('memory_usage', $metrics);
        $this->assertArrayHasKey('cpu_load', $metrics);
        $this->assertArrayHasKey('active_users', $metrics);
        $this->assertIsArray($metrics['memory_usage']);
    }
}