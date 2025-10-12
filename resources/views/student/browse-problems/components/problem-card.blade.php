{{-- resources/views/student/browse-problems/components/problem-card.blade.php --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-all duration-300 group">
    {{-- image --}}
    <div class="relative h-48 bg-gradient-to-br from-blue-500 to-green-500 overflow-hidden">
        @php
            $coverImage = $problem->coverImage;
            $isWishlisted = $problem->isWishlisted ?? false;
        @endphp
        
        @if($coverImage)
            <img src="{{ $coverImage->image_url }}" 
                 alt="{{ $problem->title }}"
                 onerror="this.onerror=null; this.src='https://via.placeholder.com/400x300?text=No+Image';"
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                 loading="lazy">
        @else
            <div class="w-full h-full flex items-center justify-center text-white">
                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
        @endif
        
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
        <span class="absolute top-3 left-3 px-3 py-1 text-xs font-semibold rounded-full
            {{ $problem->difficulty_level === 'beginner' ? 'bg-green-500 text-white' : '' }}
            {{ $problem->difficulty_level === 'intermediate' ? 'bg-yellow-500 text-white' : '' }}
            {{ $problem->difficulty_level === 'advanced' ? 'bg-red-500 text-white' : '' }}">
            {{ ucfirst($problem->difficulty_level) }}
        </span>

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
        
        @if($problem->status !== 'open')
        <span class="absolute bottom-3 left-3 {{ $status['bg'] }} text-white text-xs font-semibold px-3 py-1.5 rounded-full shadow-lg">
            {{ $status['text'] }}
        </span>
        @endif
    </div>
    
    {{-- content --}}
    <div class="p-5">
        {{-- title --}}
        <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2 group-hover:text-blue-600 transition-colors">
            <a href="{{ route('student.browse-problems.show', $problem->id) }}">
                {{ $problem->title }}
            </a>
        </h3>
        
        {{-- institution --}}
        <div class="flex items-center gap-2 text-sm text-gray-600 mb-3">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            <span class="line-clamp-1">{{ $problem->institution->name }}</span>
        </div>
        
        {{-- location --}}
        <div class="flex items-center gap-2 text-sm text-gray-600 mb-3">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span class="line-clamp-1">{{ $problem->regency->name ?? $problem->location_regency }}, {{ $problem->province->name ?? $problem->location_province }}</span>
        </div>
        
        {{-- sdg categories --}}
        <div class="flex flex-wrap gap-2 mb-4">
            @foreach(array_slice($problem->sdg_categories ?? [], 0, 3) as $sdg)
            <span class="px-2 py-1 bg-blue-50 text-blue-700 text-xs font-medium rounded">
                SDG {{ $sdg }}
            </span>
            @endforeach
            
            @if(count($problem->sdg_categories ?? []) > 3)
            <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs font-medium rounded">
                +{{ count($problem->sdg_categories) - 3 }} Lainnya
            </span>
            @endif
        </div>
        
        {{-- footer --}}
        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
            <div class="flex items-center gap-4 text-xs text-gray-500">
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
            </div>
            
            <a href="{{ route('student.browse-problems.show', $problem->id) }}" 
               class="inline-flex items-center gap-1 text-sm font-medium text-blue-600 hover:text-blue-700 transition-colors">
                Detail
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>
</div>