namespace VEL\Includes\Security;

class VEL_Security_Logger {
    private const LOG_LEVELS = [
        'EMERGENCY' => 0,
        'ALERT'     => 1,
        'CRITICAL'  => 2,
        'ERROR'     => 3,
        'WARNING'   => 4,
        'NOTICE'    => 5,
        'INFO'      => 6,
        'DEBUG'     => 7,
    ];

    public function log_security_event($event_type, $message, $context = []) {
        $log_entry = [
            'timestamp' => current_time('mysql'),
            'type'      => $event_type,
            'message'   => $message,
            'ip'        => $this->get_client_ip(),
            'user_id'   => get_current_user_id(),
            'context'   => json_encode($context)
        ];

        // 記錄到資料庫
        $this->write_to_db($log_entry);

        // 如果是高風險事件，發送通知
        if ($this->is_high_risk_event($event_type)) {
            $this->notify_administrators($log_entry);
        }

        // 同步到主站點
        if (is_multisite() && !is_main_site()) {
            $this->sync_to_main_site($log_entry);
        }
    }

    private function is_high_risk_event($event_type) {
        return in_array($event_type, ['EMERGENCY', 'ALERT', 'CRITICAL']);
    }
}