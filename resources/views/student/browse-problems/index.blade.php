@extends('layouts.app')

@section('title', 'Jelajahi Masalah')

@push('styles')
<style>
/* custom styles untuk browse problems */
.problem-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 1.5rem;
}

.filter-sidebar {
    position: sticky;
    top: 5rem;
    height: fit-content;
    max-height: calc(100vh - 6rem);
    overflow-y: auto;
}

/* smooth scrolling dengan GPU acceleration */
.filter-sidebar {
    scroll-behavior: smooth;
    -webkit-overflow-scrolling: touch;
}

/* card hover effects dengan GPU acceleration */
.problem-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    will-change: transform;
}

.problem-card:hover {
    transform: translateY(-4px) scale3d(1.02, 1.02, 1);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

/* badge animations */
.badge {
    transition: all 0.2s ease;
}

.badge:hover {
    transform: scale(1.05);
}

/* skeleton loading */
.skeleton {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: skeleton-loading 1.5s infinite;
}

@keyframes skeleton-loading {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}

/* smooth fade in animation */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translate3d(0, 20px, 0);
    }
    to {
        opacity: 1;
        transform: translate3d(0, 0, 0);
    }
}

.fade-in-up {
    animation: fadeInUp 0.6s cubic-bezier(0.4, 0, 0.2, 1);
}

/* view toggle buttons */
.view-toggle-btn {
    transition: all 0.2s ease;
}

.view-toggle-btn.active {
    background-color: #0066CC;
    color: white;
}

/* responsive adjustments */
@media (max-width: 768px) {
    .problem-grid {
        grid-template-columns: 1fr;
    }
}

/* prefers reduced motion */
@media (prefers-reduced-motion: reduce) {
    * {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50 py-8" x-data="browseProblems()">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- header section -->
        <div class="mb-8 fade-in-up">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Jelajahi Masalah</h1>
                    <p class="text-gray-600 mt-1">Temukan proyek yang sesuai dengan minat dan keahlianmu</p>
                </div>
                
                <!-- view toggle -->
                <div class="hidden md:flex items-center space-x-2 bg-white rounded-lg p-1 shadow-sm">
                    <button @click="viewMode = 'grid'" 
                            :class="viewMode === 'grid' ? 'active' : ''"
                            class="view-toggle-btn px-3 py-2 rounded-md">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                        </svg>
                    </button>
                    <button @click="viewMode = 'list'" 
                            :class="viewMode === 'list' ? 'active' : ''"
                            class="view-toggle-btn px-3 py-2 rounded-md">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- stats cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white rounded-lg p-4 shadow-sm border border-gray-200">
                    <div class="flex items-center">
                        <div class="bg-blue-100 rounded-lg p-3 mr-4">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['total_problems'] }}</p>
                            <p class="text-sm text-gray-600">Masalah Tersedia</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg p-4 shadow-sm border border-gray-200">
                    <div class="flex items-center">
                        <div class="bg-green-100 rounded-lg p-3 mr-4">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['total_slots'] }}</p>
                            <p class="text-sm text-gray-600">Slot Mahasiswa</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg p-4 shadow-sm border border-gray-200">
                    <div class="flex items-center">
                        <div class="bg-red-100 rounded-lg p-3 mr-4">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['urgent_count'] }}</p>
                            <p class="text-sm text-gray-600">Mendesak</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:grid lg:grid-cols-12 lg:gap-8">
            <!-- sidebar filter -->
            <aside class="lg:col-span-3">
                @include('student.browse-problems.components.filter-sidebar')
            </aside>

            <!-- main content -->
            <main class="lg:col-span-9 mt-6 lg:mt-0">
                <!-- search & sort bar -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
                    <form method="GET" action="{{ route('student.browse-problems') }}" class="flex flex-col md:flex-row gap-4">
                        <!-- search input -->
                        <div class="flex-1">
                            <div class="relative">
                                <input type="text" 
                                       name="search" 
                                       value="{{ request('search') }}"
                                       placeholder="Cari berdasarkan judul, deskripsi, atau instansi..."
                                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>

                        <!-- sort dropdown -->
                        <select name="sort" 
                                onchange="this.form.submit()"
                                class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Terbaru</option>
                            <option value="deadline" {{ request('sort') === 'deadline' ? 'selected' : '' }}>Deadline Terdekat</option>
                            <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>Terpopuler</option>
                            <option value="most_applied" {{ request('sort') === 'most_applied' ? 'selected' : '' }}>Paling Banyak Dilamar</option>
                        </select>

                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            Cari
                        </button>
                    </form>
                </div>

                <!-- active filters -->
                @if(request()->hasAny(['search', 'province_id', 'sdg', 'difficulty', 'duration']))
                <div class="bg-blue-50 rounded-lg p-4 mb-6 border border-blue-200">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-sm font-medium text-blue-900">Filter Aktif:</p>
                        <a href="{{ route('student.browse-problems') }}" class="text-sm text-blue-600 hover:text-blue-800">
                            Hapus Semua Filter
                        </a>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        @if(request('search'))
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-white text-blue-800 border border-blue-200">
                            <span>Pencarian: "{{ request('search') }}"</span>
                            <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" class="ml-2 text-blue-600 hover:text-blue-800">×</a>
                        </span>
                        @endif
                        @if(request('province_id'))
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-white text-blue-800 border border-blue-200">
                            <span>Provinsi</span>
                            <a href="{{ request()->fullUrlWithQuery(['province_id' => null, 'regency_id' => null]) }}" class="ml-2 text-blue-600 hover:text-blue-800">×</a>
                        </span>
                        @endif
                        @if(request('difficulty'))
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-white text-blue-800 border border-blue-200">
                            <span>Tingkat: {{ ucfirst(request('difficulty')) }}</span>
                            <a href="{{ request()->fullUrlWithQuery(['difficulty' => null]) }}" class="ml-2 text-blue-600 hover:text-blue-800">×</a>
                        </span>
                        @endif
                    </div>
                </div>
                @endif

                <!-- problems grid/list -->
                <div x-show="viewMode === 'grid'" class="problem-grid">
                    @forelse($problems as $index => $problem)
                        @include('student.browse-problems.components.problem-card', ['problem' => $problem, 'index' => $index])
                    @empty
                        <div class="col-span-full">
                            <div class="text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada masalah ditemukan</h3>
                                <p class="mt-1 text-sm text-gray-500">Coba ubah filter atau kata kunci pencarian Anda</p>
                                <div class="mt-6">
                                    <a href="{{ route('student.browse-problems') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                        Reset Filter
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>

                <div x-show="viewMode === 'list'" class="space-y-4" style="display: none;">
                    @forelse($problems as $problem)
                        @include('student.browse-problems.components.problem-card-list', ['problem' => $problem])
                    @empty
                        <div class="text-center py-12 bg-white rounded-lg">
                            <p class="text-gray-500">Tidak ada masalah ditemukan</p>
                        </div>
                    @endforelse
                </div>

                <!-- pagination -->
                @if($problems->hasPages())
                <div class="mt-8">
                    {{ $problems->links() }}
                </div>
                @endif
            </main>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// alpine.js component untuk browse problems
function browseProblems() {
    return {
        viewMode: 'grid',
        loading: false,
        
        init() {
            // preload images untuk smooth experience
            this.preloadImages();
            
            // lazy load images saat scroll
            this.setupLazyLoading();
        },
        
        preloadImages() {
            const cards = document.querySelectorAll('.problem-card');
            cards.forEach((card, index) => {
                // stagger animation
                card.style.animationDelay = `${index * 0.05}s`;
            });
        },
        
        setupLazyLoading() {
            if ('IntersectionObserver' in window) {
                const imageObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const img = entry.target;
                            if (img.dataset.src) {
                                img.src = img.dataset.src;
                                img.classList.remove('skeleton');
                                observer.unobserve(img);
                            }
                        }
                    });
                });

                document.querySelectorAll('img[data-src]').forEach(img => {
                    imageObserver.observe(img);
                });
            }
        }
    }
}

// smooth scrolling untuk anchor links
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