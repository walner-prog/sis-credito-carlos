<!DOCTYPE html>
<html class="dark" x-data="{ darkMode: true }" x-bind:class="{ 'dark': darkMode }"
    @keydown.window.escape="darkMode = false">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>sofnica | Pagos Recibidos</title>
    <meta name="description"
        content="SOFNICA - Soluciones inteligentes para tu negocio. Software estable, rápido y fácil de usar." />
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

    <!-- SwiperJS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <!-- Tippy.js -->
    <link rel="stylesheet" href="https://unpkg.com/tippy.js@6/themes/light-border.css" />

    <!-- AlpineJS -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
    <link rel="stylesheet" href="{{ asset('css/app.css') }}" />
    <script src="{{ asset('js/app.js') }}" defer></script>
    @endif
</head>

<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] min-h-screen flex flex-col">

    <!-- NAV -->
    <x-navbar />


    <main class="flex-grow">


    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        
        <h1 class="text-2xl font-bold mb-6 text-[#1b1b18] dark:text-gray-100">
            Pagos Recibidos
        </h1>

        <table class="min-w-full bg-white dark:bg-[#1a1a1a] border rounded text-[#1b1b18] dark:text-gray-200">
            <thead>
                <tr class="bg-gray-100 dark:bg-[#2a2a2a]">
                    <th class="px-4 py-2 border">Order ID</th>
                    <th class="px-4 py-2 border">Email</th>
                    <th class="px-4 py-2 border">Monto</th>
                    <th class="px-4 py-2 border">Descripcion</th>
                    <th class="px-4 py-2 border">Fecha</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pagos as $pago)
                <tr class="hover:bg-gray-50 dark:hover:bg-[#333333]">
                    <td class="px-4 py-2 border">{{ $pago->paypal_order_id }}</td>
                    <td class="px-4 py-2 border">{{ $pago->payer_email }}</td>
                    <td class="px-4 py-2 border">${{ $pago->amount }}</td>
                       <td class="px-4 py-2 border">{{ $pago->description }}</td>
                    <td class="px-4 py-2 border"> {{ $pago->created_at->setTimezone('America/Managua')->format('d/m/Y H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>


        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>



        <x-footer />
    </main>


    </div>



</body>

</html>



</body>

</html>