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

    <!-- Boxicons untuk icon -->
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>

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
    <button id="scroll-to-top" class="hidden fixed bottom-6 right-6 bg-primary-600 text-black p-3 rounded-full shadow-lg hover:bg-primary-700 transition-all duration-300 z-40">
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
            const existingContainer = document.getElementById('notification-container');
            if (existingContainer) {
                this.container = existingContainer;
                return;
            }

            this.container = document.createElement('div');
            this.container.id = 'notification-container';
            // PERBAIKAN: ubah z-50 menjadi z-[1100]
            this.container.className = 'fixed top-20 right-4 z-[1100] space-y-2 max-w-sm';
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

            notification.className = `${colors[type]} px-4 py-3 rounded-lg border mb-4 animate-slideInRight shadow-lg max-w-md flex items-center gap-3`;

            const icons = {
                success: `<svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>`,
                error: `<svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>`,
                info: `<svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                </svg>`,
                warning: `<svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>`
            };

            notification.innerHTML = `
                ${icons[type]}
                <div class="flex-1">
                    <p class="text-sm font-medium">${message}</p>
                </div>
                <button onclick="this.parentElement.remove()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            `;

            this.container.appendChild(notification);

            if (duration > 0) {
                setTimeout(() => {
                    notification.style.opacity = '0';
                    notification.style.transform = 'translateX(100px)';
                    setTimeout(() => {
                        if (notification.parentNode) {
                            notification.remove();
                        }
                    }, 300);
                }, duration);
            }
        }

        showExistingNotifications() {
            // tampilkan flash messages dari session
            @if(session('success'))
                this.show('{{ session('success') }}', 'success');
            @endif

            @if(session('error'))
                this.show('{{ session('error') }}', 'error');
            @endif

            @if(session('info'))
                this.show('{{ session('info') }}', 'info');
            @endif

            @if(session('warning'))
                this.show('{{ session('warning') }}', 'warning');
            @endif
        }
    }

    // initialize notification manager
    const notificationManager = new NotificationManager();

    // export untuk digunakan di tempat lain
    window.showNotification = (message, type, duration) => {
        notificationManager.show(message, type, duration);
    };
    </script>
</body>
</html>