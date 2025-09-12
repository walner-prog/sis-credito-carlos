<div class="p-6 lg:p-12 bg-gradient-to-r from-green-50 via-blue-50 to-purple-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 min-h-screen">
    <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-6">Gestión de Abonos</h2>
    <div class="flex flex-col sm:flex-row justify-between items-center gap-4">

        {{-- Botón Nuevo Abono --}}
        <div class="flex flex-col sm:flex-row gap-4 flex-wrap w-full sm:w-auto">
            <button wire:click="abrirModalCrear" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl shadow-md transition w-full sm:w-auto">
                + Nuevo Abono
            </button>


        </div>

        {{-- Botón Reporte --}}
        <div class="flex flex-col sm:flex-row gap-4 flex-wrap w-full sm:w-auto">
            <button wire:click="abrirModalReporte" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-xl shadow-md transition w-full sm:w-auto">
                Generar Reporte
            </button>
        </div>

        {{-- Buscador móvil --}}
        <div class="relative w-full sm:hidden">
            @if($buscarAbierto)
            <input type="text" wire:model.live="search" placeholder="Buscar por crédito o cliente..." class="block border border-gray-300 dark:border-gray-700 rounded-lg px-3 pr-10 py-2 w-full dark:bg-gray-800 dark:text-gray-200 focus:ring focus:ring-blue-400 mt-2">
            <button wire:click="$set('buscarAbierto', false); $set('search', '')" class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition">
                <i class="fas fa-times"></i>
            </button>
            @else
            <button wire:click="$set('buscarAbierto', true)" class="text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-gray-100 transition">
                <i class="fas fa-search text-xl"></i>
            </button>
            @endif
        </div>

        {{-- Buscador escritorio --}}
        <div class="relative hidden sm:block w-full sm:w-1/3">
            <input type="text" wire:model.live="search" placeholder="Buscar por crédito o cliente..." class="border border-gray-300 dark:border-gray-700 rounded-lg px-3 pr-10 py-2 w-full dark:bg-gray-800 dark:text-gray-200 focus:ring focus:ring-blue-400 mt-2 sm:mt-0">
            <button wire:click="$set('search', '')" class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition">
                <i class="fas fa-times"></i>
            </button>
        </div>

    </div>


    {{-- Notificaciones --}}
    @if (session()->has('create'))
    <div class="session-message bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 p-3 mt-4 rounded-lg shadow-md">
        {{ session('create') }}
    </div>
    @endif
    @if (session()->has('update'))
    <div class="session-message bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-200 p-3 mt-4 rounded-lg shadow-md">
        {{ session('update') }}
    </div>
    @endif
    @if (session()->has('delete'))
    <div class="session-message bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 p-3 mt-4 rounded-lg shadow-md">
        {{ session('delete') }}
    </div>
    @endif

    {{-- Tabla de Abonos --}}
    <div class="mt-6">
        {{-- Vista de Escritorio (oculto en pantallas pequeñas) --}}
        <div class="hidden md:block">
            <table class="w-full border-collapse rounded-lg overflow-hidden shadow-md">
                <thead>
                    <tr class="bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                        <th class="p-3 text-left">Crédito / Cliente</th>
                        <th class="p-3 text-left">Monto Abonado</th>
                        <th class="p-3 text-left">Fecha</th>
                        <th class="p-3 text-left">Comentarios</th>
                        <th class="p-3 text-left">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800">
                    @forelse ($abonos as $abono)
                    <tr wire:key="abono-{{ $abono->id }}" class="border-b dark:border-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-800 transition">
                        <td class="p-3">
                            Crédito #{{ optional($abono->credito)->id ?? 'N/A' }} -
                            <span class="font-semibold">{{ optional(optional($abono->credito)->cliente)->nombres ??
                            'Cliente no encontrado' }} {{ optional(optional($abono->credito)->cliente)->apellidos ??
                            '' }}</span>
                        </td>
                        <td class="p-3">C$ {{ number_format($abono->monto_abono, 2) }}</td>
                        <td class="p-3">{{ \Carbon\Carbon::parse($abono->fecha_abono)->format('d/m/Y') }}</td>
                        <td class="p-3">{{ $abono->comentarios ?? '-' }}</td>
                        <td class="p-3 flex gap-2">
                            <button wire:click="abrirModalEditar({{ $abono->id }})" class="bg-yellow-500 hover:bg-yellow-600 text-white p-2 rounded-full shadow-lg hover:scale-110 transition-transform duration-200 flex items-center justify-center" title="Editar">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button wire:click="abrirModalVer({{ $abono->id }})" class="bg-indigo-500 hover:bg-indigo-600 text-white p-2 rounded-full shadow-lg hover:scale-110 transition-transform duration-200 flex items-center justify-center" title="Detalles">
                                <i class="fas fa-eye"></i>
                            </button>

                            <button wire:click="confirmarEliminar({{ $abono->id }})" class="bg-red-600 hover:bg-red-700 text-white p-2 rounded-full shadow-lg hover:scale-110 transition-transform duration-200 flex items-center justify-center" title="Eliminar">
                                <i class="fas fa-trash-alt"></i>
                            </button>

                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-4 text-center text-gray-500 dark:text-gray-400">No se encontraron
                            abonos.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>



        {{-- Vista de Móvil (visible solo en pantallas pequeñas) --}}
        <div class="block md:hidden space-y-4">
            @forelse ($abonos as $abono)
            <div wire:key="abono-card-{{ $abono->id }}" class="bg-white dark:bg-gray-800 shadow-md rounded-xl p-4 flex items-center justify-between relative">

                {{-- Contenido principal --}}
                <div class="flex-1 min-w-0 grid grid-cols-2 gap-y-2 gap-x-4">
                    {{-- Columna 1 --}}
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-money-bill-wave text-green-500"></i>
                        <div class="text-sm">
                            <span class="font-medium text-gray-500 dark:text-gray-400">Monto:</span>
                            <span class="font-bold text-gray-900 dark:text-gray-200 block sm:inline">
                                C$ {{ number_format($abono->monto_abono, 2) }}
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center space-x-2">
                        <i class="fas fa-calendar-alt text-gray-500 dark:text-gray-400"></i>
                        <div class="text-sm">
                            <span class="font-bold text-gray-900 dark:text-gray-200 block sm:inline">
                                {{ \Carbon\Carbon::parse($abono->fecha_abono)->format('d/m/Y') }}
                            </span>
                        </div>
                    </div>

                    {{-- Columna 2 --}}
                    <div class="col-span-2 flex items-center space-x-2">
                        <i class="fas fa-user-circle text-blue-500"></i>
                        <div class="text-sm truncate">
                            <span class="font-medium text-gray-500 dark:text-gray-400">Cliente:</span>
                            <span class="font-bold text-gray-900 dark:text-gray-200 truncate">
                                {{ optional(optional($abono->credito)->cliente)->nombres ?? 'Cliente no encontrado' }}
                                {{ optional(optional($abono->credito)->cliente)->apellidos ?? '' }}
                            </span>
                        </div>
                    </div>

                    <div class="col-span-2 flex items-center space-x-2">
                        <i class="fas fa-credit-card text-purple-500"></i>
                        <div class="text-sm">
                            <span class="font-medium text-gray-500 dark:text-gray-400">Crédito:</span>
                            <span class="font-bold text-gray-900 dark:text-gray-200">
                                #{{ optional($abono->credito)->id ?? 'N/A' }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Menú de acciones (solo Livewire) --}}
                <div class="relative z-20 flex-shrink-0 ml-4">
                    <button wire:click="toggleAcciones({{ $abono->id }})" type="button" class="text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700 p-2 rounded-full">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>

                    @if($openAcciones[$abono->id] ?? false)
                    <div class="absolute right-0 mt-2 w-40 bg-white dark:bg-gray-700 rounded-md shadow-lg py-1 z-20">
                        <button wire:click.stop.prevent="abrirModalEditar({{ $abono->id }})" class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">
                            <i class="fas fa-edit mr-2 text-yellow-500"></i> Editar
                        </button>
                        <button wire:click.stop.prevent="abrirModalVer({{ $abono->id }})" class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">
                            <i class="fas fa-eye mr-2 text-indigo-500"></i> Detalles
                        </button>
                        @role('Administrador')
                        <button wire:click.stop.prevent="confirmarEliminar({{ $abono->id }})" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-100 dark:hover:bg-gray-600">
                            <i class="fas fa-trash-alt mr-2"></i> Eliminar
                        </button>
                        @endrole
                    </div>
                    @endif
                </div>
            </div>
            @empty
            <div class="p-4 text-center text-gray-500 dark:text-gray-400">
                No se encontraron abonos.
            </div>
            @endforelse
        </div>

    </div>

    {{-- Paginación --}}
    <div class="mt-4 flex flex-col sm:flex-row justify-between items-center text-sm text-gray-600 dark:text-gray-400">
        <div>
            Mostrando {{ $abonos->firstItem() }} a {{ $abonos->lastItem() }} de {{ $abonos->total() }} resultados
        </div>
        <div class="mt-2 sm:mt-0">
            {{ $abonos->links() }}
        </div>
    </div>

    {{-- Modal Crear / Editar --}}
    @if ($isOpen)
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 p-4 sm:p-6">
        {{-- Contenedor del modal --}}
        <div class="bg-white dark:bg-gray-800 w-full rounded-2xl shadow-xl overflow-y-auto transform transition-all sm:max-w-md max-h-[90vh]">
            {{-- Encabezado del modal --}}
            <div class="sticky top-0 bg-white dark:bg-gray-800 p-6 z-10 border-b dark:border-gray-700 rounded-t-2xl">
                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100">
                    {{ $modo === 'crear' ? 'Registrar Abono' : 'Editar Abono' }}
                </h2>
                <button wire:click="$set('isOpen', false)" class="absolute top-4 right-4 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
                    <i class="fas fa-times fa-lg"></i>
                </button>
            </div>


            {{-- Se agrega un padding inferior generoso para evitar la barra de navegación --}}
            <div class="p-6 pb-20">
                <form wire:submit.prevent="guardar" class="space-y-6">
                    {{-- Campo de búsqueda de crédito --}}
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Crédito</label>
                        <input type="text" wire:model.live.debounce.300ms="creditoSearch" placeholder="Buscar crédito por ID o cliente..." class="w-full border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm dark:bg-gray-700 dark:text-gray-200 focus:ring-blue-500 focus:border-blue-500" autocomplete="off" />

                        @if($form->credito_id && $creditoSearch)
                        @if($creditoSeleccionado)
                        <div class="mt-4 p-4 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700 shadow-sm space-y-2">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between text-sm">
                                <p class="text-gray-800 dark:text-gray-200 font-semibold truncate">
                                    {{ $creditoSeleccionado->cliente->nombres }} {{
                                    $creditoSeleccionado->cliente->apellidos }}
                                </p>
                                <p class="text-gray-600 dark:text-gray-400 sm:ml-4 flex-shrink-0">
                                    Crédito #{{ $creditoSeleccionado->id }}
                                </p>
                            </div>
                            <div class="text-xs text-gray-600 dark:text-gray-400 grid grid-cols-2 sm:grid-cols-3 gap-2 mt-2">
                                <div>
                                    <span class="font-medium">Monto original:</span>
                                    <span class="block font-bold">C$ {{ number_format($creditoSeleccionado->monto_total,
                                        2) }}</span>
                                </div>
                                <div>
                                    <span class="font-medium">Saldo pendiente:</span>
                                    <span class="block font-bold text-red-500">C$ {{
                                        number_format($creditoSeleccionado->saldo_pendiente, 2) }}</span>
                                </div>
                                <div>
                                    <span class="font-medium">Cuotas pagadas:</span>
                                    <span class="block font-bold">{{
                                        $creditoSeleccionado->cuotas->where('estado','pagada')->count() }} de {{
                                        $creditoSeleccionado->num_cuotas }}</span>
                                </div>
                            </div>

                            {{-- Próxima cuota pendiente --}}
                            @if($proximaCuota)
                            <div class="mt-3 p-3 rounded-lg bg-yellow-50 dark:bg-yellow-900 dark:text-yellow-100 border border-yellow-200 dark:border-yellow-700">
                                <p class="text-xs font-semibold">
                                    Próxima cuota: #{{ $proximaCuota->numero_cuota }}
                                </p>
                                <p class="text-xs mt-1">
                                    <strong>Monto:</strong> C$ {{ number_format($proximaCuota->monto, 2) }}
                                    <br>
                                    <strong>Fecha:</strong> {{
                                    \Carbon\Carbon::parse($proximaCuota->fecha_vencimiento)->format('d/m/Y') }}
                                </p>
                            </div>
                            @endif

                            {{-- Botón ver detalle --}}
                            <div class="mt-3 text-center">
                                <button type="button" wire:click="toggleDetalleCuotas" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200 text-sm font-medium transition-colors">
                                    {{ $mostrarDetalleCuotas ? 'Ocultar detalle de cuotas' : 'Ver detalle de cuotas' }}
                                </button>
                            </div>

                            {{-- Detalle completo de cuotas --}}
                            @if($mostrarDetalleCuotas)
                            <div class="overflow-y-auto max-h-48 mt-4 border border-gray-200 dark:border-gray-700 rounded-lg **subpixel-antialiased**">
                                <table class="w-full text-sm">
                                    <thead class="sticky top-0 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                        <tr>
                                            <th class="px-2 py-2 border-b dark:border-gray-600 text-center">#</th>
                                            <th class="px-2 py-2 border-b dark:border-gray-600 text-left">Monto</th>
                                            <th class="px-2 py-2 border-b dark:border-gray-600 text-left">Fecha</th>
                                            <th class="px-2 py-2 border-b dark:border-gray-600 text-left">Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                                        @foreach($cuotasCredito as $cuota)
                                        <tr class="hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                            <td class="px-2 py-1 text-center border-b dark:border-gray-700">{{
                                             $cuota['numero'] }}</td>
                                            <td class="px-2 py-1 border-b dark:border-gray-700">C$ {{
                                                 number_format($cuota['monto_original'],2) }}</td>
                                            <td class="px-2 py-1 border-b dark:border-gray-700">{{
                                                \Carbon\Carbon::parse($cuota['fecha'])->format('d/m/Y') }}</td>
                                            <td class="px-2 py-1 border-b dark:border-gray-700">
                                                <span class="@if($cuota['estado']=='pagada') text-green-600 @elseif($cuota['estado']=='atrasada') text-red-600 @else text-yellow-600 @endif font-medium">
                                                    {{ ucfirst($cuota['estado']) }}
                                                </span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @endif
                        </div>
                        @endif
                        @endif

                        @if($creditosFiltrados && count($creditosFiltrados))
                        <ul class="absolute z-20 w-full bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-lg mt-1 max-h-48 overflow-y-auto shadow-xl">
                            @foreach($creditosFiltrados as $credito)
                            <li wire:click="seleccionarCredito({{ $credito->id }})" wire:key="credito-sug-{{ $credito->id }}" class="px-4 py-3 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 text-sm border-b dark:border-gray-700 last:border-none">
                                Crédito #{{ $credito->id }} - {{ $credito->cliente->nombres }} {{
                                $credito->cliente->apellidos }}
                                <span class="text-xs text-gray-500 dark:text-gray-400 block sm:inline">
                                    (Saldo: C$ {{ number_format($credito->saldo_pendiente,2) }})
                                </span>
                            </li>
                            @endforeach
                        </ul>
                        @endif

                        @error('form.credito_id')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Otros campos del formulario --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Monto Abonado
                            C$</label>
                        <input type="number" wire:model="form.monto_abono" min="0.01" step="0.01" required placeholder="Ej: 100.00" class="w-full border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm dark:bg-gray-700 dark:text-gray-200" />
                        @error('form.monto_abono') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fecha del
                            Abono</label>
                        <input type="date" wire:model="form.fecha_abono" class="w-full border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm dark:bg-gray-700 dark:text-gray-200">
                        @error('form.fecha_abono') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Comentarios</label>
                        <textarea wire:model="form.comentarios" class="w-full border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm dark:bg-gray-700 dark:text-gray-200" rows="2"></textarea>
                        @error('form.comentarios') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    {{-- Botones de acción --}}
                    <div class="pt-4 flex justify-end gap-3">
                        <button type="button" wire:click="$set('isOpen', false)" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 rounded-lg transition-colors">
                            Cancelar
                        </button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg transition-colors">
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif


    {{-- Modal Ver Abono --}}
    @if($verModal && $abonoVer)
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50" x-data>
        <div class="bg-white dark:bg-gray-800 w-full max-w-3xl p-6 rounded-2xl shadow-xl overflow-y-auto max-h-[90vh]">

            <h2 class="text-2xl font-bold mb-4 text-gray-800 dark:text-gray-100">
                Detalles del Abono de Crédito #{{ optional($abonoVer->credito)->id ?? 'N/A' }}
            </h2>

            <p class=" text-gray-700 dark:text-gray-300"><strong class=" text-gray-800 dark:text-gray-100">Cliente:</strong>
                {{ optional(optional($abonoVer->credito)->cliente)->nombres ?? 'Cliente no encontrado' }}
                {{ optional(optional($abonoVer->credito)->cliente)->apellidos ?? '' }}
            </p>

            <p class=" text-gray-700 dark:text-gray-300"><strong class=" text-gray-800 dark:text-gray-100">Monto
                    Abonado:</strong> C$ {{ number_format($abonoVer->monto_abono,2) }}</p>
            <p class=" text-gray-700 dark:text-gray-300"><strong class=" text-gray-800 dark:text-gray-100">Fecha del
                    Abono:</strong> {{ $abonoVer->fecha_abono }}</p>
            <p class=" text-gray-700 dark:text-gray-300"><strong class=" text-gray-800 dark:text-gray-100">Comentarios:</strong> {{ $abonoVer->comentarios ?? '-' }}
            </p>

            <div class="flex justify-end mt-6">
                <button type="button" wire:click="cerrarModalVer" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition">
                    Cerrar
                </button>
            </div>
        </div>
    </div>
    @endif


    @if($modalConfirmar)
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white dark:bg-gray-800  rounded-xl shadow-lg w-96 p-6">
            <h2 class="text-xl font-semibold dark:text-gray-200 text-gray-800 mb-4">Confirmar eliminación</h2>
            <p class="text-gray-600 mb-6">¿Estás seguro de que deseas eliminar este abono? Esta acción no se puede
                deshacer.</p>

            <div class="flex justify-end space-x-3">
                <button wire:click="$set('modalConfirmar', false)" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded-lg text-gray-800">
                    Cancelar
                </button>
                <button wire:click="eliminarConfirmado" class="px-4 py-2 bg-red-600 hover:bg-red-700 rounded-lg text-white">
                    Sí, eliminar
                </button>
            </div>
        </div>
    </div>
    @endif

    @if($modalReporte)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full 
                max-w-full sm:max-w-3xl md:max-w-5xl lg:max-w-6xl xl:max-w-7xl 
                p-6 relative 
                max-h-[90vh] overflow-y-auto">

            <!-- Cerrar modal -->
            <button wire:click="cerrarModalReporte" class="absolute top-2 right-2 text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>

            <livewire:mis-clientes-report />

        </div>
    </div>
    @endif


</div>

