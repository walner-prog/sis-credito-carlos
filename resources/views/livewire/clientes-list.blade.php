<div class="p-6 lg:p-12 bg-gradient-to-r from-blue-50 via-purple-50 to-pink-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 min-h-screen">
    <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-6">Gestión de Clientes</h2>
    @if (session()->has('message'))
    <div class="mb-4 p-3 bg-blue-100 text-blue-800 rounded-xl shadow mt-4 sm:mt-0 w-full" x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show">

        {{ session('message') }}
    </div>
    @endif
    <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
        <button wire:click="abrirModalCrear" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl shadow-md transition">
            + Nuevo Cliente
        </button>
        @if(Auth::user() && Auth::user()->hasRole('Administrador'))
        <button wire:click="actualizarCuotasDiariamente" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-xl shadow-md transition w-full sm:w-auto">
            Actualizar cuotas vencidas
        </button>
        @endif

        <input type="text" wire:model.live="search" placeholder="Buscar por nombre, apellido o identificación..." class="border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 w-full sm:w-1/3 dark:bg-gray-800 dark:text-gray-200 focus:ring focus:ring-blue-400">
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


    <div class="block md:hidden space-y-4 mt-3">
        @forelse ($clientes as $cliente)
        <div wire:key="cliente-card-{{ $cliente->id }}" class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-4 relative">
            <div class="flex items-center justify-between">
                <div class="flex-1 min-w-0">
                    {{-- Nombre completo con ícono --}}
                    <div class="flex items-center space-x-2 text-sm font-semibold text-gray-900 dark:text-gray-200">
                        <i class="fas fa-user-circle text-lg text-blue-500"></i>
                        <span class="truncate">{{ $cliente->nombres }} {{ $cliente->apellidos }}</span>
                    </div>

                    {{-- Identificación y teléfono --}}
                    <div class="mt-2 text-xs text-gray-600 dark:text-gray-400 space-y-1">
                        <div class="flex items-center space-x-1">
                            <i class="fas fa-id-card"></i>
                            <span class="font-medium">Cédula:</span>
                            <span>{{ $cliente->identificacion }}</span>
                        </div>
                        <div class="flex items-center space-x-1">
                            <i class="fas fa-phone"></i>
                            <span class="font-medium">Teléfono:</span>
                            <span>{{ $cliente->telefono }}</span>
                        </div>
                    </div>
                </div>

                {{-- Menú de acciones (sin Alpine) --}}
                <div class="relative z-10 flex-shrink-0 ml-4">
                    <button type="button" wire:click="toggleAcciones({{ $cliente->id }})" class="text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700 p-2 rounded-full">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>

                    @if($openAcciones[$cliente->id] ?? false)
                    <div class="absolute right-0 mt-2 w-40 bg-white dark:bg-gray-700 rounded-md shadow-lg py-1">
                        <button wire:click.stop.prevent="abrirModalEditar({{ $cliente->id }})" class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">
                            <i class="fas fa-edit mr-2 text-yellow-500"></i> Editar
                        </button>

                        <button wire:click.stop.prevent="abrirModalVer({{ $cliente->id }})" class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">
                            <i class="fas fa-eye mr-2 text-indigo-500"></i> Detalles
                        </button>

                        @role('Administrador')
                        <button wire:click.stop.prevent="confirmarEliminar({{ $cliente->id }})" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-100 dark:hover:bg-gray-600">
                            <i class="fas fa-trash-alt mr-2"></i> Eliminar
                        </button>
                        @endrole
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="p-4 text-center text-gray-500 dark:text-gray-400">
            No se encontraron clientes.
        </div>
        @endforelse
    </div>


    {{-- Tabla para dispositivos de escritorio (md:block) --}}
    <div class="overflow-x-auto mt-6 md:block hidden">
        <table class="w-full border-collapse rounded-lg overflow-hidden shadow-md">
            <thead>
                <tr class="bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                    <th class="p-3 text-left">Nombres</th>
                    <th class="p-3 text-left">Apellidos</th>
                    <th class="p-3 text-left">Identificación</th>
                    <th class="p-3 text-left">Teléfono</th>
                    <th class="p-3 text-left">Dirección</th>
                    <th class="p-3 text-left">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800">
                @forelse ($clientes as $cliente)
                <tr wire:key="cliente-{{ $cliente->id }}" class="border-b dark:border-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-800 transition">
                    <td class="p-3">{{ $cliente->nombres }}</td>
                    <td class="p-3">{{ $cliente->apellidos }}</td>
                    <td class="p-3">{{ $cliente->identificacion }}</td>
                    <td class="p-3">{{ $cliente->telefono }}</td>
                    <td class="p-3">{{ $cliente->direccion }}</td>
                    <td class="p-3 flex flex-wrap gap-2">
                        <button wire:click="abrirModalEditar({{ $cliente->id }})" class="bg-yellow-500 hover:bg-yellow-600 text-white p-2 rounded-full shadow-lg hover:scale-110 transition-transform duration-200 flex items-center justify-center" title="Editar">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button wire:click="abrirModalVer({{ $cliente->id }})" class="bg-indigo-500 hover:bg-indigo-600 text-white p-2 rounded-full shadow-lg hover:scale-110 transition-transform duration-200 flex items-center justify-center" title="Detalles">
                            <i class="fas fa-eye"></i>
                        </button>
                        @role('Administrador')
                        <button wire:click="confirmarEliminar({{ $cliente->id }})" class="bg-red-600 hover:bg-red-700 text-white p-2 rounded-full shadow-lg hover:scale-110 transition-transform duration-200 flex items-center justify-center" title="Eliminar">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                        @endrole
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-4 text-center text-gray-500 dark:text-gray-400">No se encontraron clientes
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4 flex flex-col sm:flex-row justify-between items-center text-sm text-gray-600 dark:text-gray-400">
        <div>
            Mostrando {{ $clientes->firstItem() }} a {{ $clientes->lastItem() }} de {{ $clientes->total() }} resultados
        </div>
        <div class="mt-2 sm:mt-0">
            {{ $clientes->links() }}
        </div>
    </div>

    <!-- Modal -->


    @if ($isOpen)
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 p-4">
        <div class="bg-white dark:bg-gray-800 w-full max-w-3xl p-6 rounded-2xl shadow-xl overflow-y-auto max-h-[90vh] pb-20">
            {{-- Encabezado del Modal con botón de cerrar --}}
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 flex items-center space-x-2">
                    <i class="fas fa-user-plus text-blue-500"></i>
                    <span>{{ $modo === 'crear' ? 'Crear Cliente' : 'Editar Cliente' }}</span>
                </h2>
                <button wire:click="$set('isOpen', false)" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            {{-- Formulario con campos mejorados --}}
            <form wire:submit.prevent="guardar" class="space-y-4">
                {{-- Campos del formulario --}}
                <div class="flex flex-col sm:flex-row sm:space-x-4 space-y-4 sm:space-y-0">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            <i class="fas fa-user mr-1 text-gray-400"></i> Nombres
                        </label>
                        <input type="text" wire:model="form.nombres" class="form-input w-full border rounded-lg px-3 py-2 dark:bg-gray-700 dark:text-gray-200">
                        @error('form.nombres') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            <i class="fas fa-user mr-1 text-gray-400"></i> Apellidos
                        </label>
                        <input type="text" wire:model="form.apellidos" class="form-input w-full border rounded-lg px-3 py-2 dark:bg-gray-700 dark:text-gray-200">
                        @error('form.apellidos') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row sm:space-x-4 space-y-4 sm:space-y-0">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            <i class="fas fa-id-card mr-1 text-gray-400"></i> Identificación
                        </label>
                        <input type="text" wire:model="form.identificacion" class="form-input w-full border rounded-lg px-3 py-2 dark:bg-gray-700 dark:text-gray-200">
                        @error('form.identificacion') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            <i class="fas fa-phone mr-1 text-gray-400"></i> Teléfono
                        </label>
                        <input type="text" wire:model="form.telefono" class="form-input w-full border rounded-lg px-3 py-2 dark:bg-gray-700 dark:text-gray-200">
                        @error('form.telefono') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        <i class="fas fa-map-marker-alt mr-1 text-gray-400"></i> Dirección
                    </label>
                    <textarea wire:model="form.direccion" rows="2" class="form-textarea w-full border rounded-lg px-3 py-2 dark:bg-gray-700 dark:text-gray-200"></textarea>
                    @error('form.direccion') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="flex flex-col sm:flex-row sm:space-x-4 space-y-4 sm:space-y-0">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="cartera_id">
                            <i class="fas fa-wallet mr-1 text-gray-400"></i> Cartera
                        </label>
                        <select wire:model="form.cartera_id" id="cartera_id" class="form-select w-full border rounded-lg px-3 py-2 dark:bg-gray-700 dark:text-gray-200">
                            <option value="">Seleccione una cartera</option>
                            @foreach($form->carteras as $cartera)
                            <option value="{{ $cartera->id }}">{{ $cartera->nombre }}</option>
                            @endforeach
                        </select>
                        @error('form.cartera_id') <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            <i class="fas fa-route mr-1 text-gray-400"></i> KM Referencia
                        </label>
                        <input type="text" wire:model="form.km_referencia" class="form-input w-full border rounded-lg px-3 py-2 dark:bg-gray-700 dark:text-gray-200">
                        @error('form.km_referencia') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        <i class="fas fa-clipboard-check mr-1 text-gray-400"></i> Estado
                    </label>
                    <select wire:model="form.estado" class="form-select w-full border rounded-lg px-3 py-2 dark:bg-gray-700 dark:text-gray-200">
                        <option value="activo">Activo</option>
                        <option value="inactivo">Inactivo</option>
                    </select>
                    @error('form.estado') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Botones ocupando toda la fila --}}
                <div class="md:col-span-3 flex justify-end gap-3 pt-4">
                    <button type="button" wire:click="$set('isOpen', false)" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
                        Cancelar
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif


    <!-- Modal Ver Cliente -->
    @if($verModal && $clienteVer)
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 p-4" x-data>
        {{-- Agregamos un padding-bottom generoso (pb-20) para evitar que la nav bar inferior tape el contenido. --}}
        <div class="bg-white dark:bg-gray-800 w-full max-w-3xl p-6 rounded-2xl shadow-xl overflow-y-auto max-h-[90vh] pb-20">

            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
                    <i class="fas fa-user-circle mr-2 text-blue-500"></i>
                    Detalles del Cliente
                </h2>
                <button wire:click="cerrarModalVer" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>

            <div class="mb-6">
                <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-2">
                    {{ $clienteVer->nombres }} {{ $clienteVer->apellidos }}
                </h3>

                {{-- Resumen en tarjetas compactas --}}
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-lg text-center">
                        <i class="fas fa-credit-card text-blue-600 dark:text-blue-300 mb-1"></i>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Créditos</p>
                        <p class="text-lg font-bold text-gray-800 dark:text-gray-100">{{ $clienteVer->creditos->count() }}</p>
                    </div>
                    <div class="p-3 bg-green-100 dark:bg-green-900 rounded-lg text-center">
                        <i class="fas fa-money-bill-wave text-green-600 dark:text-green-300 mb-1"></i>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Saldo Pendiente</p>
                        <p class="text-lg font-bold text-gray-800 dark:text-gray-100">${{ number_format($clienteVer->creditos->sum('saldo_pendiente'), 2) }}</p>
                    </div>
                    <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-lg text-center col-span-2 sm:col-span-1">
                        <i class="fas fa-hand-holding-usd text-purple-600 dark:text-purple-300 mb-1"></i>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Total Abonado</p>
                        <p class="text-lg font-bold text-gray-800 dark:text-gray-100">${{ number_format($clienteVer->creditos->flatMap->abonos->sum('monto_abono'), 2) }}</p>
                    </div>
                </div>
            </div>

            <hr class="my-6 border-gray-200 dark:border-gray-700">

            {{-- Datos del Cliente en modo compacto --}}
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-3">Información Personal</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-gray-700 dark:text-gray-300">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-id-card text-gray-400"></i>
                    <div class="text-sm">
                        <span class="font-bold">Identificación:</span> {{ $clienteVer->identificacion }}
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <i class="fas fa-phone text-gray-400"></i>
                    <div class="text-sm">
                        <span class="font-bold">Teléfono:</span> {{ $clienteVer->telefono }}
                    </div>
                </div>
                <div class="flex items-start space-x-2 col-span-full">
                    <i class="fas fa-map-marker-alt text-gray-400 mt-1"></i>
                    <div class="text-sm">
                        <span class="font-bold">Dirección:</span> {{ $clienteVer->direccion }}
                    </div>
                </div>
            </div>

            <hr class="my-6 border-gray-200 dark:border-gray-700">

            {{-- Lista de Créditos en tarjetas con funcionalidad de "mostrar/ocultar" --}}
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-3">Créditos de {{ $clienteVer->nombres }} {{ $clienteVer->apellidos }}</h3>

            <div class="space-y-4">
                @forelse($clienteVer->creditos as $credito)
                <div x-data="{ open: false }" class="bg-gray-100 dark:bg-gray-700 rounded-xl shadow-inner cursor-pointer" :class="{ 'ring-2 ring-indigo-500': open }">
                    {{-- Encabezado del crédito --}}
                    <div @click="open = !open" class="p-4 flex items-center justify-between">
                        <h4 class="text-sm font-bold text-gray-900 dark:text-gray-100 flex items-center space-x-2">
                            <i class="fas fa-receipt text-lg text-indigo-500"></i>
                            <span>Crédito #{{ $credito->id }}</span>
                        </h4>
                        <div class="flex items-center space-x-2">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold
                            {{ $credito->estado === 'activo' ? 'bg-green-200 text-green-800' : ($credito->estado === 'moroso' ? 'bg-red-200 text-red-800' : 'bg-gray-200 text-gray-800') }}">
                                {{ ucfirst($credito->estado) }}
                            </span>
                            <i class="fas fa-chevron-down transform transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                        </div>
                    </div>

                    {{-- Contenido colapsable del crédito --}}
                    <div x-show="open" x-collapse.duration.400ms class="px-4 pb-4">

                        {{-- Resumen de datos del crédito --}}
                        <div class="grid grid-cols-2 gap-x-4 gap-y-2 text-xs text-gray-700 dark:text-gray-300">
                            <div class="flex items-center space-x-1">
                                <i class="fas fa-dollar-sign"></i>
                                <span>Monto: <span class="font-bold">${{ number_format($credito->monto_total, 2) }}</span></span>
                            </div>
                            <div class="flex items-center space-x-1">
                                <i class="fas fa-clipboard-list"></i>
                                <span>Plazo: <span class="font-bold">{{ $credito->plazo }} {{ $credito->unidad_plazo }}</span></span>
                            </div>

                            {{-- Fecha de creación del crédito --}}
                            <div class="flex flex-col">
                                <div class="flex items-center space-x-1">
                                    <i class="fas fa-calendar-check"></i>
                                    <span>Se creó:
                                        <span class="font-bold">{{ \Carbon\Carbon::parse($credito->fecha_inicio)->format('d/m/Y') }}</span>
                                    </span>
                                </div>
                                <span class="text-[10px] text-gray-500 dark:text-gray-400">
                                    ⚠️ Esta no es la fecha de la primera cuota
                                </span>
                            </div>

                            {{-- Fecha de la primera cuota --}}
                            <div class="flex items-center space-x-1">
                                <i class="fas fa-calendar-day"></i>
                                <span>Primera cuota:
                                    <span class="font-bold">
                                        {{ optional($credito->cuotas()->orderBy('numero_cuota')->first())->fecha_vencimiento 
                        ? \Carbon\Carbon::parse($credito->cuotas()->orderBy('numero_cuota')->first()->fecha_vencimiento)->format('d/m/Y') 
                        : '---' }}
                                    </span>
                                </span>
                            </div>

                            {{-- Fecha de vencimiento del crédito --}}
                            <div class="flex items-center space-x-1 col-span-2">
                                <i class="fas fa-calendar-times"></i>
                                <span>Vence: <span class="font-bold">{{ \Carbon\Carbon::parse($credito->fecha_vencimiento)->format('d/m/Y') }}</span></span>
                            </div>
                        </div>

                    </div>

                </div>
                @empty
                <p class="text-gray-500 dark:text-gray-400 text-center">No hay créditos registrados para este cliente.</p>
                @endforelse
            </div>
        </div>
    </div>
    @endif


    @if($modalConfirmar)
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white dark:bg-gray-800  rounded-xl shadow-lg w-96 p-6">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-4">Confirmar eliminación</h2>
            <p class="text-gray-600 dark:text-gray-300 mb-6">¿Estás seguro de que deseas eliminar este cliente? Esta acción no se puede
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


</div>
