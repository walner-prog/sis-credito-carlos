<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                  <livewire:navigation-logo size="h-9 w-auto" />
                </div>
                <div class="hidden md:flex md:items-center md:space-x-8 md:ms-10">
                    <x-nav-link class="dark:text-gray-300" :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Panel') }}
                    </x-nav-link>
                    <x-nav-link class="dark:text-gray-300" :href="route('clientes')" :active="request()->routeIs('clientes')">
                        {{ __('Clientes') }}
                    </x-nav-link>
                    <x-nav-link class="dark:text-gray-300" :href="route('carteras')" :active="request()->routeIs('carteras')">
                        {{ __('Carteras') }}
                    </x-nav-link>
                    <x-nav-link class="dark:text-gray-300" :href="route('creditos')" :active="request()->routeIs('creditos')">
                        {{ __('Créditos') }}
                    </x-nav-link>
                    <x-nav-link class="dark:text-gray-300" :href="route('abonos')" :active="request()->routeIs('abonos')">
                        {{ __('Abonos') }}
                    </x-nav-link>
                    <x-nav-link class="dark:text-gray-300" :href="route('abonos.report')" :active="request()->routeIs('abonos.report')">
                        {{ __('Reporte de Abonos') }}
                    </x-nav-link>

                    @role('Administrador')
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="flex items-center text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-gray-100 focus:outline-none transition duration-150 ease-in-out">
                                <div>{{ __('Administración') }}</div>
                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link :href="route('usuarios')">
                                {{ __('Usuarios') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('roles')">
                                {{ __('Roles') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('configuraciones.index')">
                                {{ __('Configuraciones') }}
                            </x-dropdown-link>
                        </x-slot>
                    </x-dropdown>
                    @endrole

                </div>
            </div>
            <div class="hidden md:flex md:items-center md:space-x-4">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent rounded-full bg-white dark:bg-gray-800 shadow-sm hover:shadow-md transition-all focus:outline-none">

                            <!-- Foto de perfil o icono -->
                            @if(Auth::user()->profile_photo_path)
                            <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" alt="Perfil" class="h-10 w-10 rounded-full object-cover border-2 border-gray-300 dark:border-gray-600 shadow-sm">
                            @else
                            <i class="fas fa-user h-10 w-10 text-gray-400 flex items-center justify-center bg-gray-200 rounded-full border-2 border-gray-300 dark:border-gray-600 shadow-sm text-lg"></i>
                            @endif

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4 text-gray-600 dark:text-gray-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <!-- Mostrar nombre dentro del dropdown -->
                        <div class="block px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                            {{ Auth::user()->name }}
                        </div>
                        

                        <div class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-800 transition duration-150 ease-in-out">
                            <button id="dark-mode-toggle" class="w-full text-left">
                                🌙 {{ __('Modo Oscuro') }}
                            </button>
                        </div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Cerrar sesión') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>

            </div>
            <div class="flex items-center md:hidden">
                <button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-900 dark:focus:text-gray-100 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
    <div :class="{ 'block': open, 'hidden': !open }" class="fixed inset-0 bg-black bg-opacity-50 z-40 md:hidden" @click="open = false">
    </div>
    <div :class="{ 'translate-x-0 ease-out': open, '-translate-x-full ease-in': !open }" class="fixed inset-y-0 left-0 w-64 bg-white dark:bg-gray-800 shadow-lg transform transition-transform duration-300 z-50 md:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="flex items-center space-x-2">
                <i class="fa-solid fa-gauge"></i>
                <span>{{ __('Panel') }}</span>
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('clientes')" :active="request()->routeIs('clientes')" class="flex items-center space-x-2">
                <i class="fa-solid fa-users"></i>
                <span>{{ __('Clientes') }}</span>
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('carteras')" :active="request()->routeIs('carteras')" class="flex items-center space-x-2">
                <i class="fa-solid fa-folder-open"></i>
                <span>{{ __('Carteras') }}</span>
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('creditos')" :active="request()->routeIs('creditos')" class="flex items-center space-x-2">
                <i class="fa-solid fa-credit-card"></i>
                <span>{{ __('Créditos') }}</span>
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('abonos')" :active="request()->routeIs('abonos')" class="flex items-center space-x-2">
                <i class="fa-solid fa-money-bill-transfer"></i>
                <span>{{ __('Abonos') }}</span>
            </x-responsive-nav-link>
              @role('Administrador')
            <x-responsive-nav-link :href="route('abonos.report')" :active="request()->routeIs('abonos.report')" class="flex items-center space-x-2">
                <i class="fa-solid fa-chart-line"></i>
                <span>{{ __('Reporte de Abonos') }}</span>
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('usuarios')" :active="request()->routeIs('usuarios')" class="flex items-center space-x-2">
                <i class="fa-solid fa-user-gear"></i>
                <span>{{ __('Usuarios') }}</span>
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('roles')" :active="request()->routeIs('roles')" class="flex items-center space-x-2">
                <i class="fa-solid fa-user-lock"></i>
                <span>{{ __('Roles') }}</span>
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('configuraciones')" :active="request()->routeIs('configuraciones')" class="flex items-center space-x-2">
                <i class="fa-solid fa-user-lock"></i>
                <span>{{ __('Configuraciones') }}</span>
            </x-responsive-nav-link>

            @endrole
        </div>
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-700">
            <div class="px-4 flex items-center space-x-3">
                <!-- Foto de perfil o icono -->
                @if(Auth::user()->profile_photo_path)
                <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" alt="Perfil" class="h-10 w-10 rounded-full object-cover border-2 border-gray-300 dark:border-gray-600 shadow-sm">
                @else
                <i class="fas fa-user h-10 w-10 text-gray-400 flex items-center justify-center bg-gray-200 rounded-full border-2 border-gray-300 dark:border-gray-600 shadow-sm text-lg"></i>
                @endif

                <!-- Nombre del usuario -->
                <div class="font-medium text-base text-gray-800 dark:text-gray-100">
                    {{ Auth::user()->name }}
                </div>
            </div>

            <div class="mt-3 space-y-1">
               

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Cerrar sesión') }}
                    </x-responsive-nav-link>
                </form>

                <div class="px-4 pt-2">
                    <button id="dark-mode-toggle-mobile" class="text-xl px-2 py-1 rounded hover:bg-gray-200 dark:hover:bg-gray-700 transition w-full text-left">
                        🌙
                    </button>
                </div>
            </div>
        </div>

    </div>
</nav>


