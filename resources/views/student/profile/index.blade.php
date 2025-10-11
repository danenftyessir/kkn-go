{{-- resources/views/student/profile/index.blade.php --}}
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
                    <a href="{{ route('student.dashboard') }}" class="text-gray-500 hover:text-gray-700 transition-colors">
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
            <div class="mb-6 bg-red-50 border border-red-200 text-red-800 rounded-lg p-4 flex items-center profile-transition fade-in">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- main profile section -->
            <div class="lg:col-span-2 space-y-6">
                <!-- profile card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden profile-transition fade-in">
                    <div class="h-32 bg-gradient-to-r from-blue-600 to-green-600"></div>
                    <div class="px-6 pb-6">
                        <div class="flex flex-col items-center -mt-16 mb-4">
                            @if($student->profile_photo_path)
                                <img src="{{ $student->profile_photo_url }}" 
                                     alt="{{ $student->first_name }}" 
                                     class="w-32 h-32 rounded-xl border-4 border-white shadow-lg object-cover profile-photo">
                            @else
                                <div class="w-32 h-32 rounded-xl border-4 border-white shadow-lg bg-gradient-to-br from-blue-500 to-green-500 flex items-center justify-center">
                                    <span class="text-white text-4xl font-bold">{{ strtoupper(substr($student->first_name, 0, 1)) }}</span>
                                </div>
                            @endif
                            <div class="mt-4 text-center">
                                <h2 class="text-2xl font-bold text-gray-900 mb-1">{{ $student->first_name }} {{ $student->last_name }}</h2>
                                <p class="text-gray-600">{{ $student->university->name }}</p>
                                <p class="text-sm text-gray-500">{{ $student->major }} • Semester {{ $student->semester }}</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-center gap-3 mb-4">
                            <a href="{{ route('student.profile.edit') }}" 
                               class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all font-medium transform hover:scale-105">
                                Edit Profil
                            </a>
                            <a href="{{ route('profile.public', $student->user->username) }}" 
                               target="_blank"
                               class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all font-medium transform hover:scale-105">
                                Preview Public
                            </a>
                        </div>
                    </div>

                    @if($student->bio)
                        <div class="px-6 pb-6 border-t border-gray-100 pt-6">
                            <h3 class="text-sm font-semibold text-gray-700 mb-2">Bio</h3>
                            <p class="text-gray-600 leading-relaxed">{{ $student->bio }}</p>
                        </div>
                    @endif
                </div>

                <!-- statistics grid -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 fade-in" style="animation-delay: 0.1s;">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-center profile-card hover:border-blue-500 transition-all">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-blue-100 mb-3">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['total_projects'] }}</p>
                        <p class="text-sm text-gray-600 mt-1">Total Proyek</p>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-center profile-card hover:border-green-500 transition-all">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-green-100 mb-3">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['completed_projects'] }}</p>
                        <p class="text-sm text-gray-600 mt-1">Proyek Selesai</p>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-center profile-card hover:border-yellow-500 transition-all">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-yellow-100 mb-3">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['active_projects'] }}</p>
                        <p class="text-sm text-gray-600 mt-1">Proyek Aktif</p>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-center profile-card hover:border-purple-500 transition-all">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-purple-100 mb-3">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                            </svg>
                        </div>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['total_applications'] }}</p>
                        <p class="text-sm text-gray-600 mt-1">Total Aplikasi</p>
                    </div>
                </div>

                <!-- completed projects showcase -->
                @if($completedProjects && $completedProjects->count() > 0)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 profile-transition fade-in" style="animation-delay: 0.2s;">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Proyek Yang Sudah Diselesaikan</h3>
                        <div class="space-y-4">
                            @foreach($completedProjects as $project)
                                <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-500 profile-transition">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-gray-900 mb-1">{{ $project->problem->title }}</h4>
                                            <p class="text-sm text-gray-600 mb-2">
                                                {{ $project->problem->institution->name }} • 
                                                {{ $project->problem->regency->name ?? '' }}
                                            </p>
                                            @if($project->role_in_team)
                                                <span class="inline-block px-2 py-1 text-xs bg-blue-50 text-blue-700 rounded">
                                                    {{ $project->role_in_team }}
                                                </span>
                                            @endif
                                        </div>
                                        @if($project->problem->review)
                                            <div class="flex items-center gap-1 text-yellow-500">
                                                <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                                <span class="font-semibold">{{ $project->problem->review->rating }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if($completedProjects->count() >= 5)
                            <div class="mt-4 text-center">
                                <a href="{{ route('student.projects.index') }}" class="text-blue-600 hover:text-blue-700 font-medium transition-colors">
                                    Lihat Semua Proyek →
                                </a>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <!-- sidebar kanan -->
            <div class="lg:col-span-1 space-y-6">
                <!-- informasi kontak -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 profile-transition fade-in" style="animation-delay: 0.1s;">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Kontak</h3>
                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-gray-500">Email</p>
                                <p class="text-sm text-gray-900 break-all">{{ $student->user->email }}</p>
                            </div>
                        </div>

                        @if($student->phone)
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm text-gray-500">WhatsApp</p>
                                    <p class="text-sm text-gray-900">{{ $student->phone }}</p>
                                </div>
                            </div>
                        @endif

                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                            </svg>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-gray-500">NIM</p>
                                <p class="text-sm text-gray-900">{{ $student->nim }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- skills jika ada -->
                @if($skills && count($skills) > 0)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 profile-transition fade-in" style="animation-delay: 0.2s;">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Skills</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($skills as $skill)
                                <span class="px-3 py-1 bg-blue-50 text-blue-700 text-sm rounded-full hover:bg-blue-100 transition-colors">
                                    {{ $skill }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- share portfolio -->
                <div class="bg-gradient-to-br from-blue-600 to-green-600 rounded-xl shadow-sm p-6 text-white profile-transition fade-in" style="animation-delay: 0.3s;">
                    <h3 class="text-lg font-semibold mb-2">Bagikan Portfolio</h3>
                    <p class="text-sm mb-4 opacity-90">Bagikan profil publik Anda dengan calon rekruter atau mitra</p>
                    <button onclick="copyPortfolioLink()" class="w-full px-4 py-2 bg-white text-blue-600 rounded-lg hover:bg-gray-50 transition-all font-medium transform hover:scale-105">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path>
                        </svg>
                        Salin Link Portfolio
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// fungsi copy link portfolio
function copyPortfolioLink() {
    const url = '{{ route("profile.public", $student->user->username) }}';
    
    // gunakan clipboard API
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(url).then(() => {
            showToast('Link portfolio berhasil disalin!', 'success');
        }).catch(err => {
            console.error('Gagal menyalin link:', err);
            fallbackCopy(url);
        });
    } else {
        fallbackCopy(url);
    }
}

// fallback untuk copy jika clipboard API tidak tersedia
function fallbackCopy(text) {
    const textArea = document.createElement('textarea');
    textArea.value = text;
    textArea.style.position = 'fixed';
    textArea.style.left = '-999999px';
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();
    
    try {
        document.execCommand('copy');
        showToast('Link portfolio berhasil disalin!', 'success');
    } catch (err) {
        console.error('Gagal menyalin link:', err);
        showToast('Gagal menyalin link', 'error');
    }
    
    document.body.removeChild(textArea);
}

// fungsi show toast notification
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg text-white transform transition-all duration-300 z-50 ${
        type === 'success' ? 'bg-green-600' : 'bg-red-600'
    }`;
    toast.textContent = message;
    toast.style.opacity = '0';
    toast.style.transform = 'translateY(-20px)';
    
    document.body.appendChild(toast);
    
    // trigger animation
    setTimeout(() => {
        toast.style.opacity = '1';
        toast.style.transform = 'translateY(0)';
    }, 10);
    
    // auto remove
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(-20px)';
        setTimeout(() => {
            document.body.removeChild(toast);
        }, 300);
    }, 3000);
}

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
</script>
@endpush
@endsection