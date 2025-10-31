@extends('layouts.app')

@section('title', 'Platform Digital untuk Kuliah Kerja Nyata Berkelanjutan')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<style>
    /* Hero Section dengan parallax effect */
    .hero-parallax {
        background-image: url('/dashboard-student3.jpg');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        position: relative;
    }

    .hero-overlay {
        background: linear-gradient(135deg,
            rgba(30, 64, 175, 0.85) 0%,
            rgba(37, 99, 235, 0.75) 50%,
            rgba(59, 130, 246, 0.85) 100%
        );
    }

    /* Custom animations */
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
    }

    @keyframes pulse-glow {
        0%, 100% { box-shadow: 0 0 20px rgba(59, 130, 246, 0.5); }
        50% { box-shadow: 0 0 40px rgba(59, 130, 246, 0.8); }
    }

    .float-animation {
        animation: float 6s ease-in-out infinite;
    }

    .glow-button {
        animation: pulse-glow 2s ease-in-out infinite;
    }

    /* Diagonal split section */
    .diagonal-bg::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, #f8fafc 50%, #ffffff 50%);
        z-index: -1;
    }

    /* Map container */
    #map {
        height: 500px;
        width: 100%;
        border-radius: 1.5rem;
        z-index: 1;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }

    /* Feature hover effects */
    .feature-item {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .feature-item:hover {
        transform: translateX(10px);
    }

    .feature-item::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: linear-gradient(180deg, #3b82f6, #8b5cf6);
        opacity: 0;
        transition: opacity 0.3s;
    }

    .feature-item:hover::before {
        opacity: 1;
    }

    /* Stats counter animation */
    .stat-number {
        background: linear-gradient(135deg, #fbbf24, #f59e0b);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* Responsive adjustments */
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

{{-- Hero Section with Parallax --}}
<section class="relative hero-parallax min-h-screen flex items-center overflow-hidden">
    <div class="hero-overlay absolute inset-0"></div>

    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 relative z-10 py-20">
        <div class="grid lg:grid-cols-2 gap-12 items-center">

            {{-- Left Content --}}
            <div class="text-white space-y-8" data-aos="fade-right" data-aos-duration="1000">
                <div class="inline-block px-4 py-2 bg-white/10 backdrop-blur-sm rounded-full border border-white/20">
                    <span class="text-yellow-300 font-semibold">🌍 Platform KKN Terpercaya</span>
                </div>

                <h1 class="text-5xl md:text-6xl lg:text-7xl font-bold leading-tight">
                    Wujudkan
                    <span class="block mt-2 bg-gradient-to-r from-yellow-200 to-yellow-400 bg-clip-text text-transparent">
                        Perubahan Nyata
                    </span>
                    <span class="block mt-2">di Indonesia</span>
                </h1>

                <p class="text-xl md:text-2xl text-blue-100 leading-relaxed">
                    Bergabunglah dengan ribuan mahasiswa dan ratusan instansi untuk menciptakan solusi berkelanjutan di seluruh nusantara
                </p>

                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('register') }}"
                       class="glow-button px-8 py-4 bg-yellow-400 text-blue-900 font-bold rounded-xl hover:bg-yellow-300 transition-all duration-300 transform hover:scale-105 shadow-xl">
                        Mulai Sekarang
                        <svg class="inline-block w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </a>

                    <a href="#tentang"
                       class="px-8 py-4 bg-white/10 backdrop-blur-sm border-2 border-white text-white font-bold rounded-xl hover:bg-white/20 transition-all duration-300">
                        Pelajari Lebih Lanjut
                    </a>
                </div>
            </div>

            {{-- Right Stats --}}
            <div class="grid grid-cols-2 gap-6" data-aos="fade-left" data-aos-duration="1000">
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20 float-animation" style="animation-delay: 0s;">
                    <div class="text-5xl font-bold stat-number mb-2">{{ $stats['total_projects'] }}+</div>
                    <div class="text-white text-lg">Proyek Aktif</div>
                    <div class="mt-3 h-2 bg-white/20 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-yellow-400 to-orange-400" style="width: 75%"></div>
                    </div>
                </div>

                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20 float-animation" style="animation-delay: 0.5s;">
                    <div class="text-5xl font-bold stat-number mb-2">{{ $stats['total_students'] }}+</div>
                    <div class="text-white text-lg">Mahasiswa</div>
                    <div class="mt-3 h-2 bg-white/20 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-blue-400 to-purple-400" style="width: 85%"></div>
                    </div>
                </div>

                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20 float-animation" style="animation-delay: 1s;">
                    <div class="text-5xl font-bold stat-number mb-2">{{ $stats['total_institutions'] }}+</div>
                    <div class="text-white text-lg">Instansi Mitra</div>
                    <div class="mt-3 h-2 bg-white/20 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-green-400 to-emerald-400" style="width: 60%"></div>
                    </div>
                </div>

                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20 float-animation" style="animation-delay: 1.5s;">
                    <div class="text-5xl font-bold stat-number mb-2">34</div>
                    <div class="text-white text-lg">Provinsi</div>
                    <div class="mt-3 h-2 bg-white/20 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-pink-400 to-rose-400" style="width: 100%"></div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Wave Bottom --}}
    <div class="absolute bottom-0 left-0 right-0">
        <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-auto">
            <path d="M0 120L60 110C120 100 240 80 360 70C480 60 600 60 720 65C840 70 960 80 1080 85C1200 90 1320 90 1380 90L1440 90V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0Z" fill="white"/>
        </svg>
    </div>
</section>

{{-- Features Section with Diagonal Split --}}
<section id="tentang" class="relative py-24 diagonal-bg overflow-hidden">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 relative z-10">

        <div class="text-center mb-16" data-aos="fade-up">
            <div class="inline-block px-6 py-2 bg-blue-100 text-blue-700 rounded-full font-semibold mb-4">
                Kenapa KKN-GO?
            </div>
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                Platform yang Memberdayakan
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Solusi lengkap untuk pengalaman KKN yang bermakna dan berdampak
            </p>
        </div>

        <div class="grid lg:grid-cols-2 gap-8 items-center">

            {{-- Left Features List --}}
            <div class="space-y-6" data-aos="fade-right">
                <div class="feature-item relative bg-white p-8 rounded-2xl shadow-lg border border-gray-100">
                    <div class="flex items-start gap-6">
                        <div class="flex-shrink-0 w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">Temukan Proyek Ideal</h3>
                            <p class="text-gray-600 leading-relaxed">
                                Filter berdasarkan lokasi, minat, dan keahlian. Dapatkan rekomendasi proyek yang pas untuk Anda.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="feature-item relative bg-white p-8 rounded-2xl shadow-lg border border-gray-100">
                    <div class="flex items-start gap-6">
                        <div class="flex-shrink-0 w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">Kolaborasi Real-Time</h3>
                            <p class="text-gray-600 leading-relaxed">
                                Timeline proyek, komunikasi tim, dan pelaporan terintegrasi dalam satu platform.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="feature-item relative bg-white p-8 rounded-2xl shadow-lg border border-gray-100">
                    <div class="flex items-start gap-6">
                        <div class="flex-shrink-0 w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">Portfolio Terverifikasi</h3>
                            <p class="text-gray-600 leading-relaxed">
                                Dokumentasi resmi dari instansi. Bangun kredibilitas profesional Anda.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Image/Illustration --}}
            <div data-aos="fade-left">
                <div class="relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-400 to-purple-500 rounded-3xl transform rotate-6"></div>
                    <div class="relative bg-white rounded-3xl p-8 shadow-2xl">
                        <div class="aspect-w-16 aspect-h-9 bg-gradient-to-br from-blue-50 to-purple-50 rounded-2xl flex items-center justify-center">
                            <div class="text-center p-8">
                                <div class="text-6xl mb-4">🚀</div>
                                <p class="text-2xl font-bold text-gray-900 mb-2">{{ $stats['completed_projects'] }}+ Proyek Selesai</p>
                                <p class="text-gray-600">Dengan dampak nyata di masyarakat</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- Map Section --}}
<section class="py-24 bg-gradient-to-b from-white to-gray-50">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">

        <div class="grid lg:grid-cols-5 gap-12 items-center">

            {{-- Left Side - Stats Cards --}}
            <div class="lg:col-span-2 space-y-6" data-aos="fade-right">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                    Jangkauan
                    <span class="block mt-2 bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                        Nasional
                    </span>
                </h2>
                <p class="text-xl text-gray-600 leading-relaxed mb-8">
                    Dari Sabang sampai Merauke, temukan peluang KKN di seluruh Indonesia
                </p>

                <div class="space-y-4">
                    <div class="flex items-center gap-4 p-6 bg-white rounded-xl shadow-lg border-l-4 border-blue-500">
                        <div class="text-4xl">🏝️</div>
                        <div>
                            <div class="text-3xl font-bold text-blue-600">34</div>
                            <div class="text-gray-600 font-medium">Provinsi</div>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 p-6 bg-white rounded-xl shadow-lg border-l-4 border-purple-500">
                        <div class="text-4xl">🏛️</div>
                        <div>
                            <div class="text-3xl font-bold text-purple-600">514</div>
                            <div class="text-gray-600 font-medium">Kabupaten/Kota</div>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 p-6 bg-white rounded-xl shadow-lg border-l-4 border-green-500">
                        <div class="text-4xl">🤝</div>
                        <div>
                            <div class="text-3xl font-bold text-green-600">{{ $stats['total_institutions'] }}+</div>
                            <div class="text-gray-600 font-medium">Instansi Mitra</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Side - Map --}}
            <div class="lg:col-span-3" data-aos="fade-left">
                <div id="map" class="relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-500/10 to-purple-500/10 rounded-2xl z-0"></div>
                </div>
            </div>

        </div>
    </div>
</section>

@endsection

@push('scripts')
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>

// Initialize AOS
AOS.init({
    duration: 800,
    easing: 'ease-out-cubic',
    once: true,
    offset: 100,
});

// Initialize Map
const map = L.map('map', {
    center: [-2.5489, 118.0149],
    zoom: 5,
    zoomControl: true,
    scrollWheelZoom: true
});

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
    maxZoom: 18,
}).addTo(map);

// Project data
const projects = [
    { lat: -6.2088, lng: 106.8456, name: 'Jakarta - Proyek Smart Village', institution: 'Dinas Komunikasi DKI' },
    { lat: -7.7956, lng: 110.3695, name: 'Yogyakarta - Pemberdayaan UMKM', institution: 'Pemda Sleman' },
    { lat: -6.9175, lng: 107.6191, name: 'Bandung - Digitalisasi Desa', institution: 'Pemkot Bandung' },
    { lat: -7.2575, lng: 112.7521, name: 'Surabaya - Pengelolaan Sampah', institution: 'Dinas LH Surabaya' },
    { lat: 3.5952, lng: 98.6722, name: 'Medan - Pertanian Organik', institution: 'Dinas Pertanian Sumut' },
    { lat: -5.1477, lng: 119.4327, name: 'Makassar - Edukasi Maritim', institution: 'Dinas Kelautan Sulsel' },
    { lat: -8.6705, lng: 115.2126, name: 'Denpasar - Pariwisata Desa', institution: 'Dinas Pariwisata Bali' },
    { lat: 0.5330, lng: 101.4474, name: 'Pekanbaru - Konservasi Gambut', institution: 'LSM Lingkungan Riau' },
    { lat: -0.9436, lng: 100.3631, name: 'Padang - Mitigasi Tsunami', institution: 'BPBD Sumbar' },
    { lat: -3.3190, lng: 114.5908, name: 'Banjarmasin - Restorasi Lahan', institution: 'Yayasan Lahan Basah' }
];

// Custom marker icon
const customIcon = L.divIcon({
    className: 'custom-marker',
    html: '<div style="background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%); color: white; width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-center; font-weight: bold; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4); border: 3px solid white;"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg></div>',
    iconSize: [36, 36],
    iconAnchor: [18, 36],
    popupAnchor: [0, -36]
});

// Add markers
projects.forEach(project => {
    L.marker([project.lat, project.lng], { icon: customIcon })
        .addTo(map)
        .bindPopup(`
            <div style="min-width: 240px; padding: 8px;">
                <h3 style="font-weight: bold; margin-bottom: 8px; color: #1e40af; font-size: 1.1em;">${project.name}</h3>
                <p style="color: #6b7280; margin-bottom: 0; font-size: 0.9em;">
                    <span style="font-weight: 600;">Mitra:</span> ${project.institution}
                </p>
            </div>
        `);
});
</script>
@endpush
