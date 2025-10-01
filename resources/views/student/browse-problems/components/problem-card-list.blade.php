{{-- component untuk list view masalah --}}
<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-all duration-300 mb-4">
    <a href="{{ route('student.problems.show', $problem->id) }}" class="block">
        <div class="md:flex">
            <!-- image section -->
            <div class="md:w-1/3 relative">
                @if($problem->images->isNotEmpty())
                    <img src="{{ asset('storage/' . $problem->images->first()->image_path) }}" 
                         alt="{{ $problem->title }}"
                         class="w-full h-64 md:h-full object-cover"
                         loading="lazy">
                @else
                    <div class="w-full h-64 md:h-full flex items-center justify-center bg-gradient-to-br from-blue-500 to-green-500">
                        <svg class="w-16 h-16 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                @endif
                
                <!-- badges overlay -->
                <div class="absolute top-3 left-3 flex flex-col gap-2">
                    @if($problem->is_featured)
                    <span class="px-2 py-1 bg-yellow-500 text-white text-xs font-semibold rounded-full shadow-lg">
                        ⭐ Unggulan
                    </span>
                    @endif
                    
                    @if($problem->is_urgent)
                    <span class="px-2 py-1 bg-red-500 text-white text-xs font-semibold rounded-full shadow-lg animate-pulse">
                        🔥 Mendesak
                    </span>
                    @endif
                </div>
            </div>

            <!-- content section -->
            <div class="md:w-2/3 p-6">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex-1">
                        <!-- institution info -->
                        <div class="flex items-center mb-2">
                            @if($problem->institution->logo_path)
                            <img src="{{ asset('storage/' . $problem->institution->logo_path) }}" 
                                 alt="{{ $problem->institution->name }}"
                                 class="w-6 h-6 rounded-full object-cover mr-2">
                            @endif
                            <span class="text-sm font-medium text-gray-700">{{ $problem->institution->name }}</span>
                        </div>

                        <!-- title -->
                        <h3 class="text-xl font-bold text-gray-900 mb-2 hover:text-blue-600 transition-colors">
                            {{ $problem->title }}
                        </h3>

                        <!-- location -->
                        <div class="flex items-center text-sm text-gray-600 mb-3">
                            <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            </svg>
                            {{ $problem->regency->name }}, {{ $problem->province->name }}
                        </div>
                    </div>

                    <!-- difficulty badge -->
                    <span class="px-3 py-1 {{ $problem->getDifficultyBadgeColor() }} text-xs font-semibold rounded-full ml-4">
                        {{ $problem->getDifficultyLabel() }}
                    </span>
                </div>

                <!-- description -->
                <p class="text-sm text-gray-600 mb-4 line-clamp-2">
                    {{ Str::limit($problem->description, 200) }}
                </p>

                <!-- SDG tags -->
                <div class="flex flex-wrap gap-2 mb-4">
                    @foreach(array_slice($problem->sdg_categories, 0, 5) as $sdg)
                    <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded">
                        SDG {{ $sdg }}
                    </span>
                    @endforeach
                    @if(count($problem->sdg_categories) > 5)
                    <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs font-medium rounded">
                        +{{ count($problem->sdg_categories) - 5 }}
                    </span>
                    @endif
                </div>

                <!-- metadata row -->
                <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 pt-4 border-t border-gray-100">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ $problem->getFormattedDuration() }}
                    </div>
                    
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        {{ $problem->required_students }} orang
                    </div>
                    
                    <div class="flex items-center text-red-600 font-medium">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Deadline: {{ $problem->application_deadline->format('d M Y') }}
                    </div>

                    <div class="ml-auto">
                        @if($problem->getRemainingSlots() > 0)
                        <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">
                            {{ $problem->getRemainingSlots() }} slot tersisa
                        </span>
                        @else
                        <span class="px-3 py-1 bg-gray-100 text-gray-700 text-xs font-semibold rounded-full">
                            Penuh
                        </span>
                        @endif
                    </div>
                </div>

                <!-- action buttons -->
                <div class="flex gap-2 mt-4">
                    <a href="{{ route('student.problems.show', $problem->id) }}" 
                       class="flex-1 px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition-colors text-center">
                        Lihat Detail
                    </a>
                    
                    <button type="button" 
                            class="px-4 py-2 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition-colors"
                            title="Simpan ke wishlist">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                        </svg>
                    </button>

                    <button type="button" 
                            class="px-4 py-2 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition-colors"
                            title="Bagikan">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </a>
</div>