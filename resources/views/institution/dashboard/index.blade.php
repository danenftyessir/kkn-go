@extends('layouts.app')

@section('title', 'dashboard instansi')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="container mx-auto px-4">
        
        {{-- header --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">
                dashboard instansi
            </h1>
            <p class="text-gray-600">
                selamat datang kembali, <span class="font-semibold text-blue-600">{{ $institution->name }}</span>
            </p>
        </div>

        {{-- warning jika belum verified --}}
        @if(!$institution->is_verified)
        <div class="mb-6 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">
                        akun anda masih dalam proses verifikasi
                    </h3>
                    <p class="mt-1 text-sm text-yellow-700">
                        fitur lengkap akan tersedia setelah akun anda diverifikasi oleh admin. proses verifikasi biasanya memakan waktu 1-3 hari kerja.
                    </p>
                </div>
            </div>
        </div>
        @endif

        {{-- statistics cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            
            {{-- total problems --}}
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">total proyek</p>
                        <h3 class="text-3xl font-bold text-gray-900">{{ $stats['total_problems'] }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- open problems --}}
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">proyek aktif</p>
                        <h3 class="text-3xl font-bold text-green-600">{{ $stats['open_problems'] }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- total applications --}}
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">total aplikasi</p>
                        <h3 class="text-3xl font-bold text-purple-600">{{ $stats['total_applications'] }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- pending applications --}}
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">menunggu review</p>
                        <h3 class="text-3xl font-bold text-orange-600">{{ $stats['pending_applications'] }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            {{-- recent problems --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900">proyek terbaru</h2>
                </div>
                <div class="p-6">
                    @if($recentProblems->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentProblems as $problem)
                            <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition-colors">
                                <div class="flex items-start justify-between mb-2">
                                    <h3 class="font-semibold text-gray-900 text-sm">{{ $problem->title }}</h3>
                                    <span class="px-2 py-1 text-xs rounded-full
                                        @if($problem->status === 'open') bg-green-100 text-green-800
                                        @elseif($problem->status === 'in_progress') bg-blue-100 text-blue-800
                                        @elseif($problem->status === 'completed') bg-gray-100 text-gray-800
                                        @else bg-yellow-100 text-yellow-800
                                        @endif">
                                        {{ $problem->status }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 mb-2">{{ Str::limit($problem->description, 100) }}</p>
                                <div class="flex items-center justify-between text-xs text-gray-500">
                                    <span>{{ $problem->applications_count }} aplikasi</span>
                                    <span>{{ $problem->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">belum ada proyek</p>
                            <button class="mt-4 px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition-colors">
                                buat proyek baru
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            {{-- recent applications --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900">aplikasi terbaru</h2>
                </div>
                <div class="p-6">
                    @if($recentApplications->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentApplications as $application)
                            <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition-colors">
                                <div class="flex items-start justify-between mb-2">
                                    <div>
                                        <h3 class="font-semibold text-gray-900 text-sm">{{ $application->student->user->name }}</h3>
                                        <p class="text-xs text-gray-500">{{ $application->student->university->name }}</p>
                                    </div>
                                    <span class="px-2 py-1 text-xs rounded-full {{ $application->getStatusBadgeColor() }}">
                                        {{ $application->getStatusLabel() }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 mb-2">{{ $application->problem->title }}</p>
                                <div class="flex items-center justify-between text-xs text-gray-500">
                                    <span>{{ $application->student->major }}</span>
                                    <span>{{ $application->applied_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">belum ada aplikasi masuk</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- quick actions --}}
        <div class="mt-8 bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl shadow-lg p-8 text-white">
            <h2 class="text-2xl font-bold mb-4">aksi cepat</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <button class="bg-white text-blue-600 px-6 py-3 rounded-lg font-semibold hover:bg-blue-50 transition-colors">
                    buat proyek baru
                </button>
                <button class="bg-white text-blue-600 px-6 py-3 rounded-lg font-semibold hover:bg-blue-50 transition-colors">
                    review aplikasi
                </button>
                <button class="bg-white text-blue-600 px-6 py-3 rounded-lg font-semibold hover:bg-blue-50 transition-colors">
                    lihat statistik
                </button>
            </div>
        </div>

    </div>
</div>
@endsection