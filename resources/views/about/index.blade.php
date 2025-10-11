@extends('layouts.app')

@section('title', 'Tentang Kami - KKN-Go')

@section('content')
<div class="min-h-screen bg-white">
    
    {{-- hero section dengan background image --}}
    <section class="relative h-[600px] overflow-hidden">
        {{-- background image dengan overlay --}}
        <div class="absolute inset-0">
            <img src="{{ asset('desa-about.jpeg') }}" alt="Desa Indonesia" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-900/90 via-blue-800/80 to-transparent"></div>
        </div>
        
        {{-- hero content --}}
        <div class="relative h-full container mx-auto px-4">
            <div class="h-full flex items-center">
                <div class="max-w-3xl animate-fade-in-up">
                    {{-- logo --}}
                    <div class="flex items-center gap-4 mb-6">
                        <img src="{{ asset('kkn-go-logo.png') }}" alt="KKN-GO Logo" class="h-20 w-20">
                        <div>
                            <h1 class="text-5xl md:text-6xl font-black text-white leading-tight">
                                KKN-Go
                            </h1>
                            <div class="h-1 w-32 bg-yellow-400 mt-2"></div>
                        </div>
                    </div>
                    
                    <p class="text-2xl md:text-3xl font-bold text-white mb-4">
                        Revolusi Mahasiswa – Mengubah KKN Menjadi Solusi Nyata Bangsa
                    </p>
                    <p class="text-lg text-blue-100 leading-relaxed mb-8">
                        Platform digital yang mentransformasi Kuliah Kerja Nyata dari sekadar kewajiban akademik menjadi katalisator pembangunan berbasis data di Indonesia
                    </p>
                    
                    {{-- cta buttons --}}
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('register.student') }}" class="px-8 py-4 bg-yellow-400 text-gray-900 rounded-xl font-bold hover:bg-yellow-300 transition-all duration-300 hover-scale text-center">
                            Daftar Sebagai Mahasiswa
                        </a>
                        <a href="{{ route('register.institution') }}" class="px-8 py-4 bg-white/20 backdrop-blur-sm text-white rounded-xl font-bold hover:bg-white/30 transition-all duration-300 hover-scale border-2 border-white text-center">
                            Daftar Sebagai Instansi
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- scroll indicator --}}
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
            </svg>
        </div>
    </section>

    {{-- statistik section --}}
    <section class="py-20 bg-gradient-to-br from-blue-600 to-indigo-700">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 max-w-6xl mx-auto">
                <div class="text-center stats-card">
                    <div class="text-4xl md:text-5xl font-black text-white mb-2">{{ $statistics['target_students'] }}</div>
                    <div class="text-blue-100 font-medium">Mahasiswa KKN Per Tahun</div>
                </div>
                <div class="text-center stats-card">
                    <div class="text-4xl md:text-5xl font-black text-white mb-2">{{ $statistics['target_villages'] }}</div>
                    <div class="text-blue-100 font-medium">Desa/Kelurahan Di Indonesia</div>
                </div>
                <div class="text-center stats-card">
                    <div class="text-4xl md:text-5xl font-black text-white mb-2">{{ $statistics['budget_savings'] }}</div>
                    <div class="text-blue-100 font-medium">Potensi Penghematan Tahunan</div>
                </div>
                <div class="text-center stats-card">
                    <div class="text-4xl md:text-5xl font-black text-white mb-2">{{ $statistics['repository_target'] }}</div>
                    <div class="text-blue-100 font-medium">Target Laporan Terstruktur</div>
                </div>
            </div>
        </div>
    </section>

    {{-- latar belakang section dengan image --}}
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-16 animate-fade-in-up">
                    <h2 class="text-4xl md:text-5xl font-black text-gray-900 mb-4">Latar Belakang</h2>
                    <div class="w-24 h-1 bg-blue-600 mx-auto rounded-full mb-6"></div>
                    <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                        Mengidentifikasi tantangan dalam ekosistem KKN Indonesia dan solusi yang kami tawarkan
                    </p>
                </div>
                
                <div class="grid md:grid-cols-3 gap-8">
                    <div class="bg-gradient-to-br from-red-50 to-red-100 p-8 rounded-2xl shadow-lg hover-scale">
                        <div class="w-16 h-16 bg-red-500 rounded-full flex items-center justify-center mb-6 mx-auto">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-4 text-center">Pemborosan Potensi</h3>
                        <p class="text-gray-700 text-center leading-relaxed">
                            <span class="font-semibold text-red-600">76%</span> hasil penelitian mahasiswa hanya berakhir sebagai dokumen arsip tanpa implementasi nyata, menciptakan pemborosan <span class="font-semibold">±Rp 1,2 Triliun per tahun</span>
                        </p>
                    </div>
                    
                    <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 p-8 rounded-2xl shadow-lg hover-scale">
                        <div class="w-16 h-16 bg-yellow-500 rounded-full flex items-center justify-center mb-6 mx-auto">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-4 text-center">Kesenjangan Data</h3>
                        <p class="text-gray-700 text-center leading-relaxed">
                            <span class="font-semibold text-yellow-600">72%</span> pemerintah desa kesulitan mengakses data dan riset untuk pengambilan keputusan berbasis bukti
                        </p>
                    </div>
                    
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-8 rounded-2xl shadow-lg hover-scale">
                        <div class="w-16 h-16 bg-blue-500 rounded-full flex items-center justify-center mb-6 mx-auto">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-4 text-center">Ketidakselarasan Program</h3>
                        <p class="text-gray-700 text-center leading-relaxed">
                            Mahasiswa menghabiskan waktu mencari masalah yang sering tidak tepat sasaran, tanpa pengakuan resmi atas kontribusi mereka
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- aktivitas mahasiswa section dengan real images --}}
    <section class="py-20 bg-gradient-to-br from-gray-50 to-blue-50">
        <div class="container mx-auto px-4">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-16 animate-fade-in-up">
                    <h2 class="text-4xl md:text-5xl font-black text-gray-900 mb-4">Aktivitas KKN Mahasiswa</h2>
                    <div class="w-24 h-1 bg-blue-600 mx-auto rounded-full mb-6"></div>
                    <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                        Dokumentasi nyata mahasiswa yang berkontribusi untuk pembangunan desa
                    </p>
                </div>

                <div class="grid md:grid-cols-2 gap-8 mb-12">
                    {{-- image 1: handprints --}}
                    <div class="group relative overflow-hidden rounded-2xl shadow-xl hover-scale">
                        <img src="{{ asset('handprints-about.jpeg') }}" alt="Aktivitas Mahasiswa KKN" class="w-full h-[400px] object-cover transition-transform duration-500 group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
                                <h3 class="text-2xl font-bold mb-2">Kreativitas & Inovasi</h3>
                                <p class="text-sm text-gray-200">Mahasiswa mengembangkan program kreatif untuk pemberdayaan masyarakat</p>
                            </div>
                        </div>
                    </div>

                    {{-- image 2: mahasiswa di truk --}}
                    <div class="group relative overflow-hidden rounded-2xl shadow-xl hover-scale">
                        <img src="{{ asset('mahasiswa-about.jpeg') }}" alt="Mahasiswa KKN ITB" class="w-full h-[400px] object-cover transition-transform duration-500 group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
                                <h3 class="text-2xl font-bold mb-2">Perjalanan Ke Desa</h3>
                                <p class="text-sm text-gray-200">Tim mahasiswa siap terjun langsung ke lokasi KKN untuk memberikan kontribusi nyata</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- jaket detail dengan info --}}
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                    <div class="grid md:grid-cols-2 gap-0">
                        <div class="relative h-[400px] md:h-auto">
                            <img src="{{ asset('jaket-about.jpeg') }}" alt="Jaket KKN ITB 2025" class="w-full h-full object-cover">
                        </div>
                        <div class="p-8 md:p-12 flex items-center">
                            <div>
                                <div class="inline-block px-4 py-2 bg-blue-100 text-blue-800 rounded-full text-sm font-bold mb-4">
                                    Program KKN 2025
                                </div>
                                <h3 class="text-3xl font-black text-gray-900 mb-4">
                                    Kebanggaan Mahasiswa Indonesia
                                </h3>
                                <p class="text-gray-600 leading-relaxed mb-6">
                                    Setiap mahasiswa yang terlibat dalam program KKN adalah bagian dari gerakan besar untuk membangun Indonesia. Dengan semangat kolaborasi dan inovasi, mereka menghadirkan solusi nyata untuk permasalahan di tingkat desa.
                                </p>
                                <div class="space-y-3">
                                    <div class="flex items-center gap-3">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="text-gray-700 font-medium">Program Terstruktur dan Terukur</span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="text-gray-700 font-medium">Kolaborasi Lintas Disiplin Ilmu</span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="text-gray-700 font-medium">Dampak Berkelanjutan untuk Masyarakat</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- visi misi section --}}
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                <div class="grid md:grid-cols-2 gap-12">
                    <div class="animate-fade-in-left">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-700 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </div>
                            <h2 class="text-3xl md:text-4xl font-black text-gray-900">Visi</h2>
                        </div>
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-8 rounded-2xl">
                            <p class="text-lg text-gray-700 leading-relaxed">
                                Menjadi platform digital terdepan yang mentransformasi program Kuliah Kerja Nyata menjadi gerakan nasional pembangunan berkelanjutan berbasis data, menghubungkan potensi intelektual mahasiswa dengan kebutuhan nyata pembangunan daerah.
                            </p>
                        </div>
                    </div>
                    
                    <div class="animate-fade-in-right">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-green-700 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                </svg>
                            </div>
                            <h2 class="text-3xl md:text-4xl font-black text-gray-900">Misi</h2>
                        </div>
                        <div class="bg-gradient-to-br from-green-50 to-emerald-50 p-8 rounded-2xl">
                            <ul class="space-y-4">
                                <li class="flex items-start gap-3">
                                    <div class="w-6 h-6 bg-green-600 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <span class="text-gray-700">Menciptakan jembatan digital antara mahasiswa KKN dengan kebutuhan nyata pemerintah daerah</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <div class="w-6 h-6 bg-green-600 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <span class="text-gray-700">Membangun basis pengetahuan nasional dari hasil KKN yang tervalidasi</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <div class="w-6 h-6 bg-green-600 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <span class="text-gray-700">Memfasilitasi pengembangan portofolio profesional mahasiswa melalui validasi resmi</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <div class="w-6 h-6 bg-green-600 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <span class="text-gray-700">Mendukung pencapaian target SDGs melalui kolaborasi akademisi dan pemerintah</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- fitur utama section --}}
    <section class="py-20 bg-gradient-to-br from-blue-50 to-indigo-50">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-16 animate-fade-in-up">
                    <h2 class="text-4xl md:text-5xl font-black text-gray-900 mb-4">Fitur Utama Platform</h2>
                    <div class="w-24 h-1 bg-blue-600 mx-auto rounded-full mb-6"></div>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                        Tiga pilar utama yang mengubah ekosistem KKN Indonesia
                    </p>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    @foreach($features as $index => $feature)
                    <div class="bg-white p-8 rounded-2xl shadow-xl hover-scale feature-card" style="animation-delay: {{ $index * 0.2 }}s">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center mb-6 shadow-lg">
                            @if($feature['icon'] == 'search')
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            @elseif($feature['icon'] == 'award')
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                            </svg>
                            @else
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            @endif
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">{{ $feature['title'] }}</h3>
                        <p class="text-gray-600 leading-relaxed">{{ $feature['description'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- sdgs section --}}
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-16 animate-fade-in-up">
                    <h2 class="text-4xl md:text-5xl font-black text-gray-900 mb-4">Dukungan Sustainable Development Goals</h2>
                    <div class="w-24 h-1 bg-blue-600 mx-auto rounded-full mb-6"></div>
                    <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                        KKN-Go berkontribusi langsung terhadap pencapaian target SDGs di tingkat lokal dan nasional
                    </p>
                </div>

                <div class="grid md:grid-cols-2 gap-8">
                    @foreach($sdgs as $sdg)
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-8 rounded-2xl hover-scale sdg-card border-2 border-blue-100">
                        <div class="flex items-start gap-6">
                            <div class="w-20 h-20 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg">
                                <span class="text-3xl font-black text-white">{{ $sdg['number'] }}</span>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-3">{{ $sdg['title'] }}</h3>
                                <p class="text-gray-600 leading-relaxed">{{ $sdg['description'] }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- manfaat section --}}
    <section class="py-20 bg-gradient-to-br from-gray-50 to-blue-50">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-16 animate-fade-in-up">
                    <h2 class="text-4xl md:text-5xl font-black text-gray-900 mb-4">Manfaat Platform</h2>
                    <div class="w-24 h-1 bg-blue-600 mx-auto rounded-full mb-6"></div>
                    <p class="text-lg text-gray-600">Dampak nyata untuk semua pemangku kepentingan</p>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    {{-- manfaat mahasiswa --}}
                    <div class="bg-white p-8 rounded-2xl shadow-lg hover-scale border-t-4 border-blue-600">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-700 rounded-full flex items-center justify-center mb-6 mx-auto shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-6 text-center">Bagi Mahasiswa</h3>
                        <ul class="space-y-4 text-gray-600">
                            <li class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Portofolio tervalidasi resmi</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Proyek dengan dampak terukur</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Efisiensi waktu KKN hingga 30%</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Pengalaman kolaborasi nyata</span>
                            </li>
                        </ul>
                    </div>

                    {{-- manfaat pemerintah --}}
                    <div class="bg-white p-8 rounded-2xl shadow-lg hover-scale border-t-4 border-green-600">
                        <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-700 rounded-full flex items-center justify-center mb-6 mx-auto shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-6 text-center">Bagi Pemerintah Daerah</h3>
                        <ul class="space-y-4 text-gray-600">
                            <li class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Akses sumber daya intelektual</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Data berbasis bukti untuk keputusan</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Platform publikasi transparan</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Efisiensi anggaran pembangunan</span>
                            </li>
                        </ul>
                    </div>

                    {{-- manfaat masyarakat --}}
                    <div class="bg-white p-8 rounded-2xl shadow-lg hover-scale border-t-4 border-purple-600">
                        <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-700 rounded-full flex items-center justify-center mb-6 mx-auto shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-6 text-center">Bagi Masyarakat</h3>
                        <ul class="space-y-4 text-gray-600">
                            <li class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-purple-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Pembangunan lebih terarah</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-purple-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Transparansi kontribusi mahasiswa</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-purple-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Basis pengetahuan nasional</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-purple-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Percepatan pencapaian SDGs</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- tim section --}}
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="max-w-5xl mx-auto">
                <div class="text-center mb-16 animate-fade-in-up">
                    <h2 class="text-4xl md:text-5xl font-black text-gray-900 mb-4">Tim Pengembang</h2>
                    <div class="w-24 h-1 bg-blue-600 mx-auto rounded-full mb-6"></div>
                    <p class="text-lg text-gray-600">Tim AnakSoleh - Institut Teknologi Bandung</p>
                </div>

                <div class="grid md:grid-cols-3 gap-8 mb-12">
                    @foreach($team as $member)
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-8 rounded-2xl text-center hover-scale team-card border-2 border-blue-100">
                        <div class="w-24 h-24 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-full mx-auto mb-6 flex items-center justify-center shadow-lg">
                            <span class="text-4xl font-black text-white">{{ substr($member['name'], 0, 1) }}</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $member['name'] }}</h3>
                        <p class="text-blue-600 font-semibold mb-3">{{ $member['role'] }}</p>
                        <p class="text-sm text-gray-600">{{ $member['institution'] }}</p>
                    </div>
                    @endforeach
                </div>

                <div class="bg-gradient-to-r from-blue-600 to-indigo-700 p-10 rounded-2xl text-center text-white shadow-2xl">
                    <div class="flex justify-center mb-6">
                        <img src="{{ asset('kkn-go-logo.png') }}" alt="Logo KKN-GO" class="h-16 w-16">
                    </div>
                    <h3 class="text-2xl md:text-3xl font-bold mb-4">Sekolah Teknik Elektro Dan Informatika</h3>
                    <p class="text-xl mb-3 font-semibold">Institut Teknologi Bandung</p>
                    <p class="text-blue-100 text-lg">Jl. Ganesha 10, Bandung 40132</p>
                </div>
            </div>
        </div>
    </section>

    {{-- call to action final --}}
    <section class="relative py-24 overflow-hidden">
        {{-- background dengan overlay --}}
        <div class="absolute inset-0">
            <img src="{{ asset('desa-about.jpeg') }}" alt="Desa Indonesia" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-900/95 to-indigo-900/90"></div>
        </div>
        
        <div class="relative container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-4xl md:text-5xl font-black text-white mb-6">
                    Mari Bersama Membangun Indonesia
                </h2>
                <p class="text-xl text-blue-100 mb-10 leading-relaxed">
                    Bergabunglah dengan gerakan revolusi KKN yang mengubah potensi intelektual mahasiswa menjadi solusi nyata untuk pembangunan bangsa
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('register.student') }}" class="px-8 py-4 bg-yellow-400 text-gray-900 rounded-xl font-bold hover:bg-yellow-300 transition-all duration-300 hover-scale shadow-xl">
                        Daftar Sebagai Mahasiswa
                    </a>
                    <a href="{{ route('register.institution') }}" class="px-8 py-4 bg-white/20 backdrop-blur-sm text-white rounded-xl font-bold hover:bg-white/30 transition-all duration-300 hover-scale border-2 border-white shadow-xl">
                        Daftar Sebagai Instansi
                    </a>
                </div>
            </div>
        </div>
    </section>

</div>

<style>
/* animations */
@keyframes fade-in-up {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fade-in-left {
    from {
        opacity: 0;
        transform: translateX(-30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes fade-in-right {
    from {
        opacity: 0;
        transform: translateX(30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.animate-fade-in-up {
    animation: fade-in-up 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

.animate-fade-in-left {
    animation: fade-in-left 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

.animate-fade-in-right {
    animation: fade-in-right 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

/* hover effects dengan gpu acceleration */
.hover-scale {
    transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    will-change: transform;
}

.hover-scale:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

/* staggered animations untuk cards */
.stats-card {
    animation: fade-in-up 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    opacity: 0;
}

.stats-card:nth-child(1) { animation-delay: 0.1s; }
.stats-card:nth-child(2) { animation-delay: 0.2s; }
.stats-card:nth-child(3) { animation-delay: 0.3s; }
.stats-card:nth-child(4) { animation-delay: 0.4s; }

.feature-card {
    animation: fade-in-up 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    opacity: 0;
}

.sdg-card {
    animation: fade-in-up 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    opacity: 0;
}

.sdg-card:nth-child(1) { animation-delay: 0.1s; }
.sdg-card:nth-child(2) { animation-delay: 0.2s; }
.sdg-card:nth-child(3) { animation-delay: 0.3s; }
.sdg-card:nth-child(4) { animation-delay: 0.4s; }

.team-card {
    animation: fade-in-up 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    opacity: 0;
}

.team-card:nth-child(1) { animation-delay: 0.1s; }
.team-card:nth-child(2) { animation-delay: 0.2s; }
.team-card:nth-child(3) { animation-delay: 0.3s; }

/* smooth scrolling dengan gpu acceleration */
html {
    scroll-behavior: smooth;
}

* {
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

/* image transitions */
img {
    transition: transform 0.5s cubic-bezier(0.16, 1, 0.3, 1);
    will-change: transform;
}

/* reduced motion support untuk accessibility */
@media (prefers-reduced-motion: reduce) {
    * {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
    
    html {
        scroll-behavior: auto;
    }
    
    .hover-scale:hover {
        transform: none;
    }
}

/* responsive tweaks */
@media (max-width: 768px) {
    .hover-scale:hover {
        transform: translateY(-4px) scale(1.01);
    }
}
</style>
@endsection