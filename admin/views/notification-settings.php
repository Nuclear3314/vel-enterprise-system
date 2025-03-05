<?php if (!defined('ABSPATH')) exit; ?>

<div class="wrap">
    <h1>通知系統設定</h1>
    
    <form method="post" action="options.php">
        <?php settings_fields('vel_notification_settings'); ?>
        <?php do_settings_sections('vel_notification_settings'); ?>
        
        <table class="form-table">
            <tr>
                <th scope="row">Line Notify Token</th>
                <td>
                    <input type="text" 
                           name="vel_line_token" 
                           value="<?php echo esc_attr(get_option('vel_line_token')); ?>" 
                           class="regular-text">
                </td>
            </tr>
            <tr>
                <th scope="row">啟用通知頻道</th>
                <td>
                    <label>
                        <input type="checkbox" 
                               name="vel_enable_email" 
                               value="1" 
                               <?php checked(get_option('vel_enable_email'), 1); ?>>
                        Email
                    </label>
                    <br>
                    <label>
                        <input type="checkbox" 
                               name="vel_enable_line" 
                               value="1" 
                               <?php checked(get_option('vel_enable_line'), 1); ?>>
                        Line
                    </label>
                </td>
            </tr>
        </table>
        
        <?php submit_button(); ?>
    </form>
</div>