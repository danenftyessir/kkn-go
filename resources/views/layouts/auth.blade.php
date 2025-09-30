<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }} - @yield('title')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <!-- flash messages -->
    @if(session('success'))
        <div data-success-message="{{ session('success') }}" class="hidden"></div>
    @endif
    
    @if(session('error'))
        <div data-error-message="{{ session('error') }}" class="hidden"></div>
    @endif

    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-primary-50 via-white to-blue-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full page-transition">
            @yield('content')
        </div>
    </div>

    @stack('scripts')
</body>
</html>