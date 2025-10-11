{{-- resources/views/student/profile/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- header dengan share button --}}
        <div class="mb-8 fade-in-up">
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">profil & portfolio</h1>
                    <p class="text-gray-600">kelola informasi pribadi dan showcase proyek anda</p>
                </div>
                <button onclick="sharePortfolio()" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path>
                    </svg>
                    share profil
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            {{-- sidebar kiri --}}
            <div class="lg:col-span-1 space-y-6">
                {{-- profile card --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 fade-in-up">
                    <div class="text-center">
                        {{-- foto profil --}}
                        @if($student->profile_photo_path)
                            <img src="{{ $student->profile_photo_url }}" 
                                 alt="{{ $user->name }}" 
                                 class="w-32 h-32 rounded-full mx-auto mb-4 object-cover border-4 border-blue-100">
                        @else
                            <div class="w-32 h-32 rounded-full mx-auto mb-4 bg-blue-100 flex items-center justify-center text-blue-600 text-4xl font-bold border-4 border-blue-200">
                                {{ strtoupper(substr($student->first_name, 0, 1)) }}{{ strtoupper(substr($student->last_name, 0, 1)) }}
                            </div>
                        @endif

                        <h2 class="text-2xl font-bold text-gray-900 mb-1">
                            {{ $student->first_name }} {{ $student->last_name }}
                        </h2>
                        <p class="text-gray-600 mb-2">{{ $student->major }}</p>
                        <p class="text-sm text-gray-500 mb-4">{{ $student->university->name ?? '-' }}</p>

                        @if($student->bio)
                            <p class="text-sm text-gray-600 mb-4 px-2">{{ $student->bio }}</p>
                        @endif
                    </div>

                    {{-- tombol edit profil --}}
                    <a href="{{ route('student.profile.edit') }}" 
                       class="w-full inline-flex items-center justify-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-300 font-medium">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        edit profil
                    </a>

                    {{-- tombol lihat profil publik --}}
                    <a href="{{ route('profile.public', $user->username) }}" 
                       target="_blank"
                       class="mt-3 w-full inline-flex items-center justify-center px-6 py-3 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all duration-300 font-medium">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                        </svg>
                        lihat profil publik
                    </a>
                </div>

                {{-- statistik portfolio --}}
                @if(isset($statistics) && $statistics)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 fade-in-up" style="animation-delay: 0.1s;">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">statistik portfolio</h3>
                    
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <span class="text-sm text-gray-600">proyek selesai</span>
                            </div>
                            <span class="text-xl font-bold text-gray-900">{{ $statistics['completed_projects'] }}</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <span class="text-sm text-gray-600">SDG disentuh</span>
                            </div>
                            <span class="text-xl font-bold text-gray-900">{{ $statistics['sdgs_addressed'] }}</span>
                        </div>

                        @if($statistics['average_rating'] > 0)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                </div>
                                <span class="text-sm text-gray-600">rating rata-rata</span>
                            </div>
                            <span class="text-xl font-bold text-gray-900">{{ number_format($statistics['average_rating'], 1) }}</span>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            {{-- konten kanan --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- statistik aplikasi --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 fade-in-up" style="animation-delay: 0.2s;">
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-blue-100 text-sm font-medium">total proyek</p>
                                <p class="text-3xl font-bold mt-1">{{ $stats['total_projects'] }}</p>
                            </div>
                            <div class="bg-blue-400 bg-opacity-50 rounded-full p-3">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-green-100 text-sm font-medium">aplikasi pending</p>
                                <p class="text-3xl font-bold mt-1">{{ $stats['pending_applications'] }}</p>
                            </div>
                            <div class="bg-green-400 bg-opacity-50 rounded-full p-3">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- informasi pribadi --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 fade-in-up" style="animation-delay: 0.3s;">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">informasi pribadi</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">email</p>
                            <p class="text-gray-900">{{ $user->email }}</p>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">nomor whatsapp</p>
                            <p class="text-gray-900">{{ $student->whatsapp ?? '-' }}</p>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">nim</p>
                            <p class="text-gray-900">{{ $student->nim }}</p>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">semester</p>
                            <p class="text-gray-900">{{ $student->semester ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                {{-- skills (dari portfolio) --}}
                @if(isset($skills) && $skills && count($skills) > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 fade-in-up" style="animation-delay: 0.4s;">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">skills & keahlian</h3>
                    
                    <div class="flex flex-wrap gap-2">
                        @foreach($skills as $skill)
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-medium">
                                {{ $skill }}
                            </span>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- completed projects showcase --}}
                @if(isset($completed_projects) && $completed_projects && $completed_projects->isNotEmpty())
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 fade-in-up" style="animation-delay: 0.5s;">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-bold text-gray-900">proyek yang telah diselesaikan</h3>
                        <span class="text-sm text-gray-500">{{ $completed_projects->count() }} proyek</span>
                    </div>

                    <div class="space-y-4">
                        @foreach($completed_projects as $project)
                            <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition-colors">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-gray-900 mb-2">{{ $project->problem->title }}</h4>
                                        
                                        <div class="flex flex-wrap items-center gap-3 text-sm text-gray-600 mb-2">
                                            <span class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                </svg>
                                                {{ $project->problem->institution->name }}
                                            </span>
                                            <span class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                </svg>
                                                {{ $project->problem->regency->name ?? '-' }}
                                            </span>
                                        </div>

                                        @if($project->role_in_team)
                                            <p class="text-sm text-gray-600 mb-2">
                                                <span class="font-medium">role:</span> {{ $project->role_in_team }}
                                            </p>
                                        @endif

                                        {{-- rating dari institution --}}
                                        @if($project->institutionReview)
                                            <div class="flex items-center gap-2 mt-2">
                                                <div class="flex items-center">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <svg class="w-4 h-4 {{ $i <= $project->institutionReview->rating ? 'text-yellow-400' : 'text-gray-300' }}" 
                                                             fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                        </svg>
                                                    @endfor
                                                </div>
                                                <span class="text-sm text-gray-600">
                                                    {{ number_format($project->institutionReview->rating, 1) }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="flex items-center gap-2 ml-4">
                                        <a href="{{ route('student.projects.show', $project->id) }}" 
                                           class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                                            lihat detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
</div>

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

.fade-in-up {
    animation: fadeInUp 0.6s cubic-bezier(0.4, 0, 0.2, 1);
}
</style>
@endpush

@push('scripts')
<script>
function sharePortfolio() {
    const portfolioUrl = "{{ route('profile.public', $user->username) }}";
    
    if (navigator.share) {
        navigator.share({
            title: 'Profil {{ $student->first_name }} {{ $student->last_name }}',
            text: 'lihat profil portfolio saya',
            url: portfolioUrl
        }).catch(err => console.log('Error sharing:', err));
    } else {
        // fallback: copy to clipboard
        navigator.clipboard.writeText(portfolioUrl).then(() => {
            alert('link profil berhasil disalin!');
        }).catch(err => {
            console.error('Failed to copy:', err);
        });
    }
}
</script>
@endpush

@endsection