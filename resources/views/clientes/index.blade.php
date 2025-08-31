<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Clientes</title>
</head>
<body>
    
    <x-app-layout>
    

    <div class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] min-h-screen flex flex-col">
     

    <main class="flex-grow">

      <livewire:clientes-list />
    </main>

  
      <script>
    // Esperar que el DOM cargue
    document.addEventListener('DOMContentLoaded', () => {
        const messages = document.querySelectorAll('.session-message');
        messages.forEach(msg => {
            setTimeout(() => {
                // Aplicar efecto de desvanecimiento
                msg.style.transition = 'opacity 0.5s';
                msg.style.opacity = 0;
                setTimeout(() => msg.remove(), 500); // Eliminar del DOM después del fade
            }, 7000); // 7000ms = 7 segundos
        });
    });
</script>

    </div>
    </div>
</x-app-layout>

</body>
</html>