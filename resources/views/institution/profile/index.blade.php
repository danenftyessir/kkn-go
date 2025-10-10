@extends('layouts.app')

@section('title', 'profil saya')

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
                    <span class="text-gray-900 font-medium">profil</span>
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

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- sidebar kiri -->
            <div class="lg:col-span-1">
                <!-- kartu profil -->
                <div class="bg-white rounded-lg shadow-sm p-6 text-center profile-card">
                    <div class="inline-block relative profile-photo-wrapper">
                        @if($student->profile_photo_path)
                            {{-- PERBAIKAN: gunakan accessor profile_photo_url yang sudah support Supabase --}}
                            <img src="{{ $student->profile_photo_url }}" 
                                 alt="{{ $student->first_name }}" 
                                 class="w-32 h-32 rounded-full object-cover border-4 border-blue-500 profile-photo">
                        @else
                            <div class="w-32 h-32 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-4xl font-bold border-4 border-blue-500">
                                {{ strtoupper(substr($student->first_name, 0, 1)) }}{{ strtoupper(substr($student->last_name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    
                    <h2 class="mt-4 text-2xl font-bold text-gray-900">
                        {{ $student->first_name }} {{ $student->last_name }}
                    </h2>
                    
                    <p class="text-sm text-gray-600 mt-1">@<span>{{ $user->username }}</span></p>
                    
                    <div class="mt-2 flex items-center justify-center text-sm text-gray-500">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        {{ $user->email }}
                    </div>

                    <!-- informasi detail -->
                    <div class="mt-6 space-y-3 text-left">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-gray-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 uppercase tracking-wide">universitas</p>
                                <p class="text-sm font-medium text-gray-900">{{ $student->university->name ?? 'belum diisi' }}</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-gray-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 uppercase tracking-wide">jurusan</p>
                                <p class="text-sm font-medium text-gray-900">{{ $student->major ?? 'belum diisi' }}</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-gray-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 uppercase tracking-wide">nim</p>
                                <p class="text-sm font-medium text-gray-900">{{ $student->nim ?? 'belum diisi' }}</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-gray-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 uppercase tracking-wide">semester</p>
                                <p class="text-sm font-medium text-gray-900">semester {{ $student->semester ?? 'belum diisi' }}</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-gray-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 uppercase tracking-wide">whatsapp</p>
                                <p class="text-sm font-medium text-gray-900">{{ $student->phone ?? 'belum diisi' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- bio section -->
                    @if($student->bio)
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <p class="text-xs text-gray-500 uppercase tracking-wide mb-2">bio</p>
                            <p class="text-sm text-gray-700 text-left leading-relaxed">{{ $student->bio }}</p>
                        </div>
                    @else
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <svg class="w-12 h-12 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <p class="text-gray-500">belum ada bio</p>
                            <p class="text-sm text-gray-400 mt-1">tambahkan bio untuk menampilkan deskripsi diri anda</p>
                        </div>
                    @endif
                </div>

                <!-- tombol edit profil -->
                <a href="{{ route('student.profile.edit') }}" 
                   class="mt-6 w-full inline-flex items-center justify-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-300 font-medium shadow-sm hover:shadow-md">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    edit profil
                </a>

                <!-- tombol lihat portfolio publik -->
                <a href="{{ route('portfolio.public', $user->username) }}" 
                   target="_blank"
                   class="mt-3 w-full inline-flex items-center justify-center px-6 py-3 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all duration-300 font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                    </svg>
                    lihat portfolio publik
                </a>
            </div>

            <!-- konten kanan -->
            <div class="lg:col-span-2 space-y-6">
                <!-- statistik -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg p-6 text-white profile-card hover:shadow-lg transition-shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-blue-100 text-sm font-medium">finished</p>
                                <p class="text-3xl font-bold mt-1">{{ $stats['completed_projects'] }}</p>
                            </div>
                            <div class="bg-blue-400 bg-opacity-50 rounded-full p-3">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg p-6 text-white profile-card hover:shadow-lg transition-shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-purple-100 text-sm font-medium">apply</p>
                                <p class="text-3xl font-bold mt-1">{{ $stats['total_applications'] }}</p>
                            </div>
                            <div class="bg-purple-400 bg-opacity-50 rounded-full p-3">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg p-6 text-white profile-card hover:shadow-lg transition-shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-green-100 text-sm font-medium">sdgs confirmed</p>
                                <p class="text-3xl font-bold mt-1">{{ $student->total_sdgs_addressed ?? 0 }}</p>
                            </div>
                            <div class="bg-green-400 bg-opacity-50 rounded-full p-3">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- informasi pribadi card -->
                <div class="bg-white rounded-lg shadow-sm p-6 profile-card">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">informasi pribadi</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">nama lengkap</p>
                            <p class="text-gray-900">{{ $student->first_name }} {{ $student->last_name }}</p>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">email</p>
                            <p class="text-gray-900">{{ $user->email }}</p>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">nomor whatsapp</p>
                            <p class="text-gray-900">{{ $student->phone ?? '-' }}</p>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">nim</p>
                            <p class="text-gray-900">{{ $student->nim }}</p>
                        </div>
                    </div>
                </div>

                <!-- akademik info card -->
                <div class="bg-white rounded-lg shadow-sm p-6 profile-card">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">informasi akademik</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">universitas</p>
                            <p class="text-gray-900">{{ $student->university->name ?? '-' }}</p>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">jurusan</p>
                            <p class="text-gray-900">{{ $student->major }}</p>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">semester</p>
                            <p class="text-gray-900">semester {{ $student->semester }}</p>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">status verifikasi email</p>
                            @if($user->email_verified_at)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    terverifikasi
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    belum terverifikasi
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.profile-card {
    animation: fadeInUp 0.5s ease-out;
}

.profile-transition {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.profile-photo-wrapper {
    transition: transform 0.3s ease;
}

.profile-photo-wrapper:hover {
    transform: scale(1.05);
}

.profile-photo {
    transition: all 0.3s ease;
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

/* smooth scrolling */
html {
    scroll-behavior: smooth;
}
</style>
@endpush
@endsection