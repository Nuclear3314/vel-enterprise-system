namespace VEL\Includes\AI\Providers;

class VEL_Claude_Provider implements AI_Provider_Interface {
    private $api_key;
    private $model = 'claude-3-opus-20240229';

    public function __construct() {
        $this->api_key = get_option('vel_claude_api_key');
    }

    public function generate_content($prompt, $parameters = []) {
        $response = wp_remote_post('https://api.anthropic.com/v1/messages', [
            'headers' => [
                'x-api-key' => $this->api_key,
                'anthropic-version' => '2023-06-01',
                'content-type' => 'application/json'
            ],
            'body' => json_encode([
                'model' => $this->model,
                'messages' => [
                    ['role' => 'user', 'content' => $prompt]
                ],
                'max_tokens' => 4096
            ])
        ]);

        if (is_wp_error($response)) {
            throw new \Exception('AI 生成失敗: ' . $response->get_error_message());
        }

        $body = json_decode(wp_remote_retrieve_body($response), true);
        return $body['content'];
    }
}