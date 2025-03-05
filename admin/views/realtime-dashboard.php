<?php if (!defined('ABSPATH'))
    exit; ?>

<div class="wrap vel-dashboard">
    <h1>VEL 即時監控儀表板</h1>

    <div class="vel-metrics-grid">
        <div class="vel-metric-card" id="system-health">
            <h3>系統健康狀態</h3>
            <div class="vel-metric-value" id="health-status"></div>
        </div>

        <div class="vel-metric-card" id="active-tasks">
            <h3>執行中任務</h3>
            <div class="vel-metric-value" id="tasks-count"></div>
        </div>

        <div class="vel-metric-card" id="resource-usage">
            <h3>資源使用率</h3>
            <canvas id="resource-chart"></canvas>
        </div>
    </div>
</div>