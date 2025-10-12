{{-- 
    resources/views/student/browse-problems/components/problem-card-list.blade.php
    
    komponen list view untuk problem
    digunakan di halaman browse problems (list view)
    
    props: $problem (Problem model)
--}}

<div class="bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden group mb-4">
    <div class="flex flex-col md:flex-row">
        
        {{-- gambar cover --}}
        <div class="relative md:w-80 h-48 md:h-auto overflow-hidden bg-gray-100 flex-shrink-0">
            @php
                $coverImage = $problem->images->where('is_cover', true)->first() ?? $problem->images->first();
                $isWishlisted = $problem->isWishlisted ?? false;
            @endphp
            
            @if($coverImage)
                <img src="{{ $coverImage->image_url }}" 
                     alt="{{ $problem->title }}"
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
                        style="transform: scale(1); transition: transform 0.2s cubic-bezier(0.4, 0, 0.2, 1);"
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
            <span class="absolute bottom-3 left-3 px-3 py-1.5 text-xs font-semibold rounded-full shadow-lg
                {{ $problem->difficulty_level === 'beginner' ? 'bg-green-500 text-white' : '' }}
                {{ $problem->difficulty_level === 'intermediate' ? 'bg-yellow-500 text-white' : '' }}
                {{ $problem->difficulty_level === 'advanced' ? 'bg-red-500 text-white' : '' }}">
                {{ ucfirst($problem->difficulty_level) }}
            </span>
        </div>

        {{-- content --}}
        <div class="flex-1 p-6">
            {{-- institution info --}}
            <div class="flex items-center gap-3 mb-3">
                @if($problem->institution && $problem->institution->logo_path)
                    <img src="{{ supabase_url($problem->institution->logo_path) }}" 
                         alt="{{ $problem->institution->name }}"
                         class="w-10 h-10 rounded-full object-cover"
                         loading="lazy">
                @else
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-green-500 flex items-center justify-center">
                        <span class="text-white text-sm font-bold">
                            {{ substr($problem->institution->name ?? 'I', 0, 1) }}
                        </span>
                    </div>
                @endif
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-900 truncate">{{ $problem->institution->name ?? 'Instansi' }}</p>
                    <p class="text-xs text-gray-500">{{ ucfirst($problem->institution->type ?? '') }}</p>
                </div>
            </div>

            {{-- title --}}
            <a href="{{ route('student.browse-problems.detail', $problem->id) }}" 
               class="block">
                <h3 class="text-xl font-bold text-gray-900 mb-2 hover:text-blue-600 transition-colors">
                    {{ $problem->title }}
                </h3>
            </a>

            {{-- description --}}
            <p class="text-sm text-gray-600 mb-4 line-clamp-3">
                {{ $problem->description }}
            </p>

            {{-- sdg categories --}}
            @if($problem->sdg_categories && count($problem->sdg_categories) > 0)
            <div class="flex flex-wrap gap-2 mb-4">
                @foreach(array_slice($problem->sdg_categories, 0, 3) as $sdg)
                <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded-full">
                    SDG {{ $sdg }}
                </span>
                @endforeach
                @if(count($problem->sdg_categories) > 3)
                <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs font-medium rounded-full">
                    +{{ count($problem->sdg_categories) - 3 }}
                </span>
                @endif
            </div>
            @endif

            {{-- meta info --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                {{-- location --}}
                <div class="flex items-center gap-2 text-sm text-gray-600">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span class="truncate">{{ $problem->regency->name ?? $problem->province->name ?? 'N/A' }}</span>
                </div>

                {{-- duration --}}
                <div class="flex items-center gap-2 text-sm text-gray-600">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span>{{ $problem->duration_months }} bulan</span>
                </div>

                {{-- students --}}
                <div class="flex items-center gap-2 text-sm text-gray-600">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <span>{{ $problem->required_students }} mahasiswa</span>
                </div>

                {{-- deadline --}}
                <div class="flex items-center gap-2 text-sm text-gray-600">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ \Carbon\Carbon::parse($problem->application_deadline)->format('d M Y') }}</span>
                </div>
            </div>

            {{-- action button --}}
            <div class="flex items-center gap-3">
                <a href="{{ route('student.browse-problems.detail', $problem->id) }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                    Lihat Detail
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
                
                <span class="text-xs text-gray-500">
                    {{ $problem->views_count ?? 0 }} views
                </span>
            </div>
        </div>
    </div>
</div>