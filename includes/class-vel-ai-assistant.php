<?php
/**
 * AI 助手處理類
 */
class VEL_AI_Assistant {
    private $user_role;
    private $allowed_commands;

    public function __construct() {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('rest_api_init', [$this, 'register_endpoints']);
    }

    public function enqueue_scripts() {
        if (!$this->can_use_assistant()) {
            return;
        }

        wp_enqueue_style('vel-ai-assistant');
        wp_enqueue_script('vel-ai-assistant');
        
        wp_localize_script('vel-ai-assistant', 'velAiData', [
            'apiUrl' => rest_url('vel/v1/ai'),
            'nonce' => wp_create_nonce('vel_ai_nonce'),
            'userRole' => $this->get_user_role()
        ]);
    }

    private function can_use_assistant() {
        $user = wp_get_current_user();
        return in_array($this->get_user_role(), $this->get_allowed_roles());
    }

    private function get_allowed_roles() {
        return [
            'administrator',
            'vel_creator',
            'vel_owner',
            'vel_executive',
            'vel_manager'
        ];
    }
}