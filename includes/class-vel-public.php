public function display_floating_ai_assistant() {
    // 檢查用戶權限
    if (!$this->can_access_ai_assistant()) {
        return;
    }
    
    // 載入懸浮視窗模板
    include_once VEL_PLUGIN_DIR . 'public/partials/floating-ai-assistant.php';
    
    // 加載必要的 CSS 和 JS
    wp_enqueue_style('vel-floating-window');
    wp_enqueue_script('vel-ai-assistant');
}