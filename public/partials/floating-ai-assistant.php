<?php
/**
 * AI 助手懸浮視窗
 */
defined('ABSPATH') || exit;
?>

<div id="vel-floating-window" class="vel-floating-window" data-user-role="<?php echo esc_attr(VEL_User::get_current_role()); ?>">
    <div class="vel-floating-header">
        <h3><?php esc_html_e('AI 助手', 'vel-enterprise-system'); ?></h3>
        <div class="vel-controls">
            <button class="vel-minimize">_</button>
            <button class="vel-close">&times;</button>
        </div>
    </div>
    <div class="vel-floating-content">
        <div class="vel-ai-chat"></div>
        <div class="vel-ai-controls">
            <input type="text" id="vel-ai-input" placeholder="輸入 '執行' 開始命令..." />
            <button id="vel-ai-execute" data-nonce="<?php echo wp_create_nonce('vel_ai_action'); ?>">
                <?php esc_html_e('執行', 'vel-enterprise-system'); ?>
            </button>
        </div>
    </div>
    <div class="vel-status-bar">
        <span class="vel-connection-status"></span>
        <span class="vel-current-task"></span>
    </div>
</div>
