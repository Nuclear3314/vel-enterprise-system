namespace VEL\Includes\AI;

class VEL_AI_Automation {
    private $site_type;
    private $auth_manager;

    public function __construct() {
        $this->site_type = is_main_site() ? 'main' : 'sub';
        $this->auth_manager = new VEL_Auth_Manager();
    }

    public function process_command($command) {
        if (!$this->validate_command_access($command)) {
            return ['error' => '權限不足'];
        }

        switch ($this->parse_command_type($command)) {
            case 'create':
                return $this->handle_creation_command($command);
            case 'manage':
                return $this->handle_management_command($command);
            case 'schedule':
                return $this->handle_scheduling_command($command);
        }
    }

    private function handle_scheduling_command($command) {
        $schedule = new VEL_Content_Scheduler([
            'platforms' => ['facebook', 'youtube', 'instagram'],
            'frequency' => $this->parse_frequency($command),
            'content_type' => $this->parse_content_type($command)
        ]);

        return $schedule->create();
    }
}