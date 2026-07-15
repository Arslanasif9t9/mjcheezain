@props(['labels', 'data'])

<div class="md:col-span-2 bg-white shadow-sm rounded-xl p-6 border border-gray-100">
    <div class="font-bold text-gray-800 mb-4 text-lg">Monthly Orders - Last 6 Months Sales</div>
    <div class="relative w-full h-[280px]">
        <canvas id="ordersChart"></canvas>
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