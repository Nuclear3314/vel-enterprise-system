/**
 * VEL Enterprise System Admin Scripts
 * Version: 1.0.0
 * Created: 2025-02-25 08:05:50
 */

jQuery(document).ready(function($) {
    'use strict';

    // 初始化圖表
    const initCharts = () => {
        if ($('#vel-analytics-chart').length) {
            // 實現圖表邏輯
        }
    };

    // 初始化數據表格
    const initTables = () => {
        $('.vel-data-table').DataTable({
            responsive: true,
            pageLength: 10,
            order: [[0, 'desc']]
        });
    };

    // AJAX 請求處理
    const handleAjaxRequest = (action, data) => {
        return $.ajax({
            url: velAdmin.ajaxUrl,
            type: 'POST',
            data: {
                action: 'vel_' + action,
                nonce: velAdmin.nonce,
                ...data
            }
        });
    };

    // 初始化事件監聽
    const initEventListeners = () => {
        // 保存設置
        $('#vel-save-settings').on('click', function(e) {
            e.preventDefault();
            const formData = $('#vel-settings-form').serialize();
            handleAjaxRequest('save_settings', formData)
                .done(response => {
                    if (response.success) {
                        alert(response.data.message);
                    }
                });
        });

        // AI 分析觸發
        $('#vel-run-analysis').on('click', function(e) {
            e.preventDefault();
            handleAjaxRequest('run_analysis')
                .done(response => {
                    if (response.success) {
                        updateAnalyticsDisplay(response.data);
                    }
                });
        });
    };

    // 初始化所有功能
    const init = () => {
        initCharts();
        initTables();
        initEventListeners();
    };

    // 執行初始化
    init();
});
