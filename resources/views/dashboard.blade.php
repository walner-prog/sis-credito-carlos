<x-app-layout>


    <div class=" from-blue-50 via-purple-50 to-pink-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 min-h-screen flex flex-col">



       <livewire:dashboard />

   <main class="p-6 flex-1 space-y-6">

    <!-- Indicadores y gráfico en 2 columnas -->
 <section class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    <!-- Gráfico de Crecimiento de Clientes -->
    <livewire:graficos-clientes />

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
        <livewire:graficas-dashboard />
    </div>

</section>

 
</main>


        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

 


    </div>

 
</x-app-layout>

