{{-- resources/views/student/browse-problems/components/problem-card.blade.php --}}

<a href="{{ route('student.browse-problems.show', $problem->id) }}" 
   class="group block bg-white rounded-xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-blue-200 transform hover:-translate-y-1">
    
    {{-- image section --}}
    <div class="relative h-48 bg-gradient-to-br from-blue-50 to-indigo-50 overflow-hidden">
        @if($problem->images && $problem->images->isNotEmpty())
            <img src="{{ supabase_url($problem->images->first()->image_path) }}" 
                 alt="{{ $problem->title }}"
                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
        @else
            {{-- default gradient jika tidak ada gambar --}}
            <div class="w-full h-full flex items-center justify-center">
                <svg class="w-20 h-20 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
        @endif
        
        {{-- badges overlay --}}
        <div class="absolute top-3 left-3 flex gap-2">
            @if($problem->is_urgent)
                <span class="px-3 py-1 bg-red-500 text-white text-xs font-bold rounded-full shadow-lg animate-pulse">
                    URGENT
                </span>
            @endif
            @if($problem->is_featured)
                <span class="px-3 py-1 bg-yellow-500 text-white text-xs font-bold rounded-full shadow-lg">
                    ⭐ FEATURED
                </span>
            @endif
        </div>
        
        {{-- difficulty badge --}}
        <div class="absolute top-3 right-3">
            <span class="px-3 py-1 {{ difficulty_color($problem->difficulty_level) }} text-xs font-semibold rounded-full shadow-lg backdrop-blur-sm">
                {{ difficulty_label($problem->difficulty_level) }}
            </span>
        </div>
    </div>

    {{-- content section --}}
    <div class="p-5">
        
        {{-- institution info - compact --}}
        <div class="flex items-center gap-2 mb-3">
            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center flex-shrink-0 shadow-sm">
                <span class="text-white font-bold text-xs">
                    {{ strtoupper(substr($problem->institution->name ?? 'I', 0, 1)) }}
                </span>
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-semibold text-xs text-gray-900 truncate">{{ $problem->institution->name ?? 'Instansi' }}</p>
                <p class="text-[10px] text-gray-500 truncate">{{ $problem->institution->type ?? '' }}</p>
            </div>
        </div>

        {{-- title --}}
        <h3 class="font-bold text-base text-gray-900 mb-2 line-clamp-2 h-12 group-hover:text-blue-600 transition-colors">
            {{ $problem->title }}
        </h3>

        {{-- location - compact --}}
        <div class="flex items-center gap-1 text-xs text-gray-600 mb-4">
            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span class="line-clamp-1">{{ $problem->regency->name ?? '' }}, {{ $problem->province->name ?? '' }}</span>
        </div>
        
        {{-- ✅ STATS SECTION - DESIGN BARU: ANGKA PROMINENT --}}
        <div class="grid grid-cols-3 gap-3 mb-4 pt-4 border-t border-gray-100">
            
            {{-- views count --}}
            <div class="text-center">
                <div class="text-2xl font-bold text-gray-900">
                    {{ $problem->views_count >= 1000 ? number_format($problem->views_count / 1000, 1) . 'K' : $problem->views_count }}
                </div>
                <div class="text-[10px] text-gray-500 mt-0.5">Views</div>
            </div>
            
            {{-- applications count --}}
            <div class="text-center">
                <div class="text-2xl font-bold text-blue-600">
                    {{ $problem->applications_count ?? 0 }}
                </div>
                <div class="text-[10px] text-gray-500 mt-0.5">Pendaftar</div>
            </div>
            
            {{-- days until deadline --}}
            <div class="text-center">
                @php
                    $daysLeft = $problem->application_deadline ? max(0, now()->diffInDays($problem->application_deadline, false)) : 0;
                @endphp
                <div class="text-2xl font-bold {{ $daysLeft <= 7 ? 'text-red-600' : 'text-green-600' }}">
                    {{ $daysLeft }}
                </div>
                <div class="text-[10px] text-gray-500 mt-0.5">Hari Lagi</div>
            </div>
            
        </div>

        {{-- sdg categories - compact badges --}}
        <div class="flex flex-wrap gap-1.5 mb-4">
            @php
                $sdgCategories = $problem->sdg_categories ?? [];
                if (is_string($sdgCategories)) {
                    $sdgCategories = json_decode($sdgCategories, true) ?? [];
                }
                // tampilkan maksimal 3 SDG, sisanya tampilkan +N
                $displayCategories = array_slice($sdgCategories, 0, 3);
                $remainingCount = count($sdgCategories) - 3;
            @endphp
            
            @foreach($displayCategories as $sdg)
                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold text-white shadow-sm"
                      style="background-color: {{ sdg_color($sdg) }};">
                    SDG {{ $sdg }}
                </span>
            @endforeach
            
            @if($remainingCount > 0)
                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-gray-200 text-gray-700">
                    +{{ $remainingCount }}
                </span>
            @endif
        </div>

        {{-- footer info - clean --}}
        <div class="flex items-center justify-between pt-3 border-t border-gray-100">
            <div class="flex items-center gap-1 text-xs text-gray-600">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                <span>{{ $problem->required_students }} Mahasiswa</span>
            </div>
            
            <div class="flex items-center gap-1 text-xs text-gray-600">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <span>{{ $problem->duration_months }} Bulan</span>
            </div>
        </div>
    </div>
</a>

{{-- custom styles untuk smooth animations --}}
<style>
    .line-clamp-1 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>