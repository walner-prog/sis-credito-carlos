<div class="p-6 lg:p-12 bg-gradient-to-r from-blue-50 via-purple-50 to-pink-50 
            dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 min-h-screen">
    <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-6">Gestión de Usuarios</h2>

    <!-- Solo visible en escritorio -->
    <div class="hidden lg:flex justify-between items-center gap-4">
        <button wire:click="abrirModalCrear" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl shadow-md transition">
            + Nuevo Usuario
        </button>
        <input type="text" wire:model.live="search" placeholder="Buscar por nombre o email..." class="border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 w-full sm:w-1/3 
               dark:bg-gray-800 dark:text-gray-200 focus:ring focus:ring-blue-400">
    </div>

    <!-- Solo visible en móvil/tablet -->
    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 lg:hidden">
        <button wire:click="abrirModalCrear" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl shadow-md transition">
            + Nuevo Usuario
        </button>
        <input type="text" wire:model.live="search" placeholder="Buscar por nombre o email..." class="border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 w-full sm:w-1/3 
               dark:bg-gray-800 dark:text-gray-200 focus:ring focus:ring-blue-400">
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


    {{-- Tabla --}}
    <div class="overflow-x-auto mt-6 hidden lg:block">
        <table class="w-full border-collapse rounded-lg overflow-hidden shadow-md">
            <thead>
                <tr class="bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                    <th class="p-3 text-left">Foto</th>
                    <th class="p-3 text-left">Nombre</th>
                    <th class="p-3 text-left">Usuario</th>
                    <th class="p-3 text-left">Email</th>
                    <th class="p-3 text-left">Rol</th>
                    <th class="p-3 text-left">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800">
                @forelse ($usuarios as $usuario)
                <tr wire:key="usuario-{{ $usuario->id }}" class="border-b dark:border-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-800 transition">
                    <td class="p-3">
                        @if ($usuario->profile_photo_path)
                        <img src="{{ asset('storage/'.$usuario->profile_photo_path) }}" alt="{{ $usuario->name }}" class="h-10 w-10 rounded-full object-cover">
                        @else
                        <i class="fas fa-user h-10 w-10 text-gray-400 flex items-center justify-center bg-gray-200 rounded-full text-center text-lg"></i>
                        @endif
                    </td>
                    <td class="p-3">{{ $usuario->name }}</td>
                    <td class="p-3">{{ $usuario->username }}</td>
                    <td class="p-3">{{ $usuario->email ?: 'No Registrado' }}</td>
                    <td class="p-3">
                        @foreach($usuario->roles as $rol)
                        <span class="px-2 py-1 bg-indigo-200 text-indigo-800 rounded text-xs">
                            {{ $rol->name }}
                        </span>
                        @endforeach
                    </td>
                    <td class="p-3 flex flex-wrap gap-2">
                        <button wire:click="abrirModalEditar({{ $usuario->id }})" class="bg-yellow-500 hover:bg-yellow-600 text-white p-2 rounded-full shadow-lg hover:scale-110 transition-transform duration-200 flex items-center justify-center" title="Editar">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button wire:click="abrirModalVer({{ $usuario->id }})" class="bg-indigo-500 hover:bg-indigo-600 text-white p-2 rounded-full shadow-lg hover:scale-110 transition-transform duration-200 flex items-center justify-center" title="Detalles">
                            <i class="fas fa-eye"></i>
                        </button>
                        @if(!$usuario->roles->contains('name', 'Administrador'))
                        <button wire:click="confirmarEliminar({{ $usuario->id }})" class="bg-red-600 hover:bg-red-700 text-white p-2 rounded-full shadow-lg hover:scale-110 transition-transform duration-200 flex items-center justify-center" title="Eliminar">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-4 text-center text-gray-500 dark:text-gray-400">
                        No se encontraron usuarios
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6 grid grid-cols-1 gap-4 lg:hidden">
        @forelse ($usuarios as $usuario)
        <div wire:key="mobile-usuario-{{ $usuario->id }}" class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-4">
                    {{-- Foto o ícono de usuario --}}
                    @if ($usuario->profile_photo_path)
                    <img src="{{ asset('storage/'.$usuario->profile_photo_path) }}" alt="{{ $usuario->name }}" class="h-12 w-12 rounded-full object-cover">
                    @else
                    <i class="fas fa-user h-12 w-12 text-gray-400 flex items-center justify-center bg-gray-200 rounded-full text-center text-lg"></i>
                    @endif
                    <div>
                        <h4 class="font-bold text-gray-800 dark:text-gray-100">{{ $usuario->name }}</h4>
                        <h4 class="font-bold text-gray-800 dark:text-gray-100">{{ $usuario->username }}</h4>

                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $usuario->email ?: 'No Registrado' }}</p>
                    </div>
                </div>

                {{-- Menú de Acciones --}}

                <div class="relative inline-block text-left">
                    <button wire:click="toggleMenu({{ $usuario->id }})" class="inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-700 shadow-sm px-2 py-1 
               bg-white dark:bg-gray-700 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 
               dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>

                    @if($menuAccionId === $usuario->id)
                    <div class="origin-top-right absolute right-0 mt-2 w-40 rounded-md shadow-lg 
                    bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 z-10">
                        <div class="py-1">
                            <button wire:click="abrirModalEditar({{ $usuario->id }})" class="text-gray-700 dark:text-gray-200 block w-full text-left px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                                <i class="fas fa-edit mr-2"></i> Editar
                            </button>
                            <button wire:click="abrirModalVer({{ $usuario->id }})" class="text-gray-700 dark:text-gray-200 block w-full text-left px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                                <i class="fas fa-eye mr-2"></i> Detalles
                            </button>
                            @unless($usuario->hasRole('Administrador'))
                            <button wire:click="confirmarEliminar({{ $usuario->id }})" class="text-red-600 block w-full text-left px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                                <i class="fas fa-trash-alt mr-2"></i> Eliminar
                            </button>
                            @endunless
                        </div>
                    </div>
                    @endif
                </div>

            </div>

            {{-- Información Adicional --}}
            <div class="mt-2 text-sm text-gray-600 dark:text-gray-400 border-t pt-2 border-gray-200 dark:border-gray-700">
                <p><strong>Usuario:</strong> {{ $usuario->username }}</p>
                <div class="mt-1">
                    <strong>Roles:</strong>
                    @foreach($usuario->roles as $rol)
                    <span class="px-2 py-1 bg-indigo-200 text-indigo-800 rounded text-xs inline-block mt-1">
                        {{ $rol->name }}
                    </span>
                    @endforeach
                </div>
            </div>
        </div>
        @empty
        <div class="p-4 text-center text-gray-500 dark:text-gray-400">
            No se encontraron usuarios
        </div>
        @endforelse
    </div>

    {{-- Paginación --}}
    <div class="mt-4 flex flex-col sm:flex-row justify-between items-center text-sm text-gray-600 dark:text-gray-400">
        <div>
            Mostrando {{ $usuarios->firstItem() }} a {{ $usuarios->lastItem() }} de {{ $usuarios->total() }} resultados
        </div>
        <div class="mt-2 sm:mt-0">
            {{ $usuarios->links() }}
        </div>
    </div>

    {{-- Modal Crear / Editar --}}
    @if ($isOpen)
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 px-4">
        <div class="bg-white dark:bg-gray-800 w-full max-w-lg p-6 rounded-2xl shadow-xl overflow-y-auto max-h-[80vh] relative">
            <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-gray-100">
                {{ $modo === 'crear' ? 'Crear Usuario' : 'Editar Usuario' }}
            </h2>

            <form wire:submit.prevent="guardar" class="space-y-4 mb-16">
                <div>
                    <label class="block text-gray-700 dark:text-gray-300">Nombre</label>
                    <input type="text" wire:model="form.name" class="w-full border rounded-lg px-3 py-2 dark:bg-gray-700 dark:text-gray-200">
                    @error('form.name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-gray-700 dark:text-gray-300">Usuario</label>
                    <input type="text" wire:model="form.username" class="w-full border rounded-lg px-3 py-2 dark:bg-gray-700 dark:text-gray-200">
                    @error('form.username') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-gray-700 dark:text-gray-300">Email (opcional)</label>
                    <input type="email" wire:model="form.email" class="w-full border rounded-lg px-3 py-2 dark:bg-gray-700 dark:text-gray-200">
                    @error('form.email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                 {{--  <div>
                    <label class="block text-gray-700 dark:text-gray-300">Foto de perfil</label>
                    <input type="file" wire:model="form.profile_photo_path" class="w-full border rounded-lg px-3 py-2 dark:bg-gray-700 dark:text-gray-200">
                    @error('form.profile_photo_path') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                    
                    @if ($form->profilePhotoTemp)
                    <div class="mt-2">
                        <p class="text-sm text-gray-500 dark:text-gray-300">Vista previa:</p>
                        <img src="{{ $form->profilePhotoTemp }}" alt="Vista previa" class="h-24 w-24 rounded-full object-cover border">
                    </div>
                    @elseif ($modo === 'editar' && $usuario && $usuario->profile_photo_path)
                    <div class="mt-2">
                        <p class="text-sm text-gray-500 dark:text-gray-300">Foto actual:</p>
                        <img src="{{ $usuario->profile_photo_path }}" alt="Foto actual" class="h-24 w-24 rounded-full object-cover border">
                    </div>
                    @endif
                </div>

                --}}


                <div>
                    <label class="block text-gray-700 dark:text-gray-300">Rol</label>
                    <select wire:model.live="form.role_id" class="border rounded w-full px-2 py-1 dark:bg-gray-700 dark:text-gray-200">
                        <option value="">-- Selecciona un rol --</option>
                        @foreach($form->roles as $rol)
                        <option value="{{ $rol->id }}">{{ ucfirst($rol->name) }}</option>
                        @endforeach
                    </select>
                    @error('form.role_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                {{-- 🚨 Nuevo campo para la cartera, solo visible para el rol de Cobrador --}}
                @if ($form->role_id && \Spatie\Permission\Models\Role::find($form->role_id)?->name === 'Cobrador')
                <div>
                    <label for="cartera_id" class="block text-gray-700 dark:text-gray-300">
                        Asignar Cartera
                    </label>
                    <select wire:model.live="form.cartera_id" id="cartera_id" class="w-full border rounded-lg px-3 py-2 dark:bg-gray-700 dark:text-gray-200">
                        <option value="">-- Selecciona una Cartera --</option>
                        @foreach($form->carteras as $cartera)
                        <option value="{{ $cartera->id }}">{{ $cartera->nombre }}</option>
                        @endforeach
                    </select>
                    @error('form.cartera_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                @endif

                @if($modo === 'crear')
                <div>
                    <label class="block text-gray-700 dark:text-gray-300">Contraseña</label>
                    <input type="password" wire:model="form.password" class="w-full border rounded-lg px-3 py-2 dark:bg-gray-700 dark:text-gray-200">
                    @error('form.password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                @endif

                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" wire:click="$set('isOpen', false)" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
                        Cancelar
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition flex items-center gap-2">
                        <span>Guardar</span>
                        <div wire:loading wire:target="guardar">
                            <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
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


    {{-- Modal Detalles --}}
    @if($verModal && $usuarioVer)
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white dark:bg-gray-800 dark:text-gray-200 w-full max-w-4xl p-6 rounded-2xl shadow-xl overflow-y-auto max-h-[90vh]">

            <h2 class="text-2xl font-bold mb-4 text-gray-800 dark:text-gray-100">
                Detalles del Usuario: {{ $usuarioVer->name }}
            </h2>

            <p class="text-gray-700 dark:text-gray-200"><strong class="text-gray-800 dark:text-gray-100">Email:</strong>
                {{ $usuarioVer->email }}</p>
            <p class="text-gray-700 dark:text-gray-200"><strong class="text-gray-800 dark:text-gray-100">Creado
                    el:</strong> {{ $usuarioVer->created_at->format('d/m/Y H:i') }}</p>
            <p class="text-gray-700 dark:text-gray-100"><strong class="text-gray-800 dark:text-gray-100">Roles:</strong>
                @foreach($usuarioVer->roles as $rol)
                <span class="px-2 py-1 bg-indigo-200 text-indigo-800 rounded text-xs">{{ $rol->name }}</span>
                @endforeach
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
        <div class="bg-white dark:bg-gray-800 dark:text-gray-200 rounded-xl shadow-lg w-96 p-6">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-4">Confirmar eliminación</h2>
            <p class="text-gray-600 dark:text-gray-300 mb-6">¿Estás seguro de que deseas eliminar este usuario? Esta acción no se puede
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
