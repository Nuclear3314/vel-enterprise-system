class VELRealtimeMonitor {
    constructor() {
        this.updateInterval = 5000; // 5秒更新一次
        this.charts = {};
        this.init();
    }

    init() {
        this.initCharts();
        this.startMonitoring();
    }

    startMonitoring() {
        setInterval(() => {
            this.fetchMetrics();
        }, this.updateInterval);
    }

    fetchMetrics() {
        jQuery.ajax({
            url: ajaxurl,
            data: {
                action: 'get_realtime_metrics',
                _ajax_nonce: velDashboardData.nonce
            },
            success: (response) => {
                this.updateDashboard(response);
            }
        });
    }
}

// 初始化監控
jQuery(document).ready(() => {
    new VELRealtimeMonitor();
});