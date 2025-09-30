<nav class="bg-white shadow-sm border-b border-gray-200 fixed top-0 left-0 right-0 z-50" x-data="{ mobileMenuOpen: false }">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
        <div class="flex justify-between items-center h-16">
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="flex items-center space-x-2 text-2xl font-bold text-primary-600">
                    <span>KKN-GO</span>
                </a>
            </div>

            <div class="hidden md:flex items-center space-x-8">
                @guest
                    <a href="{{ route('home') }}" class="text-gray-700 hover:text-primary-600 transition-colors">Beranda</a>
                    <a href="#tentang" class="text-gray-700 hover:text-primary-600 transition-colors">Tentang</a>
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-primary-600 transition-colors">Masuk</a>
                    <a href="{{ route('register') }}" class="btn-primary">Daftar</a>
                @endguest

                @auth
                    <a href="{{ route(auth()->user()->user_type . '.dashboard') }}" class="text-gray-700 hover:text-primary-600 transition-colors">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-700 hover:text-primary-600 transition-colors">Keluar</button>
                    </form>
                @endauth
            </div>

            <div class="md:hidden">
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" style="display: none;"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</nav>