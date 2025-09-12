<div x-data="{
        showToast: false,
        toastMessage: '',
        mostrarToast(message) {
            if(!message) return; // ✅ solo mostrar si hubo actualizaciones
            this.toastMessage = message;
            this.showToast = true;
            setTimeout(() => this.showToast = false, 5000);
        }
    }" 
    x-init="$wire.actualizarCuotas().then(msg => mostrarToast(msg))"
>
    <!-- Toast solo se renderiza si hay mensaje -->
    <template x-if="toastMessage">
        <div 
            x-show="showToast" 
            x-transition
            x-cloak
            class="fixed top-5 right-5 bg-blue-500 dark:bg-sky-600 text-white px-4 py-2 rounded shadow-lg"
            x-text="toastMessage">
        </div>
    </template>
</div>
