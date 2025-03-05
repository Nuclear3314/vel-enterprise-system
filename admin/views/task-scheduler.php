<?php if (!defined('ABSPATH'))
    exit; ?>

<div class="wrap">
    <h1>任務排程管理</h1>

    <div class="vel-scheduler-container">
        <!-- 新增排程任務表單 -->
        <form method="post" action="" class="vel-scheduler-form">
            <?php wp_nonce_field('vel_add_scheduled_task'); ?>
            <table class="form-table">
                <tr>
                    <th><label for="task_name">任務名稱</label></th>
                    <td><input type="text" name="task_name" id="task_name" required></td>
                </tr>
                <tr>
                    <th><label for="frequency">執行頻率</label></th>
                    <td>
                        <select name="frequency" id="frequency">
                            <option value="hourly">每小時</option>
                            <option value="daily">每日</option>
                            <option value="weekly">每週</option>
                            <option value="monthly">每月</option>
                        </select>
                    </td>
                </tr>
            </table>
            <p class="submit">
                <input type="submit" name="submit" class="button button-primary" value="新增排程">
            </p>
        </form>

        <!-- 排程任務列表 -->
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>任務名稱</th>
                    <th>頻率</th>
                    <th>下次執行時間</th>
                    <th>上次執行時間</th>
                    <th>狀態</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody id="scheduled-tasks-list">
                <!-- 動態載入任務列表 -->
            </tbody>
        </table>
    </div>
</div>