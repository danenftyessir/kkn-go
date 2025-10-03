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
                        <p class="text-3xl font-bold text-blue-600">{{ $stats['total_projects'] }}</p>
                        <p class="text-sm text-gray-600 mt-1">Proyek Selesai</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <p class="text-3xl font-bold text-yellow-600 flex items-center justify-center">
                            {{ number_format($stats['average_rating'], 1) }}
                            <svg class="w-6 h-6 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                        </p>
                        <p class="text-sm text-gray-600 mt-1">Rating Rata-rata</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <p class="text-3xl font-bold text-green-600">{{ $stats['total_beneficiaries'] }}</p>
                        <p class="text-sm text-gray-600 mt-1">Penerima Manfaat</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <p class="text-3xl font-bold text-purple-600">{{ count($sdg_addressed) }}</p>
                        <p class="text-sm text-gray-600 mt-1">SDG Categories</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- skills section --}}
        @if(!empty($skills))
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8 fade-in-up" style="animation-delay: 0.15s;">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Skills & Kompetensi</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($skills as $skill)
                        <span class="px-4 py-2 bg-blue-100 text-blue-700 rounded-full text-sm font-medium">{{ $skill }}</span>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- achievements --}}
        @if(!empty($achievements))
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8 fade-in-up" style="animation-delay: 0.2s;">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Pencapaian</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach($achievements as $achievement)
                        <div class="p-4 border-2 border-{{ $achievement['color'] }}-200 bg-{{ $achievement['color'] }}-50 rounded-lg text-center">
                            <div class="w-12 h-12 bg-{{ $achievement['color'] }}-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-6 h-6 text-{{ $achievement['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                </svg>
                            </div>
                            <h4 class="font-bold text-gray-900 mb-1">{{ $achievement['title'] }}</h4>
                            <p class="text-xs text-gray-600">{{ $achievement['description'] }}</p>
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
                                         alt="{{ $project->title }}"
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <svg class="w-16 h-16 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                    </div>
                                @endif
                                
                                {{-- visibility toggle --}}
                                <div class="absolute top-4 right-4">
                                    <button onclick="toggleVisibility({{ $project->id }})" 
                                            class="p-2 bg-white/90 rounded-lg hover:bg-white transition-colors shadow-lg">
                                        <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <div class="p-6">
                                <h4 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2 group-hover:text-blue-600 transition-colors">
                                    {{ $project->title }}
                                </h4>
                                
                                {{-- institution --}}
                                <div class="flex items-center text-sm text-gray-600 mb-3">
                                    @if($project->institution->logo_path)
                                        <img src="{{ asset('storage/' . $project->institution->logo_path) }}" 
                                             alt="{{ $project->institution->name }}"
                                             class="w-6 h-6 rounded mr-2 object-cover">
                                    @endif
                                    <span>{{ $project->institution->name }}</span>
                                </div>

                                {{-- duration --}}
                                <div class="flex items-center text-xs text-gray-500 mb-4">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    {{ $project->start_date->format('M Y') }} - {{ $project->end_date->format('M Y') }}
                                </div>

                                {{-- rating (jika ada) --}}
                                @if($project->reviews->isNotEmpty())
                                    @php
                                        $review = $project->reviews->first();
                                    @endphp
                                    <div class="flex items-center mb-3 pb-3 border-b">
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

<script>
function sharePortfolio() {
    fetch('/student/portfolio/share-link', {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // copy to clipboard
            navigator.clipboard.writeText(data.url).then(() => {
                alert('Link portfolio berhasil disalin!\n\n' + data.url);
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Gagal mendapatkan link portfolio');
    });
}

function toggleVisibility(projectId) {
    fetch(`/student/portfolio/projects/${projectId}/toggle-visibility`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Gagal mengubah visibility proyek');
    });
}
</script>

<style>
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

.fade-in-up {
    animation: fadeInUp 0.6s ease-out forwards;
    opacity: 0;
}
</style>
@endsection