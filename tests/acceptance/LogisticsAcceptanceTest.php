<?php
namespace VEL\Tests\Acceptance;

use VEL_Inventory;
use VEL_Shipping;
use WP_UnitTestCase;

class LogisticsAcceptanceTest extends WP_UnitTestCase {
    private $inventory;
    private $shipping;

    public function setUp(): void {
        parent::setUp();
        $this->inventory = new VEL_Inventory();
        $this->shipping = new VEL_Shipping();
    }

    public function test_complete_logistics_workflow() {
        // 1. 添加庫存
        $product_id = $this->inventory->add_product(array(
            'name' => 'Test Product',
            'sku' => 'TEST001',
            'quantity' => 100
        ));
        
        // 檢查庫存是否正確添加
        $this->assertIsInt($product_id);
        $this->assertEquals(100, $this->inventory->get_quantity($product_id));
        
        // 2. 創建訂單
        $order_id = $this->shipping->create_order(array(
            'product_id' => $product_id,
            'quantity' => 10,
            'destination' => 'Test Address'
        ));
        
        // 檢查訂單是否創建成功
        $this->assertIsInt($order_id);
        
        // 3. 處理發貨
        $shipment = $this->shipping->process_shipment($order_id);
        
        // 檢查發貨狀態
        $this->assertEquals('processing', $shipment['status']);
        
        // 4. 確認庫存更新
        $this->assertEquals(90, $this->inventory->get_quantity($product_id));
    }
}