<?php
namespace VEL\Tests\Integration;

use VEL_Analyzer;
use VEL_Predictor;
use WP_UnitTestCase;

class AIIntegrationTest extends WP_UnitTestCase {
    private $analyzer;
    private $predictor;

    public function setUp(): void {
        parent::setUp();
        $this->analyzer = new VEL_Analyzer();
        $this->predictor = new VEL_Predictor();
    }

    public function test_ai_workflow() {
        // 測試數據
        $test_data = array(
            'sales' => array(100, 200, 300, 400, 500),
            'dates' => array('2025-01', '2025-02', '2025-03', '2025-04', '2025-05')
        );
        
        // 分析數據
        $analysis = $this->analyzer->analyze_data($test_data);
        $this->assertIsArray($analysis);
        $this->assertArrayHasKey('trend', $analysis);
        
        // 預測下一個值
        $prediction = $this->predictor->predict_next_value($analysis);
        $this->assertIsNumeric($prediction);
        $this->assertGreaterThan(500, $prediction); // 應該遵循上升趨勢
    }
}