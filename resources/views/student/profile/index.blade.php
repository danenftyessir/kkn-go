{{-- resources/views/student/profile/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-green-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- kolom kiri: profile info --}}
            <div class="lg:col-span-1 space-y-6">
                {{-- profile card --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden profile-transition fade-in">
                    <div class="h-32 bg-gradient-to-r from-blue-600 to-green-600"></div>
                    <div class="px-6 -mt-16 relative">
                        <div class="flex flex-col items-center text-center">
                            @if($student->profile_photo_path)
                                <img src="{{ $student->profile_photo_url }}" 
                                     alt="{{ $student->first_name }}"
                                     class="w-32 h-32 rounded-xl border-4 border-white shadow-lg object-cover">
                            @else
                                <div class="w-32 h-32 rounded-xl border-4 border-white shadow-lg bg-gradient-to-br from-blue-500 to-green-500 flex items-center justify-center">
                                    <span class="text-white text-4xl font-bold">{{ strtoupper(substr($student->first_name, 0, 1)) }}</span>
                                </div>
                            @endif
                            <div class="mt-4 mb-4">
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

                {{-- statistics grid --}}
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-2 gap-4 fade-in" style="animation-delay: 0.1s;">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-center profile-card hover:border-blue-500 transition-all">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-blue-100 mb-3">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <p class="text-3xl font-bold text-gray-900 mb-1">{{ $stats['completed_projects'] }}</p>
                        <p class="text-sm text-gray-600">Proyek Selesai</p>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-center profile-card hover:border-green-500 transition-all">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-green-100 mb-3">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <p class="text-3xl font-bold text-gray-900 mb-1">{{ $stats['active_projects'] }}</p>
                        <p class="text-sm text-gray-600">Proyek Aktif</p>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-center profile-card hover:border-yellow-500 transition-all">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-yellow-100 mb-3">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <p class="text-3xl font-bold text-gray-900 mb-1">{{ $stats['pending_applications'] }}</p>
                        <p class="text-sm text-gray-600">Aplikasi Pending</p>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-center profile-card hover:border-purple-500 transition-all">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-purple-100 mb-3">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <p class="text-3xl font-bold text-gray-900 mb-1">{{ $stats['total_applications'] }}</p>
                        <p class="text-sm text-gray-600">Total Aplikasi</p>
                    </div>
                </div>

                {{-- skills card --}}
                @if($skills && count($skills) > 0)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 profile-transition fade-in" style="animation-delay: 0.2s;">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                            </svg>
                            Skills & Keahlian
                        </h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($skills as $skill)
                                <span class="inline-flex items-center px-3 py-1 bg-blue-50 text-blue-700 text-sm rounded-full hover:bg-blue-100 transition-colors">
                                    {{ $skill }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- share portfolio --}}
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

            {{-- kolom kanan: projects --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 fade-in" style="animation-delay: 0.4s;">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Proyek Saya</h2>
                    
                    @if($completedProjects && $completedProjects->count() > 0)
                        <div class="space-y-4">
                            @foreach($completedProjects as $project)
                                <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-500 transition-all">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $project->problem->title }}</h3>
                                            <p class="text-gray-600 text-sm mb-2 line-clamp-2">{{ $project->problem->description }}</p>
                                            <div class="flex items-center gap-4 text-xs text-gray-500">
                                                <span class="flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                    </svg>
                                                    {{ $project->problem->institution->name }}
                                                </span>
                                                <span class="flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    </svg>
                                                    {{ $project->problem->regency->name }}
                                                </span>
                                            </div>
                                        </div>
                                        @if($project->institutionReview)
                                            <div class="ml-4">
                                                <div class="flex items-center text-yellow-500">
                                                    <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                    </svg>
                                                    <span class="ml-1 font-bold">{{ number_format($project->institutionReview->rating, 1) }}</span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="text-gray-500 text-lg">Belum ada proyek yang diselesaikan</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.fade-in {
    animation: fadeIn 0.6s cubic-bezier(0.4, 0, 0.2, 1);
}

.profile-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.profile-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 24px -10px rgba(0, 0, 0, 0.15);
}

.profile-transition {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
</style>
@endpush

@push('scripts')
<script>
// fungsi copy link portfolio
function copyPortfolioLink() {
    // PERBAIKAN: pastikan username valid dan route berfungsi
    @php
        $username = $student->user->username ?? $user->username ?? '';
    @endphp
    
    const url = '{{ route("profile.public", $username) }}';
    
    // validasi url
    if (!url || url.includes('undefined')) {
        showToast('Gagal membuat link portfolio. Username tidak valid.', 'error');
        return;
    }
    
    // gunakan clipboard API
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(url).then(() => {
            showToast('Link Portfolio Berhasil Disalin!', 'success');
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
    textArea.style.top = '0';
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();
    
    try {
        const successful = document.execCommand('copy');
        if (successful) {
            showToast('Link Portfolio Berhasil Disalin!', 'success');
        } else {
            showToast('Gagal Menyalin Link', 'error');
        }
    } catch (err) {
        console.error('Gagal menyalin link:', err);
        showToast('Gagal Menyalin Link', 'error');
    }
    
    document.body.removeChild(textArea);
}

// PERBAIKAN: fungsi show toast notification dengan z-index yang lebih tinggi
function showToast(message, type = 'success') {
    // hapus toast yang sudah ada
    const existingToast = document.querySelector('.custom-toast');
    if (existingToast) {
        existingToast.remove();
    }
    
    const toast = document.createElement('div');
    // PERBAIKAN: z-[1100] lebih tinggi dari navbar (z-1000)
    toast.className = `custom-toast fixed top-20 right-4 px-6 py-3 rounded-lg shadow-lg text-white transform transition-all duration-300 z-[1100] ${
        type === 'success' ? 'bg-green-600' : 'bg-red-600'
    }`;
    toast.textContent = message;
    toast.style.opacity = '0';
    toast.style.transform = 'translateX(100px)';
    
    document.body.appendChild(toast);
    
    // trigger animation
    requestAnimationFrame(() => {
        toast.style.opacity = '1';
        toast.style.transform = 'translateX(0)';
    });
    
    // auto remove
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(100px)';
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
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