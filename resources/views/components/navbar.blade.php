{{-- resources/views/components/navbar.blade.php (UPDATED) --}}
<nav class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm" x-data="{ mobileMenuOpen: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            
            {{-- logo --}}
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="flex items-center">
                    <img src="{{ asset('kkn-go-logo.png') }}" alt="KKN-GO" class="h-8">
                </a>
            </div>

            {{-- desktop navigation --}}
            <div class="hidden md:flex items-center space-x-1">
                @auth
                    @if(Auth::user()->user_type === 'student')
                        {{-- student menu --}}
                        <a href="{{ route('student.dashboard') }}" 
                           class="px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors {{ request()->routeIs('student.dashboard') ? 'bg-gray-100 font-semibold' : '' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('student.browse-problems.index') }}" 
                           class="px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors {{ request()->routeIs('student.browse-problems.*') ? 'bg-gray-100 font-semibold' : '' }}">
                            Browse
                        </a>
                        <a href="{{ route('student.applications.index') }}" 
                           class="px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors {{ request()->routeIs('student.applications.*') ? 'bg-gray-100 font-semibold' : '' }}">
                            Applications
                        </a>
                        <a href="{{ route('student.projects.index') }}" 
                           class="px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors {{ request()->routeIs('student.projects.*') ? 'bg-gray-100 font-semibold' : '' }}">
                            Projects
                        </a>
                        <a href="{{ route('student.repository.index') }}" 
                           class="px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors {{ request()->routeIs('student.repository.*') ? 'bg-gray-100 font-semibold' : '' }}">
                            Repository
                        </a>
                    @elseif(Auth::user()->user_type === 'institution')
                        {{-- institution menu --}}
                        <a href="{{ route('institution.dashboard') }}" 
                           class="px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors {{ request()->routeIs('institution.dashboard') ? 'bg-gray-100 font-semibold' : '' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('institution.problems.index') }}" 
                           class="px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors {{ request()->routeIs('institution.problems.*') ? 'bg-gray-100 font-semibold' : '' }}">
                            Problems
                        </a>
                        <a href="{{ route('institution.applications.index') }}" 
                           class="px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors {{ request()->routeIs('institution.applications.*') ? 'bg-gray-100 font-semibold' : '' }}">
                            Applications
                        </a>
                        <a href="{{ route('institution.projects.index') }}" 
                           class="px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors {{ request()->routeIs('institution.projects.*') ? 'bg-gray-100 font-semibold' : '' }}">
                            Projects
                        </a>
                        <a href="{{ route('institution.reviews.index') }}" 
                           class="px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors {{ request()->routeIs('institution.reviews.*') ? 'bg-gray-100 font-semibold' : '' }}">
                            Reviews
                        </a>
                    @endif
                @else
                    {{-- guest menu --}}
                    <a href="{{ route('home') }}" 
                       class="px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors {{ request()->routeIs('home') ? 'bg-gray-100 font-semibold' : '' }}">
                        Beranda
                    </a>
                @endguest
            </div>

            {{-- user menu / auth buttons --}}
            <div class="hidden md:flex items-center space-x-4">
                @auth
                    {{-- user dropdown --}}
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" 
                                class="flex items-center space-x-2 px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors">
                            @if(Auth::user()->user_type === 'student' && Auth::user()->student && Auth::user()->student->profile_photo_path)
                                <img src="{{ asset('storage/' . Auth::user()->student->profile_photo_path) }}" 
                                     alt="{{ Auth::user()->name }}"
                                     class="w-8 h-8 rounded-full object-cover">
                            @elseif(Auth::user()->user_type === 'institution' && Auth::user()->institution && Auth::user()->institution->logo_path)
                                <img src="{{ asset('storage/' . Auth::user()->institution->logo_path) }}" 
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
                             class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-xl border border-gray-200 py-1 z-[9999]">
                            
                            @if(Auth::user()->user_type === 'student')
                                <a href="{{ route('student.profile.index') }}" 
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                    Profil Saya
                                </a>
                                <a href="{{ route('student.portfolio.index') }}" 
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                    Portfolio
                                </a>
                                <a href="{{ route('student.wishlist.index') }}" 
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                    Wishlist
                                </a>
                            @elseif(Auth::user()->user_type === 'institution')
                                <a href="{{ route('institution.profile.index') }}" 
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                    Profil Instansi
                                </a>
                            @endif
                            
                            <hr class="my-1">
                            
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" 
                                        class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" 
                       class="px-4 py-2 text-gray-700 hover:text-gray-900 font-medium transition-colors">
                        Login
                    </a>
                    <a href="{{ route('register') }}" 
                       class="px-4 py-2 bg-gradient-to-r from-blue-600 to-green-600 text-white rounded-lg hover:from-blue-700 hover:to-green-700 transition-all font-medium">
                        Daftar
                    </a>
                @endguest
            </div>

            {{-- mobile menu button --}}
            <div class="md:hidden">
                <button @click="mobileMenuOpen = !mobileMenuOpen" 
                        class="p-2 rounded-lg text-gray-600 hover:bg-gray-100 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              :d="mobileMenuOpen ? 'M6 18L18 6M6 6l12 12' : 'M4 6h16M4 12h16M4 18h16'"></path>
                    </svg>
                </button>
            </div>
        </div>

        {{-- mobile menu --}}
        <div x-show="mobileMenuOpen" 
             @click.away="mobileMenuOpen = false"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-1"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-1"
             class="md:hidden py-4 border-t border-gray-200">
            @auth
                @if(Auth::user()->user_type === 'student')
                    <a href="{{ route('student.dashboard') }}" 
                       class="block px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors {{ request()->routeIs('student.dashboard') ? 'bg-gray-100 font-semibold' : '' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('student.browse-problems.index') }}" 
                       class="block px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors {{ request()->routeIs('student.browse-problems.*') ? 'bg-gray-100 font-semibold' : '' }}">
                        Browse
                    </a>
                    <a href="{{ route('student.applications.index') }}" 
                       class="block px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors">
                        Applications
                    </a>
                    <a href="{{ route('student.projects.index') }}" 
                       class="block px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors">
                        Projects
                    </a>
                    <a href="{{ route('student.repository.index') }}" 
                       class="block px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors">
                        Repository
                    </a>
                    <hr class="my-2">
                    <a href="{{ route('student.profile.index') }}" 
                       class="block px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors">
                        Profil Saya
                    </a>
                @elseif(Auth::user()->user_type === 'institution')
                    <a href="{{ route('institution.dashboard') }}" 
                       class="block px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors {{ request()->routeIs('institution.dashboard') ? 'bg-gray-100 font-semibold' : '' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('institution.problems.index') }}" 
                       class="block px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors">
                        Problems
                    </a>
                    <a href="{{ route('institution.applications.index') }}" 
                       class="block px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors">
                        Applications
                    </a>
                    <a href="{{ route('institution.projects.index') }}" 
                       class="block px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors">
                        Projects
                    </a>
                    <a href="{{ route('institution.reviews.index') }}" 
                       class="block px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors">
                        Reviews
                    </a>
                    <hr class="my-2">
                    <a href="{{ route('institution.profile.index') }}" 
                       class="block px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors">
                        Profil Instansi
                    </a>
                @endif
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" 
                            class="block w-full text-left px-3 py-2 rounded-lg text-red-600 hover:bg-red-50 transition-colors">
                        Logout
                    </button>
                </form>
            @else
                <a href="{{ route('home') }}" 
                   class="block px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors">
                    Beranda
                </a>
                <hr class="my-2">
                <a href="{{ route('login') }}" 
                   class="block px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors">
                    Login
                </a>
                <a href="{{ route('register') }}" 
                   class="block px-3 py-2 rounded-lg bg-gradient-to-r from-blue-600 to-green-600 text-white text-center font-medium">
                    Daftar Sekarang
                </a>
            @endguest
        </div>
    </div>
</nav>