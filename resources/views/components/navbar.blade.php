<nav class="bg-white shadow-sm border-b border-gray-200 fixed top-0 left-0 right-0 z-50" x-data="{ mobileMenuOpen: false }">
    <div class="container-custom">
        <div class="flex justify-between items-center h-16">
            <!-- logo -->
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="flex items-center space-x-2 text-2xl font-bold text-primary-600">
                    <span>KKN-GO</span>
                </a>
            </div>

            <!-- navigation links - desktop -->
            <div class="hidden md:flex items-center space-x-8">
                @guest
                    <a href="{{ route('home') }}" class="text-gray-700 hover:text-primary-600 transition-colors">
                        Beranda
                    </a>
                    <a href="#tentang" class="text-gray-700 hover:text-primary-600 transition-colors">
                        Tentang
                    </a>
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-primary-600 transition-colors">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="btn-primary">
                        Daftar
                    </a>
                @endguest

                @auth
                    <a href="{{ route(auth()->user()->user_type . '.dashboard') }}" class="text-gray-700 hover:text-primary-600 transition-colors">
                        Dashboard
                    </a>
                    
                    <!-- user menu -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center space-x-2 text-gray-700 hover:text-primary-600 focus:outline-none">
                            <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center">
                                <span class="text-primary-600 font-semibold text-sm">
                                    {{ strtoupper(substr(auth()->user()->username, 0, 2)) }}
                                </span>
                            </div>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <!-- dropdown -->
                        <div x-show="open" @click.away="open = false" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 border border-gray-200"
                             style="display: none;">
                            
                            <div class="px-4 py-2 border-b border-gray-200">
                                <p class="text-sm font-medium text-gray-900">{{ auth()->user()->full_name }}</p>
                                <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                            </div>

                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                Profil Saya
                            </a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                Pengaturan
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-50">
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                @endauth
            </div>

            <!-- mobile menu button -->
            <div class="md:hidden">
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-700 hover:text-primary-600 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" style="display: none;"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- mobile menu -->
    <div x-show="mobileMenuOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-95"
         class="md:hidden border-t border-gray-200"
         style="display: none;">
        <div class="px-2 pt-2 pb-3 space-y-1">
            @guest
                <a href="{{ route('home') }}" class="block px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-50">
                    Beranda
                </a>
                <a href="#tentang" class="block px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-50">
                    Tentang
                </a>
                <a href="{{ route('login') }}" class="block px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-50">
                    Masuk
                </a>
                <a href="{{ route('register') }}" class="block px-3 py-2 rounded-lg bg-primary-600 text-white text-center">
                    Daftar
                </a>
            @endguest

            @auth
                <a href="{{ route(auth()->user()->user_type . '.dashboard') }}" class="block px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-50">
                    Dashboard
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left px-3 py-2 rounded-lg text-red-600 hover:bg-gray-50">
                        Keluar
                    </button>
                </form>
            @endauth
        </div>
    </div>
</nav>