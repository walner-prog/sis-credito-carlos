<nav x-data="{ open: false }" class="bg-white dark:bg-gray-900 shadow">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">
        <!-- Logo + Links grandes -->
        <div class="flex items-center space-x-8">
            <a href="{{ url('/') }}"
                class="text-2xl font-bold text-blue-600 dark:text-blue-400 animate__animated animate__fadeInLeft">
                <i class="fas fa-code"></i> SOFNICA
            </a>
            <div class="hidden md:flex space-x-4">
                
            </div>
        </div>

        <!-- Botones y modo oscuro -->
        <div class="hidden md:flex items-center space-x-4">
            @auth
                <a href="{{ url('/dashboard') }}"
                    class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded transition animate__animated animate__fadeInRight">
                    Dashboard
                </a>
            @else
                <a href="{{ route('login') }}"
                    class="inline-block whitespace-nowrap bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded transition animate__animated animate__fadeInRight">
                    Iniciar Sesión
                </a>
            @endauth

            <button @click="darkMode = !darkMode"
                class="w-full p-2 rounded border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                <template x-if="darkMode">
                    <i class="fas fa-moon"></i>
                </template>
                <template x-if="!darkMode">
                    <i class="fas fa-sun"></i>
                </template>
            </button>
        </div>

        <!-- Botón toggle hamburguesa -->
        <div class="md:hidden">
            <button @click="open = !open" class="text-gray-700 dark:text-gray-300 focus:outline-none">
                <i :class="open ? 'fas fa-times' : 'fas fa-bars'"></i>
            </button>
        </div>
    </div>

    <!-- Menú móvil -->
    <div x-show="open" x-transition class="md:hidden px-4 pb-4 space-y-2">
        

        @auth
            <a href="{{ url('/dashboard') }}"
                class="block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded transition">
                Dashboard
            </a>
        @else
            <a href="{{ route('login') }}"
                class="block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded transition">
                Iniciar Sesión
            </a>
        @endauth

        <button @click="darkMode = !darkMode"
            class="w-full p-2 rounded border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 transition">
            <template x-if="darkMode">
                <i class="fas fa-moon"></i>
            </template>
            <template x-if="!darkMode">
                <i class="fas fa-sun"></i>
            </template>
        </button>
    </div>
</nav>
