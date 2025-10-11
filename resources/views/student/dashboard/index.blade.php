{{-- resources/views/student/dashboard/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard Mahasiswa')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/browse-problems.css') }}">
<style>
    .dashboard-hero-background {
        position: relative;
        background-image: url('/dashboard-student2.jpg');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        min-height: 320px;
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
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        
        {{-- header --}}
        <div class="mb-8 fade-in-up">
            <h1 class="text-4xl font-bold text-white text-shadow-dashboard">Dashboard</h1>
            <p class="text-white text-lg mt-2 text-shadow-dashboard">Selamat Datang Kembali, {{ Auth::user()->name }}!</p>
        </div>

        {{-- profile completion alert --}}
        @if($profileCompletion['percentage'] < 100)
        <div class="stats-card-dashboard rounded-xl p-4 mb-6 fade-in-up" style="animation-delay: 0.1s;">
            <div class="flex items-start">
                <svg class="w-6 h-6 text-white mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="ml-3 flex-1">
                    <h3 class="text-sm font-semibold text-white">Lengkapi Profil Anda</h3>
                    <p class="mt-1 text-sm text-white opacity-90">
                        Profil Anda {{ $profileCompletion['percentage'] }}% lengkap. Lengkapi profil untuk mendapat rekomendasi proyek yang lebih sesuai.
                    </p>
                </div>
                <a href="{{ route('student.profile.edit') }}" 
                   class="ml-4 px-4 py-2 bg-white text-blue-600 rounded-lg hover:bg-blue-50 transition-colors font-medium text-sm whitespace-nowrap">
                    Lengkapi Sekarang
                </a>
            </div>
        </div>
        @endif

        {{-- statistics cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            {{-- total applications --}}
            <div class="stats-card-dashboard rounded-xl p-6 fade-in-up" style="animation-delay: 0.1s;">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-white opacity-90 mb-1">Total Aplikasi</p>
                        <p class="text-4xl font-bold text-white text-shadow-dashboard">{{ $stats['total_applications'] }}</p>
                    </div>
                    <div class="w-14 h-14 bg-white bg-opacity-30 rounded-xl flex items-center justify-center">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
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
                    <div class="w-14 h-14 bg-white bg-opacity-30 rounded-xl flex items-center justify-center">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
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
                    <div class="w-14 h-14 bg-white bg-opacity-30 rounded-xl flex items-center justify-center">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
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
                    <div class="w-14 h-14 bg-white bg-opacity-30 rounded-xl flex items-center justify-center">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
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
                                        {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                                    </span>
                                    <span class="text-xs text-gray-500">{{ $application->applied_at->diffForHumans() }}</span>
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
            <div class="space-y-6">
                
                {{-- recommended problems --}}
                @if($recommendedProblems->isNotEmpty())
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 fade-in-up content-card" style="animation-delay: 0.4s;">
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
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 fade-in-up content-card" style="animation-delay: 0.45s;">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Milestone Mendatang</h2>
                    <div class="space-y-3">
                        @foreach($upcomingMilestones as $milestone)
                        <div class="border-l-4 border-blue-500 pl-3 py-2">
                            <h3 class="font-semibold text-sm text-gray-900 mb-1">{{ $milestone->title }}</h3>
                            <p class="text-xs text-gray-600 mb-1">{{ $milestone->project->problem->title }}</p>
                            <div class="flex items-center text-xs text-gray-500">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span>{{ \Carbon\Carbon::parse($milestone->target_date)->format('d M Y') }}</span>
                                <span class="ml-2 text-orange-600 font-medium">
                                    ({{ \Carbon\Carbon::parse($milestone->target_date)->diffForHumans() }})
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- quick actions --}}
                <div class="bg-gradient-to-br from-blue-500 to-green-500 rounded-xl shadow-sm p-6 fade-in-up" style="animation-delay: 0.5s;">
                    <h2 class="text-lg font-bold text-white mb-4">Quick Actions</h2>
                    <div class="space-y-3">
                        <a href="{{ route('student.browse-problems.index') }}" 
                           class="flex items-center justify-between bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg p-3 text-white transition-all duration-200">
                            <span class="font-medium">Cari Proyek Baru</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </a>
                        <a href="{{ route('student.wishlist.index') }}" 
                           class="flex items-center justify-between bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg p-3 text-white transition-all duration-200">
                            <span class="font-medium">Lihat Wishlist</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                            </svg>
                        </a>
                        <a href="{{ route('student.repository.index') }}" 
                           class="flex items-center justify-between bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg p-3 text-white transition-all duration-200">
                            <span class="font-medium">Knowledge Repository</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </a>
                        <a href="{{ route('student.profile.index') }}" 
                           class="flex items-center justify-between bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg p-3 text-white transition-all duration-200">
                            <span class="font-medium">Edit Profile</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // smooth scroll untuk semua link internal
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // preload images untuk performance
    window.addEventListener('load', function() {
        const images = document.querySelectorAll('img[data-src]');
        images.forEach(img => {
            img.src = img.dataset.src;
        });
    });
</script>
@endpush