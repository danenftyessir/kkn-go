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
                    <a href="{{ route('student.profile.edit') }}" class="mt-2 inline-block text-sm font-medium text-blue-800 hover:text-blue-900">
                        Lengkapi Sekarang →
                    </a>
                </div>
            </div>
        </div>
        @endif

        {{-- statistik cards --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            {{-- active applications --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Aplikasi Aktif</p>
                        <p class="text-3xl font-bold text-blue-600">{{ $stats['pending_applications'] }}</p>
                    </div>
                    <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center">
                        <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
                <a href="{{ route('student.applications.index') }}" class="mt-3 text-sm text-blue-600 hover:text-blue-700 font-medium inline-flex items-center">
                    Lihat Semua
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

            {{-- active projects --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Proyek Aktif</p>
                        <p class="text-3xl font-bold text-yellow-600">{{ $stats['active_projects'] }}</p>
                    </div>
                    <div class="w-14 h-14 bg-yellow-100 rounded-xl flex items-center justify-center">
                        <svg class="w-7 h-7 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                </div>
                <a href="{{ route('student.projects.index') }}" class="mt-3 text-sm text-yellow-600 hover:text-yellow-700 font-medium inline-flex items-center">
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
                <a href="{{ route('student.profile.index') }}" class="mt-3 text-sm text-green-600 hover:text-green-700 font-medium inline-flex items-center">
                    Lihat Portfolio
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

            {{-- wishlist --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Wishlist</p>
                        <p class="text-3xl font-bold text-purple-600">{{ Auth::user()->student->wishlists()->count() }}</p>
                    </div>
                    <div class="w-14 h-14 bg-purple-100 rounded-xl flex items-center justify-center">
                        <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                        </svg>
                    </div>
                </div>
                <a href="{{ route('student.wishlist.index') }}" class="mt-3 text-sm text-purple-600 hover:text-purple-700 font-medium inline-flex items-center">
                    Lihat Wishlist
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
                            Lihat Semua →
                        </a>
                    </div>
                    <div class="space-y-4">
                        @foreach($activeProjects as $project)
                        <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition-colors duration-200">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900 mb-1">{{ $project->title }}</h3>
                                    <p class="text-sm text-gray-600 mb-2">{{ $project->institution->name }}</p>
                                    
                                    {{-- progress bar --}}
                                    <div class="mb-2">
                                        <div class="flex justify-between items-center mb-1">
                                            <span class="text-xs text-gray-600">Progress</span>
                                            <span class="text-xs font-semibold text-gray-700">{{ $project->progress ?? 0 }}%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-blue-600 h-2 rounded-full transition-all duration-500" style="width: {{ $project->progress ?? 0 }}%"></div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center gap-4 text-xs text-gray-500">
                                        <span class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            {{ $project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('d M Y') : '-' }} - {{ $project->end_date ? \Carbon\Carbon::parse($project->end_date)->format('d M Y') : '-' }}
                                        </span>
                                        @if($project->milestones_count > 0)
                                        <span class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                            </svg>
                                            {{ $project->milestones_count }} milestone
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                
                                <a href="{{ route('student.projects.show', $project->id) }}" 
                                   class="ml-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                                    Detail
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- recent applications --}}
                @if($recentApplications->isNotEmpty())
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 fade-in-up" style="animation-delay: 0.3s;">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold text-gray-900">Aplikasi Terbaru</h2>
                        <a href="{{ route('student.applications.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                            Lihat Semua →
                        </a>
                    </div>
                    <div class="space-y-3">
                        @foreach($recentApplications as $application)
                        <div class="flex items-start justify-between p-4 border border-gray-200 rounded-lg hover:border-blue-300 transition-colors duration-200">
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900 mb-1">{{ $application->problem->title }}</h3>
                                <p class="text-sm text-gray-600 mb-2">{{ $application->problem->institution->name }}</p>
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                        {{ $application->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $application->status === 'under_review' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $application->status === 'accepted' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $application->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                                        {{ ucfirst($application->status) }}
                                    </span>
                                    <span class="text-xs text-gray-500">{{ $application->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            <a href="{{ route('student.applications.show', $application->id) }}" 
                               class="text-blue-600 hover:text-blue-700 ml-4">
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
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 fade-in-up" style="animation-delay: 0.4s;">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Rekomendasi Proyek</h2>
                    <div class="space-y-4">
                        @foreach($recommendedProblems->take(3) as $problem)
                        <div class="border border-gray-200 rounded-lg p-3 hover:border-blue-300 transition-colors duration-200">
                            <h3 class="font-semibold text-sm text-gray-900 mb-1 line-clamp-2">{{ $problem->title }}</h3>
                            <p class="text-xs text-gray-600 mb-2">{{ $problem->institution->name }}</p>
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-gray-500">{{ $problem->location_regency }}</span>
                                <a href="{{ route('student.browse-problems.show', $problem->id) }}" 
                                   class="text-xs text-blue-600 hover:text-blue-700 font-medium">
                                    Detail →
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <a href="{{ route('student.browse-problems.index') }}" 
                       class="mt-4 block text-center text-sm text-blue-600 hover:text-blue-700 font-medium">
                        Lihat Semua Proyek →
                    </a>
                </div>
                @endif

                {{-- upcoming milestones --}}
                @if($upcomingMilestones->isNotEmpty())
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 fade-in-up" style="animation-delay: 0.5s;">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Milestone Mendatang</h2>
                    <div class="space-y-3">
                        @foreach($upcomingMilestones as $milestone)
                        <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg">
                            <div class="w-2 h-2 bg-blue-600 rounded-full mt-1.5"></div>
                            <div class="flex-1">
                                <h3 class="text-sm font-semibold text-gray-900 mb-1">{{ $milestone->title }}</h3>
                                <p class="text-xs text-gray-600 mb-1">{{ $milestone->project->title }}</p>
                                <div class="flex items-center gap-1 text-xs text-gray-500">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 fade-in-up" style="animation-delay: 0.6s;">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Notifikasi Terbaru</h2>
                    <div class="space-y-3">
                        @foreach($unreadNotifications as $notification)
                        <div class="p-3 bg-blue-50 rounded-lg">
                            <p class="text-sm text-gray-900 font-medium mb-1">{{ $notification->title }}</p>
                            <p class="text-xs text-gray-600 mb-2">{{ $notification->message }}</p>
                            <span class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</span>
                        </div>
                        @endforeach
                    </div>
                    <a href="{{ route('notifications.index') }}" 
                       class="mt-4 block text-center text-sm text-blue-600 hover:text-blue-700 font-medium">
                        Lihat Semua Notifikasi →
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('styles')
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
    animation: fadeInUp 0.6s cubic-bezier(0.4, 0, 0.2, 1);
}
</style>
@endpush
@endsection