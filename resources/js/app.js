import Chart from 'chart.js/auto';

(async function() {
    let articlesChart = null;

    function renderChart(data) {
        console.log(data);
        const ctx = document.getElementById('articlesChart');
        if (!ctx) return;

        if (articlesChart) {
            articlesChart.destroy();
        }

        articlesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.labels,
                datasets: [
                    {
                        label: 'Articles per day',
                        data: data.counts,
                        borderColor: 'rgba(54, 162, 235, 1)',
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        fill: true,
                        tension: 0.3
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true, precision: 0 }
                }
            }
        });
    }

    Livewire.on('chartDataUpdated', chartData => {
        if (Array.isArray(chartData) && chartData.length === 1) {
            chartData = chartData[0];
        }
        renderChart(chartData);
    });

})();
