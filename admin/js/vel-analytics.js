/**
 * VEL Enterprise System Analytics Dashboard Scripts
 * 
 * @package VEL_Enterprise_System
 * @since 1.0.0
 * @version 1.0.0
 * @author Nuclear3314
 * @copyright 2025 Nuclear3314
 * @last_modified 2025-02-23 15:14:16
 */

/* global jQuery, Chart, velAnalyticsData, moment */
(function($) {
    'use strict';

    // 繼續之前的代碼...

    const VELAnalytics = {
        /**
         * 加載分析數據
         *
         * @param {string} startDate 開始日期
         * @param {string} endDate   結束日期
         */
        loadAnalytics: function(startDate, endDate) {
            const $loader = $('.vel-analytics-loader');
            $loader.show();

            $.ajax({
                url: `${velAnalyticsData.apiBase}/metrics`,
                data: { start_date: startDate, end_date: endDate },
                success: (response) => {
                    this.updateCharts(response);
                    this.updateStats(response);
                    $loader.hide();
                },
                error: (xhr) => {
                    VELAdmin.showNotification('error', '加載分析數據失敗');
                    $loader.hide();
                }
            });
        },

        /**
         * 更新圖表
         *
         * @param {Object} data 分析數據
         */
        updateCharts: function(data) {
            // 更新準確率趨勢圖
            this.charts.accuracyTrend.data = {
                labels: data.prediction_metrics.map(item => item.date),
                datasets: [{
                    label: '預測準確率',
                    data: data.prediction_metrics.map(item => item.avg_accuracy * 100),
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                }]
            };
            this.charts.accuracyTrend.update();

            // 更新模型性能對比圖
            this.charts.modelPerformance.data = {
                labels: data.model_metrics.map(item => item.name),
                datasets: [{
                    label: '性能分數',
                    data: data.model_metrics.map(item => item.performance_metrics.score),
                    backgroundColor: 'rgb(54, 162, 235)'
                }]
            };
            this.charts.modelPerformance.update();

            // 更新預測分佈圖
            const distributionData = this.calculatePredictionDistribution(data.prediction_metrics);
            this.charts.predictionDistribution.data = {
                labels: Object.keys(distributionData),
                datasets: [{
                    data: Object.values(distributionData),
                    backgroundColor: [
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)',
                        'rgb(255, 206, 86)',
                        'rgb(75, 192, 192)'
                    ]
                }]
            };
            this.charts.predictionDistribution.update();
        },

        /**
         * 計算預測分佈
         *
         * @param {Array} metrics 預測指標數據
         * @return {Object}
         */
        calculatePredictionDistribution: function(metrics) {
            const distribution = {
                '高準確度 (>90%)': 0,
                '中等準確度 (70-90%)': 0,
                '低準確度 (50-70%)': 0,
                '不可靠 (<50%)': 0
            };

            metrics.forEach(item => {
                const accuracy = item.avg_accuracy * 100;
                if (accuracy > 90) {
                    distribution['高準確度 (>90%)']++;
                } else if (accuracy > 70) {
                    distribution['中等準確度 (70-90%)']++;
                } else if (accuracy > 50) {
                    distribution['低準確度 (50-70%)']++;
                } else {
                    distribution['不可靠 (<50%)']++;
                }
            });

            return distribution;
        },

        /**
         * 更新統計數據
         *
         * @param {Object} data 分析數據
         */
        updateStats: function(data) {
            // 計算總體統計
            const totalPredictions = data.prediction_metrics.reduce((sum, item) => sum + item.total, 0);
            const avgAccuracy = data.prediction_metrics.reduce((sum, item) => sum + item.avg_accuracy, 0) / 
                              data.prediction_metrics.length;
            
            // 更新統計卡片
            $('#vel-total-predictions').text(totalPredictions.toLocaleString());
            $('#vel-avg-accuracy').text((avgAccuracy * 100).toFixed(2) + '%');
            $('#vel-active-models').text(data.model_metrics.length);

            // 更新趨勢指標
            this.updateTrendIndicators(data);
        },

        /**
         * 更新趨勢指標
         *
         * @param {Object} data 分析數據
         */
        updateTrendIndicators: function(data) {
            const metrics = data.prediction_metrics;
            if (metrics.length < 2) return;

            // 計算預測數量趨勢
            const currentTotal = metrics[metrics.length - 1].total;
            const previousTotal = metrics[metrics.length - 2].total;
            const predictionTrend = ((currentTotal - previousTotal) / previousTotal * 100).toFixed(2);

            // 計算準確率趨勢
            const currentAccuracy = metrics[metrics.length - 1].avg_accuracy;
            const previousAccuracy = metrics[metrics.length - 2].avg_accuracy;
            const accuracyTrend = ((currentAccuracy - previousAccuracy) / previousAccuracy * 100).toFixed(2);

            // 更新趨勢顯示
            this.updateTrendDisplay('#vel-prediction-trend', predictionTrend);
            this.updateTrendDisplay('#vel-accuracy-trend', accuracyTrend);
        },

        /**
         * 更新趨勢顯示
         *
         * @param {string} selector 選擇器
         * @param {number} value    趨勢值
         */
        updateTrendDisplay: function(selector, value) {
            const $element = $(selector);
            const isPositive = value > 0;
            
            $element
                .text((isPositive ? '+' : '') + value + '%')
                .removeClass('trend-up trend-down')
                .addClass(isPositive ? 'trend-up' : 'trend-down');
        },

        /**
         * 導出數據
         *
         * @param {string} format 導出格式
         */
        exportData: function(format) {
            const dateRange = $('#vel-date-range').val();
            const [startDate, endDate] = dateRange.split(' - ');

            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'vel_export_analytics',
                    format: format,
                    start_date: startDate,
                    end_date: endDate
                },
                success: (response) => {
                    if (response.success) {
                        // 創建下載鏈接
                        const link = document.createElement('a');
                        link.href = response.data.url;
                        link.download = response.data.filename;
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                    } else {
                        VELAdmin.showNotification('error', response.data.message);
                    }
                },
                error: () => {
                    VELAdmin.showNotification('error', '導出失敗');
                }
            });
        }
    };

    // 文檔加載完成後初始化
    $(document).ready(function() {
        VELAnalytics.init();
    });

})(jQuery);