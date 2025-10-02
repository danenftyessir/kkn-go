{{-- resources/views/student/browse-problems/components/problem-card-list.blade.php --}}
{{-- component untuk menampilkan card masalah dalam list view --}}

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-all duration-300 fade-in-up">
    <a href="{{ route('student.problems.show', $problem->id) }}" class="flex flex-col md:flex-row">
        
        <!-- image -->
        <div class="relative md:w-64 h-48 md:h-auto overflow-hidden bg-gray-100 flex-shrink-0">
            @if($problem->images->isNotEmpty())
                <img src="{{ asset('storage/' . $problem->images->first()->image_path) }}" 
                     alt="{{ $problem->title }}"
                     class="w-full h-full object-cover"
                     loading="lazy">
            @else
                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-500 to-green-500">
                    <svg class="w-16 h-16 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            @endif
            
            <!-- badges overlay -->
            <div class="absolute top-3 left-3 flex flex-wrap gap-2">
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

        <!-- content -->
        <div class="flex-1 p-6">
            <div class="flex flex-col h-full">
                <!-- header -->
                <div class="flex-1">
                    <!-- institution -->
                    <div class="flex items-center mb-3">
                        @if($problem->institution->logo_path)
                        <img src="{{ asset('storage/' . $problem->institution->logo_path) }}" 
                             alt="{{ $problem->institution->name }}"
                             class="w-8 h-8 rounded-full object-cover mr-2">
                        @else
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-green-500 flex items-center justify-center mr-2">
                            <span class="text-white text-xs font-bold">
                                {{ strtoupper(substr($problem->institution->name, 0, 1)) }}
                            </span>
                        </div>
                        @endif
                        <span class="text-sm text-gray-600">{{ $problem->institution->name }}</span>
                    </div>

                    <!-- title -->
                    <h3 class="text-xl font-bold text-gray-900 mb-2 hover:text-blue-600 transition-colors">
                        {{ $problem->title }}
                    </h3>

                    <!-- description -->
                    <p class="text-sm text-gray-600 mb-4 line-clamp-2">
                        {{ Str::limit($problem->description, 200) }}
                    </p>

                    <!-- details grid -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
                        <div class="flex items-center text-sm text-gray-600">
                            <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            </svg>
                            <span class="truncate">{{ $problem->regency->name }}</span>
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            {{ $problem->required_students }} mahasiswa
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $problem->duration_months }} bulan
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            @php
                                $daysLeft = now()->diffInDays($problem->application_deadline, false);
                            @endphp
                            <span class="{{ $daysLeft <= 7 && $daysLeft >= 0 ? 'text-red-600 font-semibold' : '' }}">
                                {{ abs($daysLeft) }} hari {{ $daysLeft >= 0 ? 'lagi' : 'lewat' }}
                            </span>
                        </div>
                    </div>

                    <!-- tags/skills -->
                    @php
                        // handle both string (JSON) and array format
                        $skills = $problem->required_skills;
                        if (is_string($skills)) {
                            $skills = json_decode($skills, true) ?? [];
                        }
                        $skills = is_array($skills) ? $skills : [];
                    @endphp
                    @if(count($skills) > 0)
                    <div class="flex flex-wrap gap-1">
                        @foreach(array_slice($skills, 0, 5) as $skill)
                        <span class="px-2 py-1 bg-blue-50 text-blue-700 text-xs rounded-md">
                            {{ $skill }}
                        </span>
                        @endforeach
                        @if(count($skills) > 5)
                        <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded-md">
                            +{{ count($skills) - 5 }}
                        </span>
                        @endif
                    </div>
                    @endif
                </div>

                <!-- footer -->
                <div class="mt-4 pt-4 border-t border-gray-200 flex items-center justify-between">
                    <div class="flex items-center space-x-4 text-xs text-gray-500">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            {{ $problem->views_count }}
                        </span>
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            {{ $problem->applications_count }} aplikasi
                        </span>
                    </div>

                    <!-- difficulty badge -->
                    <span class="inline-flex items-center px-2 py-1 {{ $problem->getDifficultyBadgeColor() }} text-xs font-medium rounded-md">
                        {{ $problem->getDifficultyLabel() }}
                    </span>
                </div>
            </div>
        </div>
    </a>
</div>