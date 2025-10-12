{{-- resources/views/student/browse-problems/components/problem-card.blade.php --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-all duration-300 group">
    {{-- image --}}
    <div class="relative h-48 bg-gradient-to-br from-blue-500 to-green-500 overflow-hidden">
        @php
            $coverImage = $problem->images->where('is_cover', true)->first() ?? $problem->images->first();
            // FIX: tambahkan default value untuk isWishlisted
            $isWishlisted = $problem->isWishlisted ?? false;
        @endphp
        
        @if($coverImage)
            <img src="{{ $coverImage->image_url }}" 
                 alt="{{ $problem->title }}"
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
    </div>

    {{-- content --}}
    <a href="{{ route('student.browse-problems.detail', $problem->id) }}" class="block p-5">
        {{-- institution --}}
        <div class="flex items-center gap-2 mb-3">
            @if($problem->institution && $problem->institution->logo_path)
                <img src="{{ supabase_url($problem->institution->logo_path) }}" 
                     alt="{{ $problem->institution->name }}"
                     class="w-8 h-8 rounded-full object-cover"
                     loading="lazy">
            @else
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-green-500 flex items-center justify-center">
                    <span class="text-white text-xs font-bold">
                        {{ substr($problem->institution->name ?? 'I', 0, 1) }}
                    </span>
                </div>
            @endif
            <span class="text-sm font-medium text-gray-900 truncate">
                {{ $problem->institution->name ?? 'Instansi' }}
            </span>
        </div>

        {{-- title --}}
        <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2 hover:text-blue-600 transition-colors">
            {{ $problem->title }}
        </h3>

        {{-- description --}}
        <p class="text-sm text-gray-600 mb-4 line-clamp-2">
            {{ Str::limit($problem->description, 100) }}
        </p>

        {{-- meta info --}}
        <div class="flex flex-wrap gap-3 text-sm text-gray-600 mb-4">
            {{-- location --}}
            <div class="flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <span>{{ $problem->regency->name ?? $problem->province->name ?? 'Lokasi tidak tersedia' }}</span>
            </div>

            {{-- duration --}}
            <div class="flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>{{ $problem->duration_months }} bulan</span>
            </div>

            {{-- students needed --}}
            <div class="flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                <span>{{ $problem->required_students }} mahasiswa</span>
            </div>
        </div>

        {{-- footer --}}
        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
            <div class="text-xs text-gray-500">
                Deadline: <span class="font-semibold">
                    {{ \Carbon\Carbon::parse($problem->application_deadline)->format('d M Y') }}
                </span>
            </div>
            <span class="text-blue-600 text-sm font-semibold hover:text-blue-700">
                Lihat Detail →
            </span>
        </div>
    </a>
</div>