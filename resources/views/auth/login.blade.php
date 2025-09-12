<x-guest-layout>
    <div class="flex flex-col items-center justify-center min-h-[80vh] bg-gray-100 dark:bg-gray-900 px-4">
        <!-- Logo -->
        <div class="mb-4">
            <livewire:navigation-logo size="h-32 w-auto" />
        </div>

        <!-- Login Card -->
        <div class="w-full max-w-md bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 space-y-4">
            <!-- Session Status -->
            <x-auth-session-status :status="session('status')" class="mb-2" />

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf


                <!-- Usuario -->
                <div class="relative">
                    <x-input-label for="username" :value="__('Usuario')" class="text-gray-700 dark:text-gray-300" />
                    <div class="mt-1 relative">
                        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                            <!-- ícono de usuario -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M5.121 17.804A9 9 0 1118.879 17.804M12 12a3 3 0 100-6 3 3 0 000 6z" />
                            </svg>
                        </span>
                        <x-text-input id="username" name="username" type="text" :value="old('username')" required
                            autofocus autocomplete="username"
                            class="block w-full pl-10 pr-3 mt-1 bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" />
                    </div>
                    <x-input-error :messages="$errors->get('username')" class="mt-1 text-red-600 dark:text-red-400" />
                </div>



                <!-- Password -->
                <div class="relative">
                    <x-input-label for="password" :value="__('Contraseña')" class="text-gray-700 dark:text-gray-300" />
                    <div class="mt-1 relative">
                        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 11c0-1.657-1.343-3-3-3s-3 1.343-3 3 1.343 3 3 3 3-1.343 3-3z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19.428 15.341A8 8 0 1112 4a8 8 0 017.428 11.341z" />
                            </svg>
                        </span>
                        <x-text-input id="password" name="password" type="password" required
                            autocomplete="current-password"
                            class="block w-full pl-10 pr-3 mt-1 bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" />
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-1 text-red-600 dark:text-red-400" />
                </div>

                <!-- Remember Me -->
                <div class="flex items-center">
                    <label for="remember_me" class="inline-flex items-center space-x-2">
                        <input id="remember_me" type="checkbox"
                            class="rounded border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-indigo-600 focus:ring-indigo-500 shadow-sm"
                            name="remember">
                        <span class="text-sm text-gray-600 dark:text-gray-300">{{ __('Recordarme') }}</span>
                    </label>
                </div>

                <!-- Button -->
                <div>
                    <x-primary-button
                        class="w-full py-2.5 text-lg font-semibold rounded-xl bg-indigo-600 hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all">
                        {{ __('Iniciar sesión') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>