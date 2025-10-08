{{-- 
    komponen list view untuk problem
    digunakan di halaman browse problems (list view)
    
    props: $problem (Problem model)
--}}

<div class="bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden group">
    <div class="flex flex-col md:flex-row">
        
        {{-- gambar cover --}}
        <div class="relative md:w-80 h-48 md:h-auto overflow-hidden bg-gray-100 flex-shrink-0">
            @php
                $coverImage = $problem->images->where('is_cover', true)->first() ?? $problem->images->first();
            @endphp
            
            @if($coverImage)
                <img src="{{ storage_url($coverImage->image_path) }}" 
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
            <button onclick="toggleWishlist({{ $problem->id }}, this)" 
                    class="absolute top-3 right-3 w-10 h-10 bg-white/90 backdrop-blur-sm rounded-full flex items-center justify-center hover:bg-white transition-all duration-200 shadow-lg {{ $problem->isWishlisted ? 'text-red-500' : 'text-gray-400 hover:text-red-500' }}"
                    data-wishlisted="{{ $problem->isWishlisted ? 'true' : 'false' }}">
                <svg class="w-5 h-5 {{ $problem->isWishlisted ? 'fill-current' : '' }}" 
                     fill="{{ $problem->isWishlisted ? 'currentColor' : 'none' }}" 
                     stroke="currentColor" 
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
            </button>
        </div>

        {{-- content --}}
        <div class="flex-1 p-6">
            
            {{-- header --}}
            <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                    {{-- instansi info --}}
                    <div class="flex items-center gap-2 mb-3">
                        @if($problem->institution->logo_path)
                            <img src="{{ storage_url($problem->institution->logo_path) }}" 
                                 alt="{{ $problem->institution->name }}"
                                 class="w-10 h-10 rounded-full object-cover border-2 border-gray-100">
                        @else
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                <span class="text-blue-600 font-semibold">
                                    {{ strtoupper(substr($problem->institution->name, 0, 1)) }}
                                </span>
                            </div>
                        @endif
                        <div>
                            <p class="font-semibold text-gray-900">{{ $problem->institution->name }}</p>
                            <p class="text-xs text-gray-500">{{ ucfirst($problem->institution->type) }}</p>
                        </div>
                    </div>

                    {{-- title --}}
                    <h3 class="font-bold text-gray-900 text-xl mb-2 group-hover:text-blue-600 transition-colors">
                        <a href="{{ route('student.browse-problems.detail', $problem->id) }}">
                            {{ $problem->title }}
                        </a>
                    </h3>

                    {{-- description preview --}}
                    <p class="text-gray-600 mb-4 line-clamp-2">
                        {{ Str::limit($problem->description, 200) }}
                    </p>
                </div>
            </div>

            {{-- meta info --}}
            <div class="flex flex-wrap items-center gap-4 mb-4">
                <div class="flex items-center gap-2 text-sm text-gray-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span>{{ $problem->location }}</span>
                </div>
                
                <div class="flex items-center gap-2 text-sm text-gray-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <span>{{ $problem->students_needed }} mahasiswa</span>
                </div>
                
                <div class="flex items-center gap-2 text-sm text-gray-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span>{{ $problem->duration_months }} bulan</span>
                </div>
                
                <div class="flex items-center gap-2 text-sm text-gray-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span>{{ $problem->applications_count ?? 0 }} aplikasi</span>
                </div>
            </div>

            {{-- kategori SDG --}}
            <div class="flex flex-wrap gap-2 mb-4">
                @php
                    $categories = is_array($problem->sdg_categories) 
                        ? $problem->sdg_categories 
                        : json_decode($problem->sdg_categories, true) ?? [];
                @endphp
                
                @foreach(array_slice($categories, 0, 3) as $category)
                    <span class="{{ sdg_color($category) }} text-white text-xs font-semibold px-3 py-1 rounded-md">
                        {{ sdg_label($category) }}
                    </span>
                @endforeach
                
                @if(count($categories) > 3)
                    <span class="bg-gray-200 text-gray-700 text-xs font-semibold px-3 py-1 rounded-md">
                        +{{ count($categories) - 3 }}
                    </span>
                @endif
            </div>

            {{-- footer --}}
            <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                {{-- tingkat kesulitan --}}
                @php
                    $difficultyConfig = [
                        'beginner' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'label' => 'Pemula'],
                        'intermediate' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'label' => 'Menengah'],
                        'advanced' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'label' => 'Lanjut'],
                    ];
                    $difficulty = $difficultyConfig[$problem->difficulty_level] ?? $difficultyConfig['beginner'];
                @endphp
                
                <span class="{{ $difficulty['bg'] }} {{ $difficulty['text'] }} text-sm font-semibold px-3 py-1.5 rounded-md">
                    {{ $difficulty['label'] }}
                </span>

                {{-- action button --}}
                <a href="{{ route('student.browse-problems.detail', $problem->id) }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition-colors duration-200 shadow-md hover:shadow-lg">
                    Lihat Detail
                </a>
            </div>
        </div>
    </div>
</div>