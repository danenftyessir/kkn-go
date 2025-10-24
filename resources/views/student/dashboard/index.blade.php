{{-- resources/views/student/dashboard/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard Mahasiswa')

@push('styles')
<style>
    /* animasi fade in dengan smooth entrance */
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

    .fade-in-up {
        animation: fadeInUp 0.6s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        opacity: 0;
    }

    /* smooth scrolling dengan gpu acceleration */
    html {
        scroll-behavior: smooth;
    }

    .content-card {
        transform: translateZ(0);
        will-change: transform;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .content-card:hover {
        transform: translateY(-4px) translateZ(0);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    /* gradient background untuk stats cards */
    .stats-card-dashboard {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        transition: all 0.3s ease;
    }

    .stats-card-dashboard:nth-child(2) {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }

    .stats-card-dashboard:nth-child(3) {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }

    .stats-card-dashboard:nth-child(4) {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    }

    .stats-card-dashboard:hover {
        transform: translateY(-5px) scale(1.02);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
    }

    /* text shadow untuk readability */
    .text-shadow-strong {
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }

    /* progress bar animation */
    .progress-bar {
        transition: width 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* smooth hover effects */
    .hover-lift {
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .hover-lift:hover {
        transform: translateY(-2px);
    }
</style>
@endpush

@section('content')
{{-- header section dengan welcome message --}}
<div class="bg-gradient-to-r from-blue-600 via-blue-700 to-indigo-800 text-white py-12 fade-in-up">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h1 class="text-3xl md:text-4xl font-bold mb-2 text-shadow-strong">
                    Selamat Datang, {{ Auth::user()->first_name }}! 👋
                </h1>
                <p class="text-blue-100 text-lg">
                    {{ now()->format('l, d F Y') }}
                </p>
            </div>
            
            {{-- profile completion alert jika belum lengkap --}}
            @if(!$profileCompletion['is_complete'])
            <div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-lg px-4 py-3">
                <div class="flex items-center gap-3">
                    <div class="flex-shrink-0">
                        <svg class="w-6 h-6 text-yellow-300" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold">Profil {{ $profileCompletion['percentage'] }}% Lengkap</p>
                        <a href="{{ route('student.profile.edit') }}" class="text-xs text-yellow-200 hover:text-yellow-100 underline">
                            Lengkapi Sekarang →
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- statistics cards section --}}
<div class="bg-gradient-to-br from-blue-50 via-white to-green-50 py-8 -mt-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            
            {{-- total aplikasi --}}
            <div class="stats-card-dashboard rounded-xl p-6 fade-in-up" style="animation-delay: 0s;">
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
                        <svg class="w-7 h-7 md:w-8 md:h-8 text-white drop-shadow-lg" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm0 4c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm6 12H6v-1.4c0-2 4-3.1 6-3.1s6 1.1 6 3.1V19z"/>
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

            {{-- aplikasi pending --}}
            <div class="stats-card-dashboard rounded-xl p-6 fade-in-up" style="animation-delay: 0.1s;">
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
                        <svg class="w-7 h-7 md:w-8 md:h-8 text-white drop-shadow-lg" fill="currentColor" viewBox="0 0 24 24">
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

            {{-- proyek aktif --}}
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
                        <svg class="w-7 h-7 md:w-8 md:h-8 text-white drop-shadow-lg" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/>
                        </svg>
                    </div>
                </div>
                <a href="{{ route('student.projects.index', ['status' => 'active']) }}" class="mt-3 text-sm text-white hover:underline font-medium inline-flex items-center">
                    Lihat Detail
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

            {{-- proyek selesai --}}
            <div class="stats-card-dashboard rounded-xl p-6 fade-in-up" style="animation-delay: 0.3s;">
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
                        <svg class="w-7 h-7 md:w-8 md:h-8 text-white drop-shadow-lg" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4L9 16.2z"/>
                        </svg>
                    </div>
                </div>
                <a href="{{ route('student.portfolio.index') }}" class="mt-3 text-sm text-white hover:underline font-medium inline-flex items-center">
                    Lihat Portfolio
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>

        {{-- main content area dengan 2 kolom --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            {{-- main content (2 columns) --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- section proyek aktif --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 fade-in-up content-card" style="animation-delay: 0.35s;">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold text-gray-900">Proyek Aktif</h2>
                        <a href="{{ route('student.projects.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium transition-colors">
                            Lihat Semua →
                        </a>
                    </div>
                    
                    @if($activeProjects->isNotEmpty())
                    <div class="space-y-4">
                        @foreach($activeProjects as $project)
                        <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 hover:bg-blue-50 transition-all duration-200 hover-lift">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900 mb-1">{{ $project->problem->title }}</h3>
                                    <p class="text-sm text-gray-600 mb-3">{{ $project->institution->name }}</p>
                                    
                                    {{-- progress bar --}}
                                    <div class="mb-3">
                                        <div class="flex justify-between items-center mb-1">
                                            <span class="text-xs text-gray-600">Progress</span>
                                            <span class="text-xs font-semibold text-gray-700">{{ $project->progress_percentage ?? 0 }}%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="progress-bar bg-blue-600 h-2 rounded-full" style="width: {{ $project->progress_percentage ?? 0 }}%"></div>
                                        </div>
                                    </div>

                                    {{-- milestone terdekat --}}
                                    @if($project->milestones->where('status', '!=', 'completed')->first())
                                    @php
                                        $nextMilestone = $project->milestones->where('status', '!=', 'completed')->first();
                                    @endphp
                                    <div class="flex items-center gap-2 text-xs text-gray-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span>Milestone berikutnya: {{ $nextMilestone->title }} ({{ \Carbon\Carbon::parse($nextMilestone->target_date)->format('d M Y') }})</span>
                                    </div>
                                    @endif
                                </div>
                                
                                <a href="{{ route('student.projects.show', $project->id) }}" 
                                   class="ml-4 flex-shrink-0 bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                                    Lihat Detail
                                    <svg class="w-4 h-4 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

                {{-- section aplikasi terbaru --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 fade-in-up content-card" style="animation-delay: 0.4s;">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold text-gray-900">Aplikasi Terbaru</h2>
                        <a href="{{ route('student.applications.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium transition-colors">
                            Lihat Semua →
                        </a>
                    </div>
                    
                    @if($recentApplications->isNotEmpty())
                    <div class="space-y-3">
                        @foreach($recentApplications as $application)
                        <div class="flex items-start justify-between border-b border-gray-100 pb-3 last:border-0 hover-lift">
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900 text-sm mb-1">{{ $application->problem->title }}</h3>
                                <p class="text-xs text-gray-600 mb-2">{{ $application->problem->institution->name }}</p>
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                        {{ $application->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $application->status === 'under_review' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $application->status === 'accepted' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $application->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                                        @switch($application->status)
                                            @case('pending')
                                                Menunggu
                                                @break
                                            @case('under_review')
                                                Sedang Ditinjau
                                                @break
                                            @case('accepted')
                                                Diterima
                                                @break
                                            @case('rejected')
                                                Ditolak
                                                @break
                                            @default
                                                {{ ucfirst($application->status) }}
                                        @endswitch
                                    </span>
                                    <span class="text-xs text-gray-500">
                                        {{ $application->created_at->diffForHumans() }}
                                    </span>
                                </div>
                            </div>
                            <a href="{{ route('student.applications.show', $application->id) }}" 
                               class="ml-4 text-blue-600 hover:text-blue-700 text-sm font-medium whitespace-nowrap">
                                Detail →
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
                        <p class="text-gray-600 mb-4">Mulai apply ke proyek yang menarik untuk Anda</p>
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

                {{-- upcoming milestones --}}
                @if($upcomingMilestones->isNotEmpty())
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 fade-in-up content-card" style="animation-delay: 0.45s;">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold text-gray-900">Milestone Mendatang</h2>
                    </div>
                    <div class="space-y-3">
                        @foreach($upcomingMilestones as $milestone)
                        <div class="flex items-start gap-3 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="flex-shrink-0 w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-semibold text-sm text-gray-900 mb-1">{{ $milestone->title }}</h3>
                                <p class="text-xs text-gray-600 mb-1">{{ $milestone->project->problem->title }}</p>
                                <div class="flex items-center gap-2 text-xs">
                                    <span class="text-gray-500">Target: {{ \Carbon\Carbon::parse($milestone->target_date)->format('d M Y') }}</span>
                                    <span class="text-gray-400">•</span>
                                    <span class="text-orange-600 font-medium">
                                        {{ \Carbon\Carbon::parse($milestone->target_date)->diffForHumans() }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            {{-- sidebar (1 column) --}}
            <div class="space-y-6">
                
                {{-- notifikasi terbaru --}}
                @if($unreadNotifications->isNotEmpty())
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 fade-in-up content-card" style="animation-delay: 0.5s;">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-bold text-gray-900">Notifikasi</h2>
                        <a href="{{ route('notifications.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                            Lihat Semua
                        </a>
                    </div>
                    <div class="space-y-3">
                        @foreach($unreadNotifications as $notification)
                        <div class="flex items-start gap-3 p-3 bg-blue-50 border border-blue-100 rounded-lg hover:bg-blue-100 transition-colors cursor-pointer"
                             onclick="window.location='{{ route('notifications.show', $notification->id) }}'">
                            <div class="flex-shrink-0">
                                <div class="w-2 h-2 bg-blue-600 rounded-full mt-1"></div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 line-clamp-2">{{ $notification->title }}</p>
                                <p class="text-xs text-gray-600 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- rekomendasi proyek --}}
                @if($recommendedProblems->isNotEmpty())
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 fade-in-up content-card" style="animation-delay: 0.55s;">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-bold text-gray-900">Rekomendasi Untuk Anda</h2>
                    </div>
                    <div class="space-y-3">
                        @foreach($recommendedProblems->take(3) as $problem)
                        <a href="{{ route('student.browse-problems.show', $problem->id) }}" 
                           class="block border border-gray-200 rounded-lg p-3 hover:border-blue-300 hover:bg-blue-50 transition-all duration-200 hover-lift">
                            <h3 class="font-semibold text-sm text-gray-900 mb-1 line-clamp-2">{{ $problem->title }}</h3>
                            <p class="text-xs text-gray-600 mb-2">{{ $problem->institution->name }}</p>
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-gray-500">{{ $problem->regency->name }}</span>
                                <span class="text-xs font-semibold text-blue-600">Lihat →</span>
                            </div>
                        </a>
                        @endforeach
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('student.browse-problems.index') }}" 
                           class="block text-center text-sm text-blue-600 hover:text-blue-700 font-medium py-2 border border-blue-200 rounded-lg hover:bg-blue-50 transition-colors">
                            Lihat Semua Proyek
                        </a>
                    </div>
                </div>
                @endif

                {{-- quick actions --}}
                <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-xl shadow-sm p-6 text-white fade-in-up content-card" style="animation-delay: 0.6s;">
                    <h2 class="text-lg font-bold mb-4 text-shadow-strong">Aksi Cepat</h2>
                    <div class="space-y-3">
                        <a href="{{ route('student.browse-problems.index') }}" 
                           class="flex items-center gap-3 p-3 bg-white/10 backdrop-blur-sm rounded-lg hover:bg-white/20 transition-all duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <span class="text-sm font-medium">Cari Proyek</span>
                        </a>
                        <a href="{{ route('student.repository.index') }}" 
                           class="flex items-center gap-3 p-3 bg-white/10 backdrop-blur-sm rounded-lg hover:bg-white/20 transition-all duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            <span class="text-sm font-medium">Repositori Pengetahuan</span>
                        </a>
                        <a href="{{ route('student.portfolio.index') }}" 
                           class="flex items-center gap-3 p-3 bg-white/10 backdrop-blur-sm rounded-lg hover:bg-white/20 transition-all duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span class="text-sm font-medium">Portfolio Saya</span>
                        </a>
                        <a href="{{ route('student.profile.edit') }}" 
                           class="flex items-center gap-3 p-3 bg-white/10 backdrop-blur-sm rounded-lg hover:bg-white/20 transition-all duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span class="text-sm font-medium">Pengaturan Profil</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // preload untuk smooth experience
    document.addEventListener('DOMContentLoaded', function() {
        // preload images untuk recommended problems
        const images = document.querySelectorAll('img[data-src]');
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                    imageObserver.unobserve(img);
                }
            });
        });
        
        images.forEach(img => imageObserver.observe(img));

        // smooth scroll untuk anchor links
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

        // reduced motion check
        const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        if (prefersReducedMotion) {
            document.querySelectorAll('.fade-in-up').forEach(el => {
                el.style.animation = 'none';
                el.style.opacity = '1';
            });
        }
    });
</script>
@endpush
@endsection