class VELMonitoringClient {
    constructor() {
        this.ws = new WebSocket('ws://localhost:8080');
        this.initializeHandlers();
    }

    initializeHandlers() {
        this.ws.onmessage = (event) => {
            const data = JSON.parse(event.data);
            this.updateDashboard(data);
        };
    }

    updateDashboard(data) {
        Object.keys(data).forEach(metric => {
            const element = document.getElementById(`metric-${metric}`);
            if (element) {
                element.textContent = data[metric];
            }
        });
    }
}