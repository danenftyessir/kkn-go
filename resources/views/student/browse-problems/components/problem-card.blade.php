{{-- resources/views/student/browse-problems/components/problem-card.blade.php --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-lg hover:border-blue-200 transition-all duration-300 overflow-hidden group">
    {{-- problem image --}}
    <div class="relative h-48 overflow-hidden bg-gradient-to-br from-blue-50 to-indigo-50">
        @if($problem->images && $problem->images->count() > 0)
            <img src="{{ supabase_url($problem->images->first()->image_path) }}" 
                 alt="{{ $problem->title }}"
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                 loading="lazy">
        @else
            <div class="w-full h-full flex items-center justify-center">
                <svg class="w-20 h-20 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
        @endif
        
        {{-- badges --}}
        <div class="absolute top-3 right-3 flex gap-2">
            @if($problem->is_featured)
                <span class="px-2 py-1 bg-yellow-500 text-white text-xs font-bold rounded shadow-lg">
                    ⭐ Unggulan
                </span>
            @endif
            @if($problem->is_urgent)
                <span class="px-2 py-1 bg-red-500 text-white text-xs font-bold rounded shadow-lg animate-pulse">
                    🔥 Mendesak
                </span>
            @endif
        </div>
    </div>

    <div class="p-5">
        {{-- institution info --}}
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 rounded-full overflow-hidden bg-gray-100 flex-shrink-0">
                @if($problem->institution && $problem->institution->logo_path)
                    <img src="{{ supabase_url($problem->institution->logo_path) }}" 
                         alt="{{ $problem->institution->name }}"
                         class="w-full h-full object-cover"
                         loading="lazy">
                @else
                    <div class="w-full h-full flex items-center justify-center bg-blue-100 text-blue-600 font-bold text-sm">
                        {{ substr($problem->institution->name ?? 'I', 0, 1) }}
                    </div>
                @endif
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-semibold text-sm text-gray-900 truncate">{{ $problem->institution->name ?? 'Instansi' }}</p>
                <p class="text-xs text-gray-500 truncate">{{ $problem->institution->type ?? '' }}</p>
            </div>
        </div>

        {{-- problem title --}}
        <h3 class="font-bold text-lg text-gray-900 mb-2 line-clamp-2 min-h-[3.5rem] group-hover:text-blue-600 transition-colors">
            {{ $problem->title }}
        </h3>

        {{-- description --}}
        <p class="text-gray-600 text-sm mb-4 line-clamp-2">
            {{ $problem->description }}
        </p>

        {{-- metadata --}}
        <div class="flex items-center gap-4 text-xs text-gray-600 mb-4">
            <div class="flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span class="line-clamp-1">{{ $problem->regency->name ?? $problem->location_regency }}, {{ $problem->province->name ?? $problem->location_province }}</span>
            </div>
        </div>
        
        {{-- sdg categories --}}
        <div class="flex flex-wrap gap-2 mb-4">
            @php
                // ✅ perbaikan: pastikan sdg_categories adalah array sebelum di-slice
                $sdgCategories = $problem->sdg_categories ?? [];
                
                // jika masih string (JSON), decode dulu
                if (is_string($sdgCategories)) {
                    $sdgCategories = json_decode($sdgCategories, true) ?? [];
                }
                
                // pastikan hasilnya array
                if (!is_array($sdgCategories)) {
                    $sdgCategories = [];
                }
            @endphp
            
            @foreach(array_slice($sdgCategories, 0, 3) as $sdg)
            <span class="px-2 py-1 bg-blue-50 text-blue-700 text-xs font-medium rounded">
                SDG {{ $sdg }}
            </span>
            @endforeach
            
            @if(count($sdgCategories) > 3)
            <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs font-medium rounded">
                +{{ count($sdgCategories) - 3 }} Lainnya
            </span>
            @endif
        </div>

        {{-- stats --}}
        <div class="flex items-center justify-between text-xs text-gray-600 mb-4 pb-4 border-b border-gray-100">
            <div class="flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <span>{{ $problem->required_students }} Mahasiswa</span>
            </div>
            <div class="flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span>{{ $problem->duration_months }} Bulan</span>
            </div>
        </div>

        {{-- deadline & difficulty --}}
        <div class="flex items-center justify-between mb-4">
            <div>
                <p class="text-xs text-gray-500 mb-1">Deadline Aplikasi</p>
                <p class="text-sm font-semibold text-gray-900">{{ $problem->application_deadline->format('d M Y') }}</p>
            </div>
            <span class="px-3 py-1 text-xs font-semibold rounded-full
                {{ $problem->difficulty_level === 'beginner' ? 'bg-green-100 text-green-700' : '' }}
                {{ $problem->difficulty_level === 'intermediate' ? 'bg-yellow-100 text-yellow-700' : '' }}
                {{ $problem->difficulty_level === 'advanced' ? 'bg-red-100 text-red-700' : '' }}">
                {{ ucfirst($problem->difficulty_level) }}
            </span>
        </div>

        {{-- action button --}}
        <a href="{{ route('student.browse-problems.show', $problem->id) }}" 
           class="block w-full text-center px-4 py-2.5 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors group-hover:bg-blue-700">
            Lihat Detail
        </a>
    </div>
</div>