{{-- resources/views/student/portfolio/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- header dengan share button --}}
        <div class="mb-8 fade-in-up">
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Portfolio Saya</h1>
                    <p class="text-gray-600">Showcase proyek dan pencapaian Anda</p>
                </div>
                <button onclick="sharePortfolio()" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path>
                    </svg>
                    Share Portfolio
                </button>
            </div>
        </div>

        {{-- profile header --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-8 fade-in-up" style="animation-delay: 0.1s;">
            <div class="h-32 bg-gradient-to-r from-blue-600 to-green-600"></div>
            <div class="px-8 pb-8">
                <div class="flex items-end justify-between -mt-16 mb-6">
                    <div class="flex items-end space-x-6">
                        @if($student->profile_photo_path)
                            <img src="{{ asset('storage/' . $student->profile_photo_path) }}" 
                                 alt="{{ $student->user->name }}"
                                 class="w-32 h-32 rounded-xl border-4 border-white shadow-lg object-cover">
                        @else
                            <div class="w-32 h-32 rounded-xl border-4 border-white shadow-lg bg-gradient-to-br from-blue-500 to-green-500 flex items-center justify-center">
                                <span class="text-white text-4xl font-bold">{{ strtoupper(substr($student->first_name, 0, 1)) }}</span>
                            </div>
                        @endif
                        <div class="mb-4">
                            <h2 class="text-2xl font-bold text-gray-900 mb-1">{{ $student->first_name }} {{ $student->last_name }}</h2>
                            <p class="text-gray-600">{{ $student->university->name }}</p>
                            <p class="text-sm text-gray-500">{{ $student->major }} • Semester {{ $student->semester }}</p>
                        </div>
                    </div>
                    <a href="{{ route('portfolio.public', $portfolio_slug) }}" 
                       target="_blank"
                       class="mb-4 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        Preview Public View
                    </a>
                </div>

                @if($student->bio)
                    <p class="text-gray-600 leading-relaxed mb-6">{{ $student->bio }}</p>
                @endif

                {{-- statistics --}}
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <p class="text-3xl font-bold text-blue-600">{{ $statistics['total_projects'] }}</p>
                        <p class="text-sm text-gray-600 mt-1">Proyek Selesai</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <p class="text-3xl font-bold text-yellow-600 flex items-center justify-center">
                            {{ number_format($statistics['average_rating'], 1) }}
                            <svg class="w-6 h-6 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                        </p>
                        <p class="text-sm text-gray-600 mt-1">Rating Rata-rata</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <p class="text-3xl font-bold text-green-600">{{ $statistics['total_beneficiaries'] }}</p>
                        <p class="text-sm text-gray-600 mt-1">Penerima Manfaat</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <p class="text-3xl font-bold text-purple-600">{{ count($sdg_categories) }}</p>
                        <p class="text-sm text-gray-600 mt-1">SDG Categories</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- skills section --}}
        @if(!empty($skills))
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8 fade-in-up" style="animation-delay: 0.15s;">
                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                    </svg>
                    Skills
                </h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($skills as $skill)
                        <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-medium">{{ $skill }}</span>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- achievements section --}}
        @if(!empty($achievements))
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8 fade-in-up" style="animation-delay: 0.2s;">
                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                    </svg>
                    Achievements
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($achievements as $achievement)
                        <div class="border border-{{ $achievement['color'] }}-200 bg-{{ $achievement['color'] }}-50 rounded-lg p-4">
                            <div class="flex items-start space-x-3">
                                <div class="w-10 h-10 bg-{{ $achievement['color'] }}-200 rounded-full flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-{{ $achievement['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900 mb-1">{{ $achievement['title'] }}</h4>
                                    <p class="text-sm text-gray-600">{{ $achievement['description'] }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- projects showcase --}}
        <div class="fade-in-up" style="animation-delay: 0.25s;">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-gray-900">Proyek Portfolio</h3>
                <p class="text-sm text-gray-600">{{ $projects->count() }} proyek ditampilkan</p>
            </div>

            @if($projects->isEmpty())
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h4 class="text-lg font-semibold text-gray-900 mb-2">Belum Ada Proyek di Portfolio</h4>
                    <p class="text-gray-600">Selesaikan proyek untuk menambahkannya ke portfolio Anda</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($projects as $index => $project)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-all group">
                            {{-- project image placeholder --}}
                            <div class="h-48 bg-gradient-to-br from-blue-500 to-green-500 relative">
                                @if($project->problem->images->isNotEmpty())
                                    <img src="{{ asset('storage/' . $project->problem->images->first()->image_path) }}" 
                                         alt="{{ $project->problem->title }}"
                                         class="w-full h-full object-cover">
                                @endif
                                <div class="absolute top-4 right-4">
                                    <span class="px-3 py-1 bg-white text-green-600 rounded-full text-xs font-medium shadow-sm">
                                        Completed
                                    </span>
                                </div>
                            </div>

                            <div class="p-6">
                                {{-- institution logo dan nama --}}
                                <div class="flex items-center mb-3">
                                    @if($project->problem->institution->logo_path)
                                        <img src="{{ asset('storage/' . $project->problem->institution->logo_path) }}" 
                                             alt="{{ $project->problem->institution->name }}"
                                             class="w-8 h-8 rounded-lg mr-2 object-cover">
                                    @else
                                        <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center mr-2">
                                            <span class="text-blue-600 text-xs font-bold">
                                                {{ strtoupper(substr($project->problem->institution->name, 0, 1)) }}
                                            </span>
                                        </div>
                                    @endif
                                    <span class="text-sm text-gray-600">{{ $project->problem->institution->name }}</span>
                                </div>

                                {{-- judul project --}}
                                <h4 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2 group-hover:text-blue-600 transition-colors">
                                    {{ $project->problem->title }}
                                </h4>

                                {{-- lokasi dan durasi --}}
                                <div class="flex items-center text-sm text-gray-600 mb-3">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span>{{ $project->problem->regency->name ?? 'Lokasi' }}</span>
                                </div>

                                {{-- role in team --}}
                                @if($project->role_in_team)
                                    <div class="mb-3">
                                        <span class="inline-flex items-center px-2 py-1 bg-purple-100 text-purple-700 rounded text-xs font-medium">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                    <div class="flex items-center mb-2">
                                        <div class="flex">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" 
                                                     fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                            @endfor
                                        </div>
                                        <span class="ml-2 text-sm font-semibold text-gray-700">{{ number_format($review->rating, 1) }}</span>
                                    </div>
                                    <p class="text-sm text-gray-600 italic line-clamp-2">"{{ $review->review_text }}"</p>
                                @endif

                                {{-- impact metrics --}}
                                @if($project->impact_metrics)
                                    <div class="grid grid-cols-2 gap-2 mt-4">
                                        <div class="text-center p-2 bg-blue-50 rounded">
                                            <p class="text-lg font-bold text-blue-600">{{ $project->impact_metrics['beneficiaries'] ?? 0 }}</p>
                                            <p class="text-xs text-gray-600">Penerima Manfaat</p>
                                        </div>
                                        <div class="text-center p-2 bg-green-50 rounded">
                                            <p class="text-lg font-bold text-green-600">{{ $project->impact_metrics['activities'] ?? 0 }}</p>
                                            <p class="text-xs text-gray-600">Kegiatan</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</div>

{{-- share portfolio script --}}
<script>
function sharePortfolio() {
    const portfolioUrl = "{{ route('portfolio.public', $portfolio_slug) }}";
    
    if (navigator.share) {
        navigator.share({
            title: 'Portfolio - {{ $student->first_name }} {{ $student->last_name }}',
            text: 'Lihat portfolio proyek KKN saya',
            url: portfolioUrl
        }).catch(console.error);
    } else {
        // fallback: copy ke clipboard
        navigator.clipboard.writeText(portfolioUrl).then(() => {
            alert('Link portfolio telah disalin ke clipboard!');
        });
    }
}
</script>

{{-- animasi fade in --}}
<style>
.fade-in-up {
    opacity: 0;
    animation: fadeInUp 0.6s ease-out forwards;
}

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
</style>
@endsection