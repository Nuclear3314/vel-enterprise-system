namespace VEL\Includes\Controllers;

class VEL_Realtime_Monitor {
private $websocket_server;
private $data_collector;

public function __construct() {
$this->data_collector = new \VEL\Includes\Monitoring\VEL_Data_Collector();
}

public function init() {
add_action('admin_menu', [$this, 'add_dashboard_page']);
add_action('admin_enqueue_scripts', [$this, 'enqueue_assets']);
add_action('wp_ajax_get_realtime_data', [$this, 'get_realtime_data']);
}

public function get_realtime_data() {
wp_send_json($this->data_collector->collect_data());
}
}