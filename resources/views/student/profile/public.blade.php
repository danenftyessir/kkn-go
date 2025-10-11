{{-- resources/views/student/profile/public.blade.php --}}
@extends('layouts.app')

@section('title', $student->first_name . ' ' . $student->last_name . ' - profil publik')

@push('styles')
<style>
/* animasi fade in */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.profile-container {
    animation: fadeInUp 0.6s cubic-bezier(0.4, 0, 0.2, 1);
}

.project-card {
    transition: all 0.3s ease;
    border: 1px solid #e5e7eb;
}

.project-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    border-color: #3b82f6;
}

.skill-tag {
    transition: all 0.2s ease;
}

.skill-tag:hover {
    transform: scale(1.05);
    background-color: #3b82f6;
    color: white;
}

.hero-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50">
    
    {{-- hero section dengan gradient --}}
    <div class="hero-gradient py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center gap-8 profile-container">
                
                {{-- foto profil --}}
                <div class="flex-shrink-0">
                    @if($student->profile_photo_path)
                        <img src="{{ $student->profile_photo_url }}" 
                             alt="{{ $student->first_name }}" 
                             class="w-40 h-40 rounded-full object-cover border-4 border-white shadow-xl">
                    @else
                        <div class="w-40 h-40 rounded-full bg-white flex items-center justify-center text-purple-600 text-5xl font-bold border-4 border-white shadow-xl">
                            {{ strtoupper(substr($student->first_name, 0, 1)) }}{{ strtoupper(substr($student->last_name, 0, 1)) }}
                        </div>
                    @endif
                </div>
                
                {{-- info utama --}}
                <div class="flex-1 text-center md:text-left text-white">
                    <h1 class="text-4xl md:text-5xl font-bold mb-2">
                        {{ $student->first_name }} {{ $student->last_name }}
                    </h1>
                    <p class="text-xl opacity-90 mb-4">
                        {{ $student->major }} • {{ $student->university->name ?? '' }}
                    </p>
                    
                    @if($student->bio)
                        <p class="text-lg opacity-80 max-w-2xl">{{ $student->bio }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- main content --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        
        {{-- statistik cards --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12 profile-container" style="animation-delay: 0.1s;">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-3xl font-bold text-gray-900">{{ $statistics['completed_projects'] }}</p>
                        <p class="text-sm text-gray-600">proyek selesai</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-3xl font-bold text-gray-900">{{ $statistics['sdgs_addressed'] }}</p>
                        <p class="text-sm text-gray-600">SDG disentuh</p>
                    </div>
                </div>
            </div>

            @if($statistics['positive_reviews'] > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-3xl font-bold text-gray-900">{{ $statistics['positive_reviews'] }}</p>
                        <p class="text-sm text-gray-600">review positif</p>
                    </div>
                </div>
            </div>
            @endif

            @if($statistics['average_rating'] > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-3xl font-bold text-gray-900">{{ number_format($statistics['average_rating'], 1) }}</p>
                        <p class="text-sm text-gray-600">rating rata-rata</p>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- sidebar kiri --}}
            <div class="lg:col-span-1">
                
                {{-- skills section --}}
                @php
                    $skillsList = $skills ?? [];
                    // pastikan dalam bentuk array
                    if (is_string($skillsList)) {
                        $skillsList = json_decode($skillsList, true) ?? [];
                    }
                    if (!is_array($skillsList)) {
                        $skillsList = [];
                    }
                @endphp
                @if(count($skillsList) > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6 profile-container" style="animation-delay: 0.2s;">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">skills & keahlian</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($skillsList as $skill)
                            <span class="px-3 py-2 bg-blue-100 text-blue-700 rounded-lg text-sm font-medium skill-tag">
                                {{ $skill }}
                            </span>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- contact info --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 profile-container" style="animation-delay: 0.3s;">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">informasi kontak</h3>
                    
                    <div class="space-y-3">
                        <div class="flex items-center gap-3 text-gray-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                            </svg>
                            <span class="text-sm">{{ $student->user->email }}</span>
                        </div>
                        
                        @if($student->whatsapp)
                        <div class="flex items-center gap-3 text-gray-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                            <span class="text-sm">{{ $student->whatsapp }}</span>
                        </div>
                        @endif

                        <div class="flex items-center gap-3 text-gray-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <span class="text-sm">{{ $student->university->name ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- konten kanan - completed projects --}}
            <div class="lg:col-span-2">
                <div class="profile-container" style="animation-delay: 0.4s;">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">proyek yang telah diselesaikan</h2>

                    @if(!isset($completed_projects) || $completed_projects->isEmpty())
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="text-gray-500">belum ada proyek yang diselesaikan</p>
                        </div>
                    @else
                        <div class="space-y-6">
                            @foreach($completed_projects as $project)
                                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 project-card">
                                    
                                    {{-- header proyek --}}
                                    <div class="flex items-start justify-between mb-4">
                                        <div class="flex-1">
                                            <h3 class="text-xl font-bold text-gray-900 mb-2">
                                                {{ $project->problem->title }}
                                            </h3>
                                            
                                            <div class="flex items-center gap-2 mb-2">
                                                <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium">
                                                    {{ $project->problem->institution->name }}
                                                </span>
                                            </div>
                                        </div>

                                        {{-- rating --}}
                                        @if($project->institutionReview)
                                            <div class="flex items-center gap-1 bg-yellow-50 px-3 py-2 rounded-lg">
                                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                                <span class="text-sm font-bold text-gray-900">
                                                    {{ number_format($project->institutionReview->rating, 1) }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- deskripsi proyek --}}
                                    <p class="text-gray-600 mb-4 line-clamp-2">
                                        {{ Str::limit($project->problem->description, 200) }}
                                    </p>

                                    {{-- info lokasi dan role --}}
                                    <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 mb-4">
                                        <span class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            {{ $project->problem->regency->name ?? '-' }}, {{ $project->problem->province->name ?? '-' }}
                                        </span>

                                        @if($project->role_in_team)
                                            <span class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                                {{ $project->role_in_team }}
                                            </span>
                                        @endif

                                        @if($project->actual_end_date)
                                            <span class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                selesai {{ \Carbon\Carbon::parse($project->actual_end_date)->format('M Y') }}
                                            </span>
                                        @endif
                                    </div>

                                    {{-- SDG categories --}}
                                    @php
                                        $sdgCategories = $project->problem->sdg_categories;
                                        // pastikan dalam bentuk array
                                        if (is_string($sdgCategories)) {
                                            $sdgCategories = json_decode($sdgCategories, true) ?? [];
                                        }
                                        if (!is_array($sdgCategories)) {
                                            $sdgCategories = [];
                                        }
                                    @endphp
                                    @if(count($sdgCategories) > 0)
                                        <div class="flex flex-wrap gap-2 mb-4">
                                            @foreach($sdgCategories as $sdg)
                                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-medium">
                                                    SDG {{ $sdg }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif

                                    {{-- review dari institution --}}
                                    @if($project->institutionReview && $project->institutionReview->comment)
                                        <div class="mt-4 p-4 bg-gray-50 rounded-lg border-l-4 border-blue-500">
                                            <p class="text-sm font-semibold text-gray-900 mb-1">review dari instansi:</p>
                                            <p class="text-sm text-gray-600 italic">"{{ $project->institutionReview->comment }}"</p>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection