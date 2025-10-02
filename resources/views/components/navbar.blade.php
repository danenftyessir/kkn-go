<nav class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- logo & brand -->
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="flex items-center space-x-3">
                    <img src="{{ asset('kkn-go-logo.png') }}" alt="KKN-GO" class="h-10 w-auto">
                    <span class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-green-600 bg-clip-text text-transparent">
                        KKN-GO
                    </span>
                </a>
            </div>

            <!-- navigation links -->
            @auth
                <div class="hidden md:flex items-center space-x-6">
                    @if(auth()->user()->user_type === 'student')
                        <a href="{{ route('student.dashboard') }}" 
                           class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('student.dashboard') ? 'text-blue-600' : '' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('student.browse-problems') }}" 
                           class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('student.browse-problems') ? 'text-blue-600' : '' }}">
                            Cari Proyek
                        </a>
                        <!-- TODO: tambahkan link lain sesuai kebutuhan -->
                    @elseif(auth()->user()->user_type === 'institution')
                        <a href="{{ route('institution.dashboard') }}" 
                           class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('institution.dashboard') ? 'text-blue-600' : '' }}">
                            Dashboard
                        </a>
                        <!-- TODO: tambahkan link untuk instansi -->
                    @endif
                </div>

                <!-- user dropdown -->
                <div class="flex items-center" x-data="{ open: false }" @click.away="open = false">
                    <div class="relative">
                        <button 
                            @click="open = !open"
                            type="button"
                            class="flex items-center space-x-2 rounded-full p-1 hover:bg-gray-100 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                            aria-expanded="false"
                            aria-haspopup="true">
                            
                            @php
                                $user = auth()->user();
                                $profilePhoto = null;
                                $initials = strtoupper(substr($user->username, 0, 1));
                                
                                // ambil foto profil berdasarkan user type
                                if ($user->user_type === 'student' && $user->student && $user->student->profile_photo_path) {
                                    $profilePhoto = $user->student->profile_photo_path;
                                    $firstName = $user->student->first_name ?? '';
                                    $lastName = $user->student->last_name ?? '';
                                    if ($firstName && $lastName) {
                                        $initials = strtoupper(substr($firstName, 0, 1) . substr($lastName, 0, 1));
                                    }
                                } elseif ($user->user_type === 'institution' && $user->institution && $user->institution->logo_path) {
                                    $profilePhoto = $user->institution->logo_path;
                                    $initials = strtoupper(substr($user->institution->institution_name ?? $user->username, 0, 1));
                                }
                            @endphp
                            
                            @if($profilePhoto && file_exists(public_path('storage/' . $profilePhoto)))
                                <img src="{{ asset('storage/' . $profilePhoto) }}" 
                                     alt="{{ $user->username }}" 
                                     class="w-9 h-9 rounded-full object-cover border-2 border-gray-200"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-sm font-bold border-2 border-gray-200" style="display: none;">
                                    {{ $initials }}
                                </div>
                            @else
                                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-sm font-bold border-2 border-gray-200">
                                    {{ $initials }}
                                </div>
                            @endif
                            
                            <svg class="w-4 h-4 text-gray-500 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        
                        <!-- dropdown menu -->
                        <div 
                            x-show="open" 
                            x-cloak
                            x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95"
                            class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden z-[9999]"
                            @click.stop>
                            
                            <!-- info user -->
                            <div class="px-4 py-3 border-b border-gray-100 bg-gray-50">
                                @if($user->user_type === 'student' && $user->student)
                                    <p class="text-sm font-medium text-gray-900 truncate">
                                        {{ $user->student->first_name ?? '' }} {{ $user->student->last_name ?? '' }}
                                    </p>
                                @elseif($user->user_type === 'institution' && $user->institution)
                                    <p class="text-sm font-medium text-gray-900 truncate">
                                        {{ $user->institution->institution_name ?? '' }}
                                    </p>
                                @else
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $user->username }}</p>
                                @endif
                                <p class="text-xs text-gray-500 truncate mt-0.5">{{ $user->email }}</p>
                            </div>

                            <!-- menu items -->
                            <div class="py-1">
                                <a href="{{ route($user->user_type . '.profile.index') }}" 
                                   class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                    <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <span>profil saya</span>
                                </a>
                                
                                <a href="{{ route($user->user_type . '.dashboard') }}" 
                                   class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                    <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                                    </svg>
                                    <span>dashboard</span>
                                </a>

                                <div class="border-t border-gray-100"></div>
                                
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" 
                                            class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                        </svg>
                                        <span>logout</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- guest menu -->
                <div class="flex items-center space-x-4">
                    <a href="{{ route('login') }}" 
                       class="text-gray-700 hover:text-blue-600 px-4 py-2 text-sm font-medium transition-colors">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" 
                       class="bg-blue-600 text-white hover:bg-blue-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm">
                        Daftar
                    </a>
                </div>
            @endauth
        </div>
    </div>
</nav>

@push('styles')
<style>
[x-cloak] { 
    display: none !important; 
}

/* smooth transition untuk dropdown */
nav [x-transition] {
    will-change: transform, opacity;
}

/* animasi hover untuk avatar */
nav button img,
nav button > div {
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

nav button:hover img,
nav button:hover > div {
    transform: scale(1.05);
}
</style>
@endpush