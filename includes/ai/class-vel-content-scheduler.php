namespace VEL\Includes\AI;

class VEL_Content_Scheduler {
    private $schedule_types = [
        'daily' => 'everyday',
        'weekly' => 'every_week',
        'monthly' => 'every_month',
        'custom' => 'custom_interval'
    ];

    public function create_schedule($params) {
        if (!$this->validate_schedule_params($params)) {
            return ['error' => '無效的排程參數'];
        }

        $schedule_id = wp_schedule_event(
            time(),
            $params['frequency'],
            'vel_create_social_content',
            [$params]
        );

        return [
            'status' => 'success',
            'schedule_id' => $schedule_id,
            'next_run' => wp_next_scheduled('vel_create_social_content', [$params])
        ];
    }
}