<div>
    @if($nombre_sistema)
        <span class="text-3xl sm:text-4xl font-extrabold text-blue-600 dark:text-blue-400 tracking-wide">
            {{ $nombre_sistema }}
        </span>
    @else
        <span class="text-3xl sm:text-4xl font-extrabold text-blue-600 dark:text-blue-400 tracking-wide">
            <span class="text-gray-800 dark:text-gray-200">C</span>
            <span class="text-blue-600 dark:text-blue-400">G</span> Sistema
        </span>
    @endif
</div>
