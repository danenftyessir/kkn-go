@extends('layouts.app')

@section('title', 'Tentang Kami - KKN-Go')

@section('content')
<div class="min-h-screen bg-white">
    
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

    {{-- layer 4: latar belakang masalah - light gray background --}}
    <section class="py-24 bg-gray-50">
        <div class="container mx-auto px-6">
            {{-- header dengan logo --}}
            <div class="text-center mb-16">
                <div class="flex justify-center mb-8">
                    <img src="{{ asset('kkn-go-logo.png') }}" alt="Logo KKN-Go" class="h-20 w-20">
                </div>
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6 leading-tight">
                    Latar Belakang Masalah
                </h2>
                <div class="max-w-4xl mx-auto">
                    <p class="text-lg text-gray-700 leading-relaxed mb-4">
                        Indonesia memiliki potensi intelektual yang luar biasa dengan <span class="font-bold text-blue-600">8,3 juta mahasiswa aktif</span> di 4.500 perguruan tinggi, dimana sekitar <span class="font-bold text-blue-600">520.000 mahasiswa melaksanakan KKN</span> setiap tahun menghasilkan lebih dari 100.000 laporan penelitian.
                    </p>
                    <p class="text-lg text-gray-700 leading-relaxed">
                        Namun, data menunjukkan bahwa <span class="font-bold text-red-600">76% hasil penelitian mahasiswa hanya berakhir sebagai dokumen arsip</span> tanpa implementasi nyata, menciptakan pemborosan sumber daya senilai <span class="font-bold text-red-600">±Rp 1,2 triliun per tahun</span>.
                    </p>
                </div>
            </div>

            {{-- 3 masalah krusial --}}
            <div class="max-w-6xl mx-auto">
                <div class="grid md:grid-cols-3 gap-8">
                    {{-- masalah 1 --}}
                    <div class="bg-white rounded-lg p-8 shadow-lg hover:shadow-xl transition-shadow">
                        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mb-6">
                            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Ketidakselarasan Program</h3>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            Mahasiswa menghabiskan waktu mencari masalah yang sering kali tidak tepat sasaran dengan kebutuhan prioritas daerah.
                        </p>
                        <p class="text-sm text-gray-500 italic">
                            "Setiap tahun kami memiliki daftar masalah prioritas, tapi mahasiswa KKN justru mencari masalah baru yang tidak tepat sasaran." - Kepala Desa Karanganyar
                        </p>
                    </div>

                    {{-- masalah 2 --}}
                    <div class="bg-white rounded-lg p-8 shadow-lg hover:shadow-xl transition-shadow">
                        <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mb-6">
                            <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Tidak Ada Pengakuan Resmi</h3>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            Tidak ada mekanisme untuk mengevaluasi dan memberikan pengakuan resmi terhadap hasil KKN berkualitas.
                        </p>
                        <p class="text-sm text-gray-500 italic">
                            "Kami tidak memiliki sistem untuk memberikan pengakuan resmi, padahal ini bisa menjadi portofolio berharga bagi mahasiswa." - Kepala Bidang Pengembangan Desa
                        </p>
                    </div>

                    {{-- masalah 3 --}}
                    <div class="bg-white rounded-lg p-8 shadow-lg hover:shadow-xl transition-shadow">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-6">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Pemborosan Pengetahuan</h3>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            Indonesia kehilangan harta karun pengetahuan karena tidak ada sistem yang mengintegrasikan hasil KKN secara nasional.
                        </p>
                        <p class="text-sm text-gray-500 italic">
                            "Bayangkan jika seluruh warga negara bisa mengakses ribuan solusi inovatif yang dihasilkan mahasiswa setiap tahun." - Prof. Widodo, Universitas Indonesia
                        </p>
                    </div>
                </div>

                {{-- statistik tambahan --}}
                <div class="mt-16 grid md:grid-cols-2 gap-8">
                    <div class="bg-white rounded-lg p-8 border-l-4 border-blue-600">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <p class="text-gray-600 leading-relaxed">
                                    Dari <span class="font-bold text-gray-900">83.436 desa/kelurahan</span> di Indonesia, hanya <span class="font-bold text-blue-600">23%</span> yang telah menerapkan digitalisasi administrasi.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg p-8 border-l-4 border-red-600">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <p class="text-gray-600 leading-relaxed">
                                    <span class="font-bold text-red-600">72%</span> pemerintah desa mengalami kesulitan mengakses data dan riset untuk pengambilan keputusan berbasis bukti.
                                </p>
                            </div>
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

<style>
/* smooth scrolling */
html {
    scroll-behavior: smooth;
}

/* optimized rendering */
* {
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

/* image optimization */
img {
    -webkit-transform: translateZ(0);
    transform: translateZ(0);
}

/* reduced motion support */
@media (prefers-reduced-motion: reduce) {
    html {
        scroll-behavior: auto;
    }
    
    * {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}
</style>
@endsection