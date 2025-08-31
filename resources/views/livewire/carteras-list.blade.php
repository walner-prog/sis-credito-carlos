<div class="p-6 lg:p-12 bg-gradient-to-r from-blue-50 via-purple-50 to-pink-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 min-h-screen">
    
    {{-- Header para Escritorio --}}
    <div class="hidden lg:flex flex-col sm:flex-row justify-between items-center gap-4">
        @role('Administrador')
            <button wire:click="abrirModalCrear" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl shadow-md transition">
                + Nueva Cartera
            </button>
        @endrole
        <input type="text" wire:model.live="search" placeholder="Buscar por nombre..." class="border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 w-full sm:w-1/3 dark:bg-gray-800 dark:text-gray-200 focus:ring focus:ring-blue-400">
    </div>

    {{-- Header para Móvil --}}
    <div class="lg:hidden flex flex-col sm:flex-row justify-between items-center gap-4">
        @role('Administrador')
            <button wire:click="abrirModalCrear" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl shadow-md transition w-full sm:w-auto">
                + Nueva Cartera
            </button>
        @endrole
        <input type="text" wire:model.live="search" placeholder="Buscar por nombre..." class="border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 w-full dark:bg-gray-800 dark:text-gray-200 focus:ring focus:ring-blue-400 mt-2 sm:mt-0">
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

    {{-- Tabla para Escritorio --}}
    <div class="overflow-x-auto mt-6 hidden md:block">
        <table class="w-full border-collapse rounded-lg overflow-hidden shadow-md">
            <thead>
                <tr class="bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                    <th class="p-3 text-left">Nombre</th>
                    <th class="p-3 text-left">Usuario Responsable</th>
                    <th class="p-3 text-left">Estado</th>
                    <th class="p-3 text-left">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800">
                @forelse ($carteras as $cartera)
                <tr wire:key="cartera-{{ $cartera->id }}" class="border-b dark:border-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-800 transition">
                    <td class="p-3">{{ $cartera->nombre }}</td>
                    <td class="p-3">{{ $cartera->user->name ?? 'Sin usuario' }}</td>
                    <td class="p-3">
                        <span class="px-2 py-1 rounded text-sm {{ $cartera->estado ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">
                            {{ $cartera->estado ? 'Activa' : 'Inactiva' }}
                        </span>
                    </td>
                    <td class="p-3 flex flex-wrap gap-2">
                        <td class="p-3 flex flex-wrap gap-2">
                        @role('Administrador')
                            <button wire:click="abrirModalEditar({{ $cartera->id }})" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-lg shadow transition" title="Editar">
                                <i class="fas fa-edit"></i>
                            </button>
                        @endrole
                        <button wire:click="abrirModalVer({{ $cartera->id }})" class="bg-indigo-500 hover:bg-indigo-600 text-white px-3 py-1 rounded-lg shadow transition" title="Detalles">
                            <i class="fas fa-eye"></i>
                        </button>
                        @role('Administrador')
                            <button wire:click="confirmarEliminar({{ $cartera->id }})" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-lg shadow transition" title="Eliminar">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        @endrole
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="p-4 text-center text-gray-500 dark:text-gray-400">No se encontraron carteras</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Tarjetas para Móvil --}}
    <div class="mt-6 grid grid-cols-1 gap-4 md:hidden">
        @forelse ($carteras as $cartera)
        <div wire:key="cartera-{{ $cartera->id }}" class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
            <div class="flex justify-between items-center mb-2">
                <h4 class="font-bold text-gray-800 dark:text-gray-100">{{ $cartera->nombre }}</h4>
  <div class="flex gap-2">
                    @role('Administrador')
                        <button wire:click="abrirModalEditar({{ $cartera->id }})" class="bg-yellow-500 hover:bg-yellow-600 text-white p-2 rounded-full shadow transition" title="Editar">
                            <i class="fas fa-edit text-xs"></i>
                        </button>
                    @endrole
                    <button wire:click="abrirModalVer({{ $cartera->id }})" class="bg-indigo-500 hover:bg-indigo-600 text-white p-2 rounded-full shadow transition" title="Detalles">
                        <i class="fas fa-eye text-xs"></i>
                    </button>
                    @role('Administrador')
                        <button wire:click="confirmarEliminar({{ $cartera->id }})" class="bg-red-600 hover:bg-red-700 text-white p-2 rounded-full shadow transition" title="Eliminar">
                            <i class="fas fa-trash-alt text-xs"></i>
                        </button>
                    @endrole
                </div>
            </div>
            <p class="text-sm text-gray-500 dark:text-gray-400">Responsable: {{ $cartera->user->name ?? 'Sin usuario' }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Estado: 
                <span class="px-2 py-1 rounded text-xs {{ $cartera->estado ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">
                    {{ $cartera->estado ? 'Activa' : 'Inactiva' }}
                </span>
            </p>
        </div>
        @empty
        <div class="p-4 text-center text-gray-500 dark:text-gray-400">
            No se encontraron carteras
        </div>
        @endforelse
    </div>

    {{-- Paginación --}}
    <div class="mt-4 flex flex-col sm:flex-row justify-between items-center text-sm text-gray-600 dark:text-gray-400">
        <div>
            Mostrando {{ $carteras->firstItem() }} a {{ $carteras->lastItem() }} de {{ $carteras->total() }} resultados
        </div>
        <div class="mt-2 sm:mt-0">
            {{ $carteras->links() }}
        </div>
    </div>

    {{-- Modal Crear / Editar --}}
    @if ($isOpen)
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white dark:bg-gray-800 w-full max-w-lg p-6 rounded-2xl shadow-xl overflow-y-auto max-h-[80vh] mx-4 mb-16">
            <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-gray-100">
                {{ $modo === 'crear' ? 'Crear Cartera' : 'Editar Cartera' }}
            </h2>
            <form wire:submit.prevent="guardar" class="space-y-4">
                <div>
                    <label class="block text-gray-700 dark:text-gray-300">Nombre</label>
                    <input type="text" wire:model="form.nombre" class="w-full border rounded-lg px-3 py-2 dark:bg-gray-700 dark:text-gray-200">
                    @error('form.nombre') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-gray-700 dark:text-gray-300">Usuario Responsable</label>
                    <select wire:model="form.user_id" class="w-full border rounded-lg px-3 py-2 dark:bg-gray-700 dark:text-gray-200">
                        <option value="">-- Selecciona un usuario --</option>
                        @foreach($form->usuarios as $usuario)
                        <option value="{{ $usuario->id }}">{{ $usuario->name }}</option>
                        @endforeach
                    </select>
                    @error('form.user_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-gray-700 dark:text-gray-300">Estado</label>
                    <select wire:model="form.estado" class="w-full border rounded-lg px-3 py-2 dark:bg-gray-700 dark:text-gray-200">
                        <option value="activa">Activa</option>
                        <option value="inactiva">Inactiva</option>
                    </select>
                    @error('form.estado') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="flex justify-end gap-3 pt-4">
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

    {{-- Modal Ver Cartera --}}
    @if($verModal && $carteraVer)
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white dark:bg-gray-800 w-full max-w-6xl p-6 rounded-2xl shadow-xl overflow-y-auto max-h-[90vh] mx-4 mb-16 relative">
            <button wire:click="cerrarModalVer" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 transition">
                <i class="fas fa-times fa-lg"></i>
            </button>
            <h2 class="text-2xl font-bold mb-4 text-gray-800 dark:text-gray-100">
                Detalles de la Cartera: {{ $carteraVer->nombre }}
            </h2>
            <h3 class="text-lg font-semibold mb-2 dark:text-gray-300">
                Clientes (Total: {{ $carteraVer->clientes->count() }})
            </h3>
            <hr class="my-4 border-gray-300 dark:border-gray-700">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                @foreach($carteraVer->clientes as $cliente)
                <div class="p-4 border rounded-lg dark:border-gray-700 dark:text-gray-200 bg-gray-50 dark:bg-gray-700">
                    <p><strong>{{ $cliente->nombres }} {{ $cliente->apellidos }}</strong></p>
                    <p>ID: {{ $cliente->identificacion }}</p>
                    <p>Tel: {{ $cliente->telefono }}</p>
                    <p>Dirección: {{ $cliente->direccion }}</p>
                </div>
                @endforeach
            </div>
            <h3 class="text-lg font-semibold mb-2 dark:text-gray-300 mt-6">Créditos</h3>
            <hr class="my-4 border-gray-300 dark:border-gray-700">
            @php
            $todosLosCreditos = collect();
            if ($carteraVer && $carteraVer->clientes) {
            foreach ($carteraVer->clientes as $cliente) {
            $todosLosCreditos = $todosLosCreditos->merge($cliente->creditos);
            }
            }
            @endphp
            @forelse($todosLosCreditos as $credito)
            <div class="mb-4 p-4 border rounded-lg dark:border-gray-700 dark:text-gray-200 bg-gray-50 dark:bg-gray-700">
                <p><strong>Cliente:</strong> {{ $credito->cliente->nombres ?? 'Sin cliente' }} {{ $credito->cliente->apellidos ?? '' }}</p>
                <p><strong>Monto:</strong> ${{ number_format($credito->monto_total, 2) }}</p>
                <p><strong>Saldo Pendiente:</strong> ${{ number_format($credito->saldo_pendiente, 2) }}</p>
                <p><strong>Estado:</strong>
                    <span class="px-2 py-1 rounded text-sm {{ $credito->estado === 'activo' ? 'bg-green-200 text-green-800' : ($credito->estado === 'moroso' ? 'bg-red-200 text-red-800' : 'bg-gray-200 text-gray-800') }}">
                        {{ ucfirst($credito->estado) }}
                    </span>
                </p>
            </div>
            @empty
            <p class="text-gray-500 dark:text-gray-400">No hay créditos registrados para esta cartera.</p>
            @endforelse
        </div>
    </div>
    @endif

    {{-- Modal Confirmar Eliminación --}}
    @if($modalConfirmar)
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg w-full max-w-sm p-6 mx-4 mb-16">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-4">Confirmar eliminación</h2>
            <p class="text-gray-600 dark:text-gray-400 mb-6">¿Estás seguro de que deseas eliminar esta cartera? Esta acción no se puede deshacer.</p>
            <div class="flex justify-end space-x-3">
                <button wire:click="$set('modalConfirmar', false)" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded-lg text-gray-800 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600 transition">
                    Cancelar
                </button>
                <button wire:click="eliminarConfirmado" class="px-4 py-2 bg-red-600 hover:bg-red-700 rounded-lg text-white transition">
                    Sí, eliminar
                </button>
            </div>
        </div>
    </div>
    @endif
</div>