{{-- resources/views/student/browse-problems/detail.blade.php --}}
@extends('layouts.app')

@section('title', $problem->title)

@push('styles')
<style>
/* animasi smooth scroll */
html {
    scroll-behavior: smooth;
}

/* animasi fade in */
.fade-in {
    opacity: 0;
    transform: translateY(20px);
    animation: fadeInUp 0.6s ease-out forwards;
}

@keyframes fadeInUp {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* stagger animations */
.fade-in-delay-1 { animation-delay: 0.1s; }
.fade-in-delay-2 { animation-delay: 0.2s; }
.fade-in-delay-3 { animation-delay: 0.3s; }
.fade-in-delay-4 { animation-delay: 0.4s; }
.fade-in-delay-5 { animation-delay: 0.5s; }

/* image gallery */
.gallery-container {
    position: relative;
    overflow: hidden;
}

.gallery-main-image {
    transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

.gallery-main-image:hover {
    transform: scale(1.05);
}

/* thumbnail hover effect */
.gallery-thumbnail {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    opacity: 0.7;
}

.gallery-thumbnail:hover,
.gallery-thumbnail.active {
    opacity: 1;
    transform: translateY(-4px);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

/* wishlist button animation */
.wishlist-btn {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.wishlist-btn:hover {
    transform: scale(1.1) rotate(-5deg);
}

.wishlist-btn.wishlisted {
    animation: heartbeat 0.6s ease-in-out;
}

@keyframes heartbeat {
    0%, 100% { transform: scale(1); }
    25% { transform: scale(1.3); }
    50% { transform: scale(1.1); }
    75% { transform: scale(1.2); }
}

/* share button ripple effect */
.share-btn {
    position: relative;
    overflow: hidden;
}

.share-btn::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(59, 130, 246, 0.3);
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
}

.share-btn:active::after {
    width: 200px;
    height: 200px;
}

/* smooth hover effects */
.hover-lift {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.hover-lift:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 24px -10px rgba(0, 0, 0, 0.15);
}

/* badge animation */
.badge {
    transition: all 0.3s ease;
}

.badge:hover {
    transform: scale(1.05);
}

/* apply button gradient animation */
.apply-btn {
    position: relative;
    overflow: hidden;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.apply-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px -5px rgba(59, 130, 246, 0.5);
}

/* institution card animation */
.institution-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.institution-card:hover {
    transform: translateX(4px);
}

/* similar projects animation */
.similar-project {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.similar-project:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 16px -4px rgba(0, 0, 0, 0.1);
}

/* reduced motion support */
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
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-green-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- breadcrumb --}}
        <nav class="mb-6 fade-in">
            <ol class="flex items-center gap-2 text-sm">
                <li><a href="{{ route('student.dashboard') }}" class="text-gray-500 hover:text-gray-700 transition-colors">Dashboard</a></li>
                <li class="text-gray-400">/</li>
                <li><a href="{{ route('student.browse-problems.index') }}" class="text-gray-500 hover:text-gray-700 transition-colors">Browse Problems</a></li>
                <li class="text-gray-400">/</li>
                <li class="text-gray-900 font-medium">{{ Str::limit($problem->title, 30) }}</li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- main content --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- header card --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 fade-in">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <h1 class="text-3xl font-bold text-gray-900 mb-3">{{ $problem->title }}</h1>
                            
                            {{-- meta info --}}
                            <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600">
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <span>{{ $problem->regency->name ?? '-' }}, {{ $problem->province->name ?? '-' }}</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    <span>{{ $problem->views_count }} Views</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span>{{ \Carbon\Carbon::parse($problem->created_at)->format('d M Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- badges --}}
                    <div class="flex flex-wrap gap-2">
                        <span class="badge inline-flex px-3 py-1 text-sm font-semibold rounded-full
                            {{ $problem->status === 'open' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $problem->status === 'closed' ? 'bg-red-100 text-red-700' : '' }}
                            {{ $problem->status === 'in_progress' ? 'bg-yellow-100 text-yellow-700' : '' }}
                            {{ $problem->status === 'completed' ? 'bg-purple-100 text-purple-700' : '' }}">
                            {{ ucfirst($problem->status) }}
                        </span>
                        
                        <span class="badge inline-flex px-3 py-1 text-sm font-semibold rounded-full
                            {{ $problem->difficulty_level === 'beginner' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $problem->difficulty_level === 'intermediate' ? 'bg-yellow-100 text-yellow-700' : '' }}
                            {{ $problem->difficulty_level === 'advanced' ? 'bg-red-100 text-red-700' : '' }}">
                            {{ ucfirst($problem->difficulty_level) }}
                        </span>

                        {{-- sdg categories badges --}}
                        @if($problem->sdg_categories)
                            @php
                                $sdgs = is_array($problem->sdg_categories) ? $problem->sdg_categories : json_decode($problem->sdg_categories, true);
                            @endphp
                            @if($sdgs)
                                @foreach(array_slice($sdgs, 0, 3) as $sdg)
                                    <span class="badge inline-flex px-3 py-1 text-xs font-semibold bg-blue-100 text-blue-700 rounded-full">
                                        SDG {{ $sdg }}
                                    </span>
                                @endforeach
                                @if(count($sdgs) > 3)
                                    <span class="badge inline-flex px-3 py-1 text-xs font-semibold bg-gray-100 text-gray-700 rounded-full">
                                        +{{ count($sdgs) - 3 }} Lainnya
                                    </span>
                                @endif
                            @endif
                        @endif
                    </div>
                </div>

                {{-- image gallery --}}
                @if($problem->images && $problem->images->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 fade-in fade-in-delay-1">
                    <div class="gallery-container">
                        {{-- main image --}}
                        <div class="mb-4 rounded-lg overflow-hidden bg-gray-100" style="height: 400px;">
                            <img id="main-gallery-image"
                                 src="{{ supabase_url($problem->images->first()->image_path) }}" 
                                 alt="{{ $problem->title }}"
                                 class="gallery-main-image w-full h-full object-cover"
                                 loading="lazy">
                        </div>

                        {{-- thumbnails --}}
                        @if($problem->images->count() > 1)
                        <div class="grid grid-cols-6 gap-2">
                            @foreach($problem->images as $index => $image)
                            <button onclick="changeMainImage('{{ supabase_url($image->image_path) }}', this)"
                                    class="gallery-thumbnail rounded-lg overflow-hidden focus:outline-none focus:ring-2 focus:ring-blue-500 {{ $index === 0 ? 'active' : '' }}"
                                    style="height: 80px;">
                                <img src="{{ supabase_url($image->image_path) }}" 
                                     alt="Thumbnail {{ $index + 1 }}"
                                     class="w-full h-full object-cover"
                                     loading="lazy">
                            </button>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                {{-- description --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 fade-in fade-in-delay-2">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Deskripsi</h2>
                    <div class="prose prose-blue max-w-none text-gray-700 leading-relaxed">
                        {!! nl2br(e($problem->description)) !!}
                    </div>
                </div>

                {{-- background --}}
                @if($problem->background)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 fade-in fade-in-delay-3">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Latar Belakang</h2>
                    <div class="prose prose-blue max-w-none text-gray-700 leading-relaxed">
                        {!! nl2br(e($problem->background)) !!}
                    </div>
                </div>
                @endif

                {{-- objectives --}}
                @if($problem->objectives)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 fade-in fade-in-delay-4">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Tujuan</h2>
                    <div class="prose prose-blue max-w-none text-gray-700 leading-relaxed">
                        {!! nl2br(e($problem->objectives)) !!}
                    </div>
                </div>
                @endif

                {{-- requirements --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 fade-in fade-in-delay-5">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">Requirements</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- mahasiswa dibutuhkan --}}
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Mahasiswa Dibutuhkan</p>
                                <p class="text-lg font-bold text-gray-900">{{ $problem->required_students }} Orang</p>
                            </div>
                        </div>

                        {{-- durasi --}}
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Durasi Proyek</p>
                                <p class="text-lg font-bold text-gray-900">{{ $problem->duration_months }} Bulan</p>
                            </div>
                        </div>

                        {{-- deadline --}}
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Deadline Aplikasi</p>
                                <p class="text-lg font-bold text-gray-900">{{ \Carbon\Carbon::parse($problem->application_deadline)->format('d M Y') }}</p>
                            </div>
                        </div>

                        {{-- difficulty --}}
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Tingkat Kesulitan</p>
                                <p class="text-lg font-bold text-gray-900">{{ ucfirst($problem->difficulty_level) }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- skills --}}
                    @if($problem->required_skills && count($problem->required_skills) > 0)
                    <div class="mt-6">
                        <p class="text-sm font-semibold text-gray-700 mb-2">Skills Yang Dibutuhkan:</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach($problem->required_skills as $skill)
                                <span class="px-3 py-1 bg-blue-50 text-blue-700 text-sm rounded-full">{{ $skill }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- expected outcomes --}}
                    @if($problem->expected_outcomes)
                    <div class="mt-6">
                        <p class="text-sm font-semibold text-gray-700 mb-2">Expected Outcomes:</p>
                        <p class="text-gray-600 text-sm">{{ $problem->expected_outcomes }}</p>
                    </div>
                    @endif
                </div>

            </div>

            {{-- SIDEBAR --}}
            <div class="lg:col-span-1 space-y-6">
                
                {{-- combined card: CTA + similar problems --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 fade-in fade-in-delay-2">
                    
                    {{-- CTA section --}}
                    @auth
                        @if(!$hasApplied)
                            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg p-6 text-white mb-6">
                                <h3 class="font-bold text-lg mb-2">Tertarik Dengan Proyek Ini?</h3>
                                <p class="text-sm mb-4 text-blue-50">Aplikasikan dirimu sekarang dan mulai berkontribusi!</p>
                                <a href="{{ route('student.applications.create', ['problem_id' => $problem->id]) }}" 
                                   class="apply-btn block w-full text-center px-4 py-3 bg-white text-blue-600 font-semibold rounded-lg hover:bg-blue-50 transition-colors">
                                    Apply Sekarang
                                </a>
                                <p class="text-xs mt-3 text-blue-100 text-center">
                                    Deadline: {{ \Carbon\Carbon::parse($problem->application_deadline)->format('d M Y') }}
                                </p>
                            </div>
                        @else
                            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg p-6 text-white mb-6">
                                <div class="flex items-center gap-2 mb-2">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <h3 class="font-bold text-lg">Aplikasi Terkirim</h3>
                                </div>
                                <p class="text-sm text-green-50">Anda sudah mengajukan aplikasi untuk proyek ini</p>
                                <a href="{{ route('student.applications.index') }}" 
                                   class="block w-full text-center px-4 py-3 bg-white text-green-600 font-semibold rounded-lg hover:bg-green-50 transition-colors mt-4">
                                    Lihat Status Aplikasi
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg p-6 text-white mb-6">
                            <h3 class="font-bold text-lg mb-2">Tertarik Dengan Proyek Ini?</h3>
                            <p class="text-sm mb-4 text-blue-50">Login atau daftar untuk mengajukan aplikasi</p>
                            <a href="{{ route('login') }}" 
                               class="block w-full text-center px-4 py-3 bg-white text-blue-600 font-semibold rounded-lg hover:bg-blue-50 transition-colors">
                                Login / Register
                            </a>
                        </div>
                    @endauth

                    {{-- similar problems section --}}
                    @if(isset($similarProblems) && $similarProblems->isNotEmpty())
                        <div class="border-t border-gray-200 pt-6">
                            <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                                Proyek Serupa
                            </h3>
                            <div class="space-y-3">
                                @foreach($similarProblems as $similar)
                                <a href="{{ route('student.browse-problems.show', $similar->id) }}"
                                   class="block group similar-project">
                                    <div class="border border-gray-200 rounded-lg p-3 hover:border-blue-400 hover:bg-blue-50 transition-all duration-200">
                                        <h4 class="font-semibold text-gray-900 text-sm mb-2 line-clamp-2 group-hover:text-blue-600 transition-colors">
                                            {{ $similar->title }}
                                        </h4>
                                        <div class="flex items-center gap-2 text-xs text-gray-600 mb-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            </svg>
                                            <span>{{ $similar->regency->name ?? 'N/A' }}, {{ $similar->province->name ?? 'N/A' }}</span>
                                        </div>
                                        <div class="flex items-center justify-between text-xs">
                                            <span class="text-gray-600">{{ $similar->institution->name ?? 'Instansi' }}</span>
                                            <span class="text-blue-600 font-medium group-hover:underline">Lihat Detail →</span>
                                        </div>
                                    </div>
                                </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                {{-- info card instansi --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 fade-in fade-in-delay-3">
                    <h3 class="font-bold text-gray-900 mb-4">Informasi Instansi</h3>
                    <div class="flex items-start gap-3 institution-card">
                        <div class="w-12 h-12 rounded-lg overflow-hidden bg-gray-100 flex-shrink-0">
                            @if($problem->institution && $problem->institution->logo_path)
                                <img src="{{ supabase_url($problem->institution->logo_path) }}" 
                                     alt="{{ $problem->institution->name }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-blue-100 text-blue-600 font-bold text-lg">
                                    {{ substr($problem->institution->name ?? 'I', 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="font-semibold text-gray-900 mb-1">{{ $problem->institution->name ?? 'Instansi' }}</h4>
                            <p class="text-sm text-gray-600 mb-2">{{ $problem->institution->institution_type ?? 'Instansi' }}</p>
                            @if($problem->institution)
                            <a href="{{ route('institution.profile', $problem->institution->id) }}" 
                               class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                                Lihat Profil Lengkap →
                            </a>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- wishlist button (jika authenticated) --}}
                @auth
                    @if(Auth::user()->isStudent() && Auth::user()->student)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 fade-in fade-in-delay-4">
                        <button id="wishlist-btn"
                                data-problem-id="{{ $problem->id }}"
                                data-wishlisted="{{ $isWishlisted ? 'true' : 'false' }}"
                                class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-lg font-medium transition-all duration-300
                                       {{ $isWishlisted ? 'bg-red-50 text-red-600 hover:bg-red-100' : 'bg-gray-50 text-gray-700 hover:bg-gray-100' }}">
                            <svg class="w-5 h-5 {{ $isWishlisted ? 'fill-current' : '' }}" 
                                 fill="{{ $isWishlisted ? 'currentColor' : 'none' }}" 
                                 stroke="currentColor" 
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                            <span class="wishlist-text">{{ $isWishlisted ? 'Hapus Dari Wishlist' : 'Tambah Ke Wishlist' }}</span>
                        </button>
                    </div>
                    @endif
                @endauth

                {{-- share button --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 fade-in fade-in-delay-5">
                    <button id="share-btn" class="share-btn w-full flex items-center justify-center gap-2 px-4 py-3 bg-gray-50 text-gray-700 rounded-lg hover:bg-gray-100 font-medium transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path>
                        </svg>
                        Bagikan Proyek
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// fungsi untuk mengganti gambar utama di galeri
function changeMainImage(imageSrc, element) {
    const mainImage = document.getElementById('main-gallery-image');
    mainImage.style.opacity = '0';
    
    setTimeout(() => {
        mainImage.src = imageSrc;
        mainImage.style.opacity = '1';
    }, 200);
    
    // update active thumbnail
    document.querySelectorAll('.gallery-thumbnail').forEach(thumb => {
        thumb.classList.remove('active');
    });
    element.classList.add('active');
}

// wishlist functionality
document.addEventListener('DOMContentLoaded', function() {
    const wishlistBtn = document.getElementById('wishlist-btn');
    
    if (wishlistBtn) {
        wishlistBtn.addEventListener('click', async function() {
            const problemId = this.dataset.problemId;
            const isWishlisted = this.dataset.wishlisted === 'true';
            
            try {
                const response = await fetch(`/student/wishlist/${problemId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // update button state
                    this.dataset.wishlisted = data.saved ? 'true' : 'false';
                    
                    if (data.saved) {
                        this.classList.remove('bg-gray-50', 'text-gray-700', 'hover:bg-gray-100');
                        this.classList.add('bg-red-50', 'text-red-600', 'hover:bg-red-100');
                        this.querySelector('.wishlist-text').textContent = 'Hapus Dari Wishlist';
                        this.querySelector('svg').setAttribute('fill', 'currentColor');
                    } else {
                        this.classList.remove('bg-red-50', 'text-red-600', 'hover:bg-red-100');
                        this.classList.add('bg-gray-50', 'text-gray-700', 'hover:bg-gray-100');
                        this.querySelector('.wishlist-text').textContent = 'Tambah Ke Wishlist';
                        this.querySelector('svg').setAttribute('fill', 'none');
                    }
                    
                    // show notification (optional)
                    showNotification(data.message, 'success');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Terjadi kesalahan. Silakan coba lagi.', 'error');
            }
        });
    }
    
    // share functionality
    const shareBtn = document.getElementById('share-btn');
    
    if (shareBtn) {
        shareBtn.addEventListener('click', async function() {
            const shareData = {
                title: '{{ $problem->title }}',
                text: '{{ Str::limit($problem->description, 100) }}',
                url: window.location.href
            };
            
            try {
                if (navigator.share) {
                    await navigator.share(shareData);
                    showNotification('Berhasil membagikan proyek!', 'success');
                } else {
                    // fallback: copy to clipboard
                    await navigator.clipboard.writeText(window.location.href);
                    showNotification('Link proyek berhasil disalin!', 'success');
                }
            } catch (error) {
                console.error('Error sharing:', error);
            }
        });
    }
});

// helper function untuk notification (optional)
function showNotification(message, type = 'info') {
    // implementasi simple notification
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 ${
        type === 'success' ? 'bg-green-500' : 
        type === 'error' ? 'bg-red-500' : 
        'bg-blue-500'
    } text-white`;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}
</script>
@endpush