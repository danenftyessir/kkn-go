{{-- resources/views/student/browse-problems/components/problem-card-list.blade.php --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-all duration-300 fade-in-up" 
     style="animation-delay: {{ ($index % 6) * 0.1 }}s;">
    <div class="p-6">
        <div class="flex gap-6">
            {{-- thumbnail --}}
            <a href="{{ route('student.browse-problems.show', $problem->id) }}" class="flex-shrink-0">
                <div class="w-48 h-32 rounded-lg overflow-hidden bg-gradient-to-br from-blue-100 to-green-100">
                    @if($problem->images && $problem->images->first())
                        <img src="{{ asset('storage/' . $problem->images->first()->image_path) }}" 
                             alt="{{ $problem->title }}"
                             class="w-full h-full object-cover hover:scale-110 transition-transform duration-500">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                    @endif
                </div>
            </a>

            {{-- content --}}
            <div class="flex-1 min-w-0">
                {{-- header --}}
                <div class="flex items-start justify-between mb-3">
                    <div class="flex-1">
                        {{-- badges --}}
                        <div class="flex flex-wrap gap-2 mb-2">
                            @if($problem->is_featured)
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-700 text-xs font-bold rounded">
                                ⭐ Unggulan
                            </span>
                            @endif
                            
                            @if($problem->is_urgent)
                            <span class="px-2 py-1 bg-red-100 text-red-700 text-xs font-bold rounded animate-pulse">
                                🔥 Mendesak
                            </span>
                            @endif
                            
                            <span class="px-2 py-1 text-xs font-semibold rounded
                                {{ $problem->difficulty_level === 'beginner' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $problem->difficulty_level === 'intermediate' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                {{ $problem->difficulty_level === 'advanced' ? 'bg-red-100 text-red-700' : '' }}">
                                {{ ucfirst($problem->difficulty_level) }}
                            </span>
                        </div>

                        {{-- title --}}
                        <a href="{{ route('student.browse-problems.show', $problem->id) }}" class="block group">
                            <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-blue-600 transition-colors line-clamp-1">
                                {{ $problem->title }}
                            </h3>
                        </a>

                        {{-- instansi & lokasi --}}
                        <div class="flex items-center gap-4 text-sm text-gray-600 mb-3">
                            <div class="flex items-center gap-2">
                                @if($problem->institution->logo_path)
                                <img src="{{ asset('storage/' . $problem->institution->logo_path) }}" 
                                     alt="{{ $problem->institution->name }}"
                                     class="w-6 h-6 rounded-full object-cover">
                                @else
                                <div class="w-6 h-6 rounded-full bg-gradient-to-br from-blue-500 to-green-500 flex items-center justify-center">
                                    <span class="text-white text-xs font-bold">
                                        {{ strtoupper(substr($problem->institution->name, 0, 1)) }}
                                    </span>
                                </div>
                                @endif
                                <span class="truncate">{{ $problem->institution->name }}</span>
                            </div>

                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span class="truncate">{{ $problem->regency->name ?? '' }}, {{ $problem->province->name ?? '' }}</span>
                            </div>
                        </div>

                        {{-- description excerpt --}}
                        <p class="text-sm text-gray-600 mb-3 line-clamp-2">
                            {{ Str::limit(strip_tags($problem->description), 150) }}
                        </p>

                        {{-- SDG categories --}}
                        <div class="flex flex-wrap gap-1 mb-3">
                            @php
                                // parse sdg_categories dengan aman
                                $sdgCategories = [];
                                if (isset($problem->sdg_categories)) {
                                    if (is_array($problem->sdg_categories)) {
                                        $sdgCategories = $problem->sdg_categories;
                                    } elseif (is_string($problem->sdg_categories)) {
                                        $sdgCategories = json_decode($problem->sdg_categories, true) ?? [];
                                    }
                                }
                                $displayCategories = array_slice($sdgCategories, 0, 4);
                            @endphp
                            @foreach($displayCategories as $sdg)
                                <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-medium rounded">
                                    {{ is_numeric($sdg) ? 'SDG ' . $sdg : ucfirst(str_replace('_', ' ', $sdg)) }}
                                </span>
                            @endforeach
                            @if(count($sdgCategories) > 4)
                                <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs font-medium rounded">
                                    +{{ count($sdgCategories) - 4 }}
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- wishlist button --}}
                    @auth
                        @if(Auth::user()->user_type === 'student')
                        <div x-data="wishlistToggle({{ $problem->id }}, {{ $problem->wishlisted ? 'true' : 'false' }})" class="ml-4">
                            <button @click.prevent="toggle()"
                                    :disabled="loading"
                                    :class="saved ? 'bg-red-50 border-red-300' : 'bg-white border-gray-300'"
                                    class="p-2 rounded-lg border hover:shadow-lg transition-all duration-200">
                                <svg :class="saved ? 'text-red-600' : 'text-gray-600'" 
                                     class="w-5 h-5 transition-colors" 
                                     :fill="saved ? 'currentColor' : 'none'" 
                                     stroke="currentColor" 
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                            </button>
                        </div>
                        @endif
                    @endauth
                </div>

                {{-- footer dengan stats dan action --}}
                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                    <div class="flex items-center gap-6 text-xs text-gray-600">
                        <div class="flex items-center gap-1">
                            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            {{ $problem->required_students }} mahasiswa
                        </div>

                        <div class="flex items-center gap-1">
                            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $problem->duration_months }} bulan
                        </div>

                        <div class="flex items-center gap-1">
                            <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            {{ $problem->applications_count }} aplikasi
                        </div>

                        <div class="flex items-center gap-1">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            {{ $problem->views_count }} views
                        </div>
                    </div>

                    <a href="{{ route('student.browse-problems.show', $problem->id) }}" 
                       class="inline-flex items-center px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-200 hover:shadow-lg font-semibold text-sm">
                        Lihat Detail
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@once
@push('styles')
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
@endpush
@endonce