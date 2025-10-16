@props(['labels', 'data'])

<div class="col-span-2 bg-white shadow rounded p-4">
    <div class="font-semibold mb-2">Monthly Orders - Last 6 Months Sales</div>
    <canvas id="ordersChart"></canvas>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('ordersChart').getContext('2d');
        const ordersChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($labels),
                datasets: [{
                    label: 'Orders',
                    data: @json($data),
                    backgroundColor: 'rgba(70, 130, 180, 0.7)',
                    borderColor: 'rgba(70, 130, 180, 1)',
                    borderWidth: 1,
                    barPercentage: 0.5
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 5 }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                }
            }
        });
    });
</script>