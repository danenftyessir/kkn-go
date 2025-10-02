@extends('layouts.app')

@section('title', 'Profil Saya')

@push('styles')
<style>
/* animasi fade in */
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

.profile-container {
    animation: fadeInUp 0.6s cubic-bezier(0.4, 0, 0.2, 1);
}

.profile-card {
    transition: all 0.3s ease;
}

.profile-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.stat-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    transition: transform 0.3s ease;
}

.stat-card:hover {
    transform: scale(1.05);
}
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- breadcrumb -->
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('home') }}" class="text-gray-600 hover:text-blue-600 transition-colors">
                        beranda
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <a href="{{ route('student.dashboard') }}" class="ml-1 text-gray-600 hover:text-blue-600 transition-colors">
                            dashboard
                        </a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-gray-500">profil</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="lg:grid lg:grid-cols-3 lg:gap-8 profile-container">
            <!-- sidebar profil -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm p-6 profile-card">
                    <!-- foto profil -->
                    <div class="flex flex-col items-center">
                        <div class="relative">
                            @if($student->profile_photo_path)
                                <img src="{{ asset('storage/' . $student->profile_photo_path) }}" 
                                     alt="{{ $student->first_name }}" 
                                     class="w-32 h-32 rounded-full object-cover border-4 border-blue-500">
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
                        
                        <div class="mt-2 flex items-center text-sm text-gray-500">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            {{ $user->email }}
                        </div>
                    </div>

                    <!-- info singkat -->
                    <div class="mt-6 space-y-3 border-t border-gray-200 pt-6">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-gray-400 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-gray-700">universitas</p>
                                <p class="text-sm text-gray-900">{{ $student->university->name ?? 'belum diisi' }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-gray-400 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-gray-700">jurusan</p>
                                <p class="text-sm text-gray-900">{{ $student->major }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-gray-400 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-gray-700">nim</p>
                                <p class="text-sm text-gray-900">{{ $student->nim }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-gray-400 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-gray-700">semester</p>
                                <p class="text-sm text-gray-900">semester {{ $student->semester }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-gray-400 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-gray-700">whatsapp</p>
                                <p class="text-sm text-gray-900">{{ $student->phone ?? 'belum diisi' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- actions -->
                    <div class="mt-6 space-y-2">
                        <a href="{{ route('student.profile.edit') }}" 
                           class="block w-full text-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                            edit profil
                        </a>
                        <a href="{{ route('student.profile.public', $user->username) }}" 
                           class="block w-full text-center px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium"
                           target="_blank">
                            lihat profil publik
                        </a>
                    </div>
                </div>
            </div>

            <!-- main content -->
            <div class="lg:col-span-2 space-y-6 mt-6 lg:mt-0">
                <!-- statistik -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="stat-card rounded-lg p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm opacity-90">proyek selesai</p>
                                <p class="text-3xl font-bold mt-2">0</p>
                            </div>
                            <svg class="w-12 h-12 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    
                    <div class="stat-card rounded-lg p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm opacity-90">aplikasi aktif</p>
                                <p class="text-3xl font-bold mt-2">0</p>
                            </div>
                            <svg class="w-12 h-12 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                    </div>
                    
                    <div class="stat-card rounded-lg p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm opacity-90">sdgs disentuh</p>
                                <p class="text-3xl font-bold mt-2">0</p>
                            </div>
                            <svg class="w-12 h-12 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- informasi pribadi -->
                <div class="bg-white rounded-lg shadow-sm p-6 profile-card">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">informasi pribadi</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-sm font-medium text-gray-700">nama lengkap</label>
                            <p class="text-gray-900 mt-1">{{ $student->first_name }} {{ $student->last_name }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">email</label>
                            <p class="text-gray-900 mt-1">{{ $user->email }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">nomor whatsapp</label>
                            <p class="text-gray-900 mt-1">{{ $student->phone ?? 'belum diisi' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">nim</label>
                            <p class="text-gray-900 mt-1">{{ $student->nim }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">semester</label>
                            <p class="text-gray-900 mt-1">semester {{ $student->semester }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">status akun</label>
                            <p class="text-gray-900 mt-1">
                                @if($user->email_verified_at)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        terverifikasi
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        belum terverifikasi
                                    </span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- bio -->
                @if($student->bio)
                <div class="bg-white rounded-lg shadow-sm p-6 profile-card">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">tentang saya</h2>
                    <p class="text-gray-700 leading-relaxed">{{ $student->bio }}</p>
                </div>
                @endif

                <!-- TODO: skills section -->
                <!-- TODO: achievements section -->
                <!-- TODO: recent projects section -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// smooth scroll behavior
document.addEventListener('DOMContentLoaded', function() {
    // animasi untuk stats cards
    const statCards = document.querySelectorAll('.stat-card');
    
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }, index * 100);
            }
        });
    }, observerOptions);
    
    statCards.forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'all 0.5s ease';
        observer.observe(card);
    });
});
</script>
@endpush