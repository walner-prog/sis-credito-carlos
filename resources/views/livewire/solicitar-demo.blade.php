<div class="mt-6 max-w-2xl mx-auto p-6 bg-white dark:bg-gray-800 rounded-lg shadow-lg">
    @if ($successMessage)
        <div class="p-4 bg-blue-100 text-blue-800 rounded shadow mb-6" wire:poll.10s="clearSuccess">
            {{ $successMessage }}
        </div>
    @endif

    <form wire:submit.prevent="submit" class="space-y-4 animate__animated animate__fadeIn">
        <input type="text" wire:model="nombre" placeholder="Tu nombre"
               class="w-full p-3 rounded border border-blue-400 bg-blue-50 dark:bg-blue-900/20 dark:border-blue-600 dark:text-white"
               required>
        @error('nombre') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror

        <input type="email" wire:model="email" placeholder="Tu correo"
               class="w-full p-3 rounded border border-blue-400 bg-blue-50 dark:bg-blue-900/20 dark:border-blue-600 dark:text-white"
               required>
        @error('email') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror

        <input type="text" wire:model="whatsapp" placeholder="WhatsApp"
               class="w-full p-3 rounded border border-blue-400 bg-blue-50 dark:bg-blue-900/20 dark:border-blue-600 dark:text-white"
               required>
        @error('whatsapp') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror

        <button type="submit"
            class="w-full p-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-md transition duration-200 ease-in-out">
            Enviar solicitud
        </button>
    </form>
</div>
