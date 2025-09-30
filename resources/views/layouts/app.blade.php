<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'KKN-GO') }} - @yield('title', 'Platform Digital untuk Kuliah Kerja Nyata')</title>

    <!-- fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800|poppins:300,400,500,600,700,800" rel="stylesheet" />

    <!-- styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="font-sans antialiased">
    <!-- flash messages untuk notification -->
    @if(session('success'))
        <div data-success-message="{{ session('success') }}" class="hidden"></div>
    @endif
    
    @if(session('error'))
        <div data-error-message="{{ session('error') }}" class="hidden"></div>
    @endif
    
    @if(session('info'))
        <div data-info-message="{{ session('info') }}" class="hidden"></div>
    @endif
    
    @if(session('warning'))
        <div data-warning-message="{{ session('warning') }}" class="hidden"></div>
    @endif

    <div class="min-h-screen bg-gray-50">
        <!-- navbar -->
        @include('components.navbar')

        <!-- main content -->
        <main class="page-transition">
            @yield('content')
        </main>

        <!-- footer -->
        @include('components.footer')
    </div>

    <!-- scroll to top button -->
    <button id="scroll-to-top" class="hidden fixed bottom-6 right-6 bg-primary-600 text-white p-3 rounded-full shadow-lg hover:bg-primary-700 transition-all duration-300 z-40">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
        </svg>
    </button>

    @stack('scripts')
</body>
</html>