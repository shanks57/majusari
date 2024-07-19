<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - @yield('title')</title>
    
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/@phosphor-icons/web@2.1.1"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    @stack('styles')
</head>

<body class="h-full">
    <div class="min-h-full">
        <x-navbar></x-navbar>
        <main class="bg-gray-100 h-[calc(100vh-100px)] px-[80px] py-6">
            {{ $slot }}
        </main>
    </div>

    @stack('scripts')
</body>

</html>
