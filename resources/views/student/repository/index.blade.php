{{-- resources/views/student/repository/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Knowledge Repository')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/browse-problems.css') }}">
<style>
    /* hero section dengan background image */
    .hero-repository-background {
        position: relative;
        background-image: url('/repo-student.jpeg');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        min-height: 400px;
    }
    
    .hero-repository-background::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(
            135deg, 
            rgba(37, 99, 235, 0.50) 0%,
            rgba(59, 130, 246, 0.45) 35%,
            rgba(16, 185, 129, 0.45) 65%,
            rgba(5, 150, 105, 0.50) 100%
        );
        backdrop-filter: blur(1px);
    }
    
    /* stats cards dengan glassmorphism */
    .stats-card-repository {
        background: rgba(255, 255, 255, 0.20);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        will-change: transform;
    }
    
    .stats-card-repository:hover {
        background: rgba(255, 255, 255, 0.30);
        transform: translate3d(0, -4px, 0);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3);
    }
    
    .stats-card-repository svg {
        filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
    }
    
    .stats-card-repository .backdrop-blur-sm {
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
    }
    
    /* text shadow untuk hero */
    .text-shadow-repository {
        text-shadow: 
            0 2px 4px rgba(0, 0, 0, 0.4),
            0 4px 8px rgba(0, 0, 0, 0.3),
            0 1px 2px rgba(0, 0, 0, 0.5);
    }
    
    /* animation fade in */
    .repository-fade-in {
        animation: fadeInUp 0.6s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        opacity: 0;
        will-change: transform, opacity;
    }
    
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
    
    /* document card styling */
    .document-card {
        background: white;
        border-radius: 0.75rem;
        border: 1px solid #e5e7eb;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        will-change: transform, box-shadow;
        transform: translate3d(0, 0, 0);
        backface-visibility: hidden;
    }
    
    .document-card:hover {
        transform: translate3d(0, -4px, 0);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.15);
        border-color: #3b82f6;
    }
    
    /* featured document styling */
    .featured-document {
        background: white;
        border: 1px solid #e5e7eb;
        color: inherit;
    }
    
    .featured-document:hover {
        transform: translate3d(0, -6px, 0);
        box-shadow: 0 15px 35px -5px rgba(59, 130, 246, 0.3);
        border-color: #3b82f6;
    }
    
    /* filter section styling */
    .filter-section {
        background: white;
        border-radius: 0.75rem;
        border: 1px solid #e5e7eb;
        padding: 1.5rem;
    }
    
    /* smooth transitions untuk cards */
    .content-card {
        will-change: transform, box-shadow;
        transform: translate3d(0, 0, 0);
        backface-visibility: hidden;
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1),
                   box-shadow 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .content-card:hover {
        transform: translate3d(0, -2px, 0);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.15);
    }
    
    /* accessibility - prefers reduced motion */
    @media (prefers-reduced-motion: reduce) {
        *,
        *::before,
        *::after {
            animation-duration: 0.01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.01ms !important;
        }
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50">
    
    {{-- hero section dengan background --}}
    <div class="hero-repository-background">
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            
            {{-- header --}}
            <div class="mb-10 repository-fade-in">
                <h1 class="text-4xl md:text-5xl font-bold mb-4 text-white text-shadow-repository">
                    Knowledge Repository
                </h1>
                <p class="text-xl md:text-2xl text-white text-shadow-repository max-w-3xl">
                    Akses Dan Pelajari Dari Dokumentasi Proyek KKN Sebelumnya
                </p>
            </div>
            
            {{-- stats cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6 repository-fade-in" style="animation-delay: 0.1s;">
                {{-- total documents --}}
                <div class="stats-card-repository rounded-xl p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-white text-shadow-repository opacity-90 mb-1">Total Dokumen</p>
                            <p class="text-4xl md:text-5xl font-bold text-white text-shadow-repository">
                                {{ $stats['total_documents'] }}
                            </p>
                        </div>
                        <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- total downloads --}}
                <div class="stats-card-repository rounded-xl p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-white text-shadow-repository opacity-90 mb-1">Total Downloads</p>
                            <p class="text-4xl md:text-5xl font-bold text-white text-shadow-repository">
                                {{ number_format($stats['total_downloads']) }}
                            </p>
                        </div>
                        <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- total institutions --}}
                <div class="stats-card-repository rounded-xl p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-white text-shadow-repository opacity-90 mb-1">Instansi Terdaftar</p>
                            <p class="text-4xl md:text-5xl font-bold text-white text-shadow-repository">
                                {{ $stats['total_institutions'] }}
                            </p>
                        </div>
                        <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- main content --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        {{-- featured documents section --}}
        @if($featuredDocuments->count() > 0)
        <div class="mb-12 repository-fade-in" style="animation-delay: 0.2s;">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Dokumen Unggulan</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($featuredDocuments as $document)
                <a href="{{ route('student.repository.show', $document->id) }}" 
                   class="featured-document p-6 rounded-xl block group">
                    <div class="flex items-start gap-4 mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-bold mb-2 text-gray-900 group-hover:text-blue-600 transition-colors">{{ Str::limit($document->title, 60) }}</h3>
                            <p class="text-sm text-gray-600">{{ Str::limit($document->description, 100) }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 text-sm text-gray-500">
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            {{ $document->view_count }}
                        </span>
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            {{ $document->download_count }}
                        </span>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        {{-- search and filter section --}}
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
            
            {{-- filter sidebar --}}
            <div class="lg:col-span-1">
                <div class="filter-section sticky top-24">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Filter</h3>
                    
                    <form action="{{ route('student.repository.index') }}" method="GET" class="space-y-4">
                        
                        {{-- search --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Cari Dokumen</label>
                            <input type="text" 
                                name="search" 
                                value="{{ request('search') }}" 
                                placeholder="Judul, deskripsi, tags..."
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        {{-- kategori SDG menggunakan integer value 1-17 --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kategori SDG</label>
                            <select name="category" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Semua Kategori</option>
                                <option value="1" {{ request('category') == '1' ? 'selected' : '' }}>1. Tanpa Kemiskinan</option>
                                <option value="2" {{ request('category') == '2' ? 'selected' : '' }}>2. Tanpa Kelaparan</option>
                                <option value="3" {{ request('category') == '3' ? 'selected' : '' }}>3. Kehidupan Sehat Dan Sejahtera</option>
                                <option value="4" {{ request('category') == '4' ? 'selected' : '' }}>4. Pendidikan Berkualitas</option>
                                <option value="5" {{ request('category') == '5' ? 'selected' : '' }}>5. Kesetaraan Gender</option>
                                <option value="6" {{ request('category') == '6' ? 'selected' : '' }}>6. Air Bersih Dan Sanitasi</option>
                                <option value="7" {{ request('category') == '7' ? 'selected' : '' }}>7. Energi Bersih Dan Terjangkau</option>
                                <option value="8" {{ request('category') == '8' ? 'selected' : '' }}>8. Pekerjaan Layak Dan Pertumbuhan Ekonomi</option>
                                <option value="9" {{ request('category') == '9' ? 'selected' : '' }}>9. Industri, Inovasi Dan Infrastruktur</option>
                                <option value="10" {{ request('category') == '10' ? 'selected' : '' }}>10. Berkurangnya Kesenjangan</option>
                                <option value="11" {{ request('category') == '11' ? 'selected' : '' }}>11. Kota Dan Komunitas Berkelanjutan</option>
                                <option value="12" {{ request('category') == '12' ? 'selected' : '' }}>12. Konsumsi Dan Produksi Bertanggung Jawab</option>
                                <option value="13" {{ request('category') == '13' ? 'selected' : '' }}>13. Penanganan Perubahan Iklim</option>
                                <option value="14" {{ request('category') == '14' ? 'selected' : '' }}>14. Ekosistem Laut</option>
                                <option value="15" {{ request('category') == '15' ? 'selected' : '' }}>15. Ekosistem Daratan</option>
                                <option value="16" {{ request('category') == '16' ? 'selected' : '' }}>16. Perdamaian, Keadilan Dan Kelembagaan Yang Kuat</option>
                                <option value="17" {{ request('category') == '17' ? 'selected' : '' }}>17. Kemitraan Untuk Mencapai Tujuan</option>
                            </select>
                        </div>

                        {{-- province --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Provinsi</label>
                            <select name="province_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Semua Provinsi</option>
                                @foreach($provinces as $province)
                                    <option value="{{ $province->id }}" {{ request('province_id') == $province->id ? 'selected' : '' }}>
                                        {{ $province->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- year --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                            <select name="year" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Semua Tahun</option>
                                @foreach($years as $year)
                                    <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- sort --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Urutkan</label>
                            <select name="sort" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                                <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Paling Populer</option>
                                <option value="most_viewed" {{ request('sort') == 'most_viewed' ? 'selected' : '' }}>Paling Dilihat</option>
                                <option value="most_cited" {{ request('sort') == 'most_cited' ? 'selected' : '' }}>Paling Banyak Dikutip</option>
                            </select>
                        </div>

                        <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            Terapkan Filter
                        </button>
                        
                        @if(request()->hasAny(['search', 'category', 'province_id', 'year', 'sort']))
                        <a href="{{ route('student.repository.index') }}" 
                        class="block w-full text-center px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                            Reset Filter
                        </a>
                        @endif
                    </form>
                </div>
            </div>

            {{-- documents grid --}}
            <div class="lg:col-span-3">
                @if($documents->count() > 0)
                    <div class="grid grid-cols-1 gap-6">
                        @foreach($documents as $index => $document)
                        <div class="document-card p-6 content-card" style="animation-delay: {{ 0.3 + ($index * 0.05) }}s;">
                            <div class="flex items-start gap-4">
                                {{-- icon --}}
                                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-green-500 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>

                                {{-- content --}}
                                <div class="flex-1">
                                    <a href="{{ route('student.repository.show', $document->id) }}" 
                                       class="text-xl font-bold text-gray-900 hover:text-blue-600 transition-colors">
                                        {{ $document->title }}
                                    </a>
                                    
                                    <p class="text-gray-600 mt-2 mb-3">{{ Str::limit($document->description, 150) }}</p>
                                    
                                    {{-- meta info --}}
                                    <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500 mb-3">
                                        <span class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                            {{ $document->author_name ?? 'Unknown' }}
                                        </span>
                                        
                                        @if($document->province)
                                        <span class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            {{ $document->province->name }}
                                        </span>
                                        @endif
                                        
                                        @if($document->year)
                                        <span class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            {{ $document->year }}
                                        </span>
                                        @endif
                                        
                                        <span class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            {{ $document->view_count }}
                                        </span>
                                        
                                        <span class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                            </svg>
                                            {{ $document->download_count }}
                                        </span>
                                    </div>
                                    
                                    {{-- categories --}}
                                    @if($document->categories)
                                    <div class="flex flex-wrap gap-2">
                                        @php
                                            $categories = is_array($document->categories) 
                                                ? $document->categories 
                                                : json_decode($document->categories, true) ?? [];
                                        @endphp
                                        @foreach(array_slice($categories, 0, 3) as $category)
                                        <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded-full">
                                            {{ ucwords(str_replace('_', ' ', $category)) }}
                                        </span>
                                        @endforeach
                                        @if(count($categories) > 3)
                                        <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded-full">
                                            +{{ count($categories) - 3 }} lainnya
                                        </span>
                                        @endif
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- pagination --}}
                    @if($documents->hasPages())
                    <div class="mt-8">
                        {{ $documents->links() }}
                    </div>
                    @endif
                @else
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                        <svg class="w-24 h-24 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Tidak Ada Dokumen Ditemukan</h3>
                        <p class="text-gray-600 mb-4">Maaf, tidak ada dokumen yang sesuai dengan kriteria pencarian Anda.</p>
                        <a href="{{ route('student.repository.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            Lihat Semua Dokumen
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection