<div class="p-6 lg:p-12 bg-gradient-to-r from-green-50 via-blue-50 to-purple-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 min-h-screen">
    <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-6">Gestión de Créditos</h2>

    {{-- Controles de acción: Crear, Buscar, Descargar --}}
    <div class="hidden lg:flex justify-between items-center gap-4">
        <button wire:click="abrirModalCrear" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl shadow-md transition transform hover:scale-105 flex items-center gap-2">
            <i class="fas fa-plus"></i>
            <span>Nuevo Crédito</span>
        </button>

        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar por cliente..." class="border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 w-full sm:w-1/3 dark:bg-gray-800 dark:text-gray-200 focus:ring focus:ring-blue-400 transition">

        <button wire:click="downloadPDF" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-xl shadow-md transition transform hover:scale-105 flex items-center gap-2">
            <i class="fas fa-download"></i>
            <span>Descargar</span>
        </button>
    </div>
    <div class="lg:hidden">
        <div class="flex justify-between items-center px-4 py-2 relative">

            {{-- Botón Nuevo Crédito --}}
            <button wire:click="abrirModalCrear" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 mr-2 rounded-xl shadow-md transition transform hover:scale-105 flex items-center gap-2">
                <i class="fas fa-plus"></i>
                <span>Nuevo</span>
            </button>

            {{-- Contenedor de búsqueda y descarga --}}
            <div class="flex items-center gap-2 relative w-48">

                {{-- Buscar --}}
                @if($buscarAbierto)
                <div class="relative w-full">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar..." class="bg-gray-100 dark:bg-gray-700 rounded-full px-3 pr-8 py-1 w-full text-gray-800 dark:text-gray-200 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none transition-all">

                    {{-- Botón X dentro del input --}}
                    <button wire:click="cerrarBuscar" class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                @else
                <button wire:click="toggleBuscar" class="text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-gray-100 transition">
                    <i class="fas fa-search text-xl"></i>
                </button>
                @endif

                {{-- Descargar PDF --}}
                <button wire:click="downloadPDF" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-xl shadow-md transition transform hover:scale-105 flex items-center gap-2">
                    <i class="fas fa-download"></i>
                </button>

            </div>
        </div>
    </div>



    {{-- Notificaciones --}}
    @if (session()->has('create'))
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" class="session-message bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 p-3 mt-4 rounded-lg shadow-md">
        {{ session('create') }}
    </div>
    @endif

    @if (session()->has('update'))
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" class="session-message bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-200 p-3 mt-4 rounded-lg shadow-md">
        {{ session('update') }}
    </div>
    @endif

    @if (session()->has('delete'))
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" class="session-message bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 p-3 mt-4 rounded-lg shadow-md">
        {{ session('delete') }}
    </div>
    @endif

    @if (session()->has('error'))
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" class="bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 p-3 mt-4 rounded-lg shadow-md">
        {{ session('error') }}
    </div>
    @endif


    {{-- Vista para MÓVIL (cards) --}}
    <div class="grid gap-4 mt-6 lg:hidden">
        @forelse ($creditos as $credito)
        <div wire:key="credito-card-{{ $credito->id }}" class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md border border-gray-200 dark:border-gray-700">

            {{-- Contenido principal --}}
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Cliente</p>
                    <p class="font-semibold text-gray-800 dark:text-gray-200">
                        {{ $credito->cliente?->nombres ?? 'Cliente no disponible' }}
                        {{ $credito->cliente?->apellidos ?? '' }}
                    </p>
                    <p class="text-xs text-gray-600 dark:text-gray-400">
                        Monto total: C$ {{ number_format($credito->monto_total, 2) }}
                    </p>
                </div>

                {{-- Estado y menú --}}
                <div class="text-right flex items-center gap-2">
                    <span class="px-2 py-1 rounded text-xs font-semibold {{ $credito->estado === 'activo' ? 'bg-green-200 text-green-800' : ($credito->estado === 'moroso' ? 'bg-red-200 text-red-800' : 'bg-gray-200 text-gray-800') }}">
                        {{ ucfirst($credito->estado) }}
                    </span>

                    {{-- Menú de acciones --}}
                    <div class="relative inline-block text-left">
                        <button type="button" wire:click="toggleAcciones({{ $credito->id }})" class="inline-flex justify-center w-full rounded-md border border-gray-300 dark:border-gray-700 shadow-sm px-2 py-1 bg-white dark:bg-gray-700 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>

                        @if($openAcciones[$credito->id] ?? false)
                        <div class="origin-top-right absolute right-0 mt-2 w-40 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 z-10">
                            <div class="py-1">
                                <a href="#" wire:click="abrirModalEditar({{ $credito->id }})" class="text-gray-700 dark:text-gray-200 block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <i class="fas fa-edit mr-2"></i> Editar
                                </a>
                                <a href="#" wire:click="abrirModalVer({{ $credito->id }})" class="text-gray-700 dark:text-gray-200 block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <i class="fas fa-eye mr-2"></i> Detalles
                                </a>
                                <a href="#" wire:click="confirmarEliminar({{ $credito->id }})" class="text-red-600 block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <i class="fas fa-trash-alt mr-2"></i> Eliminar
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Botón y contenido expandible --}}
            <div class="mt-4 border-t pt-4 border-gray-200 dark:border-gray-700">
                <button wire:click="toggleDetalles({{ $credito->id }})" class="text-blue-600 hover:underline text-sm font-medium w-full text-left flex items-center justify-between">
                    <span>{{ ($openDetalles[$credito->id] ?? false) ? 'Ocultar detalles' : 'Mostrar detalles' }}</span>
                    <i class="fas {{ ($openDetalles[$credito->id] ?? false) ? 'fa-chevron-up' : 'fa-chevron-down' }}"></i>
                </button>

                @if($openDetalles[$credito->id] ?? false)
                <div class="mt-4 text-sm space-y-2 text-gray-600 dark:text-gray-400">
                    <p><strong>Cliente:</strong> {{ $credito->cliente?->nombres ?? 'Cliente no disponible' }} {{ $credito->cliente?->apellidos ?? '' }}</p>
                    <p><strong>Monto solicitado:</strong> C$ {{ number_format($credito->monto_solicitado, 2) }}</p>
                    <p><strong>Monto total :</strong> C$ {{ number_format($credito->monto_total, 2) }}</p>
                    <p><strong>Saldo pendiente:</strong> C$ {{ number_format($credito->saldo_pendiente, 2) }}</p>
                    <p><strong>Plazo:</strong> {{ $credito->plazo }} {{ $credito->unidad_plazo }}</p>
                    <p><strong>Tasa:</strong> {{ $credito->tasa_interes }}%</p>
                    <p><strong>Cuota:</strong> C$ {{ number_format($credito->cuota, 2) }}</p>
                    <p><strong>Frecuencia:</strong> {{ ucfirst($credito->cuota_frecuencia) }}</p>
                    <p><strong>Nº Cuotas:</strong> {{ $credito->num_cuotas }}</p>
                    <p><strong>Fecha de Creación:</strong> {{ $credito->fecha_inicio }}</p>
                    <p><strong>Vencimiento:</strong> {{ $credito->fecha_vencimiento }}</p>
                </div>
                @endif
            </div>
        </div>

        @empty
        <div class="p-4 text-center text-gray-500 dark:text-gray-400 lg:hidden">
            No se encontraron créditos.
        </div>
        @endforelse
    </div>


    {{-- Vista para ESCRITORIO (tabla) --}}
    <div class="overflow-x-auto mt-6 hidden lg:block">
        <table class="min-w-max w-full border-collapse rounded-lg overflow-hidden shadow-md">
            <thead>
                <tr class="bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                    <th class="p-3 text-left">Cliente</th>
                    <th class="p-3 text-left">Monto Solicitado C$</th>
                    <th class="p-3 text-left">Monto Total C$</th>
                    <th class="p-3 text-left">Saldo Pendiente C$</th>
                    <th class="p-3 text-left">Plazo (días)</th>
                    <th class="p-3 text-left">Tasa (%)</th>
                    <th class="p-3 text-left">Cuota C$</th>
                    <th class="p-3 text-left">Frecuencia</th>
                    <th class="p-3 text-left">Nº Cuotas</th>
                    <th class="p-3 text-left">Fecha de Inicio / Vencimiento</th>
                    <th class="p-3 text-left">Estado</th>
                    <th class="p-3 text-left">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800">
                @forelse ($creditos as $credito)
                <tr wire:key="credito-{{ $credito->id }}" class="border-b dark:border-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-800 transition">
                    <td class="p-3">{{ $credito->cliente?->nombres ?? 'Cliente N/P' }} {{ $credito->cliente?->apellidos ?? '' }}</td>
                    <td class="p-3">C$ {{ number_format($credito->monto_solicitado, 2) }}</td>
                    <td class="p-3">C$ {{ number_format($credito->monto_total, 2) }}</td>
                    <td class="p-3">C$ {{ number_format($credito->saldo_pendiente, 2) }}</td>
                    <td class="p-3">{{ $credito->plazo }} {{ $credito->unidad_plazo }}</td>
                    <td class="p-3">{{ $credito->tasa_interes }}%</td>
                    <td class="p-3">C$ {{ number_format($credito->cuota, 2) }}</td>
                    <td class="p-3">{{ ucfirst($credito->cuota_frecuencia) }}</td>
                    <td class="p-3">{{ $credito->num_cuotas }}</td>
                    <td class="p-3">{{ $credito->fecha_inicio }} / {{ $credito->fecha_vencimiento }}</td>
                    <td class="p-3">
                        <span class="px-2 py-1 rounded text-sm {{ $credito->estado === 'activo' ? 'bg-green-200 text-green-800' : ($credito->estado === 'moroso' ? 'bg-red-200 text-red-800' : 'bg-gray-200 text-gray-800') }}">
                            {{ ucfirst($credito->estado) }}
                        </span>
                    </td>
                    <td class="p-3 flex flex-wrap gap-2">
                        <button wire:click="abrirModalEditar({{ $credito->id }})" class="bg-yellow-500 hover:bg-yellow-600 text-white p-2 rounded-full shadow-lg hover:scale-110 transition-transform duration-200 flex items-center justify-center" title="Editar">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button wire:click="abrirModalVer({{ $credito->id }})" class="bg-indigo-500 hover:bg-indigo-600 text-white p-2 rounded-full shadow-lg hover:scale-110 transition-transform duration-200 flex items-center justify-center" title="Detalles">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button wire:click="confirmarEliminar({{ $credito->id }})" class="bg-red-600 hover:bg-red-700 text-white p-2 rounded-full shadow-lg hover:scale-110 transition-transform duration-200 flex items-center justify-center" title="Eliminar">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="12" class="p-4 text-center text-gray-500 dark:text-gray-400">
                        No se encontraron créditos
                    </td>
                </tr>
                @endforelse
            </tbody>

        </table>
    </div>

    {{-- Paginación --}}
    <div class="mt-4 flex flex-col sm:flex-row justify-between items-center text-sm text-gray-600 dark:text-gray-400">
        <div>
            Mostrando {{ $creditos->firstItem() }} a {{ $creditos->lastItem() }} de {{ $creditos->total() }} resultados
        </div>
        <div class="mt-2 sm:mt-0">
            {{ $creditos->links() }}
        </div>
    </div>

    {{-- Modal Crear / Editar --}}
    @if ($isOpen)
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white dark:bg-gray-800 w-full max-w-lg p-6 rounded-2xl shadow-xl overflow-y-auto max-h-[80vh] mx-4">
            <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-gray-100">
                {{ $modo === 'crear' ? 'Crear Crédito' : 'Editar Crédito' }}
            </h2>

            <form wire:submit.prevent="guardar" class="space-y-4">
                {{-- Selección de cliente --}}
                <div class="relative">
                    <label class="block text-gray-700 dark:text-gray-300">Cliente</label>
                    <input type="text" wire:model.live.debounce.300ms="clienteSearch" placeholder="Buscar cliente por nombre, apellido, cédula o teléfono..." class="w-full border rounded-lg px-3 py-2 dark:bg-gray-700 dark:text-gray-200" autocomplete="off" />

                    @if($form->cliente_id && $clienteSearch)
                    <p class="mt-1 text-base text-gray-600 dark:text-gray-400">
                        Cliente seleccionado: <strong class=" text-green-600 dark:text-green-400">{{ $clienteSearch }}</strong>
                    </p>
                    @endif

                    @if($clientesFiltrados && count($clientesFiltrados))
                    <ul class="absolute z-50 w-full bg-white dark:bg-gray-800 border rounded-lg mt-1 max-h-48 overflow-y-auto shadow-lg">
                        @foreach($clientesFiltrados as $cliente)
                        <li wire:click="seleccionarCliente({{ $cliente->id }})" wire:key="cliente-sug-{{ $cliente->id }}" class="px-3 py-2 cursor-pointer   dark:hover:bg-gray-600 dark:bg-gray-700 dark:text-gray-50">
                            {{ $cliente->nombres }} {{ $cliente->apellidos }}
                            @if($cliente->identificacion)
                            — <span class="text-xs">{{ $cliente->identificacion }}</span>
                            @endif
                        </li>
                        @endforeach
                    </ul>
                    @endif

                    @error('form.cliente_id')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Monto solicitado --}}
                <div>
                    <label class="block text-gray-700 dark:text-gray-300">Monto Solicitado (C$)</label>
                    <input type="number" wire:model.live="form.monto_solicitado" class="w-full border rounded-lg px-3 py-2 dark:bg-gray-700 dark:text-gray-200">
                    @error('form.monto_solicitado') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                {{-- Tasa de interés --}}
                <div>
                    <label class="block text-gray-700 dark:text-gray-300">Tasa de Interés (%)</label>
                    <input type="number" step="0.01" wire:model.live="form.tasa_interes" class="w-full border rounded-lg px-3 py-2 dark:bg-gray-700 dark:text-gray-200 mb-3" readonly>
                    @error('form.tasa_interes') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    <br>
                    <p class="text-gray-700 dark:text-gray-300 "><strong class=" text-gray-800 dark:text-gray-200">Frecuencia de Cuotas:</strong> {{ ucfirst($form->cuota_frecuencia) }}</p>
                </div>

                {{-- Plazo --}}
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-gray-700 dark:text-gray-300">Plazo</label>
                        <input type="number" wire:model.live="form.plazo" class="w-full border rounded-lg px-3 py-2 dark:bg-gray-700 dark:text-gray-200">
                        @error('form.plazo') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-gray-700 dark:text-gray-300">Unidad</label>
                        <select wire:model="form.unidad_plazo" class="w-full border rounded-lg px-3 py-2 dark:bg-gray-700 dark:text-gray-200">
                            <option value="dias">Días</option>
                        </select>
                    </div>
                </div>

                {{-- Muestra esta sección solo en modo de edición --}}
                @if ($modo === 'editar')
                <div class="p-3 bg-gray-100 text-gray-800 dark:text-gray-200 dark:bg-gray-700 rounded-lg text-sm space-y-1">
                    <p class="text-gray-700 dark:text-gray-300"><strong>Monto Total:</strong> C$ {{ number_format($form->monto_total, 2) }}</p>
                    <p class="text-gray-700 dark:text-gray-300"><strong>Saldo Pendiente:</strong> C$ {{ number_format($form->saldo_pendiente, 2) }}</p>
                    <p class="text-gray-700 dark:text-gray-300"><strong>Cuota:</strong> C$ {{ number_format($form->cuota, 2) }}</p>
                    <p class="text-gray-700 dark:text-gray-300"><strong>Vence el:</strong> {{ $form->fecha_vencimiento }}</p>
                </div>
                @endif

                {{-- Estado --}}
                <div>
                    <label class="block text-gray-700 dark:text-gray-300">Estado</label>
                    <select wire:model="form.estado" class="w-full border rounded-lg px-3 py-2 dark:bg-gray-700 dark:text-gray-200">
                        <option value="activo">Activo</option>
                        <option value="moroso">Moroso</option>
                        <option value="pagado">Pagado</option>
                    </select>
                    @error('form.estado') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                {{-- Fechas --}}
                <p class="text-sm text-gray-700 dark:text-slate-200">
                    Inicio: {{ $form->fecha_inicio }} - Vencimiento: {{ $form->fecha_vencimiento }}
                </p>
                <input type="hidden" wire:model="form.fecha_inicio">
                <input type="hidden" wire:model="form.fecha_vencimiento">

                {{-- Botones --}}
                <div class="flex justify-end gap-3 pt-4 mb-8">
                    <button type="button" wire:click="$set('isOpen', false)" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
                        Cancelar
                    </button>
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition flex items-center gap-2">
                        Guardar
                        <div wire:loading>
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>
                        </div>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif


    {{-- Modal Ver Crédito --}}
    @if($verModal && $creditoVer)
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white dark:bg-gray-800 w-full max-w-3xl p-6 rounded-2xl shadow-xl overflow-y-auto max-h-[90vh] mx-4 lg:mx-auto">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 lg:text-2xl">
                    Detalles del Crédito
                </h2>
                <button wire:click="cerrarModalVer" class="text-gray-500 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-100 transition lg:hidden">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            {{-- Sección de Cliente y Resumen --}}
            <div class="mb-6 pb-4 border-b dark:border-gray-700">
                <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100">
                    {{ $creditoVer->cliente->nombres ?? 'Cliente N/P' }} {{ $creditoVer->cliente->apellidos ?? '' }}
                </h3>
                <div class="mt-2 text-gray-700 dark:text-gray-200">
                    <p class="text-lg"><strong>Monto:</strong> C$ {{ number_format($creditoVer->monto_total, 2) }}</p>
                    <p class="text-lg"><strong>Saldo Pendiente:</strong> C$ {{ number_format($creditoVer->saldo_pendiente, 2) }}</p>
                    <p class="text-lg"><strong>Estado:</strong>
                        <span class="px-2 py-1 rounded text-base font-semibold {{ $creditoVer->estado === 'activo' ? 'bg-green-200 text-green-800' : ($creditoVer->estado === 'moroso' ? 'bg-red-200 text-red-800' : 'bg-gray-200 text-gray-800') }}">
                            {{ ucfirst($creditoVer->estado) }}
                        </span>
                    </p>
                </div>
            </div>

            {{-- Contenedor de Cuotas (vista para móvil) --}}
            <div class="lg:hidden">
                <h3 class="text-lg font-bold mb-3 text-gray-800 dark:text-gray-100">Cuotas</h3>
                <div class="space-y-4">
                    @foreach($cuotasCredito as $cuota)
                    <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg shadow-sm">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-gray-800 dark:text-gray-100">Cuota #{{ $cuota['numero'] }}</span>
                            <span class="px-2 py-1 rounded text-sm font-semibold {{ $cuota['estado'] === 'pendiente' ? 'bg-yellow-200 text-yellow-800' : ($cuota['estado'] === 'pagada' ? 'bg-green-200 text-green-800' : ($cuota['estado'] === 'atrasada' ? 'bg-red-200 text-red-800' : ($cuota['estado'] === 'parcial' ? 'bg-orange-200 text-orange-800' : 'bg-gray-200 text-gray-800'))) }}">
                                {{ ucfirst($cuota['estado']) }}
                            </span>
                        </div>
                        <p class="mt-2 text-gray-700 dark:text-gray-200">
                            <strong>Monto:</strong> C$ {{ number_format($cuota['monto_original'], 2) }}
                        </p>
                        
                       
                        <p class="text-gray-700 dark:text-gray-200">
                            <strong>Fecha:</strong> {{ \Carbon\Carbon::parse($cuota['fecha'])->format('d/m/Y') }}
                        </p>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Tabla de Cuotas (vista para escritorio) --}}
            <div class="hidden lg:block">
                <h3 class="text-xl font-semibold mt-4 mb-2 text-gray-800 dark:text-gray-100">Cuotas</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border border-gray-200 dark:border-gray-700 rounded-lg">
                        <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                            <tr>
                                <th class="px-4 py-2 border-b">#</th>
                                 
                                <th class="px-4 py-2 border-b">Monto</th>
                                <th class="px-4 py-2 border-b">Fecha</th>
                                <th class="px-4 py-2 border-b">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cuotasCredito as $cuota)
                            <tr class="border-b border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                <td class="px-4 py-2">{{ $cuota['numero'] }}</td>
                                <td class="px-4 py-2">C$ {{ number_format($cuota['monto_original'], 2) }}</td>
                             
                                <td class="px-4 py-2">{{ \Carbon\Carbon::parse($cuota['fecha'])->format('d/m/Y') }}</td>
                                <td class="px-4 py-2">
                                    <span class="px-2 py-1 rounded text-sm {{ $cuota['estado'] === 'pendiente' ? 'bg-yellow-200 text-yellow-800' : ($cuota['estado'] === 'pagada' ? 'bg-green-200 text-green-800' : ($cuota['estado'] === 'atrasada' ? 'bg-red-200 text-red-800' : ($cuota['estado'] === 'parcial' ? 'bg-orange-200 text-orange-800' : 'bg-gray-200 text-gray-800'))) }}">
                                        {{ ucfirst($cuota['estado']) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="flex justify-end mt-6">
                <button type="button" wire:click="cerrarModalVer" class="hidden lg:inline-block bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition">
                    Cerrar
                </button>
            </div>
        </div>
    </div>
    @endif

    @if($modalConfirmar)
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg w-96 p-6">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-4">Confirmar eliminación</h2>
            <p class="text-gray-600 dark:text-gray-300 mb-6">
                {{ $mensajeEliminarCredito ?: '¿Estás seguro de que deseas eliminar este crédito? Esta acción no se puede deshacer.' }}
            </p>

            <div class="flex justify-end space-x-3">
                <button wire:click="$set('modalConfirmar', false)" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded-lg text-gray-800">
                    Cancelar
                </button>

                {{-- Botón de eliminar: solo cambia de método si hay abonos/cuotas --}}
                <button wire:click="eliminarCredito" class="px-4 py-2 bg-red-600 hover:bg-red-700 rounded-lg text-white">
                    Sí, eliminar
                </button>
            </div>
        </div>
    </div>
    @endif

</div>

