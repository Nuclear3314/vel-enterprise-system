namespace VEL\Includes\Commerce;

class VEL_Cart {
    private $payment_gateway;
    private $order_processor;
    
    public function process_order($order_data) {
        try {
            // 處理訂單
            $order_id = $this->create_order($order_data);
            
            // 生成通知
            $this->generate_notifications($order_id);
            
            // 建立試算表
            $this->create_spreadsheet($order_id);
            
        } catch (\Exception $e) {
            $this->handle_order_error($e);
        }
    }
}