class VELDashboard {
    constructor() {
        this.charts = {};
        this.updateInterval = 5000;
        this.init();
    }

    init() {
        this.initializeCharts();
        this.startDataCollection();
    }

    initializeCharts() {
        this.charts.system = new Chart('systemChart', {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: '系統資源使用率',
                    data: []
                }]
            }
        });
    }
}