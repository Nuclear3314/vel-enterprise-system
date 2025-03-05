<?php if (!defined('ABSPATH'))
    exit; ?>

<div class="wrap">
    <h1>資料庫優化工具</h1>

    <div class="vel-optimization-tools">
        <div class="vel-tool-card">
            <h3>資料表優化</h3>
            <form method="post" action="">
                <?php wp_nonce_field('vel_optimize_tables'); ?>
                <button type="submit" name="optimize_tables" class="button button-primary">
                    執行優化
                </button>
            </form>
        </div>

        <div class="vel-tool-card">
            <h3>索引分析</h3>
            <form method="post" action="">
                <?php wp_nonce_field('vel_analyze_indexes'); ?>
                <button type="submit" name="analyze_indexes" class="button button-primary">
                    分析索引
                </button>
            </form>
        </div>
    </div>
</div>