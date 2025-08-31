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

        <!-- Próximo crédito -->
        <section class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-gray-100 dark:bg-gray-800 rounded-2xl shadow p-4">
                <h3 class="font-semibold mb-2 flex items-center gap-2">
                    <i class="fas fa-calendar-alt text-blue-500"></i> Próximo Crédito
                </h3>
                @if($proximoCredito)
                    <p><span class="font-bold">Cliente:</span> {{ $proximoCredito->cliente->nombres    }} {{ $proximoCredito->cliente->apellidos    }}</p>
                    <p><span class="font-bold">Monto:</span> ₵ {{ number_format($proximoCredito->monto_total, 2) }}</p>
                    <p><span class="font-bold">Fecha de pago de ultima cuota:</span>
                     <span class="bg-yellow-100 text-yellow-700 text-xs px-2 py-0.5 rounded-full">
                            {{ \Carbon\Carbon::parse($proximoCredito->fecha_vencimiento)->format('d/m/Y') }}
                        </span>

                    </p>
                @else
                    <p class="text-gray-500">No hay créditos próximos</p>
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
                            <span>{{ $abono->cliente->nombres }}  {{ $abono->cliente->apellidos }}</span>
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

        <!-- Créditos pendientes -->
        <section class="grid grid-cols-1">
            <div class="bg-gray-100 dark:bg-gray-800 rounded-2xl shadow p-4">
                <h3 class="font-semibold mb-2 flex items-center gap-2">
                    <i class="fas fa-exclamation-circle text-red-500"></i> Créditos Pendientes
                </h3>
                <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($creditosPendientes as $credito)
                        <li class="py-2 flex justify-between">
                            <span>{{ $credito->cliente->nombres }}  {{ $credito->cliente->apellidos }}</span>
                            <span class="bg-red-100 text-red-700 text-xs px-2 py-0.5 rounded-full">
                                ₵ {{ number_format($credito->monto_total, 2) }}
                            </span>
                        </li>
                    @empty
                        <li class="py-2 text-gray-500">Sin créditos pendientes</li>
                    @endforelse
                </ul>
            </div>
        </section>
    </main>
</div>
