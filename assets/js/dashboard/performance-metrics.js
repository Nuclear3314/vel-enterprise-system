class VELPerformanceMetrics {
    constructor() {
        this.charts = {};
        this.updateInterval = 5000;
        this.init();
    }

    async init() {
        await this.initializeCharts();
        this.startMetricsCollection();
    }

    async initializeCharts() {
        this.charts.cpu = new Chart('cpuChart', {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'CPU 使用率',
                    data: [],
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                }]
            }
        });
    }
}