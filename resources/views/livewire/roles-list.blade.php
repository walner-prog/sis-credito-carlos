<div
    class="p-6 lg:p-12 bg-gradient-to-r from-blue-50 via-purple-50 to-pink-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 min-h-screen">

    <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-6">Gestión de Roles</h2>

    {{-- Desktop Header --}}
    <div class="hidden lg:flex flex-col sm:flex-row justify-between items-center gap-4">
        <button wire:click="abrirModalCrear"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl shadow-md transition">
            + Nuevo Rol
        </button>
        <input type="text" wire:model.live="search" placeholder="Buscar por nombre..."
            class="border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 w-full sm:w-1/3 dark:bg-gray-800 dark:text-gray-200 focus:ring focus:ring-blue-400">
    </div>

    {{-- Mobile Header --}}
    <div class="lg:hidden">
        <div class="flex justify-between items-center px-4 py-2">

            {{-- Botón Nuevo Rol --}}
            <button wire:click="abrirModalCrear"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 mr-8 rounded-xl shadow-md transition transform hover:scale-105 flex items-center gap-2">
                <i class="fas fa-plus"></i>
                <span>Nuevo Rol</span>
            </button>

            {{-- Buscador --}}
            <div class="flex items-center gap-2 relative w-48">

                @if($buscarAbierto)
                <div class="relative w-full">
                    <input type="text" wire:model.live="search" placeholder="Buscar..."
                        class="bg-gray-100 dark:bg-gray-700 rounded-full px-3 pr-8 py-1 w-full text-gray-800 dark:text-gray-200 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none transition-all">

                    {{-- Botón X dentro del input --}}
                    <button wire:click="cerrarBuscar"
                        class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                @else
                <button wire:click="toggleBuscar"
                    class="text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-gray-100 transition">
                    <i class="fas fa-search text-xl"></i>
                </button>
                @endif

            </div>
        </div>
    </div>



    {{-- Notificaciones --}}
    @foreach (['create' => 'green', 'update' => 'yellow', 'delete' => 'red', 'error' => 'red'] as $msg => $color)
    @if (session()->has($msg))
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show"
        class="session-message bg-{{ $color }}-100 text-{{ $color }}-800 dark:bg-{{ $color }}-900 dark:text-{{ $color }}-200 p-3 mt-4 rounded-lg shadow-md">
        {{ session($msg) }}
    </div>
    @endif
    @endforeach

    {{-- Tabla para Escritorio --}}
    <div class="overflow-x-auto mt-6 hidden lg:block">
        <table class="w-full border-collapse rounded-lg overflow-hidden shadow-md">
            <thead>
                <tr class="bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                    <th class="p-3 text-left">Nombre</th>
                    <th class="p-3 text-left">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800">
                @forelse ($roles as $rol)
                <tr wire:key="rol-{{ $rol->id }}"
                    class="border-b dark:border-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-800 transition">
                    <td class="p-3">{{ $rol->name }}</td>
                    <td class="p-3">
                        <div class="flex flex-col sm:flex-row gap-2 justify-start sm:justify-end">
                            <button wire:click="abrirModalEditar({{ $rol->id }})"
                                class="bg-yellow-500 hover:bg-yellow-600 text-white p-2 rounded-full shadow-lg hover:scale-110 transition-transform duration-200 flex items-center justify-center {{ in_array($rol->name,['Administrador','Cobrador']) ? 'opacity-50 cursor-not-allowed' : '' }}"
                                title="Editar" @if(in_array($rol->name,['Administrador','Cobrador'])) disabled @endif>
                                <i class="fas fa-edit"></i>
                            </button>
                            <button wire:click="abrirModalVer({{ $rol->id }})"
                                class="bg-indigo-500 hover:bg-indigo-600 text-white p-2 rounded-full shadow-lg hover:scale-110 transition-transform duration-200 flex items-center justify-center"
                                title="Detalles">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button wire:click="confirmarEliminar({{ $rol->id }})"
                                class="bg-red-600 hover:bg-red-700 text-white p-2 rounded-full shadow-lg hover:scale-110 transition-transform duration-200 flex items-center justify-center {{ in_array($rol->name,['Administrador','Cobrador']) ? 'opacity-50 cursor-not-allowed' : '' }}"
                                title="Eliminar" @if(in_array($rol->name,['Administrador','Cobrador'])) disabled @endif>
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="2" class="p-4 text-center text-gray-500 dark:text-gray-400">No se encontraron roles
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Tarjetas para Móvil --}}
    <div class="mt-6 grid grid-cols-1 gap-4 lg:hidden">
        @forelse ($roles as $rol)
        <div wire:key="mobile-rol-{{ $rol->id }}"
            class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between mb-2">
                <h4 class="font-bold text-gray-800 dark:text-gray-100">{{ $rol->name }}</h4>
                <div class="relative inline-block text-left">
                    <button type="button" wire:click="toggleMenu({{ $rol->id }})"
                        class="inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-700 shadow-sm px-2 py-1 bg-white dark:bg-gray-700 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>

                    @if($menuAccionId === $rol->id)
                    <div
                        class="origin-top-right absolute right-0 mt-2 w-40 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 z-10">
                        <div class="py-1">
                            <a href="#" wire:click="abrirModalEditar({{ $rol->id }})"
                                class="text-gray-700 dark:text-gray-200 block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 {{ in_array($rol->name,['Administrador','Cobrador']) ? 'opacity-50 cursor-not-allowed pointer-events-none' : '' }}">
                                <i class="fas fa-edit mr-2"></i> Editar
                            </a>
                            <a href="#" wire:click="abrirModalVer({{ $rol->id }})"
                                class="text-gray-700 dark:text-gray-200 block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                                <i class="fas fa-eye mr-2"></i> Detalles
                            </a>
                            <a href="#" wire:click="confirmarEliminar({{ $rol->id }})"
                                class="text-red-600 block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 {{ in_array($rol->name,['Administrador','Cobrador']) ? 'opacity-50 cursor-not-allowed pointer-events-none' : '' }}">
                                <i class="fas fa-trash-alt mr-2"></i> Eliminar
                            </a>
                        </div>
                    </div>
                    @endif
                </div>

            </div>
        </div>
        @empty
        <div class="p-4 text-center text-gray-500 dark:text-gray-400">
            No se encontraron roles
        </div>
        @endforelse
    </div>

    {{-- Paginación --}}
    <div class="mt-4 flex flex-col sm:flex-row justify-between items-center text-sm text-gray-600 dark:text-gray-400">
        <div>
            Mostrando {{ $roles->firstItem() }} a {{ $roles->lastItem() }} de {{ $roles->total() }} resultados
        </div>
        <div class="mt-2 sm:mt-0">
            {{ $roles->links() }}
        </div>
    </div>



    {{-- Modal Crear / Editar --}}
    @if ($isOpen)
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div
            class="bg-white dark:bg-gray-800 w-full max-w-lg p-6 rounded-2xl shadow-xl overflow-y-auto max-h-[80vh] mx-4">
            <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-gray-100">
                {{ $modo === 'crear' ? 'Crear Rol' : 'Editar Rol' }}
            </h2>
            <form wire:submit.prevent="guardar" class="space-y-4 mb-16">
                <div>
                    <label class="block text-gray-700 dark:text-gray-300">Nombre</label>
                    <input type="text" wire:model="form.name"
                        class="w-full border rounded-lg px-3 py-2 dark:bg-gray-700 dark:text-gray-200">
                    @error('form.name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-gray-700 dark:text-gray-300 mb-2">Permisos</label>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                        @foreach($form->allPermissions as $perm)
                        <label class="flex items-center gap-2 dark:text-gray-200">
                            <input type="checkbox" wire:model="form.permissions" value="{{ $perm->id }}">
                            {{ $perm->name }}
                        </label>
                        @endforeach
                    </div>
                    @error('form.permissions')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" wire:click="$set('isOpen', false)"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- Modal Ver Rol --}}
    @if($verModal && $rolVer)
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 px-4">
        <div
            class="bg-white dark:bg-gray-800 w-full max-w-4xl p-6 rounded-2xl shadow-xl overflow-y-auto max-h-[80vh] relative mb-16">

            {{-- Botón de Cierre --}}
            <button wire:click="cerrarModalVer"
                class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 transition">
                <i class="fas fa-times fa-lg"></i>
            </button>

            <h2 class="text-2xl font-bold mb-2 text-gray-800 dark:text-gray-100">
                Detalles del Rol
            </h2>
            <p class="text-lg font-semibold text-indigo-600 dark:text-indigo-400 mb-4">{{ $rolVer->name }}</p>

            <h3 class="text-lg font-semibold mb-2 dark:text-gray-300">Permisos Asociados</h3>

            <div class="flex flex-wrap gap-2">
                @forelse($rolVer->permissions as $perm)
                <span
                    class="bg-blue-200 text-blue-800 dark:bg-blue-900 dark:text-blue-200 px-3 py-1 rounded-full text-sm font-medium">
                    {{ $perm->name }}
                </span>
                @empty
                <p class="text-gray-500 dark:text-gray-400">Este rol no tiene permisos asignados.</p>
                @endforelse
            </div>

        </div>
    </div>
    @endif

    {{-- Modal Confirmar Eliminación --}}
    @if($modalConfirmar)
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg w-full max-w-sm p-6 mx-4">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-4">Confirmar eliminación</h2>
            <p class="text-gray-600 dark:text-gray-300 mb-6">¿Estás seguro de que deseas eliminar este rol? Esta acción
                no se puede deshacer.</p>
            <div class="flex justify-end space-x-3">
                <button wire:click="$set('modalConfirmar', false)"
                    class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded-lg text-gray-800">
                    Cancelar
                </button>
                <button wire:click="eliminarConfirmado"
                    class="px-4 py-2 bg-red-600 hover:bg-red-700 rounded-lg text-white">
                    Sí, eliminar
                </button>
            </div>
        </div>
    </div>
    @endif

</div>