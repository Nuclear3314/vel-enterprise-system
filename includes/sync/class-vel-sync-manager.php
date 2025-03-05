namespace VEL\Includes\Sync;

class VEL_Sync_Manager {
    private $sync_queue = [];
    private $main_site_id;

    public function __construct() {
        $this->main_site_id = get_main_site_id();
        
        add_action('init', [$this, 'initialize_sync_system']);
        add_action('vel_sync_data', [$this, 'process_sync_queue']);
    }

    public function initialize_sync_system() {
        if (is_multisite()) {
            $this->setup_sync_hooks();
        }
    }

    private function setup_sync_hooks() {
        // 會員數據同步
        add_action('profile_update', [$this, 'queue_member_sync']);
        add_action('user_register', [$this, 'queue_member_sync']);
        
        // 內容同步
        add_action('save_post', [$this, 'queue_content_sync']);
        
        // 設定同步
        add_action('update_option', [$this, 'queue_settings_sync']);
    }

    public function queue_member_sync($user_id) {
        $this->add_to_sync_queue('member', $user_id);
    }

    private function add_to_sync_queue($type, $id) {
        $this->sync_queue[] = [
            'type' => $type,
            'id' => $id,
            'timestamp' => time()
        ];
        
        $this->maybe_process_queue();
    }
}