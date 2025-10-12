{{-- components/navbar.blade.php --}}
<nav class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            {{-- logo dan nama aplikasi --}}
            <div class="flex items-center">
                <a href="{{ Auth::check() ? (Auth::user()->isStudent() ? route('student.dashboard') : route('institution.dashboard')) : route('home') }}" 
                   class="flex items-center">
                    <img src="{{ asset('kkn-go-logo.png') }}" alt="KKN-GO" class="h-10 w-auto">
                    <span class="ml-2 text-xl font-bold text-gray-900">KKN-GO</span>
                </a>
            </div>

            {{-- navigasi menu --}}
            <div class="hidden md:flex md:items-center md:space-x-8">
                @auth
                    @if(Auth::user()->isStudent())
                        {{-- menu untuk student --}}
                        <a href="{{ route('student.dashboard') }}" 
                           class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('student.dashboard') ? 'text-blue-600' : '' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('student.browse-problems.index') }}" 
                           class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('student.browse-problems.*') || request()->routeIs('student.problems.*') ? 'text-blue-600' : '' }}">
                            Browse
                        </a>
                        <a href="{{ route('student.applications.index') }}" 
                           class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('student.applications.*') ? 'text-blue-600' : '' }}">
                            Applications
                        </a>
                        <a href="{{ route('student.projects.index') }}" 
                           class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('student.projects.*') ? 'text-blue-600' : '' }}">
                            Projects
                        </a>
                        <a href="{{ route('student.repository.index') }}" 
                           class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('student.repository.*') ? 'text-blue-600' : '' }}">
                            Repository
                        </a>
                    @elseif(Auth::user()->isInstitution())
                        {{-- menu untuk institution --}}
                        <a href="{{ route('institution.dashboard') }}" 
                           class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('institution.dashboard') ? 'text-blue-600' : '' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('institution.problems.index') }}" 
                           class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('institution.problems.*') ? 'text-blue-600' : '' }}">
                            Problems
                        </a>
                        <a href="{{ route('institution.applications.index') }}" 
                           class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('institution.applications.*') ? 'text-blue-600' : '' }}">
                            Applications
                        </a>
                        <a href="{{ route('institution.projects.index') }}" 
                           class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('institution.projects.*') ? 'text-blue-600' : '' }}">
                            Projects
                        </a>
                        <a href="{{ route('institution.reviews.index') }}" 
                           class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('institution.reviews.*') ? 'text-blue-600' : '' }}">
                            Reviews
                        </a>
                    @endif
                    
                    {{-- notification icon --}}
                    @include('components.notification-dropdown')

                    {{-- user dropdown --}}
                    <div class="relative ml-3" x-data="{ open: false }">
                        <button @click="open = !open" 
                                class="flex items-center space-x-2 p-1 rounded-lg hover:bg-gray-100 transition-colors">
                            @if(Auth::user()->isStudent() && Auth::user()->student)
                                <img src="{{ Auth::user()->student->profile_photo_url }}" 
                                     alt="{{ Auth::user()->name }}"
                                     class="w-8 h-8 rounded-full object-cover">
                            @elseif(Auth::user()->isInstitution() && Auth::user()->institution)
                                <img src="{{ Auth::user()->institution->logo_url }}" 
                                     alt="{{ Auth::user()->name }}"
                                     class="w-8 h-8 rounded-full object-cover">
                            @else
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-green-500 flex items-center justify-center">
                                    <span class="text-white text-sm font-bold">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                                </div>
                            @endif
                            <span class="text-gray-700 font-medium">{{ Auth::user()->name }}</span>
                            <svg class="w-4 h-4 text-gray-500" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        {{-- dropdown menu --}}
                        <div x-show="open" 
                             @click.away="open = false"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-56 rounded-lg shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 z-50"
                             style="display: none;">
                            
                            {{-- user info --}}
                            <div class="px-4 py-3">
                                <p class="text-sm text-gray-900 font-medium">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                            </div>

                            {{-- menu items --}}
                            <div class="py-1">
                                @if(Auth::user()->isStudent())
                                    <a href="{{ route('student.profile.index') }}" 
                                       class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        Profil & Portfolio
                                    </a>
                                    <a href="{{ route('student.wishlist.index') }}" 
                                       class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                        </svg>
                                        Wishlist
                                    </a>
                                @elseif(Auth::user()->isInstitution())
                                    <a href="{{ route('institution.profile.index') }}" 
                                       class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                        Profil Instansi
                                    </a>
                                @endif
                            </div>

                            {{-- logout --}}
                            <div class="py-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" 
                                            class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                        </svg>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    {{-- menu untuk guest --}}
                    <a href="{{ route('home') }}" 
                       class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium transition-colors">
                        Home
                    </a>
                    <a href="{{ route('about') }}" 
                       class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('about') ? 'text-blue-600' : '' }}">
                        About Us
                    </a>
                    <a href="{{ route('login') }}" 
                       class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium transition-colors">
                        Login
                    </a>
                    <a href="{{ route('register') }}" 
                       class="bg-blue-600 text-white hover:bg-blue-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        Register
                    </a>
                @endauth
            </div>

            {{-- mobile menu button --}}
            <div class="md:hidden flex items-center">
                <button @click="mobileMenuOpen = !mobileMenuOpen" 
                        type="button" 
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-700 hover:text-blue-600 hover:bg-gray-100 transition-colors"
                        x-data="{ mobileMenuOpen: false }">
                    <svg class="h-6 w-6" :class="{'hidden': mobileMenuOpen, 'block': !mobileMenuOpen}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg class="h-6 w-6" :class="{'block': mobileMenuOpen, 'hidden': !mobileMenuOpen}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- mobile menu --}}
    <div class="md:hidden" x-show="mobileMenuOpen" x-data="{ mobileMenuOpen: false }" style="display: none;">
        <div class="px-2 pt-2 pb-3 space-y-1">
            @auth
                @if(Auth::user()->isStudent())
                    <a href="{{ route('student.dashboard') }}" 
                       class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-100">
                        Dashboard
                    </a>
                    <a href="{{ route('student.browse-problems.index') }}" 
                       class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-100">
                        Browse
                    </a>
                    <a href="{{ route('student.applications.index') }}" 
                       class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-100">
                        Applications
                    </a>
                    <a href="{{ route('student.projects.index') }}" 
                       class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-100">
                        Projects
                    </a>
                    <a href="{{ route('student.repository.index') }}" 
                       class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-100">
                        Repository
                    </a>
                    <a href="{{ route('student.profile.index') }}" 
                       class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-100">
                        Profil & Portfolio
                    </a>
                @elseif(Auth::user()->isInstitution())
                    <a href="{{ route('institution.dashboard') }}" 
                       class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-100">
                        Dashboard
                    </a>
                    <a href="{{ route('institution.problems.index') }}" 
                       class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-100">
                        Problems
                    </a>
                    <a href="{{ route('institution.applications.index') }}" 
                       class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-100">
                        Applications
                    </a>
                    <a href="{{ route('institution.projects.index') }}" 
                       class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-100">
                        Projects
                    </a>
                    <a href="{{ route('institution.profile.index') }}" 
                       class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-100">
                        Profil Instansi
                    </a>
                @endif
                
                <form method="POST" action="{{ route('logout') }}" class="mt-4">
                    @csrf
                    <button type="submit" 
                            class="block w-full text-left px-3 py-2 rounded-md text-base font-medium text-red-600 hover:bg-red-50">
                        Logout
                    </button>
                </form>
            @else
                <a href="{{ route('home') }}" 
                   class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-100">
                    Home
                </a>
                <a href="{{ route('about') }}" 
                   class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-100">
                    About Us
                </a>
                <a href="{{ route('login') }}" 
                   class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-100">
                    Login
                </a>
                <a href="{{ route('register') }}" 
                   class="block px-3 py-2 rounded-md text-base font-medium bg-blue-600 text-white hover:bg-blue-700">
                    Register
                </a>
            @endauth
        </div>
    </div>
</nav>