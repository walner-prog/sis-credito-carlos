<div class="bg-gray-100 dark:bg-gray-900 min-h-screen p-4 sm:p-6 lg:p-8">



    <div class="max-w-4xl mx-auto space-y-8">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-6">Gestión de Configuración del Sistema</h2>
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-4 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.942 3.313.842 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.942 1.543-.842 3.313-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.942-3.313-.842-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.942-1.543.842-3.313 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Configuración Actual
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 text-sm">
                <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg shadow-sm">
                    <p class="text-gray-500 dark:text-gray-400 font-medium">Nombre del Sistema</p>
                    <p class="text-gray-900 dark:text-gray-200 mt-1 font-bold">{{ $config->nombre_sistema ?? 'No definido' }}</p>
                </div>
                <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg shadow-sm">
                    <p class="text-gray-500 dark:text-gray-400 font-medium">RUC</p>
                    <p class="text-gray-900 dark:text-gray-200 mt-1 font-bold">{{ $config->ruc ?? 'No definido' }}</p>
                </div>
                <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg shadow-sm">
                    <p class="text-gray-500 dark:text-gray-400 font-medium">Tasa de Interés Global</p>
                    <p class="text-gray-900 dark:text-gray-200 mt-1 font-bold">{{ $config->tasa_interes_global ?? '0' }}%</p>
                </div>
                <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg shadow-sm">
                    <p class="text-gray-500 dark:text-gray-400 font-medium">Frecuencia de Cuota</p>
                    <p class="text-gray-900 dark:text-gray-200 mt-1 font-bold capitalize">{{ $config->cuota_frecuencia_default ?? 'Diaria' }}</p>
                </div>
                <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg shadow-sm">
                    <p class="text-gray-500 dark:text-gray-400 font-medium">Días de Gracia</p>
                    <p class="text-gray-900 dark:text-gray-200 mt-1 font-bold">{{ $config->dias_gracia_primera_cuota ?? '0' }} días</p>
                </div>
                <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg shadow-sm">
                    <p class="text-gray-500 dark:text-gray-400 font-medium">Múltiples Créditos</p>
                    <p class="text-gray-900 dark:text-gray-200 mt-1 font-bold">
                        @if($config->permite_multicredito ?? false) Sí @else No @endif
                    </p>
                </div>

            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-4 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a5.25 5.25 0 017.425 7.425L10 18.995l-5 1 1-5L15.232 5.232z" />
                </svg>
                Editar Configuración
            </h2>

            <form wire:submit.prevent="guardar" class="space-y-6">

                <div>
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-4">Información General</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-gray-700 dark:text-gray-300 font-medium mb-1">Logo</label>
                            <input type="file" wire:model="logoUpload" class="block w-full text-sm text-gray-600 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-blue-700 dark:file:text-blue-100">

                            @error('logoUpload') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                            @if($logoTemp)
                            <!-- Vista previa del logo temporal subido a Imgbb -->
                            <img class="mt-4 h-20 w-auto rounded-lg shadow-md" src="{{ $logoTemp }}" alt="Nuevo Logo">
                            @elseif($config->logo)
                            <!-- Logo actual desde Imgbb -->
                            <img class="mt-4 h-20 w-auto rounded-lg shadow-md" src="{{ $config->logo }}" alt="Logo Actual">
                            @endif
                        </div>

                        <div>
                            <label class="text-gray-700 dark:text-gray-300 font-medium">Nombre del sistema</label>
                            <input type="text" wire:model="nombre_sistema" class="w-full mt-1 border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                        </div>
                        <div>
                            <label class="text-gray-700 dark:text-gray-300 font-medium">RUC</label>
                            <input type="text" wire:model="ruc" class="w-full mt-1 border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                        </div>
                        <div>
                            <label class="text-gray-700 dark:text-gray-300 font-medium">Teléfono</label>
                            <input type="text" wire:model="telefono" class="w-full mt-1 border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                        </div>
                        <div>
                            <label class="text-gray-700 dark:text-gray-300 font-medium">Propietario</label>
                            <input type="text" wire:model="propietario" class="w-full mt-1 border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                        </div>
                        <div class="md:col-span-2">
                            <label class="text-gray-700 dark:text-gray-300 font-medium">Dirección</label>
                            <input type="text" wire:model="direccion" class="w-full mt-1 border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                        </div>
                    </div>
                </div>

                <hr class="my-6 border-gray-200 dark:border-gray-700">

                <div>
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-4">Opciones Financieras</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div>
                            <label class="text-gray-700 dark:text-gray-300 font-medium">Tasa de interés global (%)</label>
                            <input type="number" step="0.01" wire:model="tasa_interes_global" class="w-full mt-1 border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                        </div>
                        <div>
                            <label class="text-gray-700 dark:text-gray-300 font-medium">Frecuencia de cuota</label>
                            <select wire:model="cuota_frecuencia_default" class="w-full mt-1 border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                                <option value="diaria">Diaria</option>
                                <option value="semanal">Semanal</option>
                                <option value="quincenal">Quincenal</option>
                                <option value="mensual">Mensual</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-gray-700 dark:text-gray-300 font-medium">Unidad de plazo</label>
                            <div class="flex items-center mt-1">
                                <input type="text" value="Días" class="w-full border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 cursor-not-allowed" readonly>
                            </div>
                        </div>
                        <div>
                            <label class="text-gray-700 dark:text-gray-300 font-medium">Días de gracia</label>
                            <input type="number" wire:model="dias_gracia_primera_cuota" class="w-full mt-1 border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                            <p class="text-xs text-gray-500 mt-1">Días antes de la primera cuota.</p>
                        </div>
                        <div class="md:col-span-2">
                            <label class="text-gray-700 dark:text-gray-300 font-medium">Días no cobrables</label>
                            <select wire:model="dias_no_cobrables" multiple class="w-full mt-1 border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 h-24">
                                <option value="domingo">Domingo</option>
                                <option value="lunes">Lunes</option>
                                <option value="martes">Martes</option>
                                <option value="miércoles">Miércoles</option>
                                <option value="jueves">Jueves</option>
                                <option value="viernes">Viernes</option>
                                <option value="sábado">Sábado</option>
                            </select>
                        </div>
                    </div>
                </div>

                <hr class="my-6 border-gray-200 dark:border-gray-700">

                <div>
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-4">Opciones Avanzadas</h3>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" wire:model="permite_multicredito" id="multi" class="rounded text-blue-600 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600">
                        <label class="text-gray-700 dark:text-gray-300 font-medium cursor-pointer" for="multi">Permitir múltiples créditos por cliente</label>
                    </div>
                </div>

                @if (session()->has('update'))
                <div class="bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 p-4 mb-6 rounded-xl shadow-lg transition-all duration-300 transform scale-100" x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show">
                    {{ session('update') }}
                </div>
                @endif

                <div class="flex justify-end pt-4">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl shadow-lg transition-all duration-300 font-bold transform hover:scale-105">
                        Guardar Configuración
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
