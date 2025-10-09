@extends('layouts.app')

@section('title', 'Dashboard Mahasiswa')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-green-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- header --}}
        <div class="mb-8 fade-in-up">
            <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
            <p class="text-gray-600 mt-1">Selamat datang kembali, {{ Auth::user()->name }}!</p>
        </div>

        {{-- profile completion alert --}}
        @if($profileCompletion['percentage'] < 100)
        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6 fade-in-up" style="animation-delay: 0.1s;">
            <div class="flex items-start">
                <svg class="w-6 h-6 text-blue-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="ml-3 flex-1">
                    <h3 class="text-sm font-semibold text-blue-800">Lengkapi Profil Anda</h3>
                    <p class="mt-1 text-sm text-blue-700">
                        Profil Anda {{ $profileCompletion['percentage'] }}% lengkap. Lengkapi profil untuk mendapat rekomendasi proyek yang lebih sesuai.
                    </p>
                    <div class="mt-3">
                        <a href="{{ route('student.profile.edit') }}" class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition-colors duration-200">
                            Lengkapi Profil
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- statistik cards --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8 fade-in-up" style="animation-delay: 0.15s;">
            {{-- active projects --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Proyek Aktif</p>
                        <p class="text-3xl font-bold text-blue-600">{{ $stats['active_projects'] }}</p>
                    </div>
                    <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center">
                        <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <a href="{{ route('student.projects.index') }}" class="mt-3 text-sm text-blue-600 hover:text-blue-700 font-medium inline-flex items-center">
                    Lihat Detail
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

            {{-- pending applications --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Aplikasi Pending</p>
                        <p class="text-3xl font-bold text-yellow-600">{{ $stats['pending_applications'] }}</p>
                    </div>
                    <div class="w-14 h-14 bg-yellow-100 rounded-xl flex items-center justify-center">
                        <svg class="w-7 h-7 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <a href="{{ route('student.applications.index') }}" class="mt-3 text-sm text-yellow-600 hover:text-yellow-700 font-medium inline-flex items-center">
                    Lihat Detail
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

            {{-- total applications --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Total Aplikasi</p>
                        <p class="text-3xl font-bold text-purple-600">{{ $stats['total_applications'] }}</p>
                    </div>
                    <div class="w-14 h-14 bg-purple-100 rounded-xl flex items-center justify-center">
                        <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
                <a href="{{ route('student.applications.index') }}" class="mt-3 text-sm text-purple-600 hover:text-purple-700 font-medium inline-flex items-center">
                    Lihat Detail
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

            {{-- completed projects --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Proyek Selesai</p>
                        <p class="text-3xl font-bold text-green-600">{{ $stats['completed_projects'] }}</p>
                    </div>
                    <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center">
                        <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                        </svg>
                    </div>
                </div>
                <a href="{{ route('student.portfolio.index') }}" class="mt-3 text-sm text-green-600 hover:text-green-700 font-medium inline-flex items-center">
                    Lihat Portfolio
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- main content (2 columns) --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- active projects --}}
                @if($activeProjects->isNotEmpty())
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 fade-in-up" style="animation-delay: 0.2s;">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold text-gray-900">Proyek Aktif</h2>
                        <a href="{{ route('student.projects.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                            Lihat Semua
                        </a>
                    </div>
                    <div class="space-y-4">
                        @foreach($activeProjects as $project)
                        <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition-colors duration-200">
                            <div class="flex items-start justify-between mb-2">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900 mb-1">{{ $project->title }}</h3>
                                    <p class="text-sm text-gray-600">{{ $project->institution->name }}</p>
                                </div>
                                <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium">
                                    Aktif
                                </span>
                            </div>
                            <div class="mt-3">
                                <div class="flex items-center justify-between text-sm text-gray-600 mb-2">
                                    <span>Progress</span>
                                    <span class="font-semibold">{{ $project->progress_percentage }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full transition-all duration-500" style="width: {{ $project->progress_percentage }}%"></div>
                                </div>
                            </div>
                            <div class="mt-3 flex items-center justify-between">
                                <span class="text-xs text-gray-500">
                                    Target selesai: {{ $project->end_date->format('d M Y') }}
                                </span>
                                <a href="{{ route('student.projects.show', $project->id) }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                                    Lihat Detail →
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- recent applications --}}
                @if($recentApplications->isNotEmpty())
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 fade-in-up" style="animation-delay: 0.25s;">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold text-gray-900">Aplikasi Terbaru</h2>
                        <a href="{{ route('student.applications.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                            Lihat Semua
                        </a>
                    </div>
                    <div class="space-y-3">
                        @foreach($recentApplications as $application)
                        <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:border-blue-300 transition-colors duration-200">
                            <div class="flex-1">
                                <h3 class="font-medium text-gray-900 text-sm mb-1">{{ $application->problem->title }}</h3>
                                <p class="text-xs text-gray-600">{{ $application->problem->institution->name }}</p>
                            </div>
                            <div class="flex items-center gap-3">
                                @if($application->status === 'pending')
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded text-xs font-medium">
                                    Pending
                                </span>
                                @elseif($application->status === 'reviewed')
                                <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs font-medium">
                                    Direview
                                </span>
                                @elseif($application->status === 'accepted')
                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-medium">
                                    Diterima
                                </span>
                                @elseif($application->status === 'rejected')
                                <span class="px-2 py-1 bg-red-100 text-red-700 rounded text-xs font-medium">
                                    Ditolak
                                </span>
                                @endif
                                <a href="{{ route('student.applications.show', $application->id) }}" class="text-blue-600 hover:text-blue-700">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- recommended problems --}}
                @if($recommendedProblems->isNotEmpty())
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 fade-in-up" style="animation-delay: 0.3s;">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold text-gray-900">Rekomendasi Proyek</h2>
                        <a href="{{ route('student.browse-problems.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                            Lihat Semua
                        </a>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($recommendedProblems as $problem)
                        <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 hover:shadow-md transition-all duration-200">
                            @if($problem->images->where('is_cover', true)->first())
                            <img src="{{ $problem->images->where('is_cover', true)->first()->image_path }}" 
                                 alt="{{ $problem->title }}"
                                 class="w-full h-32 object-cover rounded-lg mb-3">
                            @endif
                            <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2">{{ $problem->title }}</h3>
                            <p class="text-sm text-gray-600 mb-2">{{ $problem->institution->name }}</p>
                            <div class="flex items-center gap-2 text-xs text-gray-500 mb-3">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                {{ $problem->regency->name }}
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-gray-500">
                                    {{ $problem->applications_count }} aplikasi
                                </span>
                                <a href="{{ route('student.browse-problems.detail', $problem->id) }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                                    Lihat Detail →
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            {{-- sidebar (1 column) --}}
            <div class="space-y-6">
                
                {{-- upcoming milestones --}}
                @if($upcomingMilestones->isNotEmpty())
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 fade-in-up" style="animation-delay: 0.35s;">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Milestone Mendatang</h2>
                    <div class="space-y-3">
                        @foreach($upcomingMilestones as $milestone)
                        <div class="border-l-4 border-blue-500 pl-3 py-2">
                            <h3 class="font-medium text-gray-900 text-sm mb-1">{{ $milestone->title }}</h3>
                            <p class="text-xs text-gray-600 mb-1">{{ $milestone->project->title }}</p>
                            <div class="flex items-center gap-1 text-xs text-gray-500">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                {{ $milestone->target_date->format('d M Y') }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- notifications --}}
                @if($unreadNotifications->isNotEmpty())
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 fade-in-up" style="animation-delay: 0.4s;">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-bold text-gray-900">Notifikasi</h2>
                        <a href="{{ route('notifications.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                            Lihat Semua
                        </a>
                    </div>
                    <div class="space-y-3">
                        @foreach($unreadNotifications as $notification)
                        <div class="p-3 bg-blue-50 border border-blue-200 rounded-lg">
                            <p class="text-sm text-gray-900 font-medium mb-1">{{ $notification->title }}</p>
                            <p class="text-xs text-gray-600 mb-2">{{ $notification->message }}</p>
                            <span class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- quick actions --}}
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white fade-in-up" style="animation-delay: 0.45s;">
                    <h2 class="text-lg font-bold mb-4">Quick Actions</h2>
                    <div class="space-y-3">
                        <a href="{{ route('student.browse-problems.index') }}" class="block w-full bg-white/20 hover:bg-white/30 backdrop-blur-sm rounded-lg p-3 transition-colors duration-200">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-white/30 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                                <span class="font-medium">Cari Proyek Baru</span>
                            </div>
                        </a>
                        <a href="{{ route('student.profile.index') }}" class="block w-full bg-white/20 hover:bg-white/30 backdrop-blur-sm rounded-lg p-3 transition-colors duration-200">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-white/30 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <span class="font-medium">Edit Profil</span>
                            </div>
                        </a>
                        <a href="{{ route('student.portfolio.index') }}" class="block w-full bg-white/20 hover:bg-white/30 backdrop-blur-sm rounded-lg p-3 transition-colors duration-200">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-white/30 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <span class="font-medium">Lihat Portfolio</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.fade-in-up {
    animation: fadeInUp 0.6s ease-out forwards;
    opacity: 0;
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

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection