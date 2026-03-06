<!doctype html>
<html>
<head>
    {{-- <link rel="icon" type="image/x-icon" href="{{ asset('assets/images/favicon.ico') }}"> --}}
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Malang Gateway')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js']) <!-- JS sudah termasuk admin.js -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/trix@2.0.0/dist/trix.css">
    <link href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.3.0/dist/flowbite.min.css" rel="stylesheet" />
    @stack('style')
    @yield('head')
</head>
<body class="font-sans antialiased bg-gray-50">
    @include('components.toast')

    <!-- Konten penuh dikendalikan oleh child view -->
    @yield('content')

    @stack('scripts')
    <script src="https://unpkg.com/flowbite@latest/dist/flowbite.min.js"></script>
</body>
</html>