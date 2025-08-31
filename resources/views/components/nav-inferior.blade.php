<section class="fixed bottom-0 left-0 right-0 z-50 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 sm:hidden">
    <div class="flex h-16 justify-around items-center px-4">
        <a href="{{ route('clientes') }}"
            class="flex flex-col items-center justify-center text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200">
            <i class="fa-solid fa-users text-xl"></i>
            <span class="text-xs mt-1">Clientes</span>
        </a>
        <a href="{{ route('creditos') }}"
            class="flex flex-col items-center justify-center text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200">
            <i class="fa-solid fa-wallet text-xl"></i>
            <span class="text-xs mt-1">Créditos</span>
        </a>
        <a href="{{ route('abonos') }}"
            class="flex flex-col items-center justify-center text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200">
            <i class="fa-solid fa-file-invoice-dollar text-xl"></i>
            <span class="text-xs mt-1">Abonos</span>
        </a>
    </div>
</section>

<div class="h-16 w-full sm:hidden"></div>