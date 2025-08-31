<x-app-layout>


    <div class=" from-blue-50 via-purple-50 to-pink-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 min-h-screen flex flex-col">


       <livewire:dashboard />

   <main class="p-6 flex-1 space-y-6">

    <!-- Indicadores y gráfico en 2 columnas -->
    <section class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Indicadores del Cliente -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-gradient-to-r from-green-50 to-green-100 dark:from-green-900 dark:to-green-800 rounded-2xl shadow-lg p-5 flex flex-col">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm text-green-700 dark:text-green-300 flex items-center gap-2">
                        <i class="fas fa-hand-holding-usd text-green-500"></i> Créditos Activos
                    </h3>
                </div>
                <p class="mt-3 text-3xl font-bold text-green-600 dark:text-green-400">₵ 0.00</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1"></p>
            </div>

            <div class="bg-gradient-to-r from-blue-50 to-blue-100 dark:from-blue-900 dark:to-blue-800 rounded-2xl shadow-lg p-5 flex flex-col">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm text-blue-700 dark:text-blue-300 flex items-center gap-2">
                        <i class="fas fa-coins text-blue-500"></i> Total Abonos
                    </h3>
                </div>
                <p class="mt-3 text-3xl font-bold text-blue-600 dark:text-blue-400">₵ 0.00</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1"></p>
            </div>

            <div class="bg-gradient-to-r from-purple-50 to-purple-100 dark:from-purple-900 dark:to-purple-800 rounded-2xl shadow-lg p-5 flex flex-col">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm text-purple-700 dark:text-purple-300 flex items-center gap-2">
                        <i class="fas fa-calendar-alt text-purple-500"></i> Próximo Pago
                    </h3>
                </div>
                <p class="mt-3 text-3xl font-bold text-purple-600 dark:text-purple-400">₵ 0.00</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1"></p>
            </div>
        </div>

        <!-- Gráfico de Créditos / Abonos -->
        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-lg p-6 flex flex-col">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-semibold flex items-center gap-2">
                    <i class="fas fa-chart-line text-indigo-500"></i> Historial de Pagos
                </h3>
                <div class="flex gap-2">
                    <button class="px-3 py-1 text-sm bg-gray-100 dark:bg-gray-800 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 transition">12m</button>
                    <button class="px-3 py-1 text-sm bg-gray-100 dark:bg-gray-800 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 transition">30d</button>
                    <button class="px-3 py-1 text-sm bg-gray-100 dark:bg-gray-800 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 transition">7d</button>
                </div>
            </div>
            <canvas id="graficoPagos" class="w-full h-64 md:h-full"></canvas>
        </div>

    </section>

    <!-- Lista de Movimientos -->
    <section class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- Créditos del Cliente -->
        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-lg p-5">
            <h3 class="font-semibold mb-2 flex items-center gap-2">
                <i class="fas fa-file-invoice-dollar text-green-500"></i> Créditos
            </h3>
            <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                <li class="py-2 flex justify-between">
                    <span>Crédito #123</span>
                    <span class="bg-green-100 text-green-700 dark:bg-green-800 dark:text-green-200 text-xs px-2 py-0.5 rounded-full">
                        ₵ 10,000
                    </span>
                </li>
                <li class="py-2 flex justify-between">
                    <span>Crédito #124</span>
                    <span class="bg-green-100 text-green-700 dark:bg-green-800 dark:text-green-200 text-xs px-2 py-0.5 rounded-full">
                        ₵ 2,500
                    </span>
                </li>
            </ul>
        </div>

        <!-- Abonos del Cliente -->
        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-lg p-5">
            <h3 class="font-semibold mb-2 flex items-center gap-2">
                <i class="fas fa-wallet text-blue-500"></i> Abonos
            </h3>
            <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                <li class="py-2 flex justify-between">
                    <span>20/08/2025</span>
                    <span class="bg-blue-100 text-blue-700 dark:bg-blue-800 dark:text-blue-200 text-xs px-2 py-0.5 rounded-full">
                        ₵ 1,000
                    </span>
                </li>
                <li class="py-2 flex justify-between">
                    <span>15/08/2025</span>
                    <span class="bg-blue-100 text-blue-700 dark:bg-blue-800 dark:text-blue-200 text-xs px-2 py-0.5 rounded-full">
                        ₵ 800
                    </span>
                </li>
            </ul>
        </div>

    </section>
</main>


        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const ctx = document.getElementById('graficoPagos').getContext('2d');

            // Crear gradiente
            const gradient = ctx.createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, 'rgba(99, 102, 241, 0.4)'); // Indigo-500
            gradient.addColorStop(1, 'rgba(255, 255, 255, 0)');

            new Chart(ctx, {
                type: 'line'
                , data: {
                    labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul']
                    , datasets: [{
                        label: 'Abonos'
                        , data: [500, 700, 800, 1200, 900, 1500, 1000]
                        , borderColor: '#6366F1'
                        , backgroundColor: gradient
                        , tension: 0.4
                        , fill: true
                        , pointRadius: 0
                    }]
                }
                , options: {
                    responsive: true
                    , plugins: {
                        legend: {
                            display: false
                        }
                    }
                    , scales: {
                        x: {
                            grid: {
                                display: false
                            }
                        }
                        , y: {
                            grid: {
                                color: 'rgba(0,0,0,0.05)'
                            }
                        }
                    }
                }
            });

        </script>



    </div>

 
</x-app-layout>

