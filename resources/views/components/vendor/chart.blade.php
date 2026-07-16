@props(['labels', 'data'])

<div class="md:col-span-2 app-card p-4 md:p-6 min-w-0 overflow-hidden">
    <div class="font-bold text-gray-800 mb-4 text-base md:text-lg">Monthly Orders - Last 6 Months Sales</div>
    <div class="relative w-full max-w-full h-[220px] md:h-[280px] overflow-hidden">
        <canvas id="ordersChart" class="max-w-full"></canvas>
    </div>
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
                    backgroundColor: 'rgba(255, 125, 160, 0.75)',
                    borderColor: '#E85D85',
                    borderWidth: 1,
                    barPercentage: 0.5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
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