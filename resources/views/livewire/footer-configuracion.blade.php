<div class="mt-4 text-sm text-gray-600 dark:text-gray-400">
    @if(Auth::user() && Auth::user()->hasRole('Administrador') && $config)
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @if($config->ruc)
                <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg shadow-sm">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-300">RUC</p>
                    <p class="text-gray-800 dark:text-gray-100 break-words whitespace-normal">
                        {{ $config->ruc }}
                    </p>
                </div>
            @endif

            @if($config->direccion)
                <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg shadow-sm">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-300">Dirección</p>
                    <p class="text-gray-800 dark:text-gray-100 break-words whitespace-normal">
                        {{ $config->direccion }}
                    </p>
                </div>
            @endif

            @if($config->telefono)
                <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg shadow-sm">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-300">Teléfono</p>
                    <p class="text-gray-800 dark:text-gray-100 break-words whitespace-normal">
                        {{ $config->telefono }}
                    </p>
                </div>
            @endif

            @if($config->propietario)
                <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg shadow-sm">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-300">Propietario</p>
                    <p class="text-gray-800 dark:text-gray-100 break-words whitespace-normal">
                        {{ $config->propietario }}
                    </p>
                </div>
            @endif
        </div>
    @endif
</div>
