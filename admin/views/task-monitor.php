<?php if (!defined('ABSPATH'))
    exit; ?>

<div class="wrap">
    <h1>任務監控面板</h1>

    <div class="vel-task-stats">
        <div class="vel-stat-box">
            <h3>等待中任務</h3>
            <span class="vel-stat-count" id="pending-tasks">
                <?php echo esc_html($stats['pending']); ?>
            </span>
        </div>

        <div class="vel-stat-box">
            <h3>執行中任務</h3>
            <span class="vel-stat-count" id="running-tasks">
                <?php echo esc_html($stats['running']); ?>
            </span>
        </div>

        <div class="vel-stat-box">
            <h3>失敗任務</h3>
            <span class="vel-stat-count" id="failed-tasks">
                <?php echo esc_html($stats['failed']); ?>
            </span>
        </div>
    </div>

    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>名稱</th>
                <th>優先級</th>
                <th>狀態</th>
                <th>重試次數</th>
                <th>建立時間</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody id="task-list">
            <!-- 動態載入任務列表 -->
        </tbody>
    </table>
</div>