{{-- component untuk menampilkan card masalah --}}
<div class="problem-card bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-xl transition-all duration-300 fade-in-up" 
     style="animation-delay: {{ ($index ?? 0) * 0.05 }}s">
    
    <a href="{{ route('student.problems.show', $problem->id) }}" class="block">
        <!-- image dengan badges overlay -->
        <div class="relative h-48 overflow-hidden bg-gray-100">
            @if($problem->images->isNotEmpty())
                <img src="{{ asset('storage/' . $problem->images->first()->image_path) }}" 
                     alt="{{ $problem->title }}"
                     class="w-full h-full object-cover transform hover:scale-105 transition-transform duration-500"
                     loading="lazy">
            @else
                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-500 to-green-500">
                    <svg class="w-16 h-16 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            @endif
            
            <!-- badges -->
            <div class="absolute top-3 left-3 flex flex-wrap gap-2">
                @if($problem->is_featured)
                <span class="badge px-2 py-1 bg-yellow-500 text-white text-xs font-semibold rounded-full shadow-lg">
                    ⭐ Unggulan
                </span>
                @endif
                
                @if($problem->is_urgent)
                <span class="badge px-2 py-1 bg-red-500 text-white text-xs font-semibold rounded-full shadow-lg animate-pulse">
                    🔥 Mendesak
                </span>
                @endif
            </div>
            
            <!-- difficulty badge -->
            <div class="absolute top-3 right-3">
                <span class="badge px-2 py-1 {{ $problem->getDifficultyBadgeColor() }} text-xs font-semibold rounded-full shadow-lg">
                    {{ $problem->getDifficultyLabel() }}
                </span>
            </div>
        </div>

        <!-- content -->
        <div class="p-5">
            <!-- institution info -->
            <div class="flex items-center mb-3">
                @if($problem->institution->logo_path)
                <img src="{{ asset('storage/' . $problem->institution->logo_path) }}" 
                     alt="{{ $problem->institution->name }}"
                     class="w-8 h-8 rounded-full object-cover mr-2">
                @else
                <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center mr-2">
                    <span class="text-xs font-semibold text-gray-600">
                        {{ substr($problem->institution->name, 0, 1) }}
                    </span>
                </div>
                @endif
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">
                        {{ $problem->institution->name }}
                    </p>
                    <p class="text-xs text-gray-500 truncate">
                        {{ $problem->regency->name }}, {{ $problem->province->name }}
                    </p>
                </div>
            </div>

            <!-- title -->
            <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2 hover:text-blue-600 transition-colors">
                {{ $problem->title }}
            </h3>

            <!-- description -->
            <p class="text-sm text-gray-600 mb-4 line-clamp-2">
                {{ Str::limit($problem->description, 100) }}
            </p>

            <!-- SDG tags -->
            <div class="flex flex-wrap gap-1 mb-4">
                @php
                    $sdgCategories = is_array($problem->sdg_categories) 
                        ? $problem->sdg_categories 
                        : json_decode($problem->sdg_categories, true) ?? [];
                @endphp
                @foreach(array_slice($sdgCategories, 0, 3) as $sdg)
                <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded">
                    SDG {{ $sdg }}
                </span>
                @endforeach
                @if(count($sdgCategories) > 3)
                <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs font-medium rounded">
                    +{{ count($sdgCategories) - 3 }}
                </span>
                @endif
            </div>

            <!-- metadata -->
            <div class="grid grid-cols-2 gap-3 mb-4 pt-4 border-t border-gray-100">
                <div class="flex items-center text-sm text-gray-600">
                    <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ $problem->getFormattedDuration() }}
                </div>
                <div class="flex items-center text-sm text-gray-600">
                    <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    {{ $problem->required_students }} orang
                </div>
            </div>

            <!-- deadline -->
            <div class="flex items-center justify-between">
                <div class="flex items-center text-sm">
                    <svg class="w-4 h-4 mr-1 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span class="text-red-600 font-medium">
                        {{ $problem->application_deadline->format('d M Y') }}
                    </span>
                </div>
                
                <!-- remaining slots -->
                @if($problem->getRemainingSlots() > 0)
                <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded">
                    {{ $problem->getRemainingSlots() }} slot tersisa
                </span>
                @else
                <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs font-semibold rounded">
                    Penuh
                </span>
                @endif
            </div>
        </div>
    </a>

    <!-- action buttons -->
    <div class="px-5 pb-5 pt-0">
        <div class="flex gap-2">
            <a href="{{ route('student.problems.show', $problem->id) }}" 
               class="flex-1 px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition-colors text-center">
                Lihat Detail
            </a>
            
            <!-- TODO: implementasi wishlist functionality -->
            <button type="button" 
                    class="px-4 py-2 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition-colors"
                    title="Simpan ke wishlist">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                </svg>
            </button>
        </div>
    </div>
</div>