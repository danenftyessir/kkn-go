<nav class="bg-white shadow-sm border-b border-gray-200 fixed top-0 left-0 right-0 z-50" x-data="{ mobileMenuOpen: false }">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
        <div class="flex justify-between items-center h-16">
            <!-- logo dan brand -->
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="flex items-center space-x-3">
                    <!-- logo kkngo -->
                    <img src="{{ asset('kkn-go-logo.png') }}" alt="KKN-GO Logo" class="h-10 w-auto">
                    <!-- brand text -->
                    <span class="text-2xl font-bold text-black">KKN-GO</span>
                </a>
            </div>

            <!-- desktop menu -->
            <div class="hidden md:flex items-center space-x-8">
                @guest
                    <a href="{{ route('home') }}" class="text-gray-700 hover:text-blue-600 transition-colors font-medium">Beranda</a>
                    <a href="#tentang" class="text-gray-700 hover:text-blue-600 transition-colors font-medium">Tentang</a>
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 transition-colors font-medium">Masuk</a>
                    <a href="{{ route('register') }}" class="text-gray-700 hover:text-blue-600 transition-colors font-medium">Daftar</a>
                @endguest

                @auth
                    <a href="{{ route(auth()->user()->user_type . '.dashboard') }}" class="text-gray-700 hover:text-blue-600 transition-colors font-medium">Dashboard</a>
                    
                    <!-- user profile dropdown -->
                    <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                        <button 
                            @click.stop="open = !open" 
                            type="button"
                            class="flex items-center space-x-2 text-gray-700 hover:text-blue-600 transition-colors focus:outline-none"
                            :aria-expanded="open">
                            <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold">
                                {{ strtoupper(substr(auth()->user()->name ?? auth()->user()->username, 0, 1)) }}
                            </div>
                            <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        
                        <!-- dropdown menu -->
                        <div 
                            x-show="open" 
                            x-cloak
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 transform scale-95"
                            x-transition:enter-end="opacity-100 transform scale-100"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 transform scale-100"
                            x-transition:leave-end="opacity-0 transform scale-95"
                            class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg py-2 border border-gray-200 z-[9999]"
                            @click.stop>
                            
                            <!-- info user -->
                            <div class="px-4 py-3 border-b border-gray-100">
                                <p class="text-sm font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ auth()->user()->email }}</p>
                            </div>

                            <!-- menu items -->
                            <div class="py-1">
                                <a href="{{ route(auth()->user()->user_type . '.profile.index') }}" 
                                   class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                    <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    profil saya
                                </a>
                                
                                <a href="{{ route(auth()->user()->user_type . '.dashboard') }}" 
                                   class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                    <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                    </svg>
                                    dashboard
                                </a>
                            </div>

                            <!-- logout -->
                            <div class="border-t border-gray-100 py-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" 
                                            class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                        </svg>
                                        keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endauth
            </div>

            <!-- mobile menu button -->
            <div class="md:hidden">
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-700 hover:text-blue-600 transition-colors focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- mobile menu -->
    <div x-show="mobileMenuOpen" 
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform -translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform -translate-y-2"
         class="md:hidden border-t border-gray-200 bg-white">
        <div class="px-6 py-4 space-y-3">
            @guest
                <a href="{{ route('home') }}" class="block text-gray-700 hover:text-blue-600 transition-colors font-medium py-2">Beranda</a>
                <a href="#tentang" class="block text-gray-700 hover:text-blue-600 transition-colors font-medium py-2">Tentang</a>
                <a href="{{ route('login') }}" class="block text-gray-700 hover:text-blue-600 transition-colors font-medium py-2">Masuk</a>
                <a href="{{ route('register') }}" class="block text-gray-700 hover:text-blue-600 transition-colors font-medium py-2">Daftar</a>
            @endguest

            @auth
                <a href="{{ route(auth()->user()->user_type . '.dashboard') }}" class="block text-gray-700 hover:text-blue-600 transition-colors font-medium py-2">Dashboard</a>
                <a href="{{ route(auth()->user()->user_type . '.profile.index') }}" class="block text-gray-700 hover:text-blue-600 transition-colors font-medium py-2">Profil Saya</a>
                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf
                    <button type="submit" class="w-full text-left text-red-600 hover:text-red-700 transition-colors font-medium py-2">
                        Keluar
                    </button>
                </form>
            @endauth
        </div>
    </div>
</nav>

{{-- add padding to body to account for fixed navbar --}}
<div class="h-16"></div>