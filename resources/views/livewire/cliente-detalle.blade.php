<div>
    @if($isOpen)
        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div class="bg-white dark:bg-gray-800 w-full max-w-3xl p-6 rounded-2xl shadow-xl overflow-y-auto max-h-[90vh]">

                <h2 class="text-2xl font-bold mb-4 text-gray-800 dark:text-gray-100">
                    Detalles del Cliente: {{ $cliente->nombres }}
                </h2>

                {{-- Resumen --}}
                <div class="grid grid-cols-3 gap-4 my-4">
                    <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-lg text-center">
                        <p class="text-sm">Total Créditos</p>
                        <p class="text-xl font-bold">{{ $cliente->creditos->count() }}</p>
                    </div>
                    <div class="p-3 bg-green-100 dark:bg-green-900 rounded-lg text-center">
                        <p class="text-sm">Saldo Pendiente</p>
                        <p class="text-xl font-bold">
                            ${{ number_format($cliente->creditos->sum('saldo_pendiente'), 2) }}
                        </p>
                    </div>
                    <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-lg text-center">
                        <p class="text-sm">Total Abonado</p>
                        <p class="text-xl font-bold">
                            ${{ number_format($cliente->creditos->flatMap->abonos->sum('monto_abono'), 2) }}
                        </p>
                    </div>
                </div>

                {{-- Datos Cliente --}}
                <div class="grid grid-cols-2 gap-4 text-gray-700 dark:text-gray-300">
                    <p><strong>Nombres:</strong> {{ $cliente->nombres }}</p>
                    <p><strong>Apellidos:</strong> {{ $cliente->apellidos }}</p>
                    <p><strong>Identificación:</strong> {{ $cliente->identificacion }}</p>
                    <p><strong>Teléfono:</strong> {{ $cliente->telefono }}</p>
                    <p class="col-span-2"><strong>Dirección:</strong> {{ $cliente->direccion }}</p>
                </div>

                <hr class="my-4 border-gray-300 dark:border-gray-700">

                {{-- Créditos --}}
                <h3 class="text-lg font-semibold mb-2">Créditos</h3>

                @forelse($cliente->creditos as $credito)
                    <div class="mb-4 p-4 border rounded-lg dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                        <p><strong>Monto:</strong> ${{ number_format($credito->monto_total, 2) }}</p>
                        <p><strong>Saldo Pendiente:</strong> ${{ number_format($credito->saldo_pendiente, 2) }}</p>
                        <p><strong>Cartera:</strong> {{ $credito->cartera->nombre ?? 'Sin cartera' }}</p>
                        <p><strong>Estado:</strong>
                            <span class="px-2 py-1 rounded text-sm 
                                {{ $credito->estado === 'activo' ? 'bg-green-200 text-green-800' : ($credito->estado === 'moroso' ? 'bg-red-200 text-red-800' : 'bg-gray-200 text-gray-800') }}">
                                {{ ucfirst($credito->estado) }}
                            </span>
                        </p>
                        <p><strong>Plazo:</strong> {{ $credito->plazo }} {{ $credito->unidad_plazo }}</p>
                        <p><strong>Inicio:</strong> {{ $credito->fecha_inicio }}</p>
                        <p><strong>Vencimiento:</strong> {{ $credito->fecha_vencimiento }}</p>

                        {{-- Abonos --}}
                        <div class="mt-3">
                            <h4 class="font-semibold">Abonos</h4>
                            @forelse($credito->abonos as $abono)
                                <div class="text-sm flex justify-between border-b py-1">
                                    <span>Cuota #{{ $abono->numero_cuota }} - {{ $abono->fecha_abono }}</span>
                                    <span class="font-medium">${{ number_format($abono->monto_abono, 2) }}</span>
                                </div>
                            @empty
                                <p class="text-gray-500 text-sm">No hay abonos registrados para este crédito.</p>
                            @endforelse
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500">No hay créditos registrados para este cliente.</p>
                @endforelse

                <div class="flex justify-end mt-4">
                    <button type="button" wire:click="cerrarModal"
                        class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
