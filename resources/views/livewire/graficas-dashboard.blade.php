<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Gráfico de barras -->
    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-lg p-6">
        <h3 class="font-semibold mb-4 flex items-center gap-2">
            <i class="fas fa-chart-line text-indigo-500"></i> Flujo de Abonos (7 días)
        </h3>
        <canvas id="graficoPagos" class="w-full h-64"></canvas>
    </div>

    <!-- Gráfico de dona -->
    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-lg p-6">
        <h3 class="font-semibold mb-4 flex items-center gap-2">
            <i class="fas fa-chart-pie text-pink-500"></i> Estado de Créditos
        </h3>
        <canvas id="graficoCreditos" class="w-full h-64"></canvas>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Datos desde Livewire
            const pagosData = @json($graficoPagos);
            const creditosData = @json($estadoCreditos);

            // --- Gráfico de barras ---
            const ctx1 = document.getElementById('graficoPagos').getContext('2d');
            new Chart(ctx1, {
                type: 'bar',
                data: {
                    labels: pagosData.map(d => d.fecha),
                    datasets: [
                        {
                            label: 'Pagados',
                            data: pagosData.map(d => d.pagados),
                            backgroundColor: 'rgba(34, 197, 94, 0.7)', // verde
                        },
                        {
                            label: 'Esperados',
                            data: pagosData.map(d => d.esperados),
                            backgroundColor: 'rgba(239, 68, 68, 0.5)', // rojo
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'top' }
                    }
                }
            });

            // --- Gráfico de dona ---
            const ctx2 = document.getElementById('graficoCreditos').getContext('2d');
            new Chart(ctx2, {
                type: 'doughnut',
                data: {
                    labels: Object.keys(creditosData),
                    datasets: [{
                        data: Object.values(creditosData),
                        backgroundColor: [
                            'rgba(34,197,94,0.7)',   // verde activo
                            'rgba(59,130,246,0.7)', // azul pagado
                            'rgba(239,68,68,0.7)',  // rojo mora
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'bottom' }
                    }
                }
            });
        });
    </script>
</div>
