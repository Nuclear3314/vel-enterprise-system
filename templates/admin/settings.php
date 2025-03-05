<?php
/**
 * Admin Settings Template
 *
 * @package     VEL
 * @author      Nuclear3314
 * @copyright   2025 Nuclear3314
 * @license     GPL v2 or later
 * @version     1.0.0
 * @created     2025-02-25 14:23:24
 */

if (!defined('ABSPATH')) {
    exit('Direct access not allowed.');
}

$current_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'general';
$tabs = array(
    'general' => __('General Settings', 'vel-enterprise-system'),
    'security' => __('Security Settings', 'vel-enterprise-system'),
    'ai' => __('AI Configuration', 'vel-enterprise-system'),
    'logistics' => __('Logistics Settings', 'vel-enterprise-system'),
    'api' => __('API Settings', 'vel-enterprise-system'),
);
?>

<div class="wrap vel-admin-wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

    <?php settings_errors(); ?>

    <nav class="nav-tab-wrapper">
        <?php foreach ($tabs as $tab_key => $tab_name): ?>
            <a href="?page=vel-settings&tab=<?php echo esc_attr($tab_key); ?>" 
               class="nav-tab <?php echo $current_tab === $tab_key ? 'nav-tab-active' : ''; ?>">
                <?php echo esc_html($tab_name); ?>
            </a>
        <?php endforeach; ?>
    </nav>

    <form method="post" action="options.php" class="vel-settings-form">
        <?php
        settings_fields('vel_' . $current_tab . '_settings');
        do_settings_sections('vel_' . $current_tab . '_settings');
        ?>

        <div class="vel-form-actions">
            <?php submit_button(__('Save Changes', 'vel-enterprise-system'), 'primary', 'submit', true); ?>
            
            <?php if ($current_tab === 'security'): ?>
                <button type="button" class="button button-secondary" id="vel-security-test">
                    <?php esc_html_e('Test Security Settings', 'vel-enterprise-system'); ?>
                </button>
            <?php endif; ?>
            
            <?php if ($current_tab === 'ai'): ?>
                <button type="button" class="button button-secondary" id="vel-ai-test">
                    <?php esc_html_e('Test AI Model', 'vel-enterprise-system'); ?>
                </button>
            <?php endif; ?>
        </div>
    </form>
</div>
