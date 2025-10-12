@extends('layouts.app')

@section('title', 'Tentang Kami - KKN-Go')

@push('styles')
<style>
    /* hero section */
    .about-hero {
        background: linear-gradient(to bottom, rgba(0,0,0,0.3) 0%, rgba(0,0,0,0.7) 100%), 
                    url('{{ asset("mahasiswa-about.jpeg") }}') center/cover;
        min-height: 600px;
    }
    
    /* timeline horizontal scroll */
    .timeline-container {
        position: relative;
        overflow-x: auto;
        overflow-y: hidden;
        scrollbar-width: thin;
        scrollbar-color: #3b82f6 #e5e7eb;
    }
    
    .timeline-container::-webkit-scrollbar {
        height: 6px;
    }
    
    .timeline-container::-webkit-scrollbar-track {
        background: #e5e7eb;
        border-radius: 3px;
    }
    
    .timeline-container::-webkit-scrollbar-thumb {
        background: #3b82f6;
        border-radius: 3px;
    }
    
    .timeline-container::-webkit-scrollbar-thumb:hover {
        background: #2563eb;
    }
    
    /* timeline line */
    .timeline-line {
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 2px;
        background: #cbd5e1;
        transform: translateY(-50%);
        z-index: 0;
    }
    
    /* timeline dots */
    .timeline-dot {
        width: 12px;
        height: 12px;
        background: #3b82f6;
        border-radius: 50%;
        position: relative;
        z-index: 1;
        transition: all 0.3s ease;
    }
    
    .timeline-dot:hover {
        transform: scale(1.3);
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.2);
    }
    
    /* year selector */
    .year-selector {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .year-selector.active {
        background: #3b82f6;
        color: white;
        transform: scale(1.05);
    }
    
    .year-selector:not(.active) {
        background: #e5e7eb;
        color: #64748b;
    }
    
    .year-selector:not(.active):hover {
        background: #cbd5e1;
        color: #334155;
    }
    
    /* nav arrows */
    .nav-arrow {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: 2px solid #cbd5e1;
        display: flex;
        align-items: center;
        justify-content: center;
        background: white;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .nav-arrow:hover {
        border-color: #3b82f6;
        background: #eff6ff;
    }
    
    /* animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-fade-in {
        animation: fadeInUp 0.6s ease-out;
    }
    
    /* smooth scrolling */
    html {
        scroll-behavior: smooth;
    }
    
    /* accessibility */
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
<div class="min-h-screen bg-white">
    
    {{-- hero section --}}
    <section class="about-hero flex items-end">
        <div class="container mx-auto px-6 pb-20">
            <div class="max-w-4xl animate-fade-in">
                <h1 class="text-6xl md:text-7xl lg:text-8xl font-black text-white leading-tight tracking-tight">
                    Tentang Kami
                </h1>
            </div>
        </div>
    </section>

    {{-- visi misi section --}}
    <section class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="max-w-6xl mx-auto">
                <div class="grid md:grid-cols-2 gap-12">
                    {{-- visi --}}
                    <div class="animate-fade-in">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </div>
                            <h2 class="text-3xl font-bold text-gray-900">Visi</h2>
                        </div>
                        <p class="text-lg text-gray-700 leading-relaxed">
                            Menjadi platform digital terdepan yang mentransformasi ekosistem Kuliah Kerja Nyata Indonesia menjadi lebih terstruktur, terukur, dan berkelanjutan untuk menciptakan dampak nyata bagi masyarakat.
                        </p>
                    </div>
                    
                    {{-- misi --}}
                    <div class="animate-fade-in" style="animation-delay: 0.1s;">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h2 class="text-3xl font-bold text-gray-900">Misi</h2>
                        </div>
                        <ul class="space-y-3">
                            <li class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-green-600 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-gray-700">Menghubungkan mahasiswa dengan masalah nyata di pemerintahan daerah</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-green-600 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-gray-700">Membangun portofolio profesional terverifikasi untuk mahasiswa</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-green-600 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-gray-700">Menciptakan knowledge repository nasional dari hasil KKN</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- dampak & statistik --}}
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-6">
            <div class="max-w-6xl mx-auto text-center mb-12 animate-fade-in">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Dampak Platform</h2>
                <p class="text-xl text-gray-600">Transformasi KKN Indonesia melalui teknologi digital</p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                <div class="bg-white rounded-2xl p-8 text-center shadow-sm border border-gray-100 animate-fade-in" style="animation-delay: 0.1s;">
                    <div class="text-5xl font-bold text-blue-600 mb-3">520K+</div>
                    <div class="text-gray-600 font-medium">Mahasiswa Target</div>
                </div>
                <div class="bg-white rounded-2xl p-8 text-center shadow-sm border border-gray-100 animate-fade-in" style="animation-delay: 0.2s;">
                    <div class="text-5xl font-bold text-green-600 mb-3">83K+</div>
                    <div class="text-gray-600 font-medium">Desa Target</div>
                </div>
                <div class="bg-white rounded-2xl p-8 text-center shadow-sm border border-gray-100 animate-fade-in" style="animation-delay: 0.3s;">
                    <div class="text-5xl font-bold text-purple-600 mb-3">Rp 540M</div>
                    <div class="text-gray-600 font-medium">Penghematan Budget</div>
                </div>
            </div>
        </div>
    </section>

    {{-- fitur utama --}}
    <section class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-16 animate-fade-in">
                    <h2 class="text-4xl font-bold text-gray-900 mb-4">Fitur Utama</h2>
                    <p class="text-xl text-gray-600">Solusi komprehensif untuk ekosistem KKN yang lebih baik</p>
                </div>
                
                <div class="grid md:grid-cols-3 gap-8">
                    {{-- marketplace --}}
                    <div class="group hover:bg-blue-50 rounded-2xl p-8 transition-all animate-fade-in" style="animation-delay: 0.1s;">
                        <div class="w-16 h-16 bg-blue-100 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Marketplace Masalah</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Platform yang menghubungkan mahasiswa dengan masalah nyata yang dibutuhkan pemerintah daerah, meningkatkan relevansi program hingga 75%.
                        </p>
                    </div>
                    
                    {{-- portfolio --}}
                    <div class="group hover:bg-green-50 rounded-2xl p-8 transition-all animate-fade-in" style="animation-delay: 0.2s;">
                        <div class="w-16 h-16 bg-green-100 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Impact Portfolio</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Sistem validasi resmi dari pemerintah daerah yang menciptakan portofolio profesional terverifikasi untuk meningkatkan daya saing mahasiswa.
                        </p>
                    </div>
                    
                    {{-- repository --}}
                    <div class="group hover:bg-purple-50 rounded-2xl p-8 transition-all animate-fade-in" style="animation-delay: 0.3s;">
                        <div class="w-16 h-16 bg-purple-100 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Knowledge Repository</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Perpustakaan digital nasional yang mengubah hasil KKN menjadi sumber pembelajaran kolektif yang dapat diakses oleh seluruh masyarakat Indonesia.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- how it works - timeline style (PALING BAWAH SEBELUM FOOTER) --}}
    <section class="py-24 bg-gradient-to-br from-slate-50 to-blue-50">
        <div class="container mx-auto px-6">
            <div class="max-w-7xl mx-auto">
                {{-- header --}}
                <div class="mb-16 animate-fade-in">
                    <h2 class="text-4xl md:text-5xl font-bold text-blue-600 mb-4">
                        Keberhasilan Ini Adalah Hasil Sinergi Kita Bersama
                    </h2>
                    <p class="text-xl text-gray-700 leading-relaxed max-w-3xl">
                        Transformasi ekosistem KKN Indonesia melalui platform digital yang menghubungkan mahasiswa, instansi, dan masyarakat
                    </p>
                </div>

                {{-- navigation controls --}}
                <div class="flex items-center justify-between mb-8">
                    {{-- year selector --}}
                    <div class="flex gap-3 overflow-x-auto pb-2">
                        <button onclick="scrollToYear(2020)" class="year-selector px-6 py-2.5 rounded-lg font-semibold whitespace-nowrap text-sm">
                            2020
                        </button>
                        <button onclick="scrollToYear(2021)" class="year-selector active px-6 py-2.5 rounded-lg font-semibold whitespace-nowrap text-sm">
                            Tahun 2021
                        </button>
                        <button onclick="scrollToYear(2022)" class="year-selector px-6 py-2.5 rounded-lg font-semibold whitespace-nowrap text-sm">
                            2022
                        </button>
                        <button onclick="scrollToYear(2023)" class="year-selector px-6 py-2.5 rounded-lg font-semibold whitespace-nowrap text-sm">
                            2023
                        </button>
                        <button onclick="scrollToYear(2024)" class="year-selector px-6 py-2.5 rounded-lg font-semibold whitespace-nowrap text-sm">
                            2024
                        </button>
                    </div>
                    
                    {{-- navigation arrows --}}
                    <div class="flex gap-3 ml-auto">
                        <button onclick="scrollTimeline('left')" class="nav-arrow">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>
                        <button onclick="scrollTimeline('right')" class="nav-arrow">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- timeline horizontal --}}
                <div class="timeline-container" id="timelineScroll">
                    <div class="flex gap-0 min-w-max pb-8 relative" style="padding-top: 60px;">
                        {{-- timeline line --}}
                        <div class="timeline-line"></div>
                        
                        {{-- item 1 --}}
                        <div class="flex-shrink-0" style="width: 400px;" data-year="2020">
                            <div class="flex flex-col items-center">
                                {{-- label atas --}}
                                <div class="text-sm text-gray-500 mb-3 text-center">Jaklapor Privacy By Default</div>
                                
                                {{-- dot --}}
                                <div class="timeline-dot mb-8"></div>
                                
                                {{-- konten bawah --}}
                                <div class="text-center px-4">
                                    <h3 class="text-2xl font-bold text-gray-900 mb-3">
                                        Indonesia Entrepreneur TIK 2021
                                    </h3>
                                    <p class="text-sm text-gray-600 mb-2">
                                        <span class="font-semibold">Capaian:</span>
                                    </p>
                                    <p class="text-sm text-gray-700 mb-3">
                                        Runner Up Identik 2021 Bidang Public Sector
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        <span class="font-semibold">Tingkat:</span> Internasional
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        {{-- item 2 --}}
                        <div class="flex-shrink-0" style="width: 400px;" data-year="2021">
                            <div class="flex flex-col items-center">
                                <div class="text-sm text-gray-500 mb-3 text-center">Jakwifi</div>
                                <div class="timeline-dot mb-8"></div>
                                <div class="text-center px-4">
                                    <h3 class="text-2xl font-bold text-gray-900 mb-3">
                                        WSIS Prizes
                                    </h3>
                                    <p class="text-sm text-gray-600 mb-2">
                                        <span class="font-semibold">Capaian:</span>
                                    </p>
                                    <p class="text-sm text-gray-700 mb-3">
                                        Nominate Category Information Communication Infrastructure World Summit of Information Society 2021
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        <span class="font-semibold">Tingkat:</span> Internasional
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        {{-- item 3 --}}
                        <div class="flex-shrink-0" style="width: 400px;" data-year="2021">
                            <div class="flex flex-col items-center">
                                <div class="text-sm text-gray-500 mb-3 text-center">JAKI</div>
                                <div class="timeline-dot mb-8"></div>
                                <div class="text-center px-4">
                                    <h3 class="text-2xl font-bold text-gray-900 mb-3">
                                        WSIS Prizes 2021
                                    </h3>
                                    <p class="text-sm text-gray-600 mb-2">
                                        <span class="font-semibold">Capaian:</span>
                                    </p>
                                    <p class="text-sm text-gray-700 mb-3">
                                        Runner Up WSIS Prizes 2021 Bidang E-Government
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        <span class="font-semibold">Tingkat:</span> Internasional
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        {{-- item 4 --}}
                        <div class="flex-shrink-0" style="width: 400px;" data-year="2022">
                            <div class="flex flex-col items-center">
                                <div class="text-sm text-gray-500 mb-3 text-center">JAKI</div>
                                <div class="timeline-dot mb-8"></div>
                                <div class="text-center px-4">
                                    <h3 class="text-2xl font-bold text-gray-900 mb-3">
                                        Digital Innovation Awards 2022
                                    </h3>
                                    <p class="text-sm text-gray-600 mb-2">
                                        <span class="font-semibold">Capaian:</span>
                                    </p>
                                    <p class="text-sm text-gray-700 mb-3">
                                        Digital Innovation For Society Impact Reduction & Resilience
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        <span class="font-semibold">Tingkat:</span> Nasional
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        {{-- item 5 --}}
                        <div class="flex-shrink-0" style="width: 400px;" data-year="2023">
                            <div class="flex flex-col items-center">
                                <div class="text-sm text-gray-500 mb-3 text-center">Jakarta Smart City</div>
                                <div class="timeline-dot mb-8"></div>
                                <div class="text-center px-4">
                                    <h3 class="text-2xl font-bold text-gray-900 mb-3">
                                        Best Smart City ASEAN 2023
                                    </h3>
                                    <p class="text-sm text-gray-600 mb-2">
                                        <span class="font-semibold">Capaian:</span>
                                    </p>
                                    <p class="text-sm text-gray-700 mb-3">
                                        Penghargaan Smart City Terbaik Se-ASEAN
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        <span class="font-semibold">Tingkat:</span> Regional
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        {{-- item 6 --}}
                        <div class="flex-shrink-0" style="width: 400px;" data-year="2024">
                            <div class="flex flex-col items-center">
                                <div class="text-sm text-gray-500 mb-3 text-center">Platform KKN-Go</div>
                                <div class="timeline-dot mb-8"></div>
                                <div class="text-center px-4">
                                    <h3 class="text-2xl font-bold text-gray-900 mb-3">
                                        Peluncuran Platform KKN-Go
                                    </h3>
                                    <p class="text-sm text-gray-600 mb-2">
                                        <span class="font-semibold">Capaian:</span>
                                    </p>
                                    <p class="text-sm text-gray-700 mb-3">
                                        Platform Digital untuk Transformasi Ekosistem KKN Indonesia
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        <span class="font-semibold">Tingkat:</span> Nasional
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- team section --}}
    <section class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-16 animate-fade-in">
                    <h2 class="text-4xl font-bold text-gray-900 mb-4">Tim Pengembang</h2>
                    <p class="text-xl text-gray-600">Inovator di balik platform KKN-Go</p>
                </div>
                
                <div class="grid md:grid-cols-3 gap-8">
                    <div class="text-center group animate-fade-in" style="animation-delay: 0.1s;">
                        <div class="w-32 h-32 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full mx-auto mb-4 flex items-center justify-center text-white text-4xl font-bold group-hover:scale-110 transition-transform">
                            DA
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-1">Danendra Shafi Athallah</h3>
                        <p class="text-blue-600 font-medium mb-2">Full Stack Developer</p>
                        <p class="text-gray-600 text-sm">Institut Teknologi Bandung</p>
                    </div>
                    
                    <div class="text-center group animate-fade-in" style="animation-delay: 0.2s;">
                        <div class="w-32 h-32 bg-gradient-to-br from-green-500 to-teal-600 rounded-full mx-auto mb-4 flex items-center justify-center text-white text-4xl font-bold group-hover:scale-110 transition-transform">
                            KR
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-1">Kenzie Raffa Ardhana</h3>
                        <p class="text-green-600 font-medium mb-2">Backend Developer</p>
                        <p class="text-gray-600 text-sm">Institut Teknologi Bandung</p>
                    </div>
                    
                    <div class="text-center group animate-fade-in" style="animation-delay: 0.3s;">
                        <div class="w-32 h-32 bg-gradient-to-br from-orange-500 to-red-600 rounded-full mx-auto mb-4 flex items-center justify-center text-white text-4xl font-bold group-hover:scale-110 transition-transform">
                            MA
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-1">M. Abizzar Gamadrian</h3>
                        <p class="text-orange-600 font-medium mb-2">Frontend Developer</p>
                        <p class="text-gray-600 text-sm">Institut Teknologi Bandung</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>
@endsection

@push('scripts')
<script>
    // scroll timeline horizontal
    function scrollTimeline(direction) {
        const container = document.getElementById('timelineScroll');
        const scrollAmount = 400;
        
        if (direction === 'left') {
            container.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
        } else {
            container.scrollBy({ left: scrollAmount, behavior: 'smooth' });
        }
    }
    
    // scroll ke tahun tertentu
    function scrollToYear(year) {
        const container = document.getElementById('timelineScroll');
        const items = document.querySelectorAll('[data-year]');
        
        // update active state
        document.querySelectorAll('.year-selector').forEach(btn => {
            btn.classList.remove('active');
        });
        event.target.classList.add('active');
        
        // cari item dengan tahun yang sesuai
        items.forEach((item, index) => {
            if (parseInt(item.dataset.year) === year) {
                const scrollPosition = item.offsetLeft - 50;
                container.scrollTo({ left: scrollPosition, behavior: 'smooth' });
            }
        });
    }
    
    // auto-update active year saat scroll
    const timelineScroll = document.getElementById('timelineScroll');
    timelineScroll.addEventListener('scroll', () => {
        const items = document.querySelectorAll('[data-year]');
        const scrollLeft = timelineScroll.scrollLeft;
        
        items.forEach((item, index) => {
            const itemLeft = item.offsetLeft;
            const itemWidth = item.offsetWidth;
            
            if (scrollLeft >= itemLeft - 200 && scrollLeft < itemLeft + itemWidth - 200) {
                const year = parseInt(item.dataset.year);
                const activeBtn = document.querySelector(`.year-selector[onclick="scrollToYear(${year})"]`);
                
                if (activeBtn && !activeBtn.classList.contains('active')) {
                    document.querySelectorAll('.year-selector').forEach(btn => {
                        btn.classList.remove('active');
                    });
                    activeBtn.classList.add('active');
                }
            }
        });
    });
</script>
@endpush