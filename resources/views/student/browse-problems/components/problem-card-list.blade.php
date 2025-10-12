{{-- resources/views/student/browse-problems/components/problem-card-list.blade.php --}}
{{-- ✅ PERBAIKAN: gunakan accessor coverImage dari model Problem --}}
<div class="bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden group mb-4">
    <div class="flex flex-col md:flex-row">
        
        {{-- gambar cover --}}
        <div class="relative md:w-80 h-48 md:h-auto overflow-hidden bg-gray-100 flex-shrink-0">
            @php
                $coverImage = $problem->coverImage;
                $isWishlisted = $problem->isWishlisted ?? false;
            @endphp
            
            @if($coverImage)
                <img src="{{ $coverImage->image_url }}" 
                     alt="{{ $problem->title }}"
                     onerror="this.onerror=null; this.src='https://via.placeholder.com/320x200?text=No+Image';"
                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                     loading="lazy">
            @else
                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-100 to-green-100">
                    <svg class="w-20 h-20 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            @endif

            {{-- status badge --}}
            @php
                $statusConfig = [
                    'open' => ['bg' => 'bg-green-500', 'text' => 'Terbuka'],
                    'in_progress' => ['bg' => 'bg-blue-500', 'text' => 'Berlangsung'],
                    'closed' => ['bg' => 'bg-gray-500', 'text' => 'Ditutup'],
                    'completed' => ['bg' => 'bg-purple-500', 'text' => 'Selesai'],
                ];
                $status = $statusConfig[$problem->status] ?? $statusConfig['open'];
            @endphp
            
            <span class="absolute top-3 left-3 {{ $status['bg'] }} text-white text-xs font-semibold px-3 py-1.5 rounded-full shadow-lg">
                {{ $status['text'] }}
            </span>

            {{-- wishlist button --}}
            @auth
                @if(Auth::user()->user_type === 'student')
                <button onclick="toggleWishlist({{ $problem->id }}, this)" 
                        class="absolute top-3 right-3 w-10 h-10 bg-white/90 backdrop-blur-sm rounded-full flex items-center justify-center hover:bg-white transition-all duration-200 shadow-lg"
                        data-wishlisted="{{ $isWishlisted ? 'true' : 'false' }}">
                    <svg class="w-5 h-5 {{ $isWishlisted ? 'fill-red-500 text-red-500' : 'text-gray-600' }}" 
                         fill="{{ $isWishlisted ? 'currentColor' : 'none' }}" 
                         stroke="currentColor" 
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </button>
                @endif
            @endauth

            {{-- difficulty badge --}}
            <span class="absolute bottom-3 left-3 px-3 py-1 text-xs font-semibold rounded-full shadow-lg
                {{ $problem->difficulty_level === 'beginner' ? 'bg-green-500 text-white' : '' }}
                {{ $problem->difficulty_level === 'intermediate' ? 'bg-yellow-500 text-white' : '' }}
                {{ $problem->difficulty_level === 'advanced' ? 'bg-red-500 text-white' : '' }}">
                {{ ucfirst($problem->difficulty_level) }}
            </span>
        </div>

        {{-- content --}}
        <div class="flex-1 p-6">
            <div class="flex items-start justify-between mb-3">
                <div class="flex-1">
                    <h3 class="text-xl font-bold text-gray-900 mb-2 line-clamp-2 hover:text-blue-600 transition-colors">
                        <a href="{{ route('student.browse-problems.show', $problem->id) }}">
                            {{ $problem->title }}
                        </a>
                    </h3>
                    
                    {{-- institution --}}
                    <div class="flex items-center gap-2 text-sm text-gray-600 mb-2">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <span>{{ $problem->institution->name }}</span>
                    </div>
                    
                    {{-- location --}}
                    <div class="flex items-center gap-2 text-sm text-gray-600 mb-3">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>{{ $problem->regency->name ?? $problem->location_regency }}, {{ $problem->province->name ?? $problem->location_province }}</span>
                    </div>
                </div>
            </div>

            {{-- description --}}
            <p class="text-gray-700 text-sm mb-4 line-clamp-2">
                {{ $problem->description }}
            </p>

            {{-- sdg categories --}}
            <div class="flex flex-wrap gap-2 mb-4">
                @foreach(array_slice($problem->sdg_categories ?? [], 0, 4) as $sdg)
                <span class="px-2 py-1 bg-blue-50 text-blue-700 text-xs font-medium rounded">
                    SDG {{ $sdg }}
                </span>
                @endforeach
                
                @if(count($problem->sdg_categories ?? []) > 4)
                <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs font-medium rounded">
                    +{{ count($problem->sdg_categories) - 4 }} Lainnya
                </span>
                @endif
            </div>

            {{-- metadata --}}
            <div class="flex flex-wrap items-center gap-4 text-xs text-gray-600 mb-4">
                <div class="flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span>{{ $problem->required_students }} Mahasiswa</span>
                </div>
                <div class="flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>{{ $problem->duration_months }} Bulan</span>
                </div>
                <div class="flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Deadline: {{ \Carbon\Carbon::parse($problem->application_deadline)->format('d M Y') }}</span>
                </div>
            </div>

            {{-- actions --}}
            <div class="flex items-center gap-3">
                <a href="{{ route('student.browse-problems.show', $problem->id) }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    Lihat Detail
                </a>
                
                @if($problem->status === 'open')
                <a href="{{ route('student.applications.create', $problem->id) }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Daftar Sekarang
                </a>
                @endif
            </div>
        </div>
    </div>
</div>