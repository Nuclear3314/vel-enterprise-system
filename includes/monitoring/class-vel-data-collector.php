namespace VEL\Includes\Monitoring;

class VEL_Data_Collector {
public function collect_data() {
return [
'system' => $this->get_system_metrics(),
'database' => $this->get_database_metrics(),
'cache' => $this->get_cache_metrics(),
'errors' => $this->get_error_logs()
];
}

private function get_system_metrics() {
return [
'memory_usage' => memory_get_usage(true),
'cpu_load' => sys_getloadavg(),
'disk_usage' => disk_free_space(ABSPATH)
];
}
}