<?php
/**
 * AI 分析器測試
 *
 * @package VEL_Enterprise_System
 * @since 1.0.0
 * @version 1.0.0
 * @author Nuclear3314
 * @copyright 2025 Nuclear3314
 * @last_modified 2025-02-24 03:15:35
 */

use PHPUnit\Framework\TestCase;

class AIAnalyzerTest extends TestCase {
    protected $analyzer;

    protected function setUp(): void {
        $this->analyzer = new \VEL\AI\Analyzer();
    }

    public function testTrendAnalysis() {
        $data = [
            ['timestamp' => '2025-02-01', 'value' => 10],
            ['timestamp' => '2025-02-02', 'value' => 15],
            ['timestamp' => '2025-02-03', 'value' => 20]
        ];

        $result = $this->analyzer->analyze('trend', $data);
        $this->assertEquals('upward', $result['trend']);
    }

    public function testPatternAnalysis() {
        $data = [
            ['timestamp' => '2025-02-01', 'value' => 10],
            ['timestamp' => '2025-02-02', 'value' => 15],
            ['timestamp' => '2025-02-01', 'value' => 10],
            ['timestamp' => '2025-02-02', 'value' => 15]
        ];

        $result = $this->analyzer->analyze('pattern', $data);
        $this->assertEquals('repeating', $result['pattern']);
    }

    public function testAnomalyDetection() {
        $data = [
            ['timestamp' => '2025-02-01', 'value' => 10],
            ['timestamp' => '2025-02-02', 'value' => 15],
            ['timestamp' => '2025-02-03', 'value' => 100]
        ];

        $result = $this->analyzer->analyze('anomaly', $data);
        $this->assertTrue($result['has_anomaly']);
    }
}
