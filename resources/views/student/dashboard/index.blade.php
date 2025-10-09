@extends('layouts.app')

@section('title', 'Dashboard Mahasiswa')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-green-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- welcome header --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Selamat Datang, {{ Auth::user()->name }}!</h1>
            <p class="text-gray-600">{{ $student->university->name }} - {{ $student->major }}</p>
        </div>

        {{-- profile completion alert --}}
        @if(!$profileCompletion['is_complete'])
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-6 mb-8 rounded-lg">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div class="ml-3 flex-1">
                    <h3 class="text-sm font-medium text-yellow-800">Lengkapi Profil Anda</h3>
                    <div class="mt-2 text-sm text-yellow-700">
                        <p>Profil Anda belum lengkap ({{ $profileCompletion['percentage'] }}%). Lengkapi profil untuk mendapat rekomendasi yang lebih baik.</p>
                        <div class="mt-4 w-full bg-yellow-200 rounded-full h-2">
                            <div class="bg-yellow-600 h-2 rounded-full transition-all duration-500" style="width: {{ $profileCompletion['percentage'] }}%"></div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('student.profile.edit') }}" class="text-sm font-medium text-yellow-800 hover:text-yellow-900">
                            Lengkapi Profil →
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- statistik cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Proyek Aktif</p>
                        <p class="text-3xl font-bold text-green-600">{{ $stats['active_projects'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Total Aplikasi</p>
                        <p class="text-3xl font-bold text-blue-600">{{ $stats['total_applications'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Menunggu Review</p>
                        <p class="text-3xl font-bold text-yellow-600">{{ $stats['pending_applications'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Proyek Selesai</p>
                        <p class="text-3xl font-bold text-purple-600">{{ $stats['completed_projects'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- main content grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            {{-- left column - 2/3 width --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- active projects --}}
                @if($activeProjects->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-gray-900">Proyek Aktif</h3>
                        <a href="{{ route('student.projects.index') }}" class="text-blue-600 hover:text-blue-700 font-semibold text-sm">
                            Lihat Semua
                        </a>
                    </div>

                    <div class="space-y-4">
                        @foreach($activeProjects as $project)
                        <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition-colors">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h4 class="font-semibold text-gray-900 mb-1">{{ $project->problem->title }}</h4>
                                    <p class="text-sm text-gray-600">{{ $project->institution->name }}</p>
                                </div>
                                <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">Aktif</span>
                            </div>
                            
                            {{-- progress bar --}}
                            @php
                                $totalMilestones = $project->milestones->count();
                                $completedMilestones = $project->milestones->where('status', 'completed')->count();
                                $progress = $totalMilestones > 0 ? ($completedMilestones / $totalMilestones) * 100 : 0;
                            @endphp
                            <div class="mb-3">
                                <div class="flex justify-between text-sm text-gray-600 mb-1">
                                    <span>Progress</span>
                                    <span>{{ round($progress) }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full transition-all" style="width: {{ $progress }}%"></div>
                                </div>
                            </div>

                            <div class="flex justify-between items-center">
                                <span class="text-xs text-gray-500">{{ $completedMilestones }}/{{ $totalMilestones }} milestone selesai</span>
                                <a href="{{ route('student.projects.show', $project->id) }}" class="text-blue-600 hover:text-blue-700 text-sm font-semibold">
                                    Detail →
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- recommended problems --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-gray-900">Rekomendasi Proyek</h3>
                        <a href="{{ route('student.browse-problems.index') }}" class="text-blue-600 hover:text-blue-700 font-semibold text-sm">
                            Lihat Semua
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @forelse($recommendedProblems as $problem)
                        <div class="border border-gray-200 rounded-lg overflow-hidden hover:border-blue-300 transition-colors">
                            @if($problem->images->first())
                                <img src="{{ asset('storage/' . $problem->images->first()->image_path) }}" 
                                     alt="{{ $problem->title }}"
                                     class="w-full h-32 object-cover">
                            @else
                                <div class="w-full h-32 bg-gradient-to-br from-blue-500 to-green-500"></div>
                            @endif
                            
                            <div class="p-4">
                                <h4 class="font-semibold text-gray-900 mb-2 line-clamp-2">{{ $problem->title }}</h4>
                                <p class="text-sm text-gray-600 mb-3">{{ $problem->institution->name }}</p>
                                
                                <div class="flex items-center justify-between text-xs text-gray-500 mb-3">
                                    <span>{{ $problem->applications_count }} aplikasi</span>
                                    <span>{{ $problem->deadline->diffForHumans() }}</span>
                                </div>

                                <a href="{{ route('student.browse-problems.detail', $problem->id) }}" 
                                   class="block w-full text-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                        @empty
                        <div class="col-span-2 text-center py-8 text-gray-500">
                            <p>Belum ada rekomendasi proyek untuk Anda</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                {{-- recent applications --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-gray-900">Aplikasi Terbaru</h3>
                        <a href="{{ route('student.applications.index') }}" class="text-blue-600 hover:text-blue-700 font-semibold text-sm">
                            Lihat Semua
                        </a>
                    </div>

                    <div class="space-y-3">
                        @forelse($recentApplications as $application)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <div>
                                <p class="font-semibold text-gray-900 text-sm">{{ Str::limit($application->problem->title, 40) }}</p>
                                <p class="text-xs text-gray-600">{{ $application->problem->institution->name }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $application->applied_at->diffForHumans() }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                @if($application->status == 'pending')
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-700 text-xs font-semibold rounded-full">Pending</span>
                                @elseif($application->status == 'accepted')
                                <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">Diterima</span>
                                @elseif($application->status == 'rejected')
                                <span class="px-2 py-1 bg-red-100 text-red-700 text-xs font-semibold rounded-full">Ditolak</span>
                                @else
                                <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded-full">Review</span>
                                @endif
                            </div>
                        </div>
                        @empty
                        <p class="text-center py-4 text-gray-500 text-sm">Belum ada aplikasi</p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- right column - 1/3 width --}}
            <div class="space-y-6">
                
                {{-- upcoming milestones --}}
                @if($upcomingMilestones->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Milestone Mendatang</h3>
                    <div class="space-y-3">
                        @foreach($upcomingMilestones as $milestone)
                        <div class="p-3 bg-blue-50 rounded-lg border border-blue-200">
                            <p class="font-semibold text-gray-900 text-sm mb-1">{{ Str::limit($milestone->title, 30) }}</p>
                            <div class="flex items-center gap-1 text-xs text-gray-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span>{{ $milestone->target_date->format('d M Y') }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- notifications --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-gray-900">Notifikasi</h3>
                        <a href="{{ route('notifications.index') }}" class="text-blue-600 hover:text-blue-700 font-semibold text-sm">
                            Lihat Semua
                        </a>
                    </div>

                    <div class="space-y-3">
                        @forelse($unreadNotifications as $notification)
                        <div class="p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors cursor-pointer">
                            <p class="font-semibold text-gray-900 text-sm mb-1">{{ $notification->title }}</p>
                            <p class="text-xs text-gray-600">{{ Str::limit($notification->message, 80) }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                        </div>
                        @empty
                        <p class="text-center py-4 text-gray-500 text-sm">Tidak ada notifikasi baru</p>
                        @endforelse
                    </div>
                </div>

                {{-- quick actions --}}
                <div class="bg-gradient-to-br from-blue-500 to-green-500 rounded-xl shadow-sm p-6 text-white">
                    <h3 class="text-lg font-bold mb-4">Quick Actions</h3>
                    <div class="space-y-3">
                        <a href="{{ route('student.browse-problems.index') }}" 
                           class="block w-full px-4 py-3 bg-white/20 backdrop-blur rounded-lg hover:bg-white/30 transition-colors text-center font-semibold">
                            Cari Proyek
                        </a>
                        <a href="{{ route('student.portfolio.index') }}" 
                           class="block w-full px-4 py-3 bg-white/20 backdrop-blur rounded-lg hover:bg-white/30 transition-colors text-center font-semibold">
                            Lihat Portfolio
                        </a>
                        <a href="{{ route('student.repository.index') }}" 
                           class="block w-full px-4 py-3 bg-white/20 backdrop-blur rounded-lg hover:bg-white/30 transition-colors text-center font-semibold">
                            Repository
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endpush
@endsection