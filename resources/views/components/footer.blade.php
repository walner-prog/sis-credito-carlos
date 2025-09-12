<footer class="bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 py-6 border-t border-gray-200 dark:border-gray-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-6 items-start text-center md:text-left">

        {{-- Columna 1: Logo y nombre del sistema --}}
        <div class="flex flex-col items-center md:items-start">
            <livewire:nombre-sistema />

            <p class="hidden sm:block text-sm text-gray-500 dark:text-gray-400 mt-1">
                Gestión de Créditos y Abonos
            </p>
        </div>

        {{-- Columna 2: Contacto --}}
       {{--  <div class="flex flex-col items-center md:items-start space-y-1">
            <p class="flex items-center text-sm">
                <i class="fas fa-envelope mr-2 text-blue-600 dark:text-blue-400"></i>
                <a href="mailto:ca140611@gmail.com" class="hover:text-blue-600 dark:hover:text-blue-400">ca140611@gmail.com</a>
            </p>
            <p class="flex items-center text-sm">
                <i class="fab fa-whatsapp mr-2 text-green-600 dark:text-green-400"></i>
                <a href="https://wa.me/50585429144" target="_blank" class="hover:text-green-600 dark:hover:text-green-400">+505 8542 9144</a>
            </p>
        </div>
       --}}

     <div class="hidden md:flex flex-col items-center md:items-start space-y-1 text-sm text-gray-600 dark:text-gray-400">
    <p><strong>Sobre el sistema:</strong></p>
    <p>CG Sistema permite la gestión de créditos y abonos, facilitando el control de clientes, cuotas y reportes financieros de manera eficiente y segura.</p>
    <p>Optimizado para usuarios administrativos y cobradores, con configuración adaptable y control de roles.</p>
</div>


{{-- Columna 3: Configuraciones solo admin --}}
@if(Auth::user() && Auth::user()->hasRole('Administrador'))
    <div class="flex flex-col items-center md:items-end space-y-1">
        <livewire:footer-configuracion />
    </div>
@endif


    </div>

    <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700 text-center text-sm text-gray-500 dark:text-gray-400">
        &copy; {{ date('Y') }} CG Sistema. Todos los derechos reservados.
    </div>
</footer>
