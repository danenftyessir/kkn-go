@extends('layouts.app')

@section('title', 'Tentang Kami - KKN-Go')

@section('content')
<div class="min-h-screen bg-white">
    
    {{-- hero section --}}
    <section class="relative h-screen min-h-[600px] overflow-hidden">
        {{-- background image --}}
        <div class="absolute inset-0">
            <img src="{{ asset('mahasiswa-about.jpeg') }}" 
                 alt="Tentang Kami KKN-Go" 
                 class="w-full h-full object-cover">
            {{-- overlay gradient - lebih gelap di bawah --}}
            <div class="absolute inset-0 bg-gradient-to-b from-black/30 via-black/40 to-black/70"></div>
        </div>
        
        {{-- content - text di kiri bawah --}}
        <div class="relative h-full">
            <div class="container mx-auto px-6 h-full flex items-end pb-20">
                <div class="max-w-4xl">
                    <h1 class="text-6xl md:text-7xl lg:text-8xl font-black text-white leading-tight tracking-tight">
                        Tentang Kami
                    </h1>
                </div>
            </div>
        </div>
    </section>

    {{-- layer 4: perkenalan kkn-go - light gray background --}}
    <section class="py-24 bg-gray-50">
        <div class="container mx-auto px-6">
            {{-- header dengan logo --}}
            <div class="text-center mb-16">
                <div class="flex justify-center mb-8">
                    <img src="{{ asset('kkn-go-logo.png') }}" alt="Logo KKN-Go" class="h-20 w-20">
                </div>
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6 leading-tight">
                    Perkenalkan, KKN-Go
                </h2>
                <div class="max-w-4xl mx-auto">
                    <p class="text-lg text-gray-700 leading-relaxed mb-4">
                        Indonesia memiliki potensi intelektual yang luar biasa dengan <span class="font-bold text-blue-600">8,3 juta mahasiswa aktif</span> di 4.500 perguruan tinggi, dimana sekitar <span class="font-bold text-blue-600">520.000 mahasiswa melaksanakan KKN</span> setiap tahun menghasilkan lebih dari 100.000 laporan penelitian.
                    </p>
                    <p class="text-lg text-gray-700 leading-relaxed mb-6">
                        Namun, data menunjukkan bahwa <span class="font-bold text-red-600">76% hasil penelitian mahasiswa hanya berakhir sebagai dokumen arsip</span> tanpa implementasi nyata, menciptakan pemborosan sumber daya senilai <span class="font-bold text-red-600">±Rp 1,2 triliun per tahun</span>.
                    </p>
                    <div class="inline-block bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-8 py-4 rounded-lg">
                        <p class="text-xl font-bold">
                            Kamilah Jawabannya! 🚀
                        </p>
                    </div>
                </div>
            </div>

            {{-- 3 masalah yang diselesaikan kkn-go --}}
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-10">
                    <h3 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4">
                        Tiga Masalah Krusial Yang Kami Selesaikan
                    </h3>
                    <p class="text-lg text-gray-600">
                        KKN-Go hadir untuk mentransformasi ekosistem KKN Indonesia
                    </p>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    {{-- solusi 1 --}}
                    <div class="bg-white rounded-lg p-8 shadow-lg hover:shadow-xl transition-shadow border-t-4 border-red-500">
                        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mb-6">
                            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Marketplace Masalah</h3>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            Platform yang menghubungkan mahasiswa dengan masalah nyata dari pemerintah daerah, meningkatkan relevansi program hingga <span class="font-bold text-blue-600">75%</span>.
                        </p>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600 italic">
                                "Mahasiswa dapat langsung fokus pada penyelesaian masalah tanpa menghabiskan waktu untuk identifikasi."
                            </p>
                        </div>
                    </div>

                    {{-- solusi 2 --}}
                    <div class="bg-white rounded-lg p-8 shadow-lg hover:shadow-xl transition-shadow border-t-4 border-yellow-500">
                        <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mb-6">
                            <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Impact Portfolio</h3>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            Sistem validasi resmi dari pemerintah daerah yang menciptakan portofolio profesional terverifikasi untuk mahasiswa.
                        </p>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600 italic">
                                "94% mahasiswa menyatakan portofolio tervalidasi sangat berharga dalam proses rekrutmen."
                            </p>
                        </div>
                    </div>

                    {{-- solusi 3 --}}
                    <div class="bg-white rounded-lg p-8 shadow-lg hover:shadow-xl transition-shadow border-t-4 border-blue-500">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-6">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Knowledge Repository</h3>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            Perpustakaan digital nasional yang mengubah hasil KKN menjadi sumber pembelajaran kolektif yang dapat diakses seluruh masyarakat.
                        </p>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600 italic">
                                "Potensi penghematan hingga Rp 540 miliar per tahun dengan mencegah duplikasi program."
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- layer 1: intro section - white background --}}
    <section class="py-24 bg-white">
        <div class="container mx-auto px-6">
            <div class="grid md:grid-cols-2 gap-16 items-start">
                {{-- left: big title --}}
                <div>
                    <h2 class="text-5xl md:text-6xl font-bold text-gray-900 leading-tight">
                        KKN-Go Dari Tahun Ke Tahun
                    </h2>
                </div>
                
                {{-- right: description --}}
                <div class="space-y-6">
                    <p class="text-lg text-gray-700 leading-relaxed">
                        KKN-Go berdiri sebagai katalisator dalam menciptakan ekosistem Kuliah Kerja Nyata 4.0 di Indonesia. Kami adalah laboratorium inovasi sekaligus wadah sinergi antara mahasiswa dan pemerintah daerah.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- layer 2: mission section - light gray background --}}
    <section class="py-24 bg-gray-100">
        <div class="container mx-auto px-6">
            <div class="grid md:grid-cols-2 gap-16 items-center">
                {{-- left: icon/visual element --}}
                <div class="flex items-center justify-center">
                    <div class="relative">
                        {{-- decorative circle --}}
                        <div class="w-64 h-64 md:w-80 md:h-80 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-2xl">
                            <svg class="w-32 h-32 md:w-40 md:h-40 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                        {{-- decorative dots --}}
                        <div class="absolute -top-4 -right-4 w-24 h-24 bg-yellow-400 rounded-full opacity-20"></div>
                        <div class="absolute -bottom-4 -left-4 w-32 h-32 bg-green-400 rounded-full opacity-20"></div>
                    </div>
                </div>
                
                {{-- right: text content --}}
                <div>
                    <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6 leading-tight">
                        Dari Mahasiswa
                    </h2>
                    <p class="text-lg text-gray-700 leading-relaxed mb-6">
                        Menyongsong usia ke-2 tahun, KKN-Go terus tumbuh dan bertransformasi. Dari pelabuhan kecil di masa lalu hingga platform digital masa kini, KKN-Go telah menghadapi beragam tantangan dan membuka banyak peluang.
                    </p>
                    <p class="text-lg text-gray-700 leading-relaxed">
                        Saatnya kita melangkah bersama untuk membentuk KKN-Go sebagai <span class="font-semibold italic">revolusi mahasiswa</span> yang lebih inklusif dan siap bersaing di pentas nasional.
                    </p>
                    <div class="mt-8">
                        <p class="text-gray-900 font-bold text-xl">Tim AnakSoleh</p>
                        <p class="text-gray-600">Institut Teknologi Bandung</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- layer 3: impact section - white background with image and icon list --}}
    <section class="py-24 bg-white">
        <div class="container mx-auto px-6">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                {{-- left: image --}}
                <div class="order-2 md:order-1">
                    <div class="relative">
                        <img src="{{ asset('handprints-about.jpeg') }}" 
                             alt="Aktivitas Mahasiswa KKN" 
                             class="w-full h-auto rounded-lg shadow-xl">
                    </div>
                </div>
                
                {{-- right: content with icon list --}}
                <div class="order-1 md:order-2">
                    <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-8 leading-tight">
                        Platform Terpadu Untuk Mahasiswa Dan Instansi
                    </h2>
                    <p class="text-lg text-gray-600 mb-10 leading-relaxed">
                        Selalu up-to-date dengan informasi dan data program KKN yang terintegrasi, aktual, serta transparan dari seluruh perguruan tinggi dan pemerintah daerah.
                    </p>
                    
                    {{-- icon list --}}
                    <div class="space-y-8">
                        {{-- item 1 --}}
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 rounded-full bg-blue-600 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <p class="text-gray-700 leading-relaxed">
                                    Pembaruan data setiap hari oleh mahasiswa dan instansi mitra
                                </p>
                            </div>
                        </div>
                        
                        {{-- item 2 --}}
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 rounded-full bg-blue-600 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <p class="text-gray-700 leading-relaxed">
                                    Dikelola oleh tim profesional dari perguruan tinggi terkemuka
                                </p>
                            </div>
                        </div>
                        
                        {{-- item 3 --}}
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 rounded-full bg-blue-600 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <p class="text-gray-700 leading-relaxed">
                                    Hasil sinergi perguruan tinggi dan pemerintah daerah untuk pembangunan berkelanjutan
                                </p>
                            </div>
                        </div>
                        
                        {{-- item 4 --}}
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 rounded-full bg-blue-600 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <p class="text-gray-700 leading-relaxed">
                                    Dikunjungi oleh lebih dari 520,000+ mahasiswa setiap tahun
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    {{-- cta link --}}
                    <div class="mt-10">
                        <a href="#" class="inline-flex items-center text-blue-600 font-semibold hover:text-blue-700 transition-colors group">
                            Kunjungi Website
                            <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>
{{-- how it works - timeline horizontal style --}}
<section class="py-32 bg-gradient-to-br from-blue-50 via-white to-green-50 overflow-hidden">
    <div class="container mx-auto px-6">
        <div class="max-w-7xl mx-auto">
            
            {{-- header section --}}
            <div class="mb-20 text-center">
                <h2 class="text-5xl md:text-6xl font-black text-blue-600 mb-6 tracking-tight">
                    Keberhasilan Ini Adalah Hasil Sinergi Kita Bersama
                </h2>
                <p class="text-xl md:text-2xl text-gray-700 leading-relaxed max-w-4xl mx-auto">
                    Transformasi ekosistem KKN Indonesia melalui platform digital yang menghubungkan mahasiswa, instansi, dan masyarakat
                </p>
            </div>

            {{-- navigation controls --}}
            <div class="flex items-center justify-between mb-12 flex-wrap gap-6">
                {{-- year selector --}}
                <div class="flex gap-3 overflow-x-auto pb-2 scrollbar-hide">
                    <button onclick="scrollToYear(2020)" class="year-btn whitespace-nowrap">
                        2020
                    </button>
                    <button onclick="scrollToYear(2021)" class="year-btn active whitespace-nowrap">
                        2021
                    </button>
                    <button onclick="scrollToYear(2022)" class="year-btn whitespace-nowrap">
                        2022
                    </button>
                    <button onclick="scrollToYear(2023)" class="year-btn whitespace-nowrap">
                        2023
                    </button>
                    <button onclick="scrollToYear(2024)" class="year-btn whitespace-nowrap">
                        2024
                    </button>
                    <button onclick="scrollToYear(2025)" class="year-btn whitespace-nowrap">
                        2025
                    </button>
                </div>
                
                {{-- navigation arrows --}}
                <div class="flex gap-4">
                    <button onclick="scrollTimeline('left')" class="arrow-btn group">
                        <svg class="w-6 h-6 text-gray-600 group-hover:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>
                    <button onclick="scrollTimeline('right')" class="arrow-btn group">
                        <svg class="w-6 h-6 text-gray-600 group-hover:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- timeline horizontal container --}}
            <div class="timeline-wrapper" id="timelineScroll">
                <div class="timeline-track">
                    
                    {{-- timeline line background --}}
                    <div class="timeline-line-bg"></div>
                    
                    {{-- timeline item 1 - 2020 --}}
                    <div class="timeline-item" data-year="2020">
                        <div class="timeline-content">
                            <div class="timeline-label">Identifikasi Masalah</div>
                            <div class="timeline-dot"></div>
                            <div class="timeline-card">
                                <h3 class="timeline-title">
                                    Analisis Ekosistem KKN Indonesia
                                </h3>
                                <div class="timeline-achievement">
                                    <p class="achievement-label">Temuan:</p>
                                    <p class="achievement-text">
                                        76% hasil penelitian mahasiswa berakhir sebagai dokumen arsip, menciptakan pemborosan ±Rp 1,2 triliun/tahun
                                    </p>
                                </div>
                                <div class="timeline-badge">
                                    Riset Nasional
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    {{-- timeline item 2 - 2021 --}}
                    <div class="timeline-item" data-year="2021">
                        <div class="timeline-content">
                            <div class="timeline-label">Studi Kebutuhan</div>
                            <div class="timeline-dot"></div>
                            <div class="timeline-card">
                                <h3 class="timeline-title">
                                    Survei 500 Kepala Desa
                                </h3>
                                <div class="timeline-achievement">
                                    <p class="achievement-label">Hasil:</p>
                                    <p class="achievement-text">
                                        68% kepala desa merasa program KKN tidak menjawab masalah utama desa mereka
                                    </p>
                                </div>
                                <div class="timeline-badge">
                                    5 Provinsi
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    {{-- timeline item 3 - 2022 --}}
                    <div class="timeline-item" data-year="2022">
                        <div class="timeline-content">
                            <div class="timeline-label">Konsep Platform</div>
                            <div class="timeline-dot"></div>
                            <div class="timeline-card">
                                <h3 class="timeline-title">
                                    Desain Solusi Digital
                                </h3>
                                <div class="timeline-achievement">
                                    <p class="achievement-label">Inovasi:</p>
                                    <p class="achievement-text">
                                        Marketplace masalah, Impact Portfolio, dan Knowledge Repository terintegrasi
                                    </p>
                                </div>
                                <div class="timeline-badge">
                                    Prototype
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    {{-- timeline item 4 - 2023 --}}
                    <div class="timeline-item" data-year="2023">
                        <div class="timeline-content">
                            <div class="timeline-label">Pilot Project</div>
                            <div class="timeline-dot"></div>
                            <div class="timeline-card">
                                <h3 class="timeline-title">
                                    Uji Coba Terbatas
                                </h3>
                                <div class="timeline-achievement">
                                    <p class="achievement-label">Capaian:</p>
                                    <p class="achievement-text">
                                        Peningkatan relevansi program KKN hingga 78% dan efisiensi waktu 30%
                                    </p>
                                </div>
                                <div class="timeline-badge">
                                    3 Universitas
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    {{-- timeline item 5 - 2024 --}}
                    <div class="timeline-item" data-year="2024">
                        <div class="timeline-content">
                            <div class="timeline-label">Ekspansi Nasional</div>
                            <div class="timeline-dot"></div>
                            <div class="timeline-card">
                                <h3 class="timeline-title">
                                    Kolaborasi Multi-Stakeholder
                                </h3>
                                <div class="timeline-achievement">
                                    <p class="achievement-label">Target:</p>
                                    <p class="achievement-text">
                                        Partnership dengan 100+ perguruan tinggi dan 1.000+ pemerintah daerah
                                    </p>
                                </div>
                                <div class="timeline-badge">
                                    Skala Nasional
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    {{-- timeline item 6 - 2025 --}}
                    <div class="timeline-item" data-year="2025">
                        <div class="timeline-content">
                            <div class="timeline-label">Platform KKN-Go</div>
                            <div class="timeline-dot active"></div>
                            <div class="timeline-card featured">
                                <h3 class="timeline-title">
                                    Peluncuran Resmi KKN-Go
                                </h3>
                                <div class="timeline-achievement">
                                    <p class="achievement-label">Dampak:</p>
                                    <p class="achievement-text">
                                        Mengubah 520.000+ mahasiswa KKN/tahun menjadi agen perubahan terukur untuk pembangunan desa
                                    </p>
                                </div>
                                <div class="timeline-badge featured">
                                    Transformasi Digital
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>

            {{-- impact metrics --}}
            <div class="mt-20 grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="metric-card">
                    <div class="metric-value">520K+</div>
                    <div class="metric-label">Mahasiswa KKN/Tahun</div>
                </div>
                <div class="metric-card">
                    <div class="metric-value">83,436</div>
                    <div class="metric-label">Desa/Kelurahan Target</div>
                </div>
                <div class="metric-card">
                    <div class="metric-value">540M</div>
                    <div class="metric-label">Potensi Penghematan/Tahun</div>
                </div>
                <div class="metric-card">
                    <div class="metric-value">100K+</div>
                    <div class="metric-label">Repository Target</div>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ============================================ --}}
{{-- PUSH STYLES - Taruh di @push('styles')     --}}
{{-- ============================================ --}}
@push('styles')
<style>
    /* scrollbar hide utility */
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    /* timeline wrapper */
    .timeline-wrapper {
        position: relative;
        overflow-x: auto;
        overflow-y: hidden;
        scrollbar-width: thin;
        scrollbar-color: #3b82f6 #e5e7eb;
        scroll-behavior: smooth;
        padding-bottom: 20px;
    }
    
    .timeline-wrapper::-webkit-scrollbar {
        height: 8px;
    }
    
    .timeline-wrapper::-webkit-scrollbar-track {
        background: #f3f4f6;
        border-radius: 4px;
    }
    
    .timeline-wrapper::-webkit-scrollbar-thumb {
        background: linear-gradient(90deg, #3b82f6, #2563eb);
        border-radius: 4px;
        transition: background 0.3s ease;
    }
    
    .timeline-wrapper::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(90deg, #2563eb, #1d4ed8);
    }
    
    /* timeline track */
    .timeline-track {
        display: flex;
        gap: 0;
        min-width: max-content;
        position: relative;
        padding: 80px 40px 40px;
    }
    
    /* timeline line background */
    .timeline-line-bg {
        position: absolute;
        top: 50%;
        left: 40px;
        right: 40px;
        height: 3px;
        background: linear-gradient(90deg, #cbd5e1 0%, #94a3b8 50%, #cbd5e1 100%);
        transform: translateY(-50%);
        z-index: 0;
    }
    
    /* timeline item */
    .timeline-item {
        flex-shrink: 0;
        width: 420px;
        position: relative;
        z-index: 1;
    }
    
    /* timeline content */
    .timeline-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 12px;
    }
    
    /* timeline label (project name) */
    .timeline-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: #64748b;
        text-align: center;
        padding: 8px 16px;
        background: #f1f5f9;
        border-radius: 20px;
        transition: all 0.3s ease;
    }
    
    /* timeline dot */
    .timeline-dot {
        width: 16px;
        height: 16px;
        background: white;
        border: 4px solid #3b82f6;
        border-radius: 50%;
        position: relative;
        z-index: 2;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.5);
    }
    
    .timeline-dot.active {
        background: #3b82f6;
        border-color: #2563eb;
        box-shadow: 0 0 0 8px rgba(59, 130, 246, 0.2);
        animation: pulse-dot 2s infinite;
    }
    
    @keyframes pulse-dot {
        0%, 100% {
            box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.5);
        }
        50% {
            box-shadow: 0 0 0 12px rgba(59, 130, 246, 0);
        }
    }
    
    .timeline-item:hover .timeline-dot {
        transform: scale(1.3);
        box-shadow: 0 0 0 8px rgba(59, 130, 246, 0.2);
    }
    
    /* timeline card */
    .timeline-card {
        background: white;
        border-radius: 16px;
        padding: 28px;
        text-align: center;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid #e5e7eb;
        min-height: 280px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    
    .timeline-card.featured {
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        border-color: #3b82f6;
        box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.2), 0 4px 6px -2px rgba(59, 130, 246, 0.1);
    }
    
    .timeline-item:hover .timeline-card {
        transform: translateY(-8px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    
    .timeline-item:hover .timeline-label {
        background: #e0f2fe;
        color: #0284c7;
    }
    
    /* timeline title */
    .timeline-title {
        font-size: 1.5rem;
        font-weight: 800;
        color: #111827;
        line-height: 1.3;
        margin-bottom: 16px;
    }
    
    /* timeline achievement */
    .timeline-achievement {
        margin-bottom: 20px;
    }
    
    .achievement-label {
        font-size: 0.875rem;
        font-weight: 700;
        color: #6b7280;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    
    .achievement-text {
        font-size: 0.9375rem;
        color: #374151;
        line-height: 1.6;
    }
    
    /* timeline badge */
    .timeline-badge {
        display: inline-block;
        padding: 8px 20px;
        background: #f3f4f6;
        color: #4b5563;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .timeline-badge.featured {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
    }
    
    /* year buttons */
    .year-btn {
        padding: 12px 24px;
        border-radius: 12px;
        font-size: 0.9375rem;
        font-weight: 700;
        background: #f1f5f9;
        color: #64748b;
        border: 2px solid transparent;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
    }
    
    .year-btn.active {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
    }
    
    .year-btn:not(.active):hover {
        background: #e2e8f0;
        color: #334155;
        border-color: #cbd5e1;
        transform: translateY(-2px);
    }
    
    /* arrow buttons */
    .arrow-btn {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        border: 2px solid #e5e7eb;
        display: flex;
        align-items: center;
        justify-content: center;
        background: white;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
    }
    
    .arrow-btn:hover {
        border-color: #3b82f6;
        background: #eff6ff;
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
    }
    
    /* impact metrics */
    .metric-card {
        background: white;
        border-radius: 16px;
        padding: 32px 24px;
        text-align: center;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        border: 1px solid #f3f4f6;
        transition: all 0.3s ease;
    }
    
    .metric-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 20px -5px rgba(0, 0, 0, 0.1);
        border-color: #3b82f6;
    }
    
    .metric-value {
        font-size: 2.5rem;
        font-weight: 900;
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 8px;
    }
    
    .metric-label {
        font-size: 0.875rem;
        color: #6b7280;
        font-weight: 600;
    }
</style>
@endpush

{{-- ============================================ --}}
{{-- PUSH SCRIPTS - Taruh di @push('scripts')   --}}
{{-- ============================================ --}}
@push('scripts')
<script>
    // scroll timeline horizontal dengan smooth animation
    function scrollTimeline(direction) {
        const container = document.getElementById('timelineScroll');
        const scrollAmount = 450;
        
        if (direction === 'left') {
            container.scrollBy({ 
                left: -scrollAmount, 
                behavior: 'smooth' 
            });
        } else {
            container.scrollBy({ 
                left: scrollAmount, 
                behavior: 'smooth' 
            });
        }
    }
    
    // scroll ke tahun tertentu
    function scrollToYear(year) {
        const container = document.getElementById('timelineScroll');
        const items = document.querySelectorAll('[data-year]');
        
        // update active state pada year buttons
        document.querySelectorAll('.year-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        event.target.classList.add('active');
        
        // cari item dengan tahun yang sesuai dan scroll ke posisinya
        items.forEach((item) => {
            if (parseInt(item.dataset.year) === year) {
                const scrollPosition = item.offsetLeft - 60;
                container.scrollTo({ 
                    left: scrollPosition, 
                    behavior: 'smooth' 
                });
            }
        });
    }
    
    // auto-update active year saat user scroll manual
    const timelineScroll = document.getElementById('timelineScroll');
    if (timelineScroll) {
        let scrollTimeout;
        
        timelineScroll.addEventListener('scroll', () => {
            clearTimeout(scrollTimeout);
            
            scrollTimeout = setTimeout(() => {
                const items = document.querySelectorAll('[data-year]');
                const scrollLeft = timelineScroll.scrollLeft;
                const containerWidth = timelineScroll.offsetWidth;
                
                items.forEach((item) => {
                    const itemLeft = item.offsetLeft;
                    const itemWidth = item.offsetWidth;
                    const itemCenter = itemLeft + (itemWidth / 2);
                    const scrollCenter = scrollLeft + (containerWidth / 2);
                    
                    // check jika item berada di center viewport
                    if (Math.abs(itemCenter - scrollCenter) < itemWidth / 2) {
                        const year = parseInt(item.dataset.year);
                        const yearButtons = document.querySelectorAll('.year-btn');
                        
                        yearButtons.forEach(btn => {
                            const btnText = btn.textContent.trim();
                            const btnYear = parseInt(btnText);
                            
                            if (btnYear === year) {
                                document.querySelectorAll('.year-btn').forEach(b => {
                                    b.classList.remove('active');
                                });
                                btn.classList.add('active');
                            }
                        });
                    }
                });
            }, 100);
        });
    }
    
    // keyboard navigation
    document.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowLeft') {
            scrollTimeline('left');
        } else if (e.key === 'ArrowRight') {
            scrollTimeline('right');
        }
    });
</script>
@endpush