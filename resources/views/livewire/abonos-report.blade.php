<div
    class="p-6 lg:p-12 bg-gradient-to-r from-green-50 via-blue-50 to-purple-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 min-h-screen text-gray-900 dark:text-gray-100">

    <div
        class="p-4 bg-gradient-to-r from-green-50 via-blue-50 to-purple-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 min-h-screen text-gray-900 dark:text-gray-100 sm:p-6 lg:p-12">

        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-4">
                <i class="fas fa-chart-line mr-2 text-blue-500"></i>
                Resumen de Abonos del Día
            </h1>

            <div x-data="{ openSearch: false, openOptions: false }" class="lg:hidden mb-4">
                <div class="flex items-center space-x-2 mb-4">
                    <button @click="openSearch = !openSearch"
                        class="flex-shrink-0 p-2 rounded-full text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-700 shadow-sm">
                        <i class="fas fa-search"></i>
                    </button>
                    <div x-show="openSearch" x-collapse.duration.300ms class="relative flex-1">
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar cliente..."
                            class="w-full border rounded-lg p-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm pr-10">
                        @if ($search)
                        <button wire:click="resetSearch"
                            class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300">
                            <i class="fas fa-times-circle"></i>
                        </button>
                        @endif
                    </div>

                    <button @click="openOptions = !openOptions"
                        class="flex-shrink-0 p-2 rounded-lg text-sm bg-blue-500 text-white font-semibold shadow-sm flex items-center gap-2">
                        <i class="fas fa-filter"></i>
                        <span>Filtros</span>
                    </button>
                </div>

                <div x-show="openOptions" x-collapse.duration.300ms>
                    <div class="flex flex-wrap gap-3 mb-4">
                        <div class="flex-1 min-w-[120px]">
                            <label for="filtroDia" class="sr-only">Filtrar por pago</label>
                            <select id="filtroDia" wire:model.live="filtroDia"
                                class="w-full border rounded-lg p-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm">
                                <option value="">-- Pagos --</option>
                                <option value="abonaron">Pagaron</option>
                                <option value="no_abonaron">No Pagaron</option>
                            </select>
                        </div>
                        <div class="flex-1 min-w-[120px]">
                            <label for="carteraId" class="sr-only">Filtrar por cartera</label>
                            <select id="carteraId" wire:model.live="carteraId"
                                class="w-full border rounded-lg p-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm">
                                <option value="">-- carteras --</option>
                                @foreach($carteras as $cartera)
                                <option value="{{ $cartera->id }}">{{ $cartera->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex-1 min-w-[120px]">
                            <label for="filtroEstadoCredito" class="sr-only">Filtrar por estado</label>
                            <select id="filtroEstadoCredito" wire:model.live="filtroEstadoCredito"
                                class="w-full border rounded-lg p-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm">
                                <option value="">-- Estados --</option>
                                <option value="activo">Activo</option>
                                <option value="moroso">Moroso</option>
                            </select>
                        </div>
                    </div>

                    <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow-md mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-3">
                            <i class="fas fa-coins mr-2 text-yellow-500"></i>Resumen del Día
                        </h3>

                        <div class="grid grid-cols-3 gap-2 text-center">
                            <div class="flex flex-col items-center p-2 bg-green-50 dark:bg-green-900 rounded-md">
                                <i class="fas fa-dollar-sign text-green-600 dark:text-green-300 text-lg mb-1"></i>
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-300">Total Abonado</p>
                                <p class="text-base font-bold text-gray-900 dark:text-gray-100">C$ {{
                                    number_format($this->clientesFiltrados['totalAbonado'], 2) }}</p>
                            </div>

                            <div class="flex flex-col items-center p-2 bg-blue-50 dark:bg-blue-900 rounded-md">
                                <i class="fas fa-check-circle text-blue-600 dark:text-blue-300 text-lg mb-1"></i>
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-300">Abonaron</p>
                                <p class="text-base font-bold text-gray-900 dark:text-gray-100">{{
                                    $this->clientesFiltrados['clientesAbonaron'] }}</p>
                            </div>

                            <div class="flex flex-col items-center p-2 bg-red-50 dark:bg-red-900 rounded-md">
                                <i class="fas fa-times-circle text-red-600 dark:text-red-300 text-lg mb-1"></i>
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-300">No Abonaron</p>
                                <p class="text-base font-bold text-gray-900 dark:text-gray-100">{{
                                    $this->clientesFiltrados['clientesNoAbonaron'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="hidden lg:flex flex-wrap gap-3 mb-4">
                <div class="flex-1 min-w-[120px]">
                    <label for="filtroDia" class="sr-only">Filtrar por pago</label>
                    <select id="filtroDia" wire:model.live="filtroDia"
                        class="w-full border rounded-lg p-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm">
                        <option value="">-- Pagos --</option>
                        <option value="abonaron">Pagaron</option>
                        <option value="no_abonaron">No Pagaron</option>
                    </select>
                </div>
                <div class="flex-1 min-w-[120px]">
                    <label for="carteraId" class="sr-only">Filtrar por cartera</label>
                    <select id="carteraId" wire:model.live="carteraId"
                        class="w-full border rounded-lg p-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm">
                        <option value="">-- carteras --</option>
                        @foreach($carteras as $cartera)
                        <option value="{{ $cartera->id }}">{{ $cartera->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1 min-w-[120px]">
                    <label for="filtroEstadoCredito" class="sr-only">Filtrar por estado</label>
                    <select id="filtroEstadoCredito" wire:model.live="filtroEstadoCredito"
                        class="w-full border rounded-lg p-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm">
                        <option value="">-- Estados --</option>
                        <option value="activo">Activo</option>
                        <option value="moroso">Moroso</option>
                    </select>
                </div>
                <div class="relative flex-1 min-w-[200px]">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar cliente..."
                        class="w-full border rounded-lg p-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm pr-10">
                    @if ($search)
                    <button wire:click="resetSearch"
                        class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300">
                        <i class="fas fa-times-circle"></i>
                    </button>
                    @endif
                </div>
            </div>

            <div class="hidden lg:grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div class="flex items-center justify-between p-3 bg-green-100 dark:bg-green-900 rounded-lg shadow-md">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-300">Total Abonado</p>
                        <p class="text-2xl font-bold">C$ {{ number_format($this->clientesFiltrados['totalAbonado'], 2)
                            }}</p>
                    </div>
                    <i class="fas fa-dollar-sign text-green-600 dark:text-green-300 text-2xl"></i>
                </div>
                <div class="flex items-center justify-between p-3 bg-blue-100 dark:bg-blue-900 rounded-lg shadow-md">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-300">Clientes que Abonaron</p>
                        <p class="text-2xl font-bold">{{ $this->clientesFiltrados['clientesAbonaron'] }}</p>
                    </div>
                    <i class="fas fa-check-circle text-blue-600 dark:text-blue-300 text-2xl"></i>
                </div>
                <div class="flex items-center justify-between p-3 bg-red-100 dark:bg-red-900 rounded-lg shadow-md">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-300">Clientes que NO Abonaron</p>
                        <p class="text-2xl font-bold">{{ $this->clientesFiltrados['clientesNoAbonaron'] }}</p>
                    </div>
                    <i class="fas fa-times-circle text-red-600 dark:text-red-300 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Tabla Clientes -->
        <div class="space-y-4 lg:hidden">
            @forelse($this->clientesFiltrados['clientes'] as $cliente)
            @foreach($cliente->detalle_abonos as $abono)
            <div x-data="{ open: false }"
                class="rounded-xl shadow-md overflow-hidden transition-all duration-300 bg-white dark:bg-gray-700
                {{ $abono->estado === 'Pagó Hoy' ? 'border-l-4 border-green-500' : 'border-l-4 border-gray-400 dark:border-gray-600' }}">

                <div class="p-4 flex justify-between items-center cursor-pointer" @click="open = !open">
                    <div>
                        <p class="font-bold text-gray-900 dark:text-gray-100">{{ $abono->cliente }}</p>
                        <div class="flex items-center text-sm mt-1 p-1 space-x-2">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold
                        {{ $abono->estado === 'Pagó Hoy' ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">
                                {{ $abono->estado }}
                            </span>
                            <span
                                class="px-2 py-1 rounded-full text-xs font-semibold
                        {{ $abono->estado_credito === 'activo' ? 'bg-blue-500 text-white' : 'bg-red-500 text-white' }}">
                                {{ ucfirst($abono->estado_credito) }}
                            </span>
                        </div>
                    </div>
                    <i class="fas fa-chevron-down transform transition-transform duration-300 text-gray-400"
                        :class="{ 'rotate-180': open }"></i>
                </div>

                <div x-show="open" x-collapse.duration.400ms class="p-4 border-t border-gray-200 dark:border-gray-600">
                    <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                        <li class="flex items-center space-x-2">
                            <i class="fas fa-user-circle text-gray-500"></i>
                            <span><strong class="font-semibold">Registrado por:</strong> {{ $abono->user ?? '-'
                                }}</span>
                        </li>
                        <li class="flex items-center space-x-2">
                            <i class="fas fa-money-bill-wave text-gray-500"></i>
                            <span><strong class="font-semibold">Monto Abonado:</strong> C$ {{
                                number_format($abono->monto_abono, 2) }}</span>
                        </li>
                        <li class="flex items-center space-x-2">
                            <i class="fas fa-credit-card text-gray-500"></i>
                            <span><strong class="font-semibold">Monto Crédito:</strong> C$ {{
                                number_format($abono->monto_credito, 2) }}</span>
                        </li>
                        <li class="flex items-center space-x-2">
                            <i class="fas fa-calendar-alt text-gray-500"></i>
                            <span><strong class="font-semibold">Fecha:</strong> {{ $abono->fecha_abono ?
                                \Carbon\Carbon::parse($abono->fecha_abono)->format('d/m/Y') : '-' }}</span>
                        </li>
                        @if($abono->comentarios)
                        <li class="flex items-start space-x-2">
                            <i class="fas fa-comment-dots text-gray-500 mt-1"></i>
                            <span><strong class="font-semibold">Comentarios:</strong> {{ $abono->comentarios }}</span>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
            @endforeach
            @empty
            <p class="text-center text-gray-500 dark:text-gray-400">No hay resultados</p>
            @endforelse
        </div>

        <div class="hidden lg:block overflow-x-auto">
            <table class="w-full border-collapse border border-gray-300 dark:border-gray-600">
                <thead class="bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
                    <tr>
                        <th class="border p-2 text-left">Abono Registrado Por</th>
                        <th class="border p-2 text-left">Cliente</th>
                        <th class="border p-2 text-right">Monto del Crédito (C$)</th>
                        <th class="border p-2 text-right">Monto del Abono (C$)</th>
                        <th class="border p-2 text-center">Estado del Abono</th>
                        <th class="border p-2 text-center">Estado del Crédito</th>
                        <th class="border p-2 text-center">Fecha del Abono</th>
                        <th class="border p-2 text-left">Comentarios</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($this->clientesFiltrados['clientes'] as $cliente)
                    @foreach($cliente->detalle_abonos as $abono)
                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors 
                {{ $abono->estado === 'Pagó Hoy' ? 'border-l-4 border-green-500' : 'border-l-4 border-red-500' }}">
                        <td class="p-2">{{ $abono->user ?? '-' }}</td>
                        <td class="p-2">{{ $abono->cliente }}</td>
                        <td class="p-2 text-right">C$ {{ number_format($abono->monto_credito, 2) }}</td>
                        <td class="p-2 text-right">C$ {{ number_format($abono->monto_abono, 2) }}</td>
                        <td class="p-2 text-center">
                            @if ($abono->estado === 'Pagó Hoy')
                            <span class="bg-green-500 text-white text-xs font-bold px-2.5 py-1 rounded-full">
                                Pagó Hoy
                            </span>
                            @else
                            <span class="bg-red-500 text-white text-xs font-bold px-2.5 py-1 rounded-full">
                                No Pagó Hoy
                            </span>
                            @endif
                        </td>
                        <td class="p-2 text-center">
                            @if ($abono->estado_credito === 'activo')
                            <span class="bg-blue-500 text-white text-xs font-bold px-2.5 py-1 rounded-full">
                                Activo
                            </span>
                            @else
                            <span class="bg-red-500 text-white text-xs font-bold px-2.5 py-1 rounded-full">
                                Moroso
                            </span>
                            @endif
                        </td>
                        <td class="p-2 text-center">
                            {{ $abono->fecha_abono ? \Carbon\Carbon::parse($abono->fecha_abono)->format('M d, Y') : '-'
                            }}
                        </td>
                        <td class="p-2">{{ $abono->comentarios ?? '' }}</td>
                    </tr>
                    @endforeach
                    @empty
                    <tr>
                        <td colspan="8" class="text-center p-4">No hay resultados</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>


        <div class="mt-4">
            {{ $this->clientesFiltrados['clientes']->links() }}
        </div>
    </div>
</div>