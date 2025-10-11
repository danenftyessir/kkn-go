{{-- resources/views/student/dashboard/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard Mahasiswa')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/browse-problems.css') }}">
<style>
    .dashboard-hero-background {
        position: relative;
        background-image: url('/dashboard-student2.jpeg');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        min-height: 450px;
        margin-top: 64px; /* PERBAIKAN: tambah margin-top untuk memberi jarak dari navbar */
    }
    
    .dashboard-hero-background::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(
            135deg, 
            rgba(37, 99, 235, 0.50) 0%,
            rgba(59, 130, 246, 0.45) 35%,
            rgba(16, 185, 129, 0.45) 65%,
            rgba(5, 150, 105, 0.50) 100%
        );
        backdrop-filter: blur(1px);
    }
    
    .stats-card-dashboard {
        background: rgba(255, 255, 255, 0.20);
        backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .stats-card-dashboard:hover {
        background: rgba(255, 255, 255, 0.30);
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3);
    }
    
    .text-shadow-dashboard {
        text-shadow: 
            0 2px 4px rgba(0, 0, 0, 0.4),
            0 4px 8px rgba(0, 0, 0, 0.3),
            0 1px 2px rgba(0, 0, 0, 0.5);
    }
    
    .fade-in-up {
        animation: fadeInUp 0.6s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        opacity: 0;
        will-change: transform, opacity;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translate3d(0, 20px, 0);
        }
        to {
            opacity: 1;
            transform: translate3d(0, 0, 0);
        }
    }

    /* smooth transitions untuk cards */
    .content-card {
        will-change: transform, box-shadow;
        transform: translate3d(0, 0, 0);
        backface-visibility: hidden;
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1),
                   box-shadow 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .content-card:hover {
        transform: translate3d(0, -2px, 0);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.15);
    }

    /* accessibility - prefers reduced motion */
    @media (prefers-reduced-motion: reduce) {
        *,
        *::before,
        *::after {
            animation-duration: 0.01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.01ms !important;
        }
    }
</style>
@endpush

@section('content')
{{-- hero section dengan background --}}
<div class="dashboard-hero-background">
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        
        {{-- header --}}
        <div class="mb-10 fade-in-up">
            <h1 class="text-4xl font-bold text-white text-shadow-dashboard">Dashboard</h1>
            <p class="text-white text-lg mt-2 text-shadow-dashboard">Selamat Datang Kembali, {{ Auth::user()->name }}!</p>
        </div>

        {{-- statistics cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            {{-- total applications --}}
            <div class="stats-card-dashboard rounded-xl p-6 fade-in-up" style="animation-delay: 0.1s;">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-white opacity-90 mb-1">Total Aplikasi</p>
                        <p class="text-4xl font-bold text-white text-shadow-dashboard">{{ $stats['total_applications'] }}</p>
                    </div>
                    <div class="w-16 h-16 bg-white bg-opacity-30 rounded-xl flex items-center justify-center">
                        <svg class="w-9 h-9 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M7 18h10v-2H7v2zM17 14H7v-2h10v2zM7 10h4V8H7v2zM21 4H3v16h18V4zm-2 14H5V6h14v12z"/>
                        </svg>
                    </div>
                </div>
                <a href="{{ route('student.applications.index') }}" class="mt-3 text-sm text-white hover:underline font-medium inline-flex items-center">
                    Lihat Detail
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

            {{-- pending applications --}}
            <div class="stats-card-dashboard rounded-xl p-6 fade-in-up" style="animation-delay: 0.15s;">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-white opacity-90 mb-1">Aplikasi Pending</p>
                        <p class="text-4xl font-bold text-white text-shadow-dashboard">{{ $stats['pending_applications'] }}</p>
                    </div>
                    <div class="w-16 h-16 bg-white bg-opacity-30 rounded-xl flex items-center justify-center">
                        <svg class="w-9 h-9 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10 10-4.5 10-10S17.5 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.5-13H11v6l5.2 3.2.8-1.3-4.5-2.7V7z"/>
                        </svg>
                    </div>
                </div>
                <a href="{{ route('student.applications.index', ['status' => 'pending']) }}" class="mt-3 text-sm text-white hover:underline font-medium inline-flex items-center">
                    Lihat Detail
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

            {{-- active projects --}}
            <div class="stats-card-dashboard rounded-xl p-6 fade-in-up" style="animation-delay: 0.2s;">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-white opacity-90 mb-1">Proyek Aktif</p>
                        <p class="text-4xl font-bold text-white text-shadow-dashboard">{{ $stats['active_projects'] }}</p>
                    </div>
                    <div class="w-16 h-16 bg-white bg-opacity-30 rounded-xl flex items-center justify-center">
                        <svg class="w-9 h-9 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M7 2v11h3v9l7-12h-4l3-8z"/>
                        </svg>
                    </div>
                </div>
                <a href="{{ route('student.projects.index') }}" class="mt-3 text-sm text-white hover:underline font-medium inline-flex items-center">
                    Lihat Detail
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

            {{-- completed projects --}}
            <div class="stats-card-dashboard rounded-xl p-6 fade-in-up" style="animation-delay: 0.25s;">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-white opacity-90 mb-1">Proyek Selesai</p>
                        <p class="text-4xl font-bold text-white text-shadow-dashboard">{{ $stats['completed_projects'] }}</p>
                    </div>
                    <div class="w-16 h-16 bg-white bg-opacity-30 rounded-xl flex items-center justify-center">
                        <svg class="w-9 h-9 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10 10-4.5 10-10S17.5 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                        </svg>
                    </div>
                </div>
                <a href="{{ route('student.profile.index') }}" class="mt-3 text-sm text-white hover:underline font-medium inline-flex items-center">
                    Lihat Portfolio
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</div>

{{-- main content area --}}
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-green-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- main content (2 columns) --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- active projects --}}
                @if($activeProjects->isNotEmpty())
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 fade-in-up content-card" style="animation-delay: 0.3s;">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold text-gray-900">Proyek Aktif</h2>
                        <a href="{{ route('student.projects.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium transition-colors">
                            Lihat Semua →
                        </a>
                    </div>
                    <div class="space-y-4">
                        @foreach($activeProjects as $project)
                        <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition-colors duration-200">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900 mb-1">{{ $project->problem->title }}</h3>
                                    <p class="text-sm text-gray-600 mb-2">{{ $project->institution->name }}</p>
                                    
                                    {{-- progress bar --}}
                                    <div class="mb-2">
                                        <div class="flex justify-between items-center mb-1">
                                            <span class="text-xs text-gray-600">Progress</span>
                                            <span class="text-xs font-semibold text-gray-700">{{ $project->progress_percentage ?? 0 }}%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-blue-600 h-2 rounded-full transition-all duration-500" style="width: {{ $project->progress_percentage ?? 0 }}%"></div>
                                        </div>
                                    </div>

                                    {{-- next milestone --}}
                                    @if($project->milestones->where('status', '!=', 'completed')->first())
                                        @php
                                            $nextMilestone = $project->milestones->where('status', '!=', 'completed')->sortBy('target_date')->first();
                                        @endphp
                                        <div class="flex items-center text-xs text-gray-500 mt-2">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <span>Next: {{ $nextMilestone->title }} - {{ \Carbon\Carbon::parse($nextMilestone->target_date)->format('d M Y') }}</span>
                                        </div>
                                    @endif
                                </div>
                                <a href="{{ route('student.projects.show', $project->id) }}" 
                                   class="text-blue-600 hover:text-blue-700 ml-4 transition-colors">
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

                {{-- recent applications --}}
                @if($recentApplications->isNotEmpty())
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 fade-in-up content-card" style="animation-delay: 0.35s;">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold text-gray-900">Aplikasi Terbaru</h2>
                        <a href="{{ route('student.applications.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium transition-colors">
                            Lihat Semua →
                        </a>
                    </div>
                    <div class="space-y-3">
                        @foreach($recentApplications as $application)
                        <div class="flex items-start justify-between border-b border-gray-100 pb-3 last:border-0">
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900 text-sm mb-1">{{ $application->problem->title }}</h3>
                                <p class="text-xs text-gray-600 mb-1">{{ $application->problem->institution->name }}</p>
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                        {{ $application->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $application->status === 'under_review' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $application->status === 'accepted' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $application->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                                        @if($application->status === 'pending')
                                            Pending
                                        @elseif($application->status === 'under_review')
                                            Under Review
                                        @elseif($application->status === 'accepted')
                                            Accepted
                                        @elseif($application->status === 'rejected')
                                            Rejected
                                        @else
                                            {{ ucfirst($application->status) }}
                                        @endif
                                    </span>
                                    <span class="text-xs text-gray-500">{{ $application->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            <a href="{{ route('student.applications.show', $application->id) }}" 
                               class="text-blue-600 hover:text-blue-700 ml-4 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            {{-- sidebar (1 column) --}}
            <div class="lg:col-span-1 space-y-6">
                
                {{-- profile completion alert --}}
                @if($profileCompletion['percentage'] < 100)
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-sm p-6 text-white fade-in-up content-card" style="animation-delay: {{ $profileCompletion['percentage'] < 100 ? '0.4s' : '0.35s' }};">
                    <div class="flex items-start gap-3 mb-3">
                        <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <h3 class="font-bold text-lg mb-1">Lengkapi Profil Anda</h3>
                            <p class="text-sm opacity-90">
                                Profil anda {{ $profileCompletion['percentage'] }}% lengkap ({{ $profileCompletion['fields']['profile_photo'] + $profileCompletion['fields']['bio'] + $profileCompletion['fields']['skills'] + $profileCompletion['fields']['whatsapp'] + $profileCompletion['fields']['semester'] }}/{{ count($profileCompletion['fields']) }} field). Lengkapi profil untuk mendapat rekomendasi proyek yang lebih sesuai.
                            </p>
                        </div>
                    </div>
                    
                    {{-- progress bar --}}
                    <div class="mb-4">
                        <div class="w-full bg-white bg-opacity-20 rounded-full h-2">
                            <div class="bg-white h-2 rounded-full transition-all duration-500" style="width: {{ $profileCompletion['percentage'] }}%"></div>
                        </div>
                    </div>
                    
                    <a href="{{ route('student.profile.edit') }}" 
                       class="block w-full px-4 py-2 bg-white text-blue-600 rounded-lg hover:bg-blue-50 transition-colors font-semibold text-sm text-center">
                        Lengkapi Sekarang
                    </a>
                </div>
                @endif
                
                {{-- recommended problems --}}
                @if($recommendedProblems->isNotEmpty())
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 fade-in-up content-card" style="animation-delay: {{ $profileCompletion['percentage'] < 100 ? '0.45s' : '0.35s' }};">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Rekomendasi Proyek</h2>
                    <div class="space-y-4">
                        @foreach($recommendedProblems->take(3) as $problem)
                        <div class="border border-gray-200 rounded-lg p-3 hover:border-blue-300 transition-colors duration-200">
                            @if($problem->images->where('is_cover', true)->first())
                                <div class="w-full h-32 rounded-lg mb-3 overflow-hidden">
                                    <img src="{{ $problem->images->where('is_cover', true)->first()->image_url }}" 
                                         alt="{{ $problem->title }}"
                                         class="w-full h-full object-cover">
                                </div>
                            @endif
                            <h3 class="font-semibold text-sm text-gray-900 mb-1 line-clamp-2">{{ $problem->title }}</h3>
                            <p class="text-xs text-gray-600 mb-2">{{ $problem->institution->name }}</p>
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-gray-500">
                                    <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    {{ $problem->regency->name ?? $problem->location_regency }}
                                </span>
                                <a href="{{ route('student.browse-problems.show', $problem->id) }}" 
                                   class="text-xs text-blue-600 hover:text-blue-700 font-medium transition-colors">
                                    Detail →
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <a href="{{ route('student.browse-problems.index') }}" 
                       class="mt-4 block text-center text-sm text-blue-600 hover:text-blue-700 font-medium transition-colors">
                        Lihat Semua Proyek →
                    </a>
                </div>
                @endif

                {{-- upcoming milestones --}}
                @if($upcomingMilestones->isNotEmpty())
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 fade-in-up content-card" style="animation-delay: {{ $profileCompletion['percentage'] < 100 ? '0.5s' : '0.4s' }};">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Milestone Mendatang</h2>
                    <div class="space-y-3">
                        @foreach($upcomingMilestones as $milestone)
                        <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-sm font-semibold text-gray-900 mb-1">{{ $milestone->title }}</h3>
                                <p class="text-xs text-gray-600 mb-1">{{ $milestone->project->title ?? 'N/A' }}</p>
                                <div class="flex items-center text-xs text-gray-500">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    {{ \Carbon\Carbon::parse($milestone->target_date)->format('d M Y') }}
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- notifications --}}
                @if($unreadNotifications->isNotEmpty())
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 fade-in-up content-card" style="animation-delay: {{ $profileCompletion['percentage'] < 100 ? '0.55s' : '0.45s' }};">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-bold text-gray-900">Notifikasi</h2>
                        <a href="{{ route('notifications.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium transition-colors">
                            Lihat Semua →
                        </a>
                    </div>
                    <div class="space-y-3">
                        @foreach($unreadNotifications as $notification)
                        <div class="p-3 bg-blue-50 rounded-lg border border-blue-100">
                            <p class="text-sm text-gray-900 mb-1">{{ $notification->title }}</p>
                            <p class="text-xs text-gray-600">{{ $notification->created_at->diffForHumans() }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection