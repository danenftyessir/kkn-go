@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- header section --}}
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
                    <p class="mt-2 text-gray-600">Selamat Datang Kembali, {{ $student->first_name }}!</p>
                </div>
                
                {{-- profile completion badge --}}
                @if(!$profileCompletion['is_complete'])
                <div class="flex items-center space-x-2 bg-yellow-50 border border-yellow-200 rounded-lg px-4 py-2">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <span class="text-sm font-medium text-yellow-800">Profil {{ $profileCompletion['percentage'] }}% Lengkap</span>
                </div>
                @endif
            </div>
        </div>

        {{-- statistics cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            {{-- active projects --}}
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200 hover:shadow-md transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Proyek Aktif</p>
                        <p class="text-3xl font-bold text-blue-600 mt-2">{{ $stats['active_projects'] }}</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- total applications --}}
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200 hover:shadow-md transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Aplikasi</p>
                        <p class="text-3xl font-bold text-green-600 mt-2">{{ $stats['total_applications'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $applicationStats['success_rate'] }}% Diterima</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- pending applications --}}
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200 hover:shadow-md transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Menunggu Review</p>
                        <p class="text-3xl font-bold text-yellow-600 mt-2">{{ $stats['pending_applications'] }}</p>
                    </div>
                    <div class="bg-yellow-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- completed projects --}}
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200 hover:shadow-md transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Proyek Selesai</p>
                        <p class="text-3xl font-bold text-purple-600 mt-2">{{ $stats['completed_projects'] }}</p>
                        @if(isset($analytics['average_rating']) && $analytics['average_rating'] > 0)
                        <p class="text-xs text-gray-500 mt-1">⭐ {{ number_format($analytics['average_rating'], 1) }} Rating</p>
                        @endif
                    </div>
                    <div class="bg-purple-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- alerts section (overdue milestones & approaching deadlines) --}}
        @if($overdueMilestones->count() > 0 || $approachingDeadlines->count() > 0)
        <div class="mb-8 space-y-4">
            @if($overdueMilestones->count() > 0)
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-red-500 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="flex-1">
                        <h3 class="text-sm font-medium text-red-800">{{ $overdueMilestones->count() }} Milestone Terlewat Deadline</h3>
                        <div class="mt-2 text-sm text-red-700 space-y-1">
                            @foreach($overdueMilestones as $milestone)
                            <p>• {{ $milestone->title }} - {{ $milestone->project->problem->title }} ({{ $milestone->target_date->diffForHumans() }})</p>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if($approachingDeadlines->count() > 0)
            <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-r-lg">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-yellow-500 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="flex-1">
                        <h3 class="text-sm font-medium text-yellow-800">Deadline Mendekati - Segera Daftar!</h3>
                        <div class="mt-2 text-sm text-yellow-700 space-y-1">
                            @foreach($approachingDeadlines as $problem)
                            <p>• <a href="{{ route('student.browse-problems.detail', $problem->id) }}" class="underline hover:text-yellow-900">{{ $problem->title }}</a> - Deadline: {{ $problem->application_deadline->format('d M Y') }}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- main content --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- active projects section --}}
                @if($activeProjects->count() > 0)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h2 class="text-xl font-semibold text-gray-900">Proyek Aktif</h2>
                            <a href="{{ route('student.projects.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium transition-colors">
                                Lihat Semua →
                            </a>
                        </div>
                    </div>
                    <div class="divide-y divide-gray-200">
                        @foreach($activeProjects as $project)
                        <div class="p-6 hover:bg-gray-50 transition-colors">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h3 class="text-lg font-medium text-gray-900">{{ $project->problem->title }}</h3>
                                    <p class="text-sm text-gray-600 mt-1">{{ $project->problem->institution->name }}</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ $project->problem->regency->name ?? '' }}, {{ $project->problem->province->name ?? '' }}</p>
                                    
                                    {{-- progress bar --}}
                                    <div class="mt-4">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="text-sm text-gray-600">Progress</span>
                                            <span class="text-sm font-medium text-gray-900">{{ $project->progress }}%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                                            <div class="bg-blue-600 h-2.5 rounded-full transition-all duration-500" style="width: {{ $project->progress }}%"></div>
                                        </div>
                                    </div>

                                    {{-- milestones info --}}
                                    @if($project->milestones->count() > 0)
                                    <div class="mt-3 flex items-center justify-between">
                                        <div class="flex items-center text-sm text-gray-600">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                            </svg>
                                            {{ $project->milestones->where('status', 'completed')->count() }}/{{ $project->milestones->count() }} Milestone
                                        </div>
                                        @php
                                            $nextMilestone = $project->milestones->where('status', '!=', 'completed')->first();
                                        @endphp
                                        @if($nextMilestone)
                                        <span class="text-xs text-gray-500">Next: {{ $nextMilestone->title }}</span>
                                        @endif
                                    </div>
                                    @endif
                                </div>
                                <a href="{{ route('student.projects.show', $project->id) }}" class="ml-4 text-blue-600 hover:text-blue-700 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- upcoming milestones --}}
                @if($upcomingMilestones->count() > 0)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-900">Milestone Mendatang</h2>
                    </div>
                    <div class="divide-y divide-gray-200">
                        @foreach($upcomingMilestones as $milestone)
                        <div class="p-4 hover:bg-gray-50 transition-colors">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-900">{{ $milestone->title }}</h4>
                                    <p class="text-sm text-gray-600 mt-1">{{ $milestone->project->problem->title }}</p>
                                    <div class="flex items-center mt-2 space-x-4">
                                        <span class="text-xs text-gray-500">
                                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            {{ $milestone->target_date->format('d M Y') }}
                                        </span>
                                        <span class="text-xs {{ $milestone->target_date->diffInDays(now()) <= 3 ? 'text-red-600 font-medium' : 'text-gray-500' }}">
                                            {{ $milestone->target_date->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>
                                @if($milestone->status == 'in_progress')
                                <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                    In Progress
                                </span>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- recent applications section --}}
                @if($recentApplications->count() > 0)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h2 class="text-xl font-semibold text-gray-900">Aplikasi Terbaru</h2>
                            <a href="{{ route('student.applications.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium transition-colors">
                                Lihat Semua →
                            </a>
                        </div>
                    </div>
                    <div class="divide-y divide-gray-200">
                        @foreach($recentApplications as $application)
                        <div class="p-6 hover:bg-gray-50 transition-colors">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h3 class="text-lg font-medium text-gray-900">{{ $application->problem->title }}</h3>
                                    <p class="text-sm text-gray-600 mt-1">{{ $application->problem->institution->name }}</p>
                                    <p class="text-sm text-gray-500 mt-1">
                                        {{ $application->problem->regency->name ?? '' }}, {{ $application->problem->province->name ?? '' }}
                                    </p>
                                    
                                    <div class="mt-3 flex items-center space-x-4">
                                        @if($application->status == 'pending')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Menunggu Review
                                        </span>
                                        @elseif($application->status == 'accepted')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Diterima
                                        </span>
                                        @elseif($application->status == 'rejected')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Ditolak
                                        </span>
                                        @elseif($application->status == 'reviewed')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Direview
                                        </span>
                                        @endif
                                        
                                        <span class="text-xs text-gray-500">
                                            {{ $application->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>
                                <a href="{{ route('student.applications.show', $application->id) }}" class="ml-4 text-blue-600 hover:text-blue-700 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- trending problems --}}
                @if($trendingProblems->count() > 0)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <h2 class="text-xl font-semibold text-gray-900">🔥 Trending Minggu Ini</h2>
                            </div>
                            <a href="{{ route('student.browse-problems.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium transition-colors">
                                Lihat Semua →
                            </a>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-4 p-6">
                        @foreach($trendingProblems as $problem)
                        <a href="{{ route('student.browse-problems.detail', $problem->id) }}" class="block group">
                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-all hover:border-blue-300">
                                <div class="flex items-start space-x-4">
                                    @if($problem->images->where('is_cover', true)->first())
                                    <img src="{{ $problem->images->where('is_cover', true)->first()->image_url }}" 
                                         alt="{{ $problem->title }}" 
                                         class="w-20 h-20 object-cover rounded-md flex-shrink-0">
                                    @else
                                    <div class="w-20 h-20 bg-gray-200 rounded-md flex items-center justify-center flex-shrink-0">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    @endif
                                    
                                    <div class="flex-1 min-w-0">
                                        <h3 class="font-medium text-gray-900 group-hover:text-blue-600 transition-colors line-clamp-1">
                                            {{ $problem->title }}
                                        </h3>
                                        <p class="text-sm text-gray-600 mt-1">{{ $problem->institution->name }}</p>
                                        
                                        <div class="mt-2 flex items-center space-x-4 text-sm">
                                            <span class="text-gray-500">
                                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                </svg>
                                                {{ $problem->applications_count }} Pelamar Minggu Ini
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- recommended problems section --}}
                @if($recommendedProblems->count() > 0)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h2 class="text-xl font-semibold text-gray-900">Rekomendasi Untuk Anda</h2>
                            <a href="{{ route('student.browse-problems.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium transition-colors">
                                Lihat Semua →
                            </a>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-6">
                        @foreach($recommendedProblems as $problem)
                        <a href="{{ route('student.browse-problems.detail', $problem->id) }}" class="block group">
                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-all hover:border-blue-300">
                                @if($problem->images->where('is_cover', true)->first())
                                <img src="{{ $problem->images->where('is_cover', true)->first()->image_url }}" 
                                     alt="{{ $problem->title }}" 
                                     class="w-full h-32 object-cover rounded-md mb-3">
                                @else
                                <div class="w-full h-32 bg-gray-200 rounded-md mb-3 flex items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                @endif
                                
                                <h3 class="font-medium text-gray-900 group-hover:text-blue-600 transition-colors line-clamp-2">
                                    {{ $problem->title }}
                                </h3>
                                <p class="text-sm text-gray-600 mt-1">{{ $problem->institution->name }}</p>
                                
                                <div class="mt-3 flex items-center justify-between text-sm">
                                    <span class="text-gray-500">
                                        {{ $problem->applications_count ?? 0 }} Pelamar
                                    </span>
                                    @if($problem->application_deadline)
                                    <span class="text-gray-500">
                                        {{ \Carbon\Carbon::parse($problem->application_deadline)->format('d M Y') }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- recent activities timeline --}}
                @if($recentActivities->count() > 0)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-900">Aktivitas Terbaru</h2>
                    </div>
                    <div class="p-6">
                        <div class="flow-root">
                            <ul class="-mb-8">
                                @foreach($recentActivities as $index => $activity)
                                <li>
                                    <div class="relative pb-8">
                                        @if($index < $recentActivities->count() - 1)
                                        <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                        @endif
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="h-8 w-8 rounded-full bg-{{ $activity['color'] }}-100 flex items-center justify-center ring-8 ring-white">
                                                    @if($activity['icon'] == 'document')
                                                    <svg class="h-5 w-5 text-{{ $activity['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                    </svg>
                                                    @elseif($activity['icon'] == 'check')
                                                    <svg class="h-5 w-5 text-{{ $activity['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                    @endif
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5">
                                                <p class="text-sm font-medium text-gray-900">{{ $activity['title'] }}</p>
                                                <p class="text-sm text-gray-500">{{ $activity['description'] }}</p>
                                                <p class="mt-0.5 text-xs text-gray-400">{{ $activity['timestamp']->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                @endif

            </div>

            {{-- sidebar --}}
            <div class="lg:col-span-1 space-y-6">
                
                {{-- quick actions --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                    <div class="space-y-3">
                        <a href="{{ route('student.browse-problems.index') }}" class="flex items-center justify-between p-3 rounded-lg bg-blue-50 hover:bg-blue-100 transition-colors group">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                <span class="text-sm font-medium text-gray-900">Cari Proyek</span>
                            </div>
                            <svg class="w-4 h-4 text-gray-400 group-hover:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                        
                        <a href="{{ route('student.applications.index') }}" class="flex items-center justify-between p-3 rounded-lg bg-green-50 hover:bg-green-100 transition-colors group">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span class="text-sm font-medium text-gray-900">Aplikasi Saya</span>
                            </div>
                            <svg class="w-4 h-4 text-gray-400 group-hover:text-green-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                        
                        <a href="{{ route('student.profile.index') }}" class="flex items-center justify-between p-3 rounded-lg bg-purple-50 hover:bg-purple-100 transition-colors group">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <span class="text-sm font-medium text-gray-900">Portfolio</span>
                            </div>
                            <svg class="w-4 h-4 text-gray-400 group-hover:text-purple-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>

                        <a href="{{ route('student.wishlist.index') }}" class="flex items-center justify-between p-3 rounded-lg bg-pink-50 hover:bg-pink-100 transition-colors group">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-pink-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                                <span class="text-sm font-medium text-gray-900">Wishlist</span>
                                @if($stats['wishlist_count'] > 0)
                                <span class="ml-2 bg-pink-200 text-pink-800 text-xs font-medium px-2 py-0.5 rounded-full">
                                    {{ $stats['wishlist_count'] }}
                                </span>
                                @endif
                            </div>
                            <svg class="w-4 h-4 text-gray-400 group-hover:text-pink-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>

                        <a href="{{ route('student.repository.index') }}" class="flex items-center justify-between p-3 rounded-lg bg-yellow-50 hover:bg-yellow-100 transition-colors group">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-yellow-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                <span class="text-sm font-medium text-gray-900">Knowledge Repository</span>
                            </div>
                            <svg class="w-4 h-4 text-gray-400 group-hover:text-yellow-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>

                {{-- wishlist problems --}}
                @if($wishlistProblems->count() > 0)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Wishlist Saya</h3>
                        <a href="{{ route('student.wishlist.index') }}" class="text-blue-600 hover:text-blue-700 text-sm">
                            Lihat Semua →
                        </a>
                    </div>
                    <div class="space-y-3">
                        @foreach($wishlistProblems->take(3) as $problem)
                        <a href="{{ route('student.browse-problems.detail', $problem->id) }}" class="block p-3 rounded-lg border border-gray-200 hover:border-blue-300 hover:shadow-sm transition-all">
                            <h4 class="text-sm font-medium text-gray-900 line-clamp-2">{{ $problem->title }}</h4>
                            <p class="text-xs text-gray-600 mt-1">{{ $problem->institution->name }}</p>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- notifications --}}
                @if($notifications->count() > 0)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Notifikasi</h3>
                        @if($unreadCount > 0)
                        <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                            {{ $unreadCount }}
                        </span>
                        @endif
                    </div>
                    <div class="space-y-3">
                        @foreach($notifications->take(3) as $notification)
                        <div class="p-3 rounded-lg {{ $notification->is_read ? 'bg-gray-50' : 'bg-blue-50' }} hover:bg-gray-100 transition-colors">
                            <p class="text-sm font-medium text-gray-900">{{ $notification->title }}</p>
                            <p class="text-xs text-gray-600 mt-1">{{ $notification->message }}</p>
                            <p class="text-xs text-gray-500 mt-2">{{ $notification->created_at->diffForHumans() }}</p>
                        </div>
                        @endforeach
                    </div>
                    <a href="{{ route('notifications.index') }}" class="block mt-4 text-center text-sm text-blue-600 hover:text-blue-700 font-medium">
                        Lihat Semua Notifikasi →
                    </a>
                </div>
                @endif

                {{-- sdgs worked on --}}
                @if($sdgsWorkedOn->count() > 0)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">SDGs Yang Pernah Dikerjakan</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($sdgsWorkedOn as $sdg)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            {{ $sdg }}
                        </span>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- profile completion --}}
                @if(!$profileCompletion['is_complete'])
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-sm p-6 text-white">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold">Lengkapi Profil Anda</h3>
                        <span class="text-2xl font-bold">{{ $profileCompletion['percentage'] }}%</span>
                    </div>
                    
                    <div class="w-full bg-blue-400 rounded-full h-2.5 mb-4">
                        <div class="bg-white h-2.5 rounded-full transition-all duration-500" style="width: {{ $profileCompletion['percentage'] }}%"></div>
                    </div>
                    
                    <p class="text-sm text-blue-100 mb-3">
                        Profil yang lengkap meningkatkan peluang diterima hingga 70%
                    </p>
                    
                    @if(count($profileCompletion['missing_fields']) > 0)
                    <div class="mb-4">
                        <p class="text-xs text-blue-200 mb-2">Yang perlu dilengkapi:</p>
                        <ul class="text-sm space-y-1">
                            @foreach($profileCompletion['missing_fields'] as $field)
                            <li class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                {{ $field }}
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    
                    <a href="{{ route('student.profile.edit') }}" class="inline-block bg-white text-blue-600 px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-50 transition-colors w-full text-center">
                        Lengkapi Sekarang
                    </a>
                </div>
                @endif

            </div>
        </div>

    </div>
</div>

<style>
/* smooth scroll behavior */
html {
    scroll-behavior: smooth;
}

/* custom animations */
@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-slide-in {
    animation: slideIn 0.5s ease-out;
}

/* smooth transitions */
* {
    transition-property: background-color, border-color, color, fill, stroke, opacity, box-shadow, transform;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 150ms;
}

/* GPU acceleration */
.transform {
    transform: translate3d(0, 0, 0);
    will-change: transform;
}
</style>
@endsection