@extends('layouts.app')

@section('title', 'Kontak Kami - ' . config('app.name'))

@push('styles')
<style>
    /* smooth scroll behavior */
    html {
        scroll-behavior: smooth;
    }

    /* hero section dengan parallax effect */
    .hero-section {
        position: relative;
        height: 60vh;
        min-height: 500px;
        background-image: url('{{ asset('contact.jpg') }}');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        overflow: hidden;
    }

    .hero-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(37, 99, 235, 0.85) 0%, rgba(16, 185, 129, 0.75) 100%);
        backdrop-filter: blur(2px);
    }

    .hero-content {
        position: relative;
        z-index: 10;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        padding: 2rem;
        animation: fadeInUp 1s cubic-bezier(0.4, 0, 0.2, 1);
    }

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

    /* team member cards */
    .team-card {
        background: white;
        border-radius: 1rem;
        padding: 2rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        transform: translateZ(0);
        will-change: transform, box-shadow;
    }

    .team-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.15), 0 10px 10px -5px rgba(0, 0, 0, 0.1);
    }

    .team-photo {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
        border: 5px solid #E5E7EB;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .team-card:hover .team-photo {
        border-color: #3B82F6;
        transform: scale(1.05);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.2);
    }

    /* contact buttons */
    .contact-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        font-weight: 600;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none;
        transform: translateZ(0);
    }

    .contact-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.15);
    }

    .btn-email {
        background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%);
        color: white;
    }

    .btn-email:hover {
        background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%);
    }

    .btn-whatsapp {
        background: linear-gradient(135deg, #10B981 0%, #059669 100%);
        color: white;
    }

    .btn-whatsapp:hover {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
    }

    /* stagger animation untuk team cards */
    .team-card {
        opacity: 0;
        animation: fadeInUp 0.6s cubic-bezier(0.4, 0, 0.2, 1) forwards;
    }

    .team-card:nth-child(1) {
        animation-delay: 0.1s;
    }

    .team-card:nth-child(2) {
        animation-delay: 0.2s;
    }

    .team-card:nth-child(3) {
        animation-delay: 0.3s;
    }

    /* section header animation */
    .section-header {
        opacity: 0;
        animation: fadeInUp 0.8s cubic-bezier(0.4, 0, 0.2, 1) 0.2s forwards;
    }

    /* accessibility - reduced motion */
    @media (prefers-reduced-motion: reduce) {
        *,
        *::before,
        *::after {
            animation-duration: 0.01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.01ms !important;
            scroll-behavior: auto !important;
        }

        .hero-section {
            background-attachment: scroll;
        }

        .team-card:hover {
            transform: none;
        }

        .contact-btn:hover {
            transform: none;
        }
    }

    /* responsive adjustments */
    @media (max-width: 768px) {
        .hero-section {
            height: 50vh;
            min-height: 400px;
            background-attachment: scroll;
        }

        .team-card {
            margin-bottom: 1.5rem;
        }

        .team-card:hover {
            transform: translateY(-4px);
        }
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50">
    
    {{-- hero section --}}
    <div class="hero-section">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1 class="text-5xl md:text-6xl font-bold text-white mb-4">Kontak Kami</h1>
            <p class="text-xl md:text-2xl text-white/90 max-w-2xl">
                Ingin Berkolaborasi Dengan Jakarta Smart City? Hubungi Kami Di Sini.
            </p>
        </div>
    </div>

    {{-- team section --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        
        {{-- section header --}}
        <div class="text-center mb-16 section-header">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Tim Kami Siap Membantu</h2>
            <div class="w-24 h-1 bg-gradient-to-r from-blue-500 to-green-500 mx-auto mb-6"></div>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Hubungi tim kami untuk informasi lebih lanjut mengenai platform KKN-GO dan layanan yang tersedia.
            </p>
        </div>

        {{-- team members grid --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($teamMembers as $member)
            <div class="team-card">
                {{-- photo --}}
                <div class="flex justify-center mb-6">
                    <img src="{{ asset($member['photo']) }}" 
                         alt="{{ $member['name'] }}"
                         class="team-photo"
                         loading="lazy">
                </div>

                {{-- info --}}
                <div class="text-center mb-6">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $member['name'] }}</h3>
                    <p class="text-lg font-semibold text-blue-600 mb-3">{{ $member['role'] }}</p>
                    <p class="text-gray-600 leading-relaxed">{{ $member['description'] }}</p>
                </div>

                {{-- contact buttons --}}
                <div class="space-y-3">
                    <a href="mailto:{{ $member['email'] }}" 
                       class="contact-btn btn-email w-full justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Kirim Email
                    </a>
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $member['whatsapp']) }}" 
                       target="_blank"
                       rel="noopener noreferrer"
                       class="contact-btn btn-whatsapp w-full justify-center">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                        WhatsApp
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        {{-- additional info section --}}
        <div class="mt-20 bg-white rounded-2xl shadow-lg p-8 md:p-12 border border-gray-100">
            <div class="text-center mb-8">
                <h3 class="text-3xl font-bold text-gray-900 mb-4">Informasi Lebih Lanjut</h3>
                <p class="text-gray-600 text-lg">
                    Kami siap menjawab pertanyaan Anda dan membantu Anda memanfaatkan platform KKN-GO secara optimal.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                {{-- jam operasional --}}
                <div class="text-center p-6 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-500 text-white rounded-full mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-2">Jam Operasional</h4>
                    <p class="text-gray-700">Senin - Jumat<br>09.00 - 17.00 WIB</p>
                </div>

                {{-- response time --}}
                <div class="text-center p-6 bg-gradient-to-br from-green-50 to-green-100 rounded-xl">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-green-500 text-white rounded-full mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-2">Waktu Respon</h4>
                    <p class="text-gray-700">Maksimal 1x24 Jam<br>Pada Hari Kerja</p>
                </div>

                {{-- support channels --}}
                <div class="text-center p-6 bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-purple-500 text-white rounded-full mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-2">Saluran Dukungan</h4>
                    <p class="text-gray-700">Email & WhatsApp<br>Tersedia</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection