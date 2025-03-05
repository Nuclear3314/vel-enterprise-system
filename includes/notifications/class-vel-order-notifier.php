namespace VEL\Includes\Notifications;

class VEL_Order_Notifier {
    private $spreadsheet_generator;
    private $notification_channels;

    public function send_order_notification($order_id) {
        $order_data = $this->get_order_data($order_id);
        
        // 生成試算表
        $spreadsheet_url = $this->spreadsheet_generator->create([
            'type' => $this->get_site_preference('spreadsheet_type'),
            'data' => $order_data
        ]);
        
        // 發送通知
        $this->send_notification([
            'channel' => $this->get_site_preference('notification_channel'),
            'data' => $order_data,
            'spreadsheet_url' => $spreadsheet_url
        ]);
    }
}