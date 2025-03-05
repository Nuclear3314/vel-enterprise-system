<div class="vel-offline-notice">
    <h2><?php _e('暫時無法連線', 'vel-enterprise-system'); ?></h2>
    <p><?php _e('請檢查您的網路連線並重試。', 'vel-enterprise-system'); ?></p>
    <div class="vel-offline-actions">
        <button onclick="location.reload()"><?php _e('重新整理', 'vel-enterprise-system'); ?></button>
        <button onclick="vel.offlineHandler.retryLastAction()"><?php _e('重試上次操作', 'vel-enterprise-system'); ?></button>
    </div>
</div>