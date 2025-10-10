{{-- resources/views/student/portfolio/public.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    {{-- hero header dengan gradient --}}
    <div class="bg-gradient-to-r from-blue-600 to-green-600 pt-24 pb-32">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                {{-- profile photo --}}
                <div class="flex justify-center mb-6">
                    @if($student->profile_photo_path)
                        <img src="{{ asset('storage/' . $student->profile_photo_path) }}" 
                             alt="{{ $student->user->name }}"
                             class="w-40 h-40 rounded-full border-8 border-white shadow-2xl object-cover">
                    @else
                        <div class="w-40 h-40 rounded-full border-8 border-white shadow-2xl bg-gradient-to-br from-blue-400 to-green-400 flex items-center justify-center">
                            <span class="text-white text-5xl font-bold">{{ strtoupper(substr($student->first_name, 0, 1)) }}</span>
                        </div>
                    @endif
                </div>

                {{-- nama dan info --}}
                <h1 class="text-4xl font-bold text-white mb-3">{{ $student->first_name }} {{ $student->last_name }}</h1>
                <p class="text-xl text-blue-100 mb-2">{{ $student->university->name }}</p>
                <p class="text-lg text-blue-50">{{ $student->major }} • Semester {{ $student->semester }}</p>

                @if($student->bio)
                    <p class="mt-6 max-w-3xl mx-auto text-lg text-blue-50 leading-relaxed">{{ $student->bio }}</p>
                @endif
            </div>
        </div>
    </div>

    {{-- main content --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-16 pb-16">
        
        {{-- statistics cards --}}
        <div class="mb-12 fade-in-up">
            <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 p-8">
                @if($student->bio)
                    <p class="text-gray-600 leading-relaxed mb-8 text-center max-w-4xl mx-auto">{{ $student->bio }}</p>
                @endif

                {{-- statistics --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <div class="text-center p-6 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl">
                        <p class="text-4xl font-bold text-blue-600 mb-2">{{ $statistics['total_projects'] }}</p>
                        <p class="text-sm text-gray-700 font-medium">Proyek Selesai</p>
                    </div>
                    <div class="text-center p-6 bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl">
                        <div class="flex items-center justify-center mb-2">
                            <p class="text-4xl font-bold text-yellow-600">{{ number_format($statistics['average_rating'], 1) }}</p>
                            <svg class="w-8 h-8 ml-2 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-700 font-medium">Rating Rata-rata</p>
                    </div>
                    <div class="text-center p-6 bg-gradient-to-br from-green-50 to-green-100 rounded-xl">
                        <p class="text-4xl font-bold text-green-600 mb-2">{{ $statistics['total_beneficiaries'] }}</p>
                        <p class="text-sm text-gray-700 font-medium">Penerima Manfaat</p>
                    </div>
                    <div class="text-center p-6 bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl">
                        <p class="text-4xl font-bold text-purple-600 mb-2">{{ count($sdg_categories) }}</p>
                        <p class="text-sm text-gray-700 font-medium">SDG Categories</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- skills --}}
        @if(!empty($skills))
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8 mb-8 fade-in-up" style="animation-delay: 0.1s;">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Skills & Kompetensi</h2>
                <div class="flex flex-wrap gap-3">
                    @foreach($skills as $skill)
                        <span class="px-5 py-2 bg-gradient-to-r from-blue-100 to-green-100 text-gray-800 rounded-full font-medium">
                            {{ $skill }}
                        </span>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- achievements --}}
        @if(!empty($achievements))
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8 mb-8 fade-in-up" style="animation-delay: 0.15s;">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Pencapaian</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($achievements as $achievement)
                        <div class="p-6 border-2 border-{{ $achievement['color'] }}-200 bg-{{ $achievement['color'] }}-50 rounded-xl text-center hover:shadow-lg transition-all">
                            <div class="w-16 h-16 bg-{{ $achievement['color'] }}-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-{{ $achievement['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                </svg>
                            </div>
                            <h3 class="font-bold text-gray-900 mb-2 text-lg">{{ $achievement['title'] }}</h3>
                            <p class="text-sm text-gray-600">{{ $achievement['description'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- projects showcase --}}
        <div class="fade-in-up" style="animation-delay: 0.2s;">
            <h2 class="text-3xl font-bold text-gray-900 mb-8">Proyek Portfolio</h2>

            @if($projects->isEmpty())
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-16 text-center">
                    <svg class="w-20 h-20 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Proyek</h3>
                    <p class="text-gray-600">Portfolio proyek akan ditampilkan di sini</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($projects as $index => $project)
                        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden hover:shadow-2xl transition-all group">
                            {{-- project image --}}
                            <div class="h-56 bg-gradient-to-br from-blue-500 to-green-500 relative overflow-hidden">
                                @if($project->problem->images->isNotEmpty())
                                    <img src="{{ asset('storage/' . $project->problem->images->first()->image_path) }}" 
                                         alt="{{ $project->problem->title }}"
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                @endif
                                <div class="absolute top-4 right-4">
                                    <span class="px-4 py-2 bg-white text-green-600 rounded-full text-sm font-bold shadow-lg">
                                        ✓ Completed
                                    </span>
                                </div>
                            </div>

                            <div class="p-6">
                                {{-- institution info --}}
                                <div class="flex items-center mb-4">
                                    @if($project->problem->institution->logo_path)
                                        <img src="{{ asset('storage/' . $project->problem->institution->logo_path) }}" 
                                             alt="{{ $project->problem->institution->name }}"
                                             class="w-10 h-10 rounded-lg mr-3 object-cover border border-gray-200">
                                    @else
                                        <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center mr-3 border border-gray-200">
                                            <span class="text-blue-600 font-bold text-sm">
                                                {{ strtoupper(substr($project->problem->institution->name, 0, 1)) }}
                                            </span>
                                        </div>
                                    @endif
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">{{ $project->problem->institution->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $project->problem->institution->institution_type }}</p>
                                    </div>
                                </div>

                                {{-- project title --}}
                                <h3 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2 group-hover:text-blue-600 transition-colors">
                                    {{ $project->problem->title }}
                                </h3>

                                {{-- location --}}
                                <div class="flex items-center text-sm text-gray-600 mb-3">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span>{{ $project->problem->regency->name ?? $project->problem->province->name }}</span>
                                </div>

                                {{-- role in team --}}
                                @if($project->role_in_team)
                                    <div class="mb-4">
                                        <span class="inline-flex items-center px-3 py-1 bg-purple-100 text-purple-700 rounded-lg text-sm font-medium">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                            {{ $project->role_in_team }}
                                        </span>
                                    </div>
                                @endif

                                {{-- institution review --}}
                                @if($project->institutionReview)
                                    @php
                                        $review = $project->institutionReview;
                                    @endphp
                                    <div class="border-t border-gray-100 pt-4 mb-4">
                                        <div class="flex items-center mb-2">
                                            <div class="flex">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <svg class="w-5 h-5 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" 
                                                         fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                    </svg>
                                                @endfor
                                            </div>
                                            <span class="ml-2 text-sm font-bold text-gray-700">{{ number_format($review->rating, 1) }}/5.0</span>
                                        </div>
                                        <p class="text-sm text-gray-600 italic line-clamp-3">"{{ $review->review_text }}"</p>
                                    </div>
                                @endif

                                {{-- impact metrics --}}
                                @if($project->impact_metrics)
                                    <div class="grid grid-cols-2 gap-3 mt-4">
                                        <div class="text-center p-3 bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg">
                                            <p class="text-2xl font-bold text-blue-600">{{ $project->impact_metrics['beneficiaries'] ?? 0 }}</p>
                                            <p class="text-xs text-gray-600 mt-1">Penerima Manfaat</p>
                                        </div>
                                        <div class="text-center p-3 bg-gradient-to-br from-green-50 to-green-100 rounded-lg">
                                            <p class="text-2xl font-bold text-green-600">{{ $project->impact_metrics['activities'] ?? 0 }}</p>
                                            <p class="text-xs text-gray-600 mt-1">Kegiatan</p>
                                        </div>
                                    </div>
                                @endif

                                {{-- SDG tags --}}
                                @if($project->problem->sdg_categories)
                                    @php
                                        $sdgs = is_array($project->problem->sdg_categories) 
                                            ? $project->problem->sdg_categories 
                                            : json_decode($project->problem->sdg_categories, true) ?? [];
                                    @endphp
                                    @if(!empty($sdgs))
                                        <div class="flex flex-wrap gap-2 mt-4">
                                            @foreach(array_slice($sdgs, 0, 3) as $sdg)
                                                <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs font-medium">
                                                    SDG {{ $sdg }}
                                                </span>
                                            @endforeach
                                            @if(count($sdgs) > 3)
                                                <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded text-xs font-medium">
                                                    +{{ count($sdgs) - 3 }} lainnya
                                                </span>
                                            @endif
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</div>

{{-- animasi --}}
<style>
.fade-in-up {
    opacity: 0;
    animation: fadeInUp 0.6s ease-out forwards;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection