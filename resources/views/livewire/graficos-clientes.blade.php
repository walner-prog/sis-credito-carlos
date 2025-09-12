<div class="bg-white dark:bg-gray-900 rounded-2xl shadow-lg p-6">
    <h3 class="font-semibold mb-4 flex items-center gap-2 text-gray-800 dark:text-gray-100">
        <i class="fas fa-user-friends text-indigo-500"></i> Crecimiento de Clientes ({{ now()->year }})
    </h3>
    <canvas id="graficoClientes" class="w-full h-64"></canvas>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const clientesData = @json($graficoClientes);

            // Detectar colores según modo
            const styles = getComputedStyle(document.documentElement);
            const textColor = document.documentElement.classList.contains("dark")
                ? styles.getPropertyValue("--tw-prose-invert-body") || "#d1d5db"
                : "#374151"; // gris Tailwind

            const gridColor = document.documentElement.classList.contains("dark")
                ? "#374151"
                : "#e5e7eb";

            const ctx = document.getElementById('graficoClientes').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: clientesData.labels,
                    datasets: [{
                        label: 'Nuevos Clientes',
                        data: clientesData.data,
                        borderColor: '#4F46E5',
                        backgroundColor: 'rgba(79,70,229,0.2)',
                        tension: 0.4,
                        fill: true,
                        pointRadius: 5,
                        pointBackgroundColor: '#4F46E5'
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        x: {
                            ticks: { color: textColor },
                            grid: { color: gridColor }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: { color: textColor },
                            grid: { color: gridColor }
                        }
                    }
                }
            });
        });
    </script>
</div>
