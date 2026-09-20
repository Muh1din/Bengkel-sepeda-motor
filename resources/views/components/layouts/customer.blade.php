@props(['title'])
<!DOCTYPE html>
<html lang="id" class="h-full bg-ui-main">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nirwana Garage | {{ $title }} </title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="font-sans antialiased min-h-screen bg-ui-main text-ui-text flex flex-col md:flex-row relative overflow-x-hidden">

    <!-- Sidebar Component -->
    <x-customer.sidebar></x-customer.sidebar>

    <!-- Mobile Overlay (Backdrop saat sidebar terbuka) -->
    <div id="sidebar-overlay"
        class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-30 hidden md:hidden transition-opacity duration-300">
    </div>

    <!-- Main Content Wrapper -->
    <div class="flex-1 md:pl-64 flex flex-col min-w-0 min-h-screen transition-all duration-300">
        <!-- Header Component -->
        <x-customer.header :title="$title"></x-customer.header>

        <!-- Main Content Body -->
        <main class="flex-1 p-4 sm:p-6 md:p-8 space-y-6 md:space-y-8">
            {{ $slot }}
        </main>
    </div>

    <x-customer.logout-modal></x-customer.logout-modal>

    <script src="https://unpkg.com/lucide@latest"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
        });
    </script>


</body>

</html>
