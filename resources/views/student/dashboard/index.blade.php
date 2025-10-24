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
        padding-top: 40px;
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
    
    .text-shadow-strong {
        text-shadow: 
            0 2px 4px rgba(0, 0, 0, 0.4),
            0 4px 8px rgba(0, 0, 0, 0.3),
            0 1px 2px rgba(0, 0, 0, 0.5);
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
            <h1 class="text-4xl md:text-5xl font-bold mb-4 text-white text-shadow-repository">
                Dashboard
            </h1>
            <p class="text-xl md:text-2xl text-white text-shadow-strong max-w-3xl">
                Selamat Datang Kembali, {{ Auth::user()->name }}!
            </p>
        </div>

        {{-- statistics cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mt-10">
            {{-- total applications --}}
            <div class="stats-card-dashboard rounded-xl p-6 fade-in-up" style="animation-delay: 0.1s;">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-4xl md:text-5xl font-bold text-white text-shadow-strong">
                            {{ $stats['total_applications'] }}
                        </div>
                        <div class="text-sm md:text-base text-white font-medium text-shadow-strong mt-2">
                            Total Aplikasi
                        </div>
                    </div>
                    <div class="w-14 h-14 md:w-16 md:h-16 bg-white/25 rounded-xl flex items-center justify-center backdrop-blur-sm">
                        <svg class="w-7 h-7 md:w-8 md:h-8 text-white drop-shadow-lg" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
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
                        <div class="text-4xl md:text-5xl font-bold text-white text-shadow-strong">
                            {{ $stats['pending_applications'] }}
                        </div>
                        <div class="text-sm md:text-base text-white font-medium text-shadow-strong mt-2">
                            Aplikasi Pending
                        </div>
                    </div>
                    <div class="w-14 h-14 md:w-16 md:h-16 bg-white/25 rounded-xl flex items-center justify-center backdrop-blur-sm">
                        <svg class="w-7 h-7 md:w-8 md:h-8 text-white drop-shadow-lg" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
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
                        <div class="text-4xl md:text-5xl font-bold text-white text-shadow-strong">
                            {{ $stats['active_projects'] }}
                        </div>
                        <div class="text-sm md:text-base text-white font-medium text-shadow-strong mt-2">
                            Proyek Aktif
                        </div>
                    </div>
                    <div class="w-14 h-14 md:w-16 md:h-16 bg-white/25 rounded-xl flex items-center justify-center backdrop-blur-sm">
                        <svg class="w-7 h-7 md:w-8 md:h-8 text-white drop-shadow-lg" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
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
                        <div class="text-4xl md:text-5xl font-bold text-white text-shadow-strong">
                            {{ $stats['completed_projects'] }}
                        </div>
                        <div class="text-sm md:text-base text-white font-medium text-shadow-strong mt-2">
                            Proyek Selesai
                        </div>
                    </div>
                    <div class="w-14 h-14 md:w-16 md:h-16 bg-white/25 rounded-xl flex items-center justify-center backdrop-blur-sm">
                        <svg class="w-7 h-7 md:w-8 md:h-8 text-white drop-shadow-lg" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
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
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 fade-in-up content-card" style="animation-delay: 0.3s;">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold text-gray-900">Proyek Aktif</h2>
                        <a href="{{ route('student.projects.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium transition-colors">
                            Lihat Semua →
                        </a>
                    </div>
                    
                    @if($activeProjects->isNotEmpty())
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
                    @else
                    {{-- empty state untuk proyek aktif --}}
                    <div class="text-center py-12">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum Ada Proyek Aktif</h3>
                        <p class="text-gray-600 mb-4">Proyek yang sedang Anda kerjakan akan muncul di sini</p>
                        <a href="{{ route('student.browse-problems.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Cari Proyek
                        </a>
                    </div>
                    @endif
                </div>

                {{-- recent applications --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 fade-in-up content-card" style="animation-delay: 0.35s;">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold text-gray-900">Aplikasi Terbaru</h2>
                        <a href="{{ route('student.applications.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium transition-colors">
                            Lihat Semua →
                        </a>
                    </div>
                    
                    @if($recentApplications->isNotEmpty())
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
                    @else
                    {{-- empty state untuk aplikasi --}}
                    <div class="text-center py-12">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-4">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum Ada Aplikasi</h3>
                        <p class="text-gray-600 mb-4">Status aplikasi yang Anda kirim akan muncul di sini</p>
                        <a href="{{ route('student.browse-problems.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Mulai Apply
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            {{-- sidebar (1 column) --}}
            <div class="space-y-6">
                
                {{-- recommended problems --}}
                @if($recommendedProblems->isNotEmpty())
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 fade-in-up content-card" style="animation-delay: 0.4s;">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-bold text-gray-900">Rekomendasi Untuk Anda</h2>
                    </div>
                    <div class="space-y-3">
                        @foreach($recommendedProblems->take(3) as $problem)
                        <a href="{{ route('student.browse-problems.show', $problem->id) }}" 
                           class="block border border-gray-200 rounded-lg p-3 hover:border-blue-300 hover:bg-blue-50 transition-all duration-200">
                            <h3 class="font-semibold text-sm text-gray-900 mb-1 line-clamp-2">{{ $problem->title }}</h3>
                            <p class="text-xs text-gray-600 mb-2">{{ $problem->institution->name }}</p>
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-gray-500">{{ $problem->regency->name }}</span>
                                <span class="text-xs font-semibold text-blue-600">Lihat →</span>
                            </div>
                        </a>
                        @endforeach
                    </div>
                    <a href="{{ route('student.browse-problems.index') }}" 
                       class="block mt-4 text-center text-sm text-blue-600 hover:text-blue-700 font-medium">
                        Lihat Semua Proyek
                    </a>
                </div>
                @endif

                {{-- upcoming milestones --}}
                @if(isset($upcomingMilestones) && $upcomingMilestones->isNotEmpty())
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 fade-in-up content-card" style="animation-delay: 0.45s;">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-bold text-gray-900">Milestone Mendatang</h2>
                    </div>
                    <div class="space-y-3">
                        @foreach($upcomingMilestones->take(5) as $milestone)
                        <div class="border-l-4 border-orange-400 pl-3 py-2">
                            <h3 class="font-semibold text-sm text-gray-900">{{ $milestone->title }}</h3>
                            <p class="text-xs text-gray-600 mt-1">{{ $milestone->project->problem->title }}</p>
                            <div class="flex items-center text-xs text-orange-600 mt-1">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ \Carbon\Carbon::parse($milestone->target_date)->format('d M Y') }}
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
@endsection