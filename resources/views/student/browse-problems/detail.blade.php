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
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.apply-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(255,255,255,0.2) 0%, rgba(255,255,255,0) 100%);
    transition: left 0.5s ease;
}

.apply-btn:hover::before {
    left: 100%;
}

.apply-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px -5px rgba(102, 126, 234, 0.5);
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
                                    </svg>
                                    <span>{{ $problem->regency->name }}, {{ $problem->province->name }}</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span>{{ $problem->duration_months }} bulan</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    <span>{{ number_format($problem->views_count ?? 0) }} views</span>
                                </div>
                            </div>
                        </div>

                        {{-- action buttons --}}
                        <div class="flex items-center gap-2">
                            {{-- wishlist button --}}
                            @auth
                                @if(Auth::user()->user_type === 'student')
                                <button onclick="toggleWishlist({{ $problem->id }})" 
                                        id="wishlist-btn"
                                        class="wishlist-btn w-10 h-10 bg-white border-2 rounded-full flex items-center justify-center hover:bg-gray-50 transition-all duration-200 {{ $isWishlisted ? 'border-red-500' : 'border-gray-300' }}"
                                        data-wishlisted="{{ $isWishlisted ? 'true' : 'false' }}">
                                    <svg class="w-5 h-5 {{ $isWishlisted ? 'fill-red-500 text-red-500' : 'text-gray-600' }}" 
                                         fill="{{ $isWishlisted ? 'currentColor' : 'none' }}" 
                                         stroke="currentColor" 
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                    </svg>
                                </button>
                                @endif
                            @endauth

                            {{-- share button --}}
                            <button onclick="shareProject()" 
                                    class="share-btn w-10 h-10 bg-white border-2 border-gray-300 rounded-full flex items-center justify-center hover:bg-gray-50 transition-all duration-200">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- badges --}}
                    <div class="flex flex-wrap gap-2">
                        <span class="badge inline-flex px-3 py-1 text-sm font-semibold rounded-full
                            {{ $problem->status === 'open' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $problem->status === 'in_progress' ? 'bg-blue-100 text-blue-700' : '' }}
                            {{ $problem->status === 'closed' ? 'bg-gray-100 text-gray-700' : '' }}
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
                                        +{{ count($sdgs) - 3 }} lainnya
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
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Deskripsi Proyek</h2>
                    <div class="prose max-w-none text-gray-700">
                        <p class="whitespace-pre-line">{{ $problem->description }}</p>
                    </div>
                </div>

                {{-- background --}}
                @if($problem->background)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 fade-in fade-in-delay-2">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Latar Belakang</h2>
                    <div class="prose max-w-none text-gray-700">
                        <p class="whitespace-pre-line">{{ $problem->background }}</p>
                    </div>
                </div>
                @endif

                {{-- objectives --}}
                @if($problem->objectives)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 fade-in fade-in-delay-3">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Tujuan</h2>
                    <div class="prose max-w-none text-gray-700">
                        <p class="whitespace-pre-line">{{ $problem->objectives }}</p>
                    </div>
                </div>
                @endif

                {{-- scope --}}
                @if($problem->scope)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 fade-in fade-in-delay-3">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Ruang Lingkup</h2>
                    <div class="prose max-w-none text-gray-700">
                        <p class="whitespace-pre-line">{{ $problem->scope }}</p>
                    </div>
                </div>
                @endif

                {{-- requirements --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 fade-in fade-in-delay-3">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Persyaratan</h2>
                    
                    <div class="space-y-4">
                        {{-- required students --}}
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900 mb-1">Jumlah Mahasiswa</h3>
                                <p class="text-gray-600">{{ $problem->required_students }} mahasiswa dibutuhkan</p>
                            </div>
                        </div>

                        {{-- required skills --}}
                        @if($problem->required_skills)
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900 mb-2">Keahlian yang Dibutuhkan</h3>
                                <div class="flex flex-wrap gap-2">
                                    @php
                                        $skills = is_array($problem->required_skills) ? $problem->required_skills : json_decode($problem->required_skills, true);
                                    @endphp
                                    @if($skills)
                                        @foreach($skills as $skill)
                                            <span class="px-3 py-1 bg-gray-100 text-gray-700 text-sm rounded-full">{{ $skill }}</span>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif

                        {{-- required majors --}}
                        @if($problem->required_majors)
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 14l9-5-9-5-9 5 9 5z"/>
                                    <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900 mb-2">Jurusan yang Dibutuhkan</h3>
                                <div class="flex flex-wrap gap-2">
                                    @php
                                        $majors = is_array($problem->required_majors) ? $problem->required_majors : json_decode($problem->required_majors, true);
                                    @endphp
                                    @if($majors)
                                        @foreach($majors as $major)
                                            <span class="px-3 py-1 bg-gray-100 text-gray-700 text-sm rounded-full">{{ $major }}</span>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- expected outcomes --}}
                @if($problem->expected_outcomes)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Hasil yang Diharapkan</h2>
                    <div class="prose max-w-none text-gray-700">
                        <p class="whitespace-pre-line">{{ $problem->expected_outcomes }}</p>
                    </div>
                </div>
                @endif

                {{-- deliverables --}}
                @php
                    $deliverables = is_array($problem->deliverables) 
                        ? $problem->deliverables 
                        : (json_decode($problem->deliverables, true) ?? []);
                @endphp
                @if(count($deliverables) > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 fade-in fade-in-delay-2">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Deliverables</h2>
                    <ul class="list-disc list-inside space-y-2 text-gray-700">
                        @foreach($deliverables as $deliverable)
                            <li class="pl-2">{{ $deliverable }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                {{-- facilities provided --}}
                @php
                    $facilities = is_array($problem->facilities_provided) 
                        ? $problem->facilities_provided 
                        : (json_decode($problem->facilities_provided, true) ?? []);
                @endphp
                @if(count($facilities) > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 fade-in fade-in-delay-2">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Fasilitas Yang Disediakan</h2>
                    <ul class="list-disc list-inside space-y-2 text-gray-700">
                        @foreach($facilities as $facility)
                            <li class="pl-2">{{ $facility }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>

            {{-- sidebar --}}
            <div class="space-y-6">
                {{-- institution card --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 institution-card fade-in fade-in-delay-1">
                    <h3 class="font-bold text-gray-900 mb-4">Informasi Instansi</h3>
                    
                    <div class="flex items-center gap-3 mb-4">
                        @if($problem->institution->logo_path)
                            <img src="{{ supabase_url($problem->institution->logo_path) }}" 
                                 alt="{{ $problem->institution->name }}"
                                 class="w-16 h-16 rounded-full object-cover border-2 border-gray-100"
                                 loading="lazy">
                        @else
                            <div class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-500 to-green-500 flex items-center justify-center">
                                <span class="text-white text-xl font-bold">
                                    {{ substr($problem->institution->name, 0, 1) }}
                                </span>
                            </div>
                        @endif
                        <div>
                            <h4 class="font-semibold text-gray-900">{{ $problem->institution->name }}</h4>
                            <p class="text-sm text-gray-600">{{ ucfirst($problem->institution->type) }}</p>
                        </div>
                    </div>

                    <div class="space-y-2 mb-4 text-sm">
                        @if($problem->institution->email)
                        <div class="flex items-start gap-2">
                            <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span class="break-all">{{ $problem->institution->email }}</span>
                        </div>
                        @endif
                        
                        @if($problem->institution->address)
                        <div class="flex items-start gap-2">
                            <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            </svg>
                            <span>{{ $problem->institution->address }}</span>
                        </div>
                        @endif
                        
                        @if($problem->institution->phone)
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <span>{{ $problem->institution->phone }}</span>
                        </div>
                        @endif
                    </div>

                    {{-- ✅ FIX: ganti institution.profile.public menjadi institution.public --}}
                    <a href="{{ route('institution.public', $problem->institution->id) }}" 
                       class="block w-full text-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-900 rounded-lg transition-colors">
                        Lihat Profile
                    </a>
                </div>

                {{-- apply card --}}
                @auth
                    @if(Auth::user()->user_type === 'student' && $problem->status === 'open')
                    <div class="bg-gradient-to-br from-blue-500 to-green-500 rounded-xl shadow-sm p-6 text-white fade-in fade-in-delay-2">
                        <h3 class="font-bold text-lg mb-2">Tertarik dengan proyek ini?</h3>
                        <p class="text-sm mb-4 text-blue-50">Aplikasikan dirimu sekarang dan mulai berkontribusi!</p>
                        
                        @if($hasApplied)
                            <div class="bg-white/20 backdrop-blur-sm rounded-lg p-3 text-center">
                                <p class="text-sm font-medium">Anda sudah mengajukan aplikasi</p>
                            </div>
                        @elseif(\Carbon\Carbon::parse($problem->application_deadline) < now())
                            <div class="bg-white/20 backdrop-blur-sm rounded-lg p-3 text-center">
                                <p class="text-sm font-medium">Deadline aplikasi telah berakhir</p>
                            </div>
                        @else
                            <a href="{{ route('student.applications.create', $problem->id) }}" 
                               class="apply-btn block w-full text-center px-4 py-3 bg-white text-white font-semibold rounded-lg hover:bg-blue-50 transition-colors">
                                Apply Sekarang
                            </a>
                            <p class="text-xs text-center mt-3 text-blue-100">
                                Deadline: {{ \Carbon\Carbon::parse($problem->application_deadline)->format('d M Y') }}
                            </p>
                        @endif
                    </div>
                    @endif
                @else
                    <div class="bg-gradient-to-br from-blue-500 to-green-500 rounded-xl shadow-sm p-6 text-white fade-in fade-in-delay-2">
                        <h3 class="font-bold text-lg mb-2">Tertarik dengan proyek ini?</h3>
                        <p class="text-sm mb-4 text-blue-50">Login atau daftar untuk mengajukan aplikasi</p>
                        <a href="{{ route('login') }}" 
                           class="block w-full text-center px-4 py-3 bg-white text-blue-600 font-semibold rounded-lg hover:bg-blue-50 transition-colors">
                            Login / Register
                        </a>
                    </div>
                @endauth

                {{-- similar problems --}}
                @if(isset($similarProblems) && $similarProblems->isNotEmpty())
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 fade-in fade-in-delay-3">
                    <h3 class="font-bold text-gray-900 mb-4">Proyek Serupa</h3>
                    <div class="space-y-4">
                        @foreach($similarProblems as $similar)
                        <a href="{{ route('student.browse-problems.detail', $similar->id) }}" 
                           class="block group similar-project">
                            <div class="border border-gray-200 rounded-lg p-3 hover:border-blue-500 transition-all duration-200">
                                <h4 class="font-semibold text-gray-900 text-sm mb-2 line-clamp-2 group-hover:text-blue-600 transition-colors">
                                    {{ $similar->title }}
                                </h4>
                                <div class="flex items-center gap-2 text-xs text-gray-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    </svg>
                                    <span>{{ $similar->regency->name ?? '-' }}</span>
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
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

// fungsi untuk toggle wishlist
async function toggleWishlist(problemId) {
    const btn = document.getElementById('wishlist-btn');
    const isWishlisted = btn.getAttribute('data-wishlisted') === 'true';
    
    try {
        const response = await fetch(`/student/wishlist/${problemId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            // toggle ui
            btn.setAttribute('data-wishlisted', data.saved ? 'true' : 'false');
            btn.classList.toggle('border-red-500', data.saved);
            btn.classList.toggle('border-gray-300', !data.saved);
            
            const svg = btn.querySelector('svg');
            svg.classList.toggle('fill-red-500', data.saved);
            svg.classList.toggle('text-red-500', data.saved);
            svg.classList.toggle('text-gray-600', !data.saved);
            svg.setAttribute('fill', data.saved ? 'currentColor' : 'none');
            
            // tambahkan animasi
            btn.classList.add('wishlisted');
            setTimeout(() => btn.classList.remove('wishlisted'), 600);
            
            // tampilkan notifikasi
            showNotification(data.message, 'success');
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('Terjadi kesalahan, coba lagi', 'error');
    }
}

// fungsi untuk share proyek
async function shareProject() {
    const url = window.location.href;
    const title = document.querySelector('h1').textContent;
    
    if (navigator.share) {
        try {
            await navigator.share({
                title: title,
                url: url
            });
        } catch (error) {
            if (error.name !== 'AbortError') {
                copyToClipboard(url);
            }
        }
    } else {
        copyToClipboard(url);
    }
}

// fungsi untuk copy ke clipboard
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        showNotification('Link berhasil disalin!', 'success');
    }).catch(() => {
        showNotification('Gagal menyalin link', 'error');
    });
}

// fungsi untuk menampilkan notifikasi
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `fixed bottom-4 right-4 px-6 py-3 rounded-lg shadow-lg text-white z-50 transform transition-all duration-300 ${
        type === 'success' ? 'bg-green-500' : 'bg-red-500'
    }`;
    notification.textContent = message;
    notification.style.opacity = '0';
    notification.style.transform = 'translateY(20px)';
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.opacity = '1';
        notification.style.transform = 'translateY(0)';
    }, 10);
    
    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transform = 'translateY(20px)';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// preload images untuk smooth gallery transition
document.addEventListener('DOMContentLoaded', () => {
    const thumbnails = document.querySelectorAll('.gallery-thumbnail img');
    thumbnails.forEach(img => {
        const preload = new Image();
        preload.src = img.src;
    });
});
</script>
@endpush