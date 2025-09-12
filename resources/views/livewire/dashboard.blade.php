<div>
    <main class="p-4 flex-1 space-y-6">
        <section class="grid grid-cols-1 md:grid-cols-5 gap-4">

            <!-- Clientes -->
            <div class="bg-gray-100 dark:bg-gray-800 rounded-2xl shadow p-4 flex flex-col">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm text-gray-600 dark:text-gray-400 flex items-center gap-2">
                        <i class="fas fa-users text-blue-500"></i> Clientes
                    </h3>
                    <span class="bg-blue-100 text-blue-700 text-xs px-2 py-0.5 rounded-full">
                        Activos
                    </span>
                </div>
                <p class="mt-3 text-2xl font-bold text-blue-600">{{ $clientesCount }}</p>
            </div>

            <!-- Carteras -->
            <div class="bg-gray-100 dark:bg-gray-800 rounded-2xl shadow p-4 flex flex-col">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm text-gray-600 dark:text-gray-400 flex items-center gap-2">
                        <i class="fas fa-briefcase text-purple-500"></i> Carteras
                    </h3>
                    <span class="bg-purple-100 text-purple-700 text-xs px-2 py-0.5 rounded-full">
                        Vigentes
                    </span>
                </div>
                <p class="mt-3 text-2xl font-bold text-purple-600">{{ $carterasCount }}</p>
            </div>

            <!-- Créditos -->
            <div class="bg-gray-100 dark:bg-gray-800 rounded-2xl shadow p-4 flex flex-col">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm text-gray-600 dark:text-gray-400 flex items-center gap-2">
                        <i class="fas fa-hand-holding-usd text-green-500"></i> Créditos
                    </h3>
                    <span class="bg-green-100 text-green-700 text-xs px-2 py-0.5 rounded-full">
                        En curso
                    </span>
                </div>
                <p class="mt-3 text-2xl font-bold text-green-600">{{ $creditosCount }}</p>
            </div>

            <!-- Roles -->
            <div class="bg-gray-100 dark:bg-gray-800 rounded-2xl shadow p-4 flex flex-col">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm text-gray-600 dark:text-gray-400 flex items-center gap-2">
                        <i class="fas fa-coins text-red-500"></i> Roles
                    </h3>
                    <span class="bg-red-100 text-red-700 text-xs px-2 py-0.5 rounded-full">
                        Activos
                    </span>
                </div>
                <p class="mt-3 text-2xl font-bold text-red-500"> {{$rolesCount }}</p>
            </div>

            <!-- Usuarios -->
            <div class="bg-gray-100 dark:bg-gray-800 rounded-2xl shadow p-4 flex flex-col">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm text-gray-600 dark:text-gray-400 flex items-center gap-2">
                        <i class="fas fa-user-shield text-indigo-500"></i> Usuarios
                    </h3>
                    <span class="bg-indigo-100 text-indigo-700 text-xs px-2 py-0.5 rounded-full">
                        Activos
                    </span>
                </div>
                <p class="mt-3 text-2xl font-bold text-indigo-600">{{ $usuariosCount }}</p>
            </div>
        </section>


        <section class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-gray-100 dark:bg-gray-800 rounded-2xl shadow p-4">
                <h3 class="font-semibold mb-2 flex items-center gap-2">
                    <i class="fas fa-calendar-alt text-blue-500"></i> Próximo Crédito a Vencer en 2 días
                </h3>
                @if($creditosPorVencer->isNotEmpty())
                <p><span class="font-bold">Cliente:</span> {{ $creditosPorVencer->first()->cliente->nombres }} {{
                    $creditosPorVencer->first()->cliente->apellidos }}</p>
                <p><span class="font-bold">Monto:</span> ₵ {{ number_format($creditosPorVencer->first()->monto_total, 2)
                    }}</p>
                <p><span class="font-bold">Fecha de pago de ultima cuota:</span>
                    <span class="bg-yellow-100 text-yellow-700 text-xs px-2 py-0.5 rounded-full">
                        {{ \Carbon\Carbon::parse($creditosPorVencer->first()->fecha_vencimiento)->format('d/m/Y') }}
                    </span>

                </p>
                @else
                <p class="text-gray-500">No hay créditos próximos a vencer en 2 días</p>
                @endif
            </div>

            <!-- Abonos recientes -->
            <div class="bg-gray-100 dark:bg-gray-800 rounded-2xl shadow p-4">
                <h3 class="font-semibold mb-2 flex items-center gap-2">
                    <i class="fas fa-check-circle text-green-500"></i> Abonos Recientes
                </h3>
                <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($abonosRecientes as $abono)
                    <li class="py-2 flex justify-between">
                        <span>{{ $abono->cliente->nombres }} {{ $abono->cliente->apellidos }}</span>
                        <span class="bg-green-100 text-green-700 text-xs px-2 py-0.5 rounded-full">
                            ₵ {{ number_format($abono->monto_abono, 2) }}
                        </span>
                    </li>
                    @empty
                    <li class="py-2 text-gray-500">Sin registros</li>
                    @endforelse
                </ul>
            </div>
        </section>

        <section class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">

            <!-- Créditos en Mora -->
            <div class="bg-gray-100 dark:bg-gray-800 rounded-2xl shadow p-4">
                <h3 class="font-semibold mb-2 flex items-center gap-2">
                    <i class="fas fa-exclamation-circle text-red-500"></i> Créditos en Mora
                </h3>
                <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($creditosMora->take(5) as $credito)
                    <li class="py-2 flex justify-between">
                        <span>{{ $credito->cliente->nombres }} {{ $credito->cliente->apellidos }}</span>
                        <span class="bg-red-100 text-red-700 text-xs px-2 py-0.5 rounded-full">
                            ₵ {{ number_format($credito->monto_total, 2) }}
                        </span>
                    </li>
                    @empty
                    <li class="py-2 text-gray-500">Sin créditos en mora</li>
                    @endforelse
                </ul>

                <button wire:click="$set('openModalMora', true)" class="mt-2 text-sm text-blue-500 hover:underline">
                    Ver todos
                </button>
            </div>

            <!-- Créditos por Vencer -->
            <div class="bg-gray-100 dark:bg-gray-800 rounded-2xl shadow p-4">
                <h3 class="font-semibold mb-2 flex items-center gap-2">
                    <i class="fas fa-clock text-yellow-500"></i> Créditos por Vencer
                </h3>
                <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($creditosPorVencer->take(5) as $credito)
                    <li class="py-2 flex justify-between">
                        <span>{{ $credito->cliente->nombres }} {{ $credito->cliente->apellidos }}</span>
                        <span class="bg-yellow-100 text-yellow-700 text-xs px-2 py-0.5 rounded-full">
                            Vence: {{ \Carbon\Carbon::parse($credito->fecha_vencimiento)->format('d/m/Y') }}
                        </span>
                    </li>
                    @empty
                    <li class="py-2 text-gray-500">Sin créditos por vencer</li>
                    @endforelse
                </ul>

                <button wire:click="$set('openModalVencer', true)" class="mt-2 text-sm text-blue-500 hover:underline">
                    Ver todos
                </button>
            </div>

        </section>


        <!-- Indicadores principales -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            <!-- Créditos Activos -->
            <div
                class="bg-gradient-to-r from-green-50 to-green-100 dark:from-green-900 dark:to-green-800 rounded-2xl shadow-lg p-5 flex flex-col">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm text-green-700 dark:text-green-300 flex items-center gap-2">
                        <i class="fas fa-hand-holding-usd text-green-500"></i> Créditos Activos
                    </h3>
                </div>
                <p class="mt-3 text-3xl font-bold text-green-600 dark:text-green-400">
                    {{ $creditosCount }}
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Actualmente en gestión</p>
            </div>

            <!-- Total Abonos -->
            <div
                class="bg-gradient-to-r from-blue-50 to-blue-100 dark:from-blue-900 dark:to-blue-800 rounded-2xl shadow-lg p-5 flex flex-col">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm text-blue-700 dark:text-blue-300 flex items-center gap-2">
                        <i class="fas fa-coins text-blue-500"></i> Total Abonos
                    </h3>
                </div>
                <p class="mt-3 text-3xl font-bold text-blue-600 dark:text-blue-400">
                    ₵ {{ number_format($abonosTotal, 2) }}
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Ingresos recibidos</p>
            </div>

            <!-- Créditos en Mora -->
            <div
                class="bg-gradient-to-r from-red-50 to-red-100 dark:from-red-900 dark:to-red-800 rounded-2xl shadow-lg p-5 flex flex-col">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm text-red-700 dark:text-red-300 flex items-center gap-2">
                        <i class="fas fa-exclamation-triangle text-red-500"></i> Créditos en Mora
                    </h3>
                </div>
                <p class="mt-3 text-3xl font-bold text-red-600 dark:text-red-400">
                    {{ $creditosMoraTotal }}
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Clientes con deuda vencida</p>
            </div>

        </div>


        <div>



        </div>

        <!-- Modal Créditos en Mora -->
        @if($openModalMora)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg w-11/12 md:w-2/3 max-h-[80vh] overflow-auto p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Créditos en Mora</h2>
                    <button wire:click="$set('openModalMora', false)"
                        class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">&times;</button>
                </div>

                <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($creditosMoraPaginated as $credito)
                    <li class="py-2 flex justify-between">
                        <span>{{ $credito->cliente->nombres }} {{ $credito->cliente->apellidos }}</span>
                        <span class="bg-red-100 text-red-700 text-xs px-2 py-0.5 rounded-full">
                            ₵ {{ number_format($credito->monto_total, 2) }}
                        </span>
                    </li>
                    @empty
                    <li class="py-2 text-gray-500">Sin créditos en mora</li>
                    @endforelse
                </ul>

                <div class="mt-4">
                    {{ $creditosMoraPaginated->links() }}
                </div>

                <div class="mt-4 flex justify-end">
                    <button wire:click="$set('openModalMora', false)"
                        class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600">Cerrar</button>
                </div>
            </div>
        </div>
        @endif

        <!-- Modal Créditos por Vencer -->
        @if($openModalVencer)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg w-11/12 md:w-2/3 max-h-[80vh] overflow-auto p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Créditos por Vencer</h2>
                    <button wire:click="$set('openModalVencer', false)"
                        class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">&times;</button>
                </div>

                <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($creditosPorVencerPaginated as $credito)
                    <li class="py-2 flex justify-between">
                        <span>{{ $credito->cliente->nombres }} {{ $credito->cliente->apellidos }}</span>
                        <span class="bg-yellow-100 text-yellow-700 text-xs px-2 py-0.5 rounded-full">
                            Vence: {{ \Carbon\Carbon::parse($credito->fecha_vencimiento)->format('d/m/Y') }}
                        </span>
                    </li>
                    @empty
                    <li class="py-2 text-gray-500">Sin créditos por vencer</li>
                    @endforelse
                </ul>

                <div class="mt-4">
                    {{ $creditosPorVencerPaginated->links() }}
                </div>

                <div class="mt-4 flex justify-end">
                    <button wire:click="$set('openModalVencer', false)"
                        class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600">Cerrar</button>
                </div>
            </div>
        </div>
        @endif


    </main>
</div>