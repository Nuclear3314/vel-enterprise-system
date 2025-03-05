namespace VEL\Includes\API;

class VEL_System_Status_API {
public function register_routes() {
register_rest_route('vel/v1', '/system-status', [
'methods' => 'GET',
'callback' => [$this, 'get_system_status'],
'permission_callback' => [$this, 'check_permissions']
]);
}

public function get_system_status() {
$collector = new \VEL\Includes\Monitoring\VEL_Data_Collector();
return rest_ensure_response($collector->collect_data());
}
}