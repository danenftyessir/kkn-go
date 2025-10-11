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

    {{-- temporary content untuk testing scroll --}}
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <h2 class="text-4xl font-bold text-gray-900 mb-6">KKN-Go</h2>
                <p class="text-lg text-gray-600 leading-relaxed mb-4">
                    Platform digital yang mentransformasi Kuliah Kerja Nyata dari sekadar kewajiban akademik menjadi katalisator pembangunan berbasis data di Indonesia.
                </p>
                <p class="text-lg text-gray-600 leading-relaxed">
                    Revolusi Mahasiswa – Mengubah KKN Menjadi Solusi Nyata Bangsa.
                </p>
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