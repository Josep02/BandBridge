import { Controller } from "@hotwired/stimulus";
import { Chart, registerables } from 'chart.js';

Chart.register(...registerables);

export default class extends Controller {
    static targets = ["canvas"];

    connect() {
        fetch('/api/chart')
            .then(response => response.json())
            .then(data => {
                this.renderChart(data.labels, data.data);
            });
    }

    renderChart(labels, data) {
        const ctx = this.canvasTarget.getContext('2d');

        const chartData = {
            labels: labels,
            datasets: [{
                label: 'Events per Month',
                data: data,
                fill: false,
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1
            }]
        };

        const config = {
            type: 'line',
            data: chartData,
            options: {}
        };

        new Chart(ctx, config);
    }
}