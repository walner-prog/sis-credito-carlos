<!DOCTYPE html>
<html class="dark" x-data="{ darkMode: true }" x-bind:class="{ 'dark': darkMode }" @keydown.window.escape="darkMode = false">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>sofnica | Inicio</title>
    <meta name="description" content="SOFNICA - Soluciones inteligentes para tu negocio. Software estable, rápido y fácil de usar." />
    <meta name="keywords" content="sofnica, software, soluciones, negocio, gimnasio, punto de venta, licencias" />
    <meta name="author" content="Walner Alvarez" />
    <link rel="icon" href="data:image/svg+xml,
    %3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 316 316'%3E
    %3Cpath fill='blue' d='M305.8 81.125C305.77 80.995...Z'/%3E
    %3C/svg%3E" type="image/svg+xml">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />


    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />



    @livewireStyles
    @vite(['resources/js/app.js', 'resources/css/app.css'])

</head>

<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] min-h-screen flex flex-col">

    <!-- NAV -->
    <x-navbar />


    <main class="flex-grow">

        <div class=" ">

            <div class="max-w-2xl mx-auto p-8 bg-white dark:bg-gray-800 rounded-2xl shadow-lg mt-10">
                <h2 class="text-3xl font-bold mb-6 text-green-600 dark:text-green-300">
                    ¡Gracias por tu compra!
                </h2>
                <p class="mb-4 text-gray-700 dark:text-gray-200">
                    Hemos recibido tu pago correctamente. A continuación, los detalles:
                </p>
                <ul class="space-y-2 text-gray-700 dark:text-gray-300">
                    <li><strong>Pedido ID:</strong> {{ $pago->id }}</li>
                    <li><strong>Correo del comprador:</strong> {{ $pago->payer_email }}</li>
                    <li><strong>Monto:</strong> ${{ number_format($pago->amount, 2) }}</li>
                    <li><strong>Referencia PayPal:</strong> {{ $pago->paypal_order_id }}</li>
                </ul>

                <div class="mt-8 flex gap-4">
                    <a href="{{ route('pago.factura.pdf', $pago) }}" class="px-6 py-3 bg-emerald-600 text-white rounded-xl font-bold hover:bg-emerald-700 transition">
                        Descargar factura PDF
                    </a>

                    <a href="/" class="px-6 py-3 bg-gray-400 text-white rounded-xl font-bold hover:bg-gray-500 transition">
                        Volver al inicio
                    </a>
                </div>
            </div>

        </div>

        <x-footer />
    </main>


    </div>






    @livewireScripts
</body>

</html>



</body>

</html>
