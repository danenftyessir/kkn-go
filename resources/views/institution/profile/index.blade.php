{{-- resources/views/institution/profile/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Profil Saya')

@push('styles')
<style>
/* animasi smooth untuk transisi */
.profile-transition {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.profile-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.1);
}

.profile-photo-wrapper {
    transition: transform 0.3s ease;
}

.profile-photo-wrapper:hover {
    transform: scale(1.05);
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

.fade-in {
    animation: fadeInUp 0.6s ease-out;
}

/* smooth scroll */
html {
    scroll-behavior: smooth;
}
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- breadcrumb -->
        <nav class="mb-6 profile-transition" aria-label="breadcrumb">
            <ol class="flex items-center space-x-2 text-sm">
                <li>
                    <a href="{{ route('institution.dashboard') }}" class="text-gray-500 hover:text-gray-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                    </a>
                </li>
                <li class="text-gray-400">/</li>
                <li>
                    <span class="text-gray-900 font-medium">Profil</span>
                </li>
            </ol>
        </nav>

        <!-- flash messages -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 rounded-lg p-4 flex items-center profile-transition fade-in">
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
                        @if($institution->logo_path)
                            <img src="{{ Storage::url($institution->logo_path) }}" 
                                 alt="{{ $institution->name }}" 
                                 class="w-32 h-32 rounded-lg object-cover border-4 border-blue-500 mx-auto">
                        @else
                            <div class="w-32 h-32 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white text-4xl font-bold border-4 border-blue-500 mx-auto">
                                {{ strtoupper(substr($institution->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    
                    <h2 class="mt-4 text-2xl font-bold text-gray-900">
                        {{ $institution->name }}
                    </h2>
                    
                    <p class="text-sm text-gray-600 mt-1">{{ ucfirst($institution->type) }}</p>
                    
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 uppercase tracking-wide">Lokasi</p>
                                <p class="text-sm font-medium text-gray-900">{{ $institution->regency->name ?? '-' }}, {{ $institution->province->name ?? '-' }}</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-gray-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 uppercase tracking-wide">Telepon</p>
                                <p class="text-sm font-medium text-gray-900">{{ $institution->phone ?? '-' }}</p>
                            </div>
                        </div>

                        @if($institution->website)
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-gray-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                            </svg>
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 uppercase tracking-wide">Website</p>
                                <a href="{{ $institution->website }}" target="_blank" class="text-sm font-medium text-blue-600 hover:text-blue-700 break-all">
                                    {{ $institution->website }}
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- tombol aksi -->
                    <div class="mt-6 space-y-2">
                        <a href="{{ route('institution.profile.edit') }}" 
                           class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                            Edit Profil
                        </a>
                        <a href="{{ route('institution.profile.public', $institution->id) }}" 
                           target="_blank"
                           class="block w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-4 rounded-lg transition-colors">
                            Lihat Profil Publik
                        </a>
                    </div>
                </div>

                <!-- statistik ringkas -->
                <div class="mt-6 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-sm p-6 text-white">
                    <h3 class="font-bold mb-4">Statistik</h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-blue-100">Total Masalah</span>
                            <span class="text-2xl font-bold">{{ $stats['total_problems'] ?? 0 }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-blue-100">Masalah Aktif</span>
                            <span class="text-2xl font-bold">{{ $stats['active_problems'] ?? 0 }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-blue-100">Total Proyek</span>
                            <span class="text-2xl font-bold">{{ $stats['total_projects'] ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- konten utama -->
            <div class="lg:col-span-2 space-y-6">
                <!-- informasi instansi card -->
                <div class="bg-white rounded-lg shadow-sm p-6 profile-card">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Informasi Instansi</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">Nama Instansi</p>
                            <p class="text-gray-900">{{ $institution->name }}</p>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">Jenis Instansi</p>
                            <p class="text-gray-900">{{ ucfirst($institution->type) }}</p>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">Email</p>
                            <p class="text-gray-900">{{ $user->email }}</p>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">Nomor Telepon</p>
                            <p class="text-gray-900">{{ $institution->phone ?? '-' }}</p>
                        </div>

                        <div class="md:col-span-2">
                            <p class="text-sm font-medium text-gray-500 mb-1">Alamat</p>
                            <p class="text-gray-900">{{ $institution->address ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <!-- person in charge card -->
                <div class="bg-white rounded-lg shadow-sm p-6 profile-card">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Penanggung Jawab</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">Nama PIC</p>
                            <p class="text-gray-900">{{ $institution->pic_name ?? '-' }}</p>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">Jabatan PIC</p>
                            <p class="text-gray-900">{{ $institution->pic_position ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <!-- deskripsi card -->
                @if($institution->description)
                <div class="bg-white rounded-lg shadow-sm p-6 profile-card">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Deskripsi</h3>
                    <p class="text-gray-700 leading-relaxed">{{ $institution->description }}</p>
                </div>
                @endif

                <!-- statistik detail -->
                <div class="bg-white rounded-lg shadow-sm p-6 profile-card">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Statistik Detail</h3>
                    
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <div class="text-center p-4 bg-blue-50 rounded-lg">
                            <div class="text-3xl font-bold text-blue-600">{{ $stats['total_problems'] ?? 0 }}</div>
                            <div class="text-sm text-gray-600 mt-1">Total Masalah</div>
                        </div>

                        <div class="text-center p-4 bg-green-50 rounded-lg">
                            <div class="text-3xl font-bold text-green-600">{{ $stats['active_problems'] ?? 0 }}</div>
                            <div class="text-sm text-gray-600 mt-1">Masalah Aktif</div>
                        </div>

                        <div class="text-center p-4 bg-purple-50 rounded-lg">
                            <div class="text-3xl font-bold text-purple-600">{{ $stats['completed_problems'] ?? 0 }}</div>
                            <div class="text-sm text-gray-600 mt-1">Masalah Selesai</div>
                        </div>

                        <div class="text-center p-4 bg-orange-50 rounded-lg">
                            <div class="text-3xl font-bold text-orange-600">{{ $stats['total_projects'] ?? 0 }}</div>
                            <div class="text-sm text-gray-600 mt-1">Total Proyek</div>
                        </div>

                        <div class="text-center p-4 bg-teal-50 rounded-lg">
                            <div class="text-3xl font-bold text-teal-600">{{ $stats['active_projects'] ?? 0 }}</div>
                            <div class="text-sm text-gray-600 mt-1">Proyek Aktif</div>
                        </div>

                        <div class="text-center p-4 bg-indigo-50 rounded-lg">
                            <div class="text-3xl font-bold text-indigo-600">{{ $stats['completed_projects'] ?? 0 }}</div>
                            <div class="text-sm text-gray-600 mt-1">Proyek Selesai</div>
                        </div>
                    </div>
                </div>

                <!-- quick actions -->
                <div class="bg-white rounded-lg shadow-sm p-6 profile-card">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Aksi Cepat</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <a href="{{ route('institution.problems.create') }}" 
                           class="flex items-center gap-3 p-4 border-2 border-gray-200 rounded-lg hover:border-blue-500 hover:bg-blue-50 transition-all">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">Buat Masalah Baru</p>
                                <p class="text-sm text-gray-500">Publikasikan masalah baru</p>
                            </div>
                        </a>

                        <a href="{{ route('institution.problems.index') }}" 
                           class="flex items-center gap-3 p-4 border-2 border-gray-200 rounded-lg hover:border-green-500 hover:bg-green-50 transition-all">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">Kelola Masalah</p>
                                <p class="text-sm text-gray-500">Lihat dan edit masalah</p>
                            </div>
                        </a>

                        <a href="{{ route('institution.applications.index') }}" 
                           class="flex items-center gap-3 p-4 border-2 border-gray-200 rounded-lg hover:border-purple-500 hover:bg-purple-50 transition-all">
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">Review Aplikasi</p>
                                <p class="text-sm text-gray-500">Tinjau aplikasi mahasiswa</p>
                            </div>
                        </a>

                        <a href="{{ route('institution.projects.index') }}" 
                           class="flex items-center gap-3 p-4 border-2 border-gray-200 rounded-lg hover:border-orange-500 hover:bg-orange-50 transition-all">
                            <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">Kelola Proyek</p>
                                <p class="text-sm text-gray-500">Pantau progress proyek</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection