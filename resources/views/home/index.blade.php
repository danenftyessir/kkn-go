@extends('layouts.app')

@section('title', 'Platform Digital untuk Kuliah Kerja Nyata Berkelanjutan')

@section('content')

<!-- hero section -->
<section class="relative bg-gradient-to-br from-primary-600 via-blue-600 to-primary-800 text-white overflow-hidden">
    <!-- background pattern -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\'40\' height=\'40\' viewBox=\'0 0 40 40\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.4\'%3E%3Cpath d=\'M20 20c0 11.046-8.954 20-20 20v-40c11.046 0 20 8.954 20 20z\'/%3E%3C/g%3E%3C/svg%3E');"></div>
    </div>

    <div class="container-custom relative z-10 py-20 lg:py-28">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <!-- text content -->
            <div data-aos="fade-right">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6 leading-tight">
                    Platform Digital untuk 
                    <span class="text-yellow-300">Kuliah Kerja Nyata</span> 
                    Berkelanjutan
                </h1>
                <p class="text-xl mb-8 text-blue-100">
                    Menghubungkan mahasiswa dengan instansi pemerintah untuk menciptakan solusi berkelanjutan di seluruh Indonesia
                </p>
                
                <!-- cta buttons -->
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('register') }}" class="btn bg-white text-primary-600 hover:bg-gray-100 hover:shadow-xl px-8 py-4 text-lg">
                        Mulai Sekarang
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </a>
                    <a href="#tentang" class="btn border-2 border-white text-white hover:bg-white/10 px-8 py-4 text-lg">
                        Pelajari Lebih Lanjut
                    </a>
                </div>

                <!-- statistics -->
                <div class="mt-12 grid grid-cols-3 gap-8">
                    <div data-aos="fade-up" data-aos-delay="100">
                        <div class="text-3xl font-bold">{{ $stats['total_projects'] }}+</div>
                        <div class="text-blue-200">Proyek Aktif</div>
                    </div>
                    <div data-aos="fade-up" data-aos-delay="200">
                        <div class="text-3xl font-bold">{{ $stats['total_students'] }}+</div>
                        <div class="text-blue-200">Mahasiswa</div>
                    </div>
                    <div data-aos="fade-up" data-aos-delay="300">
                        <div class="text-3xl font-bold">{{ $stats['total_institutions'] }}+</div>
                        <div class="text-blue-200">Instansi Mitra</div>
                    </div>
                </div>
            </div>

            <!-- illustration -->
            <div class="hidden lg:block" data-aos="fade-left">
                <div class="relative">
                    <div class="w-full h-96 bg-white/10 backdrop-blur-sm rounded-3xl"></div>
                    <!-- TODO: tambahkan ilustrasi atau image -->
                </div>
            </div>
        </div>
    </div>
</section>

<!-- platform info section -->
<section id="tentang" class="py-20 bg-white">
    <div class="container-custom">
        <div class="text-center mb-16" data-aos="fade-up">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">
                Kenapa Memilih KKN-GO?
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Platform terpadu yang memudahkan kolaborasi antara mahasiswa dan instansi untuk program KKN yang berdampak
            </p>
        </div>

        <!-- features grid -->
        <div class="grid md:grid-cols-3 gap-8">
            <!-- feature 1 -->
            <div class="card-hover p-8 text-center" data-aos="fade-up" data-aos-delay="100">
                <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Temukan Proyek</h3>
                <p class="text-gray-600">
                    Cari dan temukan proyek KKN yang sesuai dengan minat, keahlian, dan lokasi yang anda inginkan
                </p>
            </div>

            <!-- feature 2 -->
            <div class="card-hover p-8 text-center" data-aos="fade-up" data-aos-delay="200">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Kolaborasi Mudah</h3>
                <p class="text-gray-600">
                    Platform terintegrasi untuk komunikasi, manajemen proyek, dan pelaporan hasil KKN
                </p>
            </div>

            <!-- feature 3 -->
            <div class="card-hover p-8 text-center" data-aos="fade-up" data-aos-delay="300">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Portofolio Tervalidasi</h3>
                <p class="text-gray-600">
                    Bangun portofolio profesional dengan hasil KKN yang terverifikasi oleh instansi mitra
                </p>
            </div>
        </div>
    </div>
</section>

<!-- cta section -->
<section class="py-20 bg-gradient-to-r from-primary-600 to-blue-600 text-white">
    <div class="container-custom text-center" data-aos="zoom-in">
        <h2 class="text-4xl font-bold mb-6">
            Siap Memulai Perjalanan KKN Anda?
        </h2>
        <p class="text-xl mb-8 text-blue-100 max-w-2xl mx-auto">
            Bergabunglah dengan ribuan mahasiswa dan instansi yang sudah mempercayai KKN-GO
        </p>
        <a href="{{ route('register') }}" class="btn bg-white text-primary-600 hover:bg-gray-100 hover:shadow-xl px-8 py-4 text-lg inline-flex items-center">
            Daftar Gratis Sekarang
            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
            </svg>
        </a>
    </div>
</section>

@endsection