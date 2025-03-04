<?php
/**
 * 預測器測試
 *
 * @package VEL_Enterprise_System
 * @since 1.0.0
 * @version 1.0.0
 * @author Nuclear3314
 * @copyright 2025 Nuclear3314
 * @last_modified 2025-02-24 03:15:35
 */

use PHPUnit\Framework\TestCase;

class PredictorTest extends TestCase {
    protected $model;
    protected $predictor;

    protected function setUp(): void {
        $this->model = new \VEL\AI\Model(1);
        $this->predictor = new \VEL\AI\Predictor($this->model);
    }

    public function testPrediction() {
        $data = [
            'feature1' => 1.5,
            'feature2' => 3.2
        ];

        $result = $this->predictor->predict($data);
        $this->assertArrayHasKey('prediction', $result);
        $this->assertIsFloat($result['prediction']);
    }

    public function testInvalidData() {
        $data = [
            'invalid_feature' => 1.5
        ];

        $this->expectException(\Exception::class);
        $this->predictor->predict($data);
    }
}
