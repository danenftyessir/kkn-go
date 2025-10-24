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
// fungsi show share options modal
function copyPortfolioLink() {
    showShareModal();
}

function showShareModal() {
    // buat modal element
    const modal = document.createElement('div');
    modal.id = 'share-modal';
    modal.className = 'fixed inset-0 z-[1200] flex items-center justify-center p-4';
    modal.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';
    modal.style.backdropFilter = 'blur(4px)';
    modal.style.opacity = '0';
    modal.style.transition = 'opacity 0.3s ease';
    
    @php
        $username = $student->user->username ?? $user->username ?? '';
    @endphp
    
    const portfolioUrl = '{{ route("profile.public", $username) }}';
    const studentName = '{{ $student->first_name }} {{ $student->last_name }}';
    const shareText = `Lihat portfolio saya di KKN-GO: ${studentName}`;
    modal.innerHTML = `
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full transform transition-all duration-300" 
             style="transform: scale(0.9);"
             onclick="event.stopPropagation()">
            <!-- header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h3 class="text-xl font-bold text-gray-900">Bagikan Portfolio</h3>
                <button onclick="closeShareModal()" 
                        class="text-gray-400 hover:text-gray-600 transition-colors p-1 rounded-lg hover:bg-gray-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <!-- content -->
            <div class="p-6">
                <p class="text-gray-600 text-sm mb-6">Bagikan profil portfolio Anda melalui:</p>
                
                <!-- share buttons grid -->
                <div class="grid grid-cols-2 gap-3">
                    <!-- whatsapp -->
                    <button onclick="shareToWhatsApp('${portfolioUrl}', '${shareText}')" 
                            class="flex items-center justify-center gap-3 px-4 py-3 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition-all transform hover:scale-105 border border-green-200">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                        <span class="font-medium">WhatsApp</span>
                    </button>
                    
                    <!-- email -->
                    <button onclick="shareToEmail('${portfolioUrl}', '${shareText}')" 
                            class="flex items-center justify-center gap-3 px-4 py-3 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition-all transform hover:scale-105 border border-blue-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <span class="font-medium">Email</span>
                    </button>
                    
                    <!-- linkedin -->
                    <button onclick="shareToLinkedIn('${portfolioUrl}')" 
                            class="flex items-center justify-center gap-3 px-4 py-3 bg-sky-50 text-sky-700 rounded-lg hover:bg-sky-100 transition-all transform hover:scale-105 border border-sky-200">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                        </svg>
                        <span class="font-medium">LinkedIn</span>
                    </button>
                    
                    <!-- twitter/x -->
                    <button onclick="shareToTwitter('${portfolioUrl}', '${shareText}')" 
                            class="flex items-center justify-center gap-3 px-4 py-3 bg-gray-50 text-gray-700 rounded-lg hover:bg-gray-100 transition-all transform hover:scale-105 border border-gray-200">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                        </svg>
                        <span class="font-medium">Twitter</span>
                    </button>
                </div>
                
                <!-- copy link button -->
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <button onclick="copyLinkToClipboard('${portfolioUrl}')" 
                            class="w-full flex items-center justify-center gap-3 px-4 py-3 bg-gradient-to-r from-blue-600 to-green-600 text-white rounded-lg hover:from-blue-700 hover:to-green-700 transition-all transform hover:scale-105 shadow-md">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                        <span class="font-semibold">Salin Link</span>
                    </button>
                </div>
                
                <!-- link preview -->
                <div class="mt-4 p-3 bg-gray-50 rounded-lg border border-gray-200">
                    <p class="text-xs text-gray-500 mb-1">Link Portfolio:</p>
                    <p class="text-sm text-gray-700 font-mono break-all">${portfolioUrl}</p>
                </div>
            </div>
        </div>
    `;
    
    // append ke body
    document.body.appendChild(modal);
    
    // trigger animation
    requestAnimationFrame(() => {
        modal.style.opacity = '1';
        const content = modal.querySelector('div > div');
        if (content) {
            content.style.transform = 'scale(1)';
        }
    });
    
    // close on backdrop click
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeShareModal();
        }
    });
    
    // close on escape key
    document.addEventListener('keydown', function escHandler(e) {
        if (e.key === 'Escape') {
            closeShareModal();
            document.removeEventListener('keydown', escHandler);
        }
    });
}

function closeShareModal() {
    const modal = document.getElementById('share-modal');
    if (modal) {
        modal.style.opacity = '0';
        const content = modal.querySelector('div > div');
        if (content) {
            content.style.transform = 'scale(0.9)';
        }
        setTimeout(() => {
            modal.remove();
        }, 300);
    }
}

// share ke whatsapp
function shareToWhatsApp(url, text) {
    const whatsappUrl = `https://wa.me/?text=${encodeURIComponent(text + '\n' + url)}`;
    window.open(whatsappUrl, '_blank');
    closeShareModal();
    showToast('Membuka WhatsApp...', 'success');
}

// share ke email
function shareToEmail(url, text) {
    const subject = 'Portfolio KKN-GO';
    const body = `${text}\n\n${url}`;
    const emailUrl = `mailto:?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;
    window.location.href = emailUrl;
    closeShareModal();
    showToast('Membuka Email Client...', 'success');
}

// share ke linkedin
function shareToLinkedIn(url) {
    const linkedInUrl = `https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(url)}`;
    window.open(linkedInUrl, '_blank', 'width=600,height=600');
    closeShareModal();
    showToast('Membuka LinkedIn...', 'success');
}

// share ke twitter
function shareToTwitter(url, text) {
    const twitterUrl = `https://twitter.com/intent/tweet?text=${encodeURIComponent(text)}&url=${encodeURIComponent(url)}`;
    window.open(twitterUrl, '_blank', 'width=600,height=600');
    closeShareModal();
    showToast('Membuka Twitter...', 'success');
}

// copy link to clipboard
function copyLinkToClipboard(url) {
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(url).then(() => {
            closeShareModal();
            showToast('Link Portfolio Berhasil Disalin!', 'success');
        }).catch(err => {
            console.error('Gagal menyalin link:', err);
            fallbackCopyLink(url);
        });
    } else {
        fallbackCopyLink(url);
    }
}

// fallback copy method
function fallbackCopyLink(text) {
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
            closeShareModal();
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

// fungsi show toast notification dengan z-index yang lebih tinggi
function showToast(message, type = 'success') {
    // hapus toast yang sudah ada
    const existingToast = document.querySelector('.custom-toast');
    if (existingToast) {
        existingToast.remove();
    }
    
    const toast = document.createElement('div');
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