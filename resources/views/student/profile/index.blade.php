@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- breadcrumb -->
        <nav class="mb-6 profile-transition" aria-label="breadcrumb">
            <ol class="flex items-center space-x-2 text-sm">
                <li>
                    <a href="{{ route('student.dashboard') }}" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                    </a>
                </li>
                <li class="text-gray-400">/</li>
                <li>
                    <span class="text-gray-900 font-medium">Profil</span>
                </li>
            </ol>
        </nav>

        <!-- flash messages -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 rounded-lg p-4 flex items-center profile-transition">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-800 rounded-lg p-4 flex items-center profile-transition">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- main profile section -->
            <div class="lg:col-span-2 space-y-6">
                <!-- profile card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden profile-transition">
                    <div class="h-32 bg-gradient-to-r from-blue-600 to-green-600"></div>
                    <div class="px-6 pb-6">
                        <div class="flex flex-col items-center -mt-16 mb-4">
                            @if($student->profile_photo_path)
                                <img src="{{ asset('storage/' . $student->profile_photo_path) }}" 
                                     alt="{{ $student->first_name }}" 
                                     class="w-32 h-32 rounded-xl border-4 border-white shadow-lg object-cover">
                            @else
                                <div class="w-32 h-32 rounded-xl border-4 border-white shadow-lg bg-gradient-to-br from-blue-500 to-green-500 flex items-center justify-center">
                                    <span class="text-white text-4xl font-bold">{{ strtoupper(substr($student->first_name, 0, 1)) }}</span>
                                </div>
                            @endif
                            <div class="mt-4 text-center">
                                <h2 class="text-2xl font-bold text-gray-900 mb-1">{{ $student->first_name }} {{ $student->last_name }}</h2>
                                <p class="text-gray-600">{{ $student->university->name }}</p>
                                <p class="text-sm text-gray-500">{{ $student->major }} • Semester {{ $student->semester }}</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-center gap-3 mb-4">
                            <a href="{{ route('student.profile.edit') }}" 
                               class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                                Edit Profil
                            </a>
                            <a href="{{ route('profile.public', $student->user->username) }}" 
                               target="_blank"
                               class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                                Preview Public
                            </a>
                        </div>
                    </div>

                    @if($student->bio)
                        <div class="px-6 pb-6">
                            <h3 class="text-sm font-semibold text-gray-700 mb-2">Bio</h3>
                            <p class="text-gray-600 leading-relaxed">{{ $student->bio }}</p>
                        </div>
                    @endif

                    <!-- statistics -->
                    <div class="px-6 pb-6">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div class="text-center p-4 bg-gray-50 rounded-lg">
                                <p class="text-3xl font-bold text-blue-600">{{ $statistics['completed_projects'] }}</p>
                                <p class="text-sm text-gray-600 mt-1">Proyek Selesai</p>
                            </div>
                            <div class="text-center p-4 bg-gray-50 rounded-lg">
                                <p class="text-3xl font-bold text-green-600">{{ $statistics['sdgs_addressed'] }}</p>
                                <p class="text-sm text-gray-600 mt-1">SDGs</p>
                            </div>
                            <div class="text-center p-4 bg-gray-50 rounded-lg">
                                <p class="text-3xl font-bold text-purple-600">{{ $statistics['total_impact_beneficiaries'] }}</p>
                                <p class="text-sm text-gray-600 mt-1">Penerima Manfaat</p>
                            </div>
                            <div class="text-center p-4 bg-gray-50 rounded-lg">
                                <p class="text-3xl font-bold text-yellow-600">{{ number_format($statistics['average_rating'], 1) }}</p>
                                <p class="text-sm text-gray-600 mt-1">Rating Rata-rata</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- skills section -->
                @if($student->skills)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 profile-transition">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Skills & Keahlian</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach(json_decode($student->skills, true) ?? [] as $skill)
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-medium">
                                {{ $skill }}
                            </span>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- completed projects portfolio -->
                @if($completedProjects->isNotEmpty())
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 profile-transition">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-900">Portfolio Proyek</h3>
                        <span class="text-sm text-gray-500">{{ $completedProjects->count() }} proyek</span>
                    </div>
                    <div class="space-y-4">
                        @foreach($completedProjects as $project)
                            <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition-colors">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-gray-900 mb-1">{{ $project->title }}</h4>
                                        <p class="text-sm text-gray-600 mb-2">{{ $project->institution->name }}</p>
                                        <div class="flex items-center gap-4 text-xs text-gray-500 mb-2">
                                            <span class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                </svg>
                                                {{ $project->institution->regency->name ?? $project->location_regency }}
                                            </span>
                                            @if($project->institutionReview)
                                            <span class="flex items-center gap-1">
                                                <svg class="w-4 h-4 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                                {{ number_format($project->institutionReview->rating, 1) }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center gap-2 ml-4">
                                        <a href="{{ route('student.projects.show', $project->id) }}" 
                                           class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                                            Lihat Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- sidebar -->
            <div class="space-y-6">
                <!-- contact info -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 profile-transition">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Informasi Kontak</h3>
                    <div class="space-y-3">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <div class="flex-1">
                                <p class="text-sm text-gray-600">Email</p>
                                <p class="text-sm font-medium text-gray-900">{{ $user->email }}</p>
                            </div>
                        </div>
                        @if($student->whatsapp_number)
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <div class="flex-1">
                                <p class="text-sm text-gray-600">WhatsApp</p>
                                <p class="text-sm font-medium text-gray-900">{{ $student->whatsapp_number }}</p>
                            </div>
                        </div>
                        @endif
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                            </svg>
                            <div class="flex-1">
                                <p class="text-sm text-gray-600">NIM</p>
                                <p class="text-sm font-medium text-gray-900">{{ $student->nim }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- share profile -->
                <div class="bg-gradient-to-br from-blue-50 to-green-50 rounded-xl shadow-sm border border-blue-100 p-6 profile-transition">
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Bagikan Portfolio</h3>
                    <p class="text-sm text-gray-600 mb-4">Bagikan profil publik Anda dengan calon rekruter atau mitra</p>
                    <button onclick="sharePortfolio()" 
                            class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                        </svg>
                        Bagikan Portfolio
                    </button>
                </div>

                <!-- quick stats -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 profile-transition">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Statistik</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Total Proyek</span>
                            <span class="text-lg font-bold text-gray-900">{{ $stats['total_projects'] }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Proyek Aktif</span>
                            <span class="text-lg font-bold text-yellow-600">{{ $stats['active_projects'] }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Proyek Selesai</span>
                            <span class="text-lg font-bold text-green-600">{{ $stats['completed_projects'] }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Total Aplikasi</span>
                            <span class="text-lg font-bold text-blue-600">{{ $stats['total_applications'] }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Pending</span>
                            <span class="text-lg font-bold text-orange-600">{{ $stats['pending_applications'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.profile-transition {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.profile-transition:hover {
    transform: translateY(-2px);
}
</style>
@endpush

@push('scripts')
<script>
function sharePortfolio() {
    const portfolioUrl = "{{ route('profile.public', $student->user->username) }}";
    
    if (navigator.share) {
        navigator.share({
            title: 'Portfolio {{ $student->first_name }} {{ $student->last_name }}',
            text: 'Lihat portfolio saya',
            url: portfolioUrl
        });
    } else {
        // fallback: copy to clipboard
        navigator.clipboard.writeText(portfolioUrl).then(() => {
            alert('Link portfolio berhasil disalin!');
        });
    }
}
</script>
@endpush
@endsection