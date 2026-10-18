@props(['title'])
<!DOCTYPE html>
<html lang="id" class="h-full bg-ui-main">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nirwana Garage | {{ $title }} </title>
    
    <link rel="icon" type="image/png" href="{{ asset('logo/logo.jpeg') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="font-sans antialiased min-h-screen bg-ui-main text-ui-text flex flex-col md:flex-row relative overflow-x-hidden">

    <x-mechanic.sidebar></x-mechanic.sidebar>

    <div class="flex-1 md:pl-64 flex flex-col min-w-0 min-h-screen transition-all duration-300">
        <!-- Header Component -->
        <x-mechanic.header :title="$title"></x-mechanic.header>

        <!-- Main Content Body -->
        <main class="flex-1 p-4 sm:p-6 md:p-8 space-y-6 md:space-y-8">
            {{ $slot }}
        </main>
    </div>

    <x-mechanic.logout-modal></x-mechanic.logout-modal>

</body>

</html>
