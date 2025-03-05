<?php
namespace VEL\Includes\Reports;

class VEL_Report_View_Generator
{
    public function generate_html_report($data)
    {
        ob_start();
        ?>
        <div class="vel-report-container">
            <h2><?php echo esc_html($data['type']); ?> 效能報表</h2>

            <div class="vel-report-summary">
                <h3>摘要</h3>
                <ul>
                    <li>平均記憶體使用: <?php echo esc_html($data['summary']['avg_memory']); ?> MB</li>
                    <li>最高記憶體使用: <?php echo esc_html($data['summary']['peak_memory']); ?> MB</li>
                    <li>總任務數: <?php echo esc_html($data['summary']['total_tasks']); ?></li>
                </ul>
            </div>

            <div class="vel-report-chart">
                <canvas id="performanceChart"></canvas>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
}