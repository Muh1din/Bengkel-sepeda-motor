<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="description"
        content="Nirwana Garage adalah bengkel cat dan servis motor yang menyediakan layanan pengecatan, perawatan, dan perbaikan sepeda motor dengan pelayanan profesional dan terpercaya.">

    <title>Nirwana Garage | Premium Motorcycle Repaint & Performance</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <link rel="icon" type="image/png" href="{{ asset('logo/logo.jpeg') }}">

    <title>Nirwana Garage | Bengkel Cat dan Servis Motor</title>


    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<!-- Hapus overflow-x-hidden dari <body> -->

<body class="bg-ui-main text-ui-text font-sans">

    <x-home.navigation.navigation></x-home.navigation.navigation>

    <!-- Bungkus $slot dengan div pembungkus khusus jika butuh cegah horizontal scroll -->
    <main class="overflow-x-hidden">
        {{ $slot }}
    </main>

    <x-home.footer.footer></x-home.footer.footer>

</body>

</html>
