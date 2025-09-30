@extends('layouts.app')

@section('title', 'Platform Digital untuk Kuliah Kerja Nyata Berkelanjutan')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<style>
    .hero-background {
        background-image: 
            linear-gradient(135deg, rgba(37, 99, 235, 0.75) 0%, rgba(59, 130, 246, 0.7) 50%, rgba(29, 78, 216, 0.75) 100%),
            url('/hero-background.jpg');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
    }

    #map {
        height: 500px;
        width: 100%;
        border-radius: 1rem;
        z-index: 1;
    }
</style>
@endpush

@section('content')

<!-- hero section -->
<section class="relative hero-background text-white overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.4\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
    </div>

    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 relative z-10 py-24 lg:py-32">
        <div class="max-w-4xl" data-aos="fade-right">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6 leading-tight">
                Platform Digital untuk 
                <span class="text-yellow-300 block mt-2">Kuliah Kerja Nyata</span> 
                <span class="block mt-2">Berkelanjutan</span>
            </h1>
            
            <p class="text-xl mb-10 text-blue-100 leading-relaxed max-w-2xl">
                Menghubungkan mahasiswa dengan instansi pemerintah untuk menciptakan solusi berkelanjutan di seluruh Indonesia
            </p>

            <div class="grid grid-cols-3 gap-8 max-w-2xl">
                <div data-aos="fade-up" data-aos-delay="100">
                    <div class="text-4xl md:text-5xl font-bold mb-2">{{ $stats['total_projects'] }}+</div>
                    <div class="text-blue-100 text-sm md:text-base">Proyek Aktif</div>
                </div>
                <div data-aos="fade-up" data-aos-delay="200">
                    <div class="text-4xl md:text-5xl font-bold mb-2">{{ $stats['total_students'] }}+</div>
                    <div class="text-blue-100 text-sm md:text-base">Mahasiswa</div>
                </div>
                <div data-aos="fade-up" data-aos-delay="300">
                    <div class="text-4xl md:text-5xl font-bold mb-2">{{ $stats['total_institutions'] }}+</div>
                    <div class="text-blue-100 text-sm md:text-base">Instansi Mitra</div>
                </div>
            </div>
        </div>
    </div>

    <div class="absolute bottom-0 left-0 right-0">
        <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-auto">
            <path d="M0 120L60 110C120 100 240 80 360 70C480 60 600 60 720 65C840 70 960 80 1080 85C1200 90 1320 90 1380 90L1440 90V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0Z" fill="white"/>
        </svg>
    </div>
</section>

<!-- platform info section -->
<section id="tentang" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
        <div class="text-center mb-16" data-aos="fade-up">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                Kenapa Memilih KKN-GO?
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Platform terpadu yang memudahkan kolaborasi antara mahasiswa dan instansi untuk program KKN yang berdampak
            </p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <div class="card-hover p-8 text-center group" data-aos="fade-up" data-aos-delay="100">
                <div class="w-20 h-20 bg-gradient-to-br from-primary-100 to-primary-200 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-10 h-10 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-semibold text-gray-900 mb-4">Temukan Proyek</h3>
                <p class="text-gray-600 leading-relaxed">
                    Cari dan temukan proyek KKN yang sesuai dengan minat, keahlian, dan lokasi yang anda inginkan
                </p>
            </div>

            <div class="card-hover p-8 text-center group" data-aos="fade-up" data-aos-delay="200">
                <div class="w-20 h-20 bg-gradient-to-br from-blue-100 to-blue-200 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-semibold text-gray-900 mb-4">Kolaborasi Mudah</h3>
                <p class="text-gray-600 leading-relaxed">
                    Platform terintegrasi untuk komunikasi, manajemen proyek, dan pelaporan hasil KKN
                </p>
            </div>

            <div class="card-hover p-8 text-center group" data-aos="fade-up" data-aos-delay="300">
                <div class="w-20 h-20 bg-gradient-to-br from-green-100 to-green-200 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-semibold text-gray-900 mb-4">Portofolio Tervalidasi</h3>
                <p class="text-gray-600 leading-relaxed">
                    Bangun portofolio profesional dengan hasil KKN yang terverifikasi oleh instansi mitra
                </p>
            </div>
        </div>
    </div>
</section>

<!-- map section -->
<section class="py-20 bg-gradient-to-b from-white to-gray-50">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
        <div class="text-center mb-12" data-aos="fade-up">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                Jangkauan di Seluruh Indonesia
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Temukan peluang KKN dari Sabang sampai Merauke
            </p>
        </div>

        <div data-aos="zoom-in" data-aos-delay="200">
            <div id="map" class="shadow-2xl"></div>
        </div>

        <div class="grid md:grid-cols-4 gap-6 mt-12" data-aos="fade-up" data-aos-delay="400">
            <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-100 text-center">
                <div class="text-3xl font-bold text-primary-600 mb-2">34</div>
                <div class="text-gray-600">Provinsi</div>
            </div>
            <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-100 text-center">
                <div class="text-3xl font-bold text-blue-600 mb-2">514</div>
                <div class="text-gray-600">Kabupaten/Kota</div>
            </div>
            <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-100 text-center">
                <div class="text-3xl font-bold text-green-600 mb-2">{{ $stats['total_institutions'] }}+</div>
                <div class="text-gray-600">Instansi Mitra</div>
            </div>
            <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-100 text-center">
                <div class="text-3xl font-bold text-purple-600 mb-2">{{ $stats['completed_projects'] }}+</div>
                <div class="text-gray-600">Proyek Selesai</div>
            </div>
        </div>
    </div>
</section>

<!-- cta section -->
<section class="py-20 bg-gradient-to-r from-primary-600 via-blue-600 to-primary-700 text-white relative overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 left-0 w-96 h-96 bg-white rounded-full -translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-white rounded-full translate-x-1/2 translate-y-1/2"></div>
    </div>

    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 text-center relative z-10" data-aos="zoom-in">
        <h2 class="text-4xl md:text-5xl font-bold mb-6">
            Siap Memulai Perjalanan KKN Anda?
        </h2>
        <p class="text-xl mb-8 text-blue-100 max-w-2xl mx-auto leading-relaxed">
            Bergabunglah dengan ribuan mahasiswa dan instansi yang sudah mempercayai KKN-GO
        </p>
        <a href="{{ route('register') }}" class="inline-flex items-center bg-white text-primary-600 hover:bg-gray-100 px-10 py-5 rounded-xl text-lg font-semibold shadow-2xl hover:shadow-3xl transition-all duration-300 hover:-translate-y-2 hover:scale-105">
            Daftar Gratis Sekarang
            <svg class="w-6 h-6 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
            </svg>
        </a>
    </div>
</section>

@endsection

@push('scripts')
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
// initialize AOS
AOS.init({
    duration: 600,
    easing: 'ease-out',
    once: true,
    offset: 50,
});

// initialize map
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

const projects = [
    { lat: -6.2088, lng: 106.8456, name: 'Jakarta - Proyek Smart Village', institution: 'Dinas Komunikasi DKI' },
    { lat: -7.7956, lng: 110.3695, name: 'Yogyakarta - Pemberdayaan UMKM', institution: 'Pemda Sleman' },
    { lat: -6.9175, lng: 107.6191, name: 'Bandung - Digitalisasi Desa', institution: 'Pemkot Bandung' },
    { lat: -7.2575, lng: 112.7521, name: 'Surabaya - Pengelolaan Sampah', institution: 'Dinas LH Surabaya' },
    { lat: 3.5952, lng: 98.6722, name: 'Medan - Pertanian Organik', institution: 'Dinas Pertanian Sumut' },
];

const customIcon = L.divIcon({
    className: 'custom-marker',
    html: '<div style="background: #2563eb; color: white; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-center; font-weight: bold; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border: 3px solid white;"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg></div>',
    iconSize: [32, 32],
    iconAnchor: [16, 32],
    popupAnchor: [0, -32]
});

projects.forEach(project => {
    L.marker([project.lat, project.lng], { icon: customIcon })
        .addTo(map)
        .bindPopup(`
            <div style="min-width: 200px;">
                <h3 style="font-weight: bold; margin-bottom: 8px; color: #1e40af;">${project.name}</h3>
                <p style="color: #6b7280; margin-bottom: 4px;">${project.institution}</p>
                <a href="#" style="color: #2563eb; text-decoration: underline; font-size: 14px;">Lihat Detail →</a>
            </div>
        `);
});
</script>
@endpush