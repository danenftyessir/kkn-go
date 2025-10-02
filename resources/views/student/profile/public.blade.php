@extends('layouts.app')

@section('title', $student->first_name . ' ' . $student->last_name . ' - Portfolio')

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

.portfolio-container {
    animation: fadeInUp 0.6s cubic-bezier(0.4, 0, 0.2, 1);
}

.project-card {
    transition: all 0.3s ease;
    border: 1px solid #e5e7eb;
}

.project-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    border-color: #3b82f6;
}

.skill-tag {
    transition: all 0.2s ease;
}

.skill-tag:hover {
    transform: scale(1.05);
    background-color: #3b82f6;
    color: white;
}

.hero-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- hero section dengan gradient -->
    <div class="hero-gradient py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center gap-8 portfolio-container">
                <!-- foto profil -->
                <div class="flex-shrink-0">
                    @if($student->profile_photo_path)
                        <img src="{{ asset('storage/' . $student->profile_photo_path) }}" 
                             alt="{{ $student->first_name }}" 
                             class="w-40 h-40 rounded-full object-cover border-4 border-white shadow-xl">
                    @else
                        <div class="w-40 h-40 rounded-full bg-white flex items-center justify-center text-purple-600 text-5xl font-bold border-4 border-white shadow-xl">
                            {{ strtoupper(substr($student->first_name, 0, 1)) }}{{ strtoupper(substr($student->last_name, 0, 1)) }}
                        </div>
                    @endif
                </div>
                
                <!-- info utama -->
                <div class="flex-1 text-center md:text-left text-white">
                    <h1 class="text-4xl md:text-5xl font-bold mb-2">
                        {{ $student->first_name }} {{ $student->last_name }}
                    </h1>
                    <p class="text-xl opacity-90 mb-4">
                        {{ $student->major }} • {{ $student->university->name ?? '' }}
                    </p>
                    <div class="flex flex-wrap gap-3 justify-center md:justify-start">
                        <span class="inline-flex items-center px-4 py-2 bg-white bg-opacity-20 rounded-full text-sm backdrop-blur-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            semester {{ $student->semester }}
                        </span>
                        <span class="inline-flex items-center px-4 py-2 bg-white bg-opacity-20 rounded-full text-sm backdrop-blur-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                            {{ $student->nim }}
                        </span>
                    </div>
                </div>

                <!-- tombol share -->
                <div class="flex-shrink-0">
                    <button onclick="shareProfile()" 
                            class="inline-flex items-center px-6 py-3 bg-white text-purple-600 rounded-lg hover:bg-gray-100 transition-colors font-medium shadow-lg">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path>
                        </svg>
                        bagikan profil
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- statistik cards -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-lg p-6 text-center transform hover:scale-105 transition-transform">
                <div class="text-4xl font-bold text-blue-600">{{ $stats['total_projects'] }}</div>
                <div class="text-sm text-gray-600 mt-2">proyek selesai</div>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6 text-center transform hover:scale-105 transition-transform">
                <div class="text-4xl font-bold text-green-600">{{ $stats['sdgs_addressed'] }}</div>
                <div class="text-sm text-gray-600 mt-2">sdgs disentuh</div>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6 text-center transform hover:scale-105 transition-transform">
                <div class="text-4xl font-bold text-yellow-600">{{ $stats['positive_reviews'] }}</div>
                <div class="text-sm text-gray-600 mt-2">ulasan positif</div>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6 text-center transform hover:scale-105 transition-transform">
                <div class="text-4xl font-bold text-purple-600">{{ number_format($stats['average_rating'], 1) }}</div>
                <div class="text-sm text-gray-600 mt-2">rating rata-rata</div>
            </div>
        </div>
    </div>

    <!-- main content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- sidebar kiri -->
            <div class="lg:col-span-1 space-y-6">
                <!-- bio -->
                @if($student->bio)
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        tentang saya
                    </h2>
                    <p class="text-gray-700 leading-relaxed">{{ $student->bio }}</p>
                </div>
                @endif

                <!-- TODO: skills section -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                        </svg>
                        keahlian
                    </h2>
                    <div class="flex flex-wrap gap-2">
                        <!-- TODO: loop skills dari database -->
                        <span class="skill-tag inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                            coming soon
                        </span>
                    </div>
                </div>

                <!-- kontak -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        kontak
                    </h2>
                    <div class="space-y-3">
                        <div class="flex items-center text-sm">
                            <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <span class="text-gray-700">{{ $user->email }}</span>
                        </div>
                        @if($student->phone)
                        <div class="flex items-center text-sm">
                            <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <span class="text-gray-700">{{ $student->phone }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- konten utama - projects showcase -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <svg class="w-6 h-6 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        proyek yang telah diselesaikan
                    </h2>

                    @if(count($completedProjects) > 0)
                        <div class="space-y-6">
                            @foreach($completedProjects as $project)
                            <div class="project-card rounded-lg p-6 bg-gray-50">
                                <!-- TODO: render project details -->
                                <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $project['title'] }}</h3>
                                <p class="text-gray-600 text-sm mb-4">{{ $project['description'] }}</p>
                                
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center text-sm text-gray-500">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        {{ $project['duration'] }}
                                    </div>
                                    <a href="#" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        lihat detail →
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="text-gray-500">belum ada proyek yang diselesaikan</p>
                        </div>
                    @endif
                </div>

                <!-- TODO: achievements section -->
                <!-- TODO: certifications section -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// fungsi share profile
function shareProfile() {
    const url = window.location.href;
    const title = '{{ $student->first_name }} {{ $student->last_name }} - Portfolio KKN-GO';
    
    // cek apakah browser support web share api
    if (navigator.share) {
        navigator.share({
            title: title,
            text: 'Lihat portfolio saya di KKN-GO',
            url: url
        }).catch(err => console.log('error sharing:', err));
    } else {
        // fallback: copy ke clipboard
        navigator.clipboard.writeText(url).then(() => {
            alert('link profil berhasil disalin ke clipboard!');
        }).catch(err => {
            console.error('gagal menyalin link:', err);
        });
    }
}

// animasi scroll reveal
document.addEventListener('DOMContentLoaded', function() {
    const projectCards = document.querySelectorAll('.project-card');
    
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
    
    projectCards.forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'all 0.5s ease';
        observer.observe(card);
    });
});
</script>
@endpush