{{-- resources/views/student/repository/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Knowledge Repository')

@push('styles')
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
    
    /* fade in animation */
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
    
    .fade-in-up {
        animation: fadeInUp 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        opacity: 0;
        animation-fill-mode: forwards;
    }
    
    /* document card animations */
    .document-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        will-change: transform, box-shadow;
    }
    
    .document-card:hover {
        transform: translate3d(0, -8px, 0);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.15);
    }
    
    /* featured card special styling */
    .featured-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .featured-card:hover {
        transform: translate3d(0, -8px, 0);
        box-shadow: 0 25px 35px -5px rgba(102, 126, 234, 0.4);
    }
    
    /* smooth scrolling */
    html {
        scroll-behavior: smooth;
    }
    
    /* GPU acceleration untuk performa */
    .stats-card-repository,
    .document-card,
    .featured-card {
        transform: translateZ(0);
        backface-visibility: hidden;
        perspective: 1000px;
    }
    
    /* filter sidebar sticky */
    .filter-sidebar-sticky {
        position: sticky;
        top: 100px;
        max-height: calc(100vh - 120px);
        overflow-y: auto;
    }
    
    /* custom scrollbar untuk sidebar */
    .filter-sidebar-sticky::-webkit-scrollbar {
        width: 6px;
    }
    
    .filter-sidebar-sticky::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .filter-sidebar-sticky::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
    }
    
    .filter-sidebar-sticky::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
    
    /* accessibility - prefers reduced motion */
    @media (prefers-reduced-motion: reduce) {
        *,
        *::before,
        *::after {
            animation-duration: 0.01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.01ms !important;
            scroll-behavior: auto !important;
        }
        
        .stats-card-repository:hover,
        .document-card:hover,
        .featured-card:hover {
            transform: none;
        }
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50">
    
    {{-- hero section dengan background image --}}
    <div class="hero-repository-background text-white py-16 md:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="fade-in-up">
                <h1 class="text-4xl md:text-5xl font-bold mb-4 text-shadow-repository">
                    Knowledge Repository
                </h1>
                <p class="text-xl md:text-2xl text-white text-shadow-repository max-w-3xl">
                    Akses dan pelajari dari dokumentasi proyek KKN sebelumnya
                </p>
            </div>
            
            {{-- statistics cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6 mt-10 fade-in-up" style="animation-delay: 0.2s;">
                {{-- total documents --}}
                <div class="stats-card-repository rounded-xl p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-white opacity-90 mb-1">Total Dokumen</p>
                            <p class="text-4xl md:text-5xl font-bold text-white text-shadow-repository">{{ $stats['total_documents'] }}</p>
                        </div>
                        <div class="w-14 h-14 md:w-16 md:h-16 bg-white bg-opacity-30 rounded-xl flex items-center justify-center backdrop-blur-sm shadow-lg">
                            <svg class="w-8 h-8 md:w-9 md:h-9 text-white drop-shadow-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- total downloads --}}
                <div class="stats-card-repository rounded-xl p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-white opacity-90 mb-1">Total Downloads</p>
                            <p class="text-4xl md:text-5xl font-bold text-white text-shadow-repository">{{ number_format($stats['total_downloads']) }}</p>
                        </div>
                        <div class="w-14 h-14 md:w-16 md:h-16 bg-white bg-opacity-30 rounded-xl flex items-center justify-center backdrop-blur-sm shadow-lg">
                            <svg class="w-8 h-8 md:w-9 md:h-9 text-white drop-shadow-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- contributing institutions --}}
                <div class="stats-card-repository rounded-xl p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-white opacity-90 mb-1">Instansi Berkontribusi</p>
                            <p class="text-4xl md:text-5xl font-bold text-white text-shadow-repository">{{ $stats['contributing_institutions'] }}</p>
                        </div>
                        <div class="w-14 h-14 md:w-16 md:h-16 bg-white bg-opacity-30 rounded-xl flex items-center justify-center backdrop-blur-sm shadow-lg">
                            <svg class="w-8 h-8 md:w-9 md:h-9 text-white drop-shadow-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- main content area --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        {{-- featured documents section --}}
        @if($featured_documents && $featured_documents->count() > 0)
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6 fade-in-up" style="animation-delay: 0.3s;">Dokumen Unggulan</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($featured_documents as $index => $featured)
                        <div class="featured-card rounded-xl p-6 text-white fade-in-up" style="animation-delay: {{ 0.35 + ($index * 0.1) }}s;">
                            <div class="flex items-start gap-3 mb-4">
                                <div class="w-12 h-12 bg-white bg-opacity-30 rounded-lg flex items-center justify-center flex-shrink-0 backdrop-blur-sm">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-bold text-white mb-2 line-clamp-2">{{ $featured->title }}</h3>
                                    <p class="text-sm text-white opacity-90 line-clamp-2">{{ $featured->description }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between text-sm text-white opacity-90 mb-4">
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>
                                    {{ $featured->download_count }}
                                </span>
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    {{ $featured->view_count }}
                                </span>
                            </div>
                            
                            <a href="{{ route('student.repository.show', $featured->id) }}" 
                               class="block w-full px-4 py-2 bg-white text-purple-600 text-center rounded-lg hover:bg-gray-100 transition-colors text-sm font-semibold">
                                Lihat Detail
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            
            {{-- filters sidebar --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 filter-sidebar-sticky fade-in-up" style="animation-delay: 0.4s;">
                    <h3 class="font-semibold text-gray-900 mb-4 text-lg">Filter & Pencarian</h3>
                    
                    <form method="GET" action="{{ route('student.repository.index') }}" class="space-y-4">
                        {{-- search --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kata Kunci</label>
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Cari dokumen..."
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm transition-all">
                        </div>

                        {{-- category filter --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kategori SDG</label>
                            <select name="category" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm transition-all">
                                <option value="">Semua Kategori</option>
                                @for($i = 1; $i <= 17; $i++)
                                    <option value="{{ $i }}" {{ request('category') == $i ? 'selected' : '' }}>SDG {{ $i }}</option>
                                @endfor
                            </select>
                        </div>

                        {{-- year filter --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                            <select name="year" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm transition-all">
                                <option value="">Semua Tahun</option>
                                @foreach($years as $year)
                                    <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- province filter --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Provinsi</label>
                            <select name="province_id" 
                                    id="province-select"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm transition-all">
                                <option value="">Semua Provinsi</option>
                                @foreach($provinces as $province)
                                    <option value="{{ $province->id }}" {{ request('province_id') == $province->id ? 'selected' : '' }}>
                                        {{ $province->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- regency filter (conditional) --}}
                        @if(request('province_id') && $regencies->count() > 0)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kabupaten/Kota</label>
                            <select name="regency_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm transition-all">
                                <option value="">Semua Kabupaten/Kota</option>
                                @foreach($regencies as $regency)
                                    <option value="{{ $regency->id }}" {{ request('regency_id') == $regency->id ? 'selected' : '' }}>
                                        {{ $regency->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        {{-- action buttons --}}
                        <div class="flex gap-2 pt-2">
                            <button type="submit" 
                                    class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all font-semibold text-sm shadow-sm hover:shadow-md">
                                Filter
                            </button>
                            @if(request()->hasAny(['search', 'category', 'year', 'province_id', 'regency_id']))
                            <a href="{{ route('student.repository.index') }}" 
                               class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-all font-semibold text-sm">
                                Reset
                            </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            {{-- documents list --}}
            <div class="lg:col-span-3">
                @if($documents->isEmpty())
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center fade-in-up" style="animation-delay: 0.5s;">
                        <svg class="w-20 h-20 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Tidak Ada Dokumen</h3>
                        <p class="text-gray-600 mb-6">Tidak ada dokumen yang sesuai dengan kriteria pencarian Anda.</p>
                        <a href="{{ route('student.repository.index') }}" 
                           class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:shadow-lg transition-all font-semibold">
                            Lihat Semua Dokumen
                        </a>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($documents as $index => $document)
                            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden document-card fade-in-up" 
                                 style="animation-delay: {{ 0.5 + ($index * 0.05) }}s;">
                                <div class="p-6">
                                    <div class="flex items-start gap-3 mb-4">
                                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-green-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="font-bold text-gray-900 mb-2 line-clamp-2">{{ $document->title }}</h3>
                                            <p class="text-sm text-gray-600 line-clamp-2">{{ $document->description }}</p>
                                        </div>
                                    </div>

                                    <div class="flex flex-wrap gap-2 text-xs text-gray-600 mb-4">
                                        <span class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            {{ $document->view_count }} views
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                            </svg>
                                            {{ $document->download_count }} downloads
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                            </svg>
                                            {{ $document->citation_count }} citations
                                        </span>
                                        <span class="px-2 py-1 bg-gray-100 rounded">
                                            {{ $document->readable_file_size ?? 'N/A' }}
                                        </span>
                                    </div>

                                    {{-- categories --}}
                                    @php
                                        $categories = [];
                                        if ($document->categories) {
                                            if (is_array($document->categories)) {
                                                $categories = $document->categories;
                                            } elseif (is_string($document->categories)) {
                                                $decoded = json_decode($document->categories, true);
                                                $categories = is_array($decoded) ? $decoded : [];
                                            }
                                        }
                                    @endphp
                                    
                                    @if(!empty($categories))
                                    <div class="flex flex-wrap gap-2 mb-4">
                                        @foreach(array_slice($categories, 0, 3) as $cat)
                                            <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded">
                                                SDG {{ $cat }}
                                            </span>
                                        @endforeach
                                        @if(count($categories) > 3)
                                            <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs font-semibold rounded">
                                                +{{ count($categories) - 3 }}
                                            </span>
                                        @endif
                                    </div>
                                    @endif

                                    <a href="{{ route('student.repository.show', $document->id) }}" 
                                       class="block w-full px-4 py-2 bg-gradient-to-r from-blue-600 to-green-600 text-white text-center rounded-lg hover:shadow-lg transition-all font-semibold text-sm">
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- pagination --}}
                    <div class="mt-8 fade-in-up" style="animation-delay: 0.7s;">
                        {{ $documents->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection