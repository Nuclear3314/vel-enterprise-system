<?php
/**
 * 設置頁面視圖
 *
 * @package VEL_Enterprise_System
 * @since 1.0.0
 * @version 1.0.0
 * @author Nuclear3314
 * @copyright 2025 Nuclear3314
 * @last_modified 2025-02-23 15:25:39
 */

defined('ABSPATH') || exit;

// 獲取當前設置
$settings = get_option('vel_settings', array());
?>

<div class="wrap vel-admin-wrap">
    <div class="vel-header">
        <h1><?php esc_html_e('系統設置', 'vel-enterprise-system'); ?></h1>
    </div>

    <div class="vel-card">
        <form id="vel-settings-form" method="post" action="options.php">
            <?php settings_fields('vel_settings_group'); ?>
            
            <!-- API 設置 -->
            <div class="vel-card">
                <div class="vel-card-header">
                    <h2 class="vel-card-title"><?php esc_html_e('API 設置', 'vel-enterprise-system'); ?></h2>
                </div>
                <div class="vel-card-content">
                    <div class="vel-form-group">
                        <label class="vel-form-label">
                            <?php esc_html_e('API 密鑰', 'vel-enterprise-system'); ?>
                        </label>
                        <input type="text" 
                               class="vel-form-control" 
                               name="vel_settings[api_key]" 
                               value="<?php echo esc_attr($settings['api_key'] ?? ''); ?>"
                               readonly>
                        <button type="button" class="vel-button vel-button-secondary" id="vel-generate-api-key">
                            <?php esc_html_e('生成新密鑰', 'vel-enterprise-system'); ?>
                        </button>
                    </div>

                    <div class="vel-form-group">
                        <label class="vel-form-label">
                            <?php esc_html_e('請求限制', 'vel-enterprise-system'); ?>
                        </label>
                        <input type="number" 
                               class="vel-form-control" 
                               name="vel_settings[rate_limit]" 
                               value="<?php echo esc_attr($settings['rate_limit'] ?? 1000); ?>"
                               min="1" 
                               max="10000">
                        <p class="vel-form-help">
                            <?php esc_html_e('每小時最大請求次數', 'vel-enterprise-system'); ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- 模型設置 -->
            <div class="vel-card">
                <div class="vel-card-header">
                    <h2 class="vel-card-title"><?php esc_html_e('模型設置', 'vel-enterprise-system'); ?></h2>
                </div>
                <div class="vel-card-content">
                    <div class="vel-form-group">
                        <label class="vel-form-label">
                            <?php esc_html_e('默認學習率', 'vel-enterprise-system'); ?>
                        </label>
                        <input type="number" 
                               class="vel-form-control" 
                               name="vel_settings[learning_rate]" 
                               value="<?php echo esc_attr($settings['learning_rate'] ?? 0.01); ?>"
                               step="0.001"
                               min="0.001"
                               max="1">
                    </div>

                    <div class="vel-form-group">
                        <label class="vel-form-label">
                            <?php esc_html_e('批次大小', 'vel-enterprise-system'); ?>
                        </label>
                        <input type="number" 
                               class="vel-form-control" 
                               name="vel_settings[batch_size]" 
                               value="<?php echo esc_attr($settings['batch_size'] ?? 32); ?>"
                               min="1"
                               max="1000">
                    </div>

                    <div class="vel-form-group">
                        <label class="vel-form-label">
                            <?php esc_html_e('訓練週期', 'vel-enterprise-system'); ?>
                        </label>
                        <input type="number" 
                               class="vel-form-control" 
                               name="vel_settings[epochs]" 
                               value="<?php echo esc_attr($settings['epochs'] ?? 100); ?>"
                               min="1"
                               max="1000">
                    </div>
                </div>
            </div>

            <!-- 系統設置 -->
            <div class="vel-card">
                <div class="vel-card-header">
                    <h2 class="vel-card-title"><?php esc_html_e('系統設置', 'vel-enterprise-system'); ?></h2>
                </div>
                <div class="vel-card-content">
                    <div class="vel-form-group">
                        <label class="vel-form-label">
                            <?php esc_html_e('日誌保留天數', 'vel-enterprise-system'); ?>
                        </label>
                        <input type="number" 
                               class="vel-form-control" 
                               name="vel_settings[log_retention]" 
                               value="<?php echo esc_attr($settings['log_retention'] ?? 30); ?>"
                               min="1"
                               max="365">
                    </div>

                    <div class="vel-form-group">
                        <label class="vel-form-label">
                            <?php esc_html_e('自動清理頻率', 'vel-enterprise-system'); ?>
                        </label>
                        <select class="vel-form-control" 
                                name="vel_settings[cleanup_frequency]">
                            <option value="daily" <?php selected($settings['cleanup_frequency'] ?? 'weekly', 'daily'); ?>>
                                <?php esc_html_e('每天', 'vel-enterprise-system'); ?>
                            </option>
                            <option value="weekly" <?php selected($settings['cleanup_frequency'] ?? 'weekly', 'weekly'); ?>>
                                <?php esc_html_e('每週', 'vel-enterprise-system'); ?>
                            </option>
                            <option value="monthly" <?php selected($settings['cleanup_frequency'] ?? 'weekly', 'monthly'); ?>>
                                <?php esc_html_e('每月', 'vel-enterprise-system'); ?>
                            </option>
                        </select>
                    </div>

                    <div class="vel-form-group">
                        <label class="vel-form-switch">
                            <input type="checkbox" 
                                   name="vel_settings[debug_mode]" 
                                   value="1" 
                                   <?php checked($settings['debug_mode'] ?? false); ?>>
                            <?php esc_html_e('啟用調試模式', 'vel-enterprise-system'); ?>
                        </label>
                        <p class="vel-form-help">
                            <?php esc_html_e('在調試模式下，系統會記錄更詳細的日誌信息', 'vel-enterprise-system'); ?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="vel-form-actions">
                <?php submit_button(__('保存設置', 'vel-enterprise-system'), 'primary', 'submit', false); ?>
                <button type="button" class="vel-button vel-button-danger" id="vel-reset-settings">
                    <?php esc_html_e('重置設置', 'vel-enterprise-system'); ?>
                </button>
            </div>
        </form>
    </div>

    <!-- 通知容器 -->
    <div class="vel-notifications-container"></div>
</div>

<?php
// 添加必要的腳本
wp_enqueue_script('vel-admin');
wp_enqueue_style('vel-admin');