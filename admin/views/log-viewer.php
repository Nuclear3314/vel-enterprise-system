<?php
if (!defined('ABSPATH'))
    exit;
?>

<div class="wrap">
    <h1>系統日誌檢視器</h1>

    <div class="tablenav top">
        <div class="alignleft actions">
            <select name="log_file">
                <?php foreach (array_keys($logs) as $file): ?>
                    <option value="<?php echo esc_attr($file); ?>">
                        <?php echo esc_html($file); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th>時間</th>
                <th>級別</th>
                <th>訊息</th>
            </tr>
        </thead>
        <tbody id="log-entries">
        </tbody>
    </table>
</div>