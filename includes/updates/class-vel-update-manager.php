namespace VEL\Includes\Updates;

class VEL_Update_Manager {
    private $version_controller;
    private $update_scheduler;

    public function check_updates() {
        // 檢查更新
        $available_updates = $this->scan_for_updates();

        if (!empty($available_updates)) {
            // 通知主站點管理員
            $this->notify_main_admin($available_updates);

            // 排程更新
            $this->schedule_updates($available_updates);
        }
    }
}