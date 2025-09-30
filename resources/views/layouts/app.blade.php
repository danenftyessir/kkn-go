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
    @vite(['resources/css/app.css'])
    
    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>

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

    <!-- scripts -->
    @vite(['resources/js/app.js'])
    @stack('scripts')

    <script>
    // scroll to top functionality
    const scrollBtn = document.getElementById('scroll-to-top');
    if (scrollBtn) {
        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                scrollBtn.classList.remove('hidden');
            } else {
                scrollBtn.classList.add('hidden');
            }
        }, { passive: true });

        scrollBtn.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }

    // notification manager
    class NotificationManager {
        constructor() {
            this.container = null;
            this.init();
        }

        init() {
            this.createContainer();
            this.showExistingNotifications();
        }

        createContainer() {
            if (document.getElementById('notification-container')) return;

            this.container = document.createElement('div');
            this.container.id = 'notification-container';
            this.container.className = 'fixed top-4 right-4 z-50 space-y-2';
            document.body.appendChild(this.container);
        }

        show(message, type = 'info', duration = 5000) {
            const notification = document.createElement('div');
            const colors = {
                success: 'bg-green-50 border-green-200 text-green-800',
                error: 'bg-red-50 border-red-200 text-red-800',
                info: 'bg-blue-50 border-blue-200 text-blue-800',
                warning: 'bg-yellow-50 border-yellow-200 text-yellow-800'
            };

            notification.className = `${colors[type]} px-4 py-3 rounded-lg border mb-4 animate-slideInRight shadow-lg max-w-sm`;
            notification.innerHTML = `
                <div class="flex items-center justify-between">
                    <span>${message}</span>
                    <button onclick="this.parentElement.parentElement.remove()" 
                            class="ml-4 text-gray-500 hover:text-gray-700">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
            `;

            this.container.appendChild(notification);

            if (duration > 0) {
                setTimeout(() => {
                    notification.classList.add('animate-fadeOut');
                    setTimeout(() => notification.remove(), 300);
                }, duration);
            }
        }

        showExistingNotifications() {
            const successMsg = document.querySelector('[data-success-message]');
            const errorMsg = document.querySelector('[data-error-message]');
            const infoMsg = document.querySelector('[data-info-message]');
            const warningMsg = document.querySelector('[data-warning-message]');

            if (successMsg) this.show(successMsg.dataset.successMessage, 'success');
            if (errorMsg) this.show(errorMsg.dataset.errorMessage, 'error');
            if (infoMsg) this.show(infoMsg.dataset.infoMessage, 'info');
            if (warningMsg) this.show(warningMsg.dataset.warningMessage, 'warning');
        }
    }

    // initialize notification manager
    document.addEventListener('DOMContentLoaded', () => {
        window.notificationManager = new NotificationManager();
    });
    </script>
</body>
</html>