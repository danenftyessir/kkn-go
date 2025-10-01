@extends('layouts.app')

@section('title', $problem->title)

@push('styles')
<style>
/* custom styles untuk detail page */
.detail-hero {
    background: linear-gradient(135deg, #0066CC 0%, #66CC00 100%);
}

.sticky-sidebar {
    position: sticky;
    top: 5rem;
    max-height: calc(100vh - 6rem);
}

.gallery-image {
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.gallery-image:hover {
    transform: scale(1.05);
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
}

.tab-button {
    transition: all 0.2s ease;
    border-bottom: 2px solid transparent;
}

.tab-button.active {
    color: #0066CC;
    border-bottom-color: #0066CC;
}

/* image lightbox */
.lightbox {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.9);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem;
}

.lightbox img {
    max-width: 90vw;
    max-height: 90vh;
    object-fit: contain;
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.slide-in-right {
    animation: slideInRight 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50" x-data="problemDetail()">
    
    <!-- hero section dengan breadcrumb -->
    <div class="detail-hero text-white py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- breadcrumb -->
            <nav class="flex mb-4 text-sm">
                <a href="{{ route('student.browse-problems') }}" class="hover:text-white/80 transition-colors">
                    Jelajahi Masalah
                </a>
                <span class="mx-2">/</span>
                <span class="text-white/80">{{ Str::limit($problem->title, 50) }}</span>
            </nav>
            
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <h1 class="text-3xl md:text-4xl font-bold mb-2">{{ $problem->title }}</h1>
                    <div class="flex flex-wrap items-center gap-4 text-sm">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            {{ $problem->institution->name }}
                        </div>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            </svg>
                            {{ $problem->regency->name }}, {{ $problem->province->name }}
                        </div>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            {{ $problem->views_count }} views
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="lg:grid lg:grid-cols-3 lg:gap-8">
            
            <!-- main content -->
            <main class="lg:col-span-2">
                
                <!-- badges dan tags -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                    <div class="flex flex-wrap gap-2 mb-4">
                        @if($problem->is_featured)
                        <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-sm font-semibold rounded-full">
                            ⭐ Unggulan
                        </span>
                        @endif
                        
                        @if($problem->is_urgent)
                        <span class="px-3 py-1 bg-red-100 text-red-800 text-sm font-semibold rounded-full">
                            🔥 Mendesak
                        </span>
                        @endif
                        
                        <span class="px-3 py-1 {{ $problem->getDifficultyBadgeColor() }} text-sm font-semibold rounded-full">
                            {{ $problem->getDifficultyLabel() }}
                        </span>
                        
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm font-semibold rounded-full">
                            {{ $problem->getFormattedDuration() }}
                        </span>
                    </div>

                    <!-- SDG categories -->
                    <div>
                        <p class="text-sm font-medium text-gray-700 mb-2">Kategori SDG:</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach($problem->sdg_categories as $sdg)
                            <span class="px-3 py-1 bg-green-100 text-green-800 text-sm font-medium rounded-lg">
                                SDG {{ $sdg }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- galeri foto -->
                @if($problem->images->isNotEmpty())
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Galeri Dokumentasi</h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($problem->images as $image)
                        <div class="gallery-image rounded-lg overflow-hidden"
                             @click="openLightbox('{{ asset('storage/' . $image->image_path) }}')">
                            <img src="{{ asset('storage/' . $image->image_path) }}" 
                                 alt="{{ $image->caption ?? $problem->title }}"
                                 class="w-full h-48 object-cover"
                                 loading="lazy">
                            @if($image->caption)
                            <p class="text-xs text-gray-600 mt-2 px-2">{{ $image->caption }}</p>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- tabs navigation -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                    <div class="border-b border-gray-200">
                        <nav class="flex space-x-8 px-6" x-data="{ activeTab: 'deskripsi' }">
                            <button @click="activeTab = 'deskripsi'" 
                                    :class="activeTab === 'deskripsi' ? 'active' : ''"
                                    class="tab-button py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700">
                                Deskripsi
                            </button>
                            <button @click="activeTab = 'kebutuhan'" 
                                    :class="activeTab === 'kebutuhan' ? 'active' : ''"
                                    class="tab-button py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700">
                                Kebutuhan
                            </button>
                            <button @click="activeTab = 'fasilitas'" 
                                    :class="activeTab === 'fasilitas' ? 'active' : ''"
                                    class="tab-button py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700">
                                Fasilitas
                            </button>
                            <button @click="activeTab = 'qna'" 
                                    :class="activeTab === 'qna' ? 'active' : ''"
                                    class="tab-button py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700">
                                Q&A
                            </button>
                        </nav>
                    </div>

                    <!-- tab content deskripsi -->
                    <div x-show="activeTab === 'deskripsi'" class="p-6">
                        <div class="prose max-w-none">
                            <h3 class="text-lg font-bold text-gray-900 mb-3">Deskripsi Masalah</h3>
                            <p class="text-gray-700 mb-6 whitespace-pre-line">{{ $problem->description }}</p>
                            
                            @if($problem->background)
                            <h3 class="text-lg font-bold text-gray-900 mb-3">Latar Belakang</h3>
                            <p class="text-gray-700 mb-6 whitespace-pre-line">{{ $problem->background }}</p>
                            @endif
                            
                            @if($problem->objectives)
                            <h3 class="text-lg font-bold text-gray-900 mb-3">Tujuan</h3>
                            <p class="text-gray-700 mb-6 whitespace-pre-line">{{ $problem->objectives }}</p>
                            @endif
                            
                            @if($problem->scope)
                            <h3 class="text-lg font-bold text-gray-900 mb-3">Ruang Lingkup</h3>
                            <p class="text-gray-700 whitespace-pre-line">{{ $problem->scope }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- tab content kebutuhan -->
                    <div x-show="activeTab === 'kebutuhan'" class="p-6" style="display: none;">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Kebutuhan Mahasiswa</h3>
                        
                        <div class="space-y-6">
                            <!-- jumlah mahasiswa -->
                            <div class="flex items-start">
                                <div class="bg-blue-100 rounded-lg p-3 mr-4">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">Jumlah Mahasiswa Dibutuhkan</p>
                                    <p class="text-2xl font-bold text-blue-600 mt-1">{{ $problem->required_students }} orang</p>
                                </div>
                            </div>

                            <!-- skills required -->
                            @if($problem->required_skills)
                            <div>
                                <p class="font-semibold text-gray-900 mb-2">Skills yang Dibutuhkan:</p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($problem->required_skills as $skill)
                                    <span class="px-3 py-1 bg-purple-100 text-purple-800 text-sm font-medium rounded-lg">
                                        {{ $skill }}
                                    </span>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <!-- majors required -->
                            @if($problem->required_majors)
                            <div>
                                <p class="font-semibold text-gray-900 mb-2">Jurusan yang Dicari:</p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($problem->required_majors as $major)
                                    <span class="px-3 py-1 bg-indigo-100 text-indigo-800 text-sm font-medium rounded-lg">
                                        {{ $major }}
                                    </span>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <!-- expected outcomes -->
                            @if($problem->expected_outcomes)
                            <div>
                                <p class="font-semibold text-gray-900 mb-2">Expected Outcomes:</p>
                                <p class="text-gray-700 whitespace-pre-line">{{ $problem->expected_outcomes }}</p>
                            </div>
                            @endif

                            <!-- deliverables -->
                            @if($problem->deliverables)
                            <div>
                                <p class="font-semibold text-gray-900 mb-2">Deliverables:</p>
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach($problem->deliverables as $deliverable)
                                    <li class="text-gray-700">{{ $deliverable }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- tab content fasilitas -->
                    <div x-show="activeTab === 'fasilitas'" class="p-6" style="display: none;">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Fasilitas yang Disediakan</h3>
                        
                        @if($problem->facilities_provided)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($problem->facilities_provided as $facility)
                            <div class="flex items-center p-4 bg-green-50 rounded-lg border border-green-200">
                                <svg class="w-6 h-6 text-green-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-800">{{ $facility }}</span>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <p class="text-gray-500 italic">Informasi fasilitas akan dibahas lebih lanjut saat proses seleksi.</p>
                        @endif
                    </div>

                    <!-- tab content Q&A -->
                    <div x-show="activeTab === 'qna'" class="p-6" style="display: none;">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Pertanyaan & Jawaban</h3>
                        
                        <!-- TODO: implementasi Q&A dari database -->
                        <div class="text-center py-8">
                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-gray-500 mb-4">Belum ada pertanyaan untuk proyek ini</p>
                            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                Ajukan Pertanyaan
                            </button>
                        </div>
                    </div>
                </div>

                <!-- similar problems -->
                @if($similarProblems->isNotEmpty())
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Masalah Serupa</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($similarProblems as $similar)
                        <a href="{{ route('student.problems.show', $similar->id) }}" 
                           class="block p-4 border border-gray-200 rounded-lg hover:border-blue-500 hover:shadow-md transition-all">
                            <h4 class="font-semibold text-gray-900 mb-2 line-clamp-2">{{ $similar->title }}</h4>
                            <p class="text-sm text-gray-600 mb-2">{{ $similar->institution->name }}</p>
                            <div class="flex items-center text-sm text-gray-500">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                </svg>
                                {{ $similar->regency->name }}
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </main>

            <!-- sidebar -->
            <aside class="lg:col-span-1 mt-6 lg:mt-0">
                <div class="sticky-sidebar space-y-6">
                    
                    <!-- action card -->
                    <div class="bg-white rounded-lg shadow-lg border border-gray-200 p-6 slide-in-right">
                        <div class="text-center mb-6">
                            <div class="bg-blue-100 rounded-full p-4 inline-block mb-4">
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $problem->getRemainingSlots() }}</h3>
                            <p class="text-sm text-gray-600">Slot tersisa dari {{ $problem->required_students }} mahasiswa</p>
                        </div>

                        <!-- deadline -->
                        <div class="bg-red-50 rounded-lg p-4 mb-6">
                            <div class="flex items-center text-red-600 mb-1">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="font-semibold">Deadline Aplikasi</span>
                            </div>
                            <p class="text-2xl font-bold text-red-600">
                                {{ $problem->application_deadline->format('d M Y') }}
                            </p>
                            <p class="text-sm text-red-600 mt-1">
                                {{ $problem->application_deadline->diffForHumans() }}
                            </p>
                        </div>

                        <!-- timeline -->
                        <div class="space-y-3 mb-6">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-gray-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Mulai Proyek</p>
                                    <p class="text-sm text-gray-600">{{ $problem->start_date->format('d M Y') }}</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-gray-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Selesai Proyek</p>
                                    <p class="text-sm text-gray-600">{{ $problem->end_date->format('d M Y') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- action buttons -->
                        @if($hasApplied)
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                            <div class="flex items-center text-green-800">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="font-semibold">Anda sudah melamar</span>
                            </div>
                        </div>
                        <a href="{{ route('student.applications.index') }}" 
                           class="block w-full px-6 py-3 bg-gray-100 text-gray-700 text-center font-semibold rounded-lg hover:bg-gray-200 transition-colors">
                            Lihat Status Aplikasi
                        </a>
                        @elseif($problem->isOpenForApplication())
                        <a href="{{ route('student.applications.create', ['problem_id' => $problem->id]) }}" 
                           class="block w-full px-6 py-3 bg-blue-600 text-white text-center font-semibold rounded-lg hover:bg-blue-700 transition-colors mb-3">
                            Lamar Sekarang
                        </a>
                        @else
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-4">
                            <p class="text-gray-600 text-center">Aplikasi sudah ditutup atau slot penuh</p>
                        </div>
                        @endif

                        <!-- TODO: wishlist button -->
                        <button class="w-full px-6 py-3 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition-colors flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                            </svg>
                            Simpan ke Wishlist
                        </button>

                        <!-- share button -->
                        <button @click="shareProject()" 
                                class="w-full mt-3 px-6 py-3 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition-colors flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path>
                            </svg>
                            Bagikan Proyek
                        </button>
                    </div>

                    <!-- institution info card -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="font-bold text-gray-900 mb-4">Informasi Instansi</h3>
                        
                        <div class="flex items-start mb-4">
                            @if($problem->institution->logo_path)
                            <img src="{{ asset('storage/' . $problem->institution->logo_path) }}" 
                                 alt="{{ $problem->institution->name }}"
                                 class="w-16 h-16 rounded-lg object-cover mr-4">
                            @else
                            <div class="w-16 h-16 rounded-lg bg-gray-200 flex items-center justify-center mr-4">
                                <span class="text-2xl font-bold text-gray-600">
                                    {{ substr($problem->institution->name, 0, 1) }}
                                </span>
                            </div>
                            @endif
                            
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-900 mb-1">{{ $problem->institution->name }}</h4>
                                <p class="text-sm text-gray-600">{{ $problem->institution->type }}</p>
                            </div>
                        </div>

                        @if($problem->institution->description)
                        <p class="text-sm text-gray-700 mb-4">{{ Str::limit($problem->institution->description, 150) }}</p>
                        @endif

                        <div class="space-y-2 text-sm">
                            <div class="flex items-start">
                                <svg class="w-4 h-4 text-gray-400 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                </svg>
                                <span class="text-gray-600">{{ $problem->institution->getFullAddress() }}</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                <span class="text-gray-600">{{ $problem->institution->email }}</span>
                            </div>
                        </div>

                        <a href="#" class="block mt-4 text-sm text-blue-600 hover:text-blue-800 font-medium">
                            Lihat Profil Lengkap →
                        </a>
                    </div>
                </div>
            </aside>
        </div>
    </div>

    <!-- lightbox -->
    <div x-show="lightboxOpen" 
         x-cloak
         @click="closeLightbox()"
         class="lightbox"
         style="display: none;">
        <img :src="lightboxImage" alt="Gallery Image" class="rounded-lg">
        <button @click="closeLightbox()" 
                class="absolute top-4 right-4 text-white hover:text-gray-300">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
</div>
@endsection

@push('scripts')
<script>
function problemDetail() {
    return {
        lightboxOpen: false,
        lightboxImage: '',
        
        openLightbox(imageUrl) {
            this.lightboxImage = imageUrl;
            this.lightboxOpen = true;
            document.body.style.overflow = 'hidden';
        },
        
        closeLightbox() {
            this.lightboxOpen = false;
            this.lightboxImage = '';
            document.body.style.overflow = '';
        },
        
        shareProject() {
            if (navigator.share) {
                navigator.share({
                    title: '{{ $problem->title }}',
                    text: 'Lihat proyek KKN ini: {{ $problem->title }}',
                    url: window.location.href
                }).catch(err => console.log('Error sharing:', err));
            } else {
                // fallback: copy to clipboard
                navigator.clipboard.writeText(window.location.href);
                alert('Link berhasil disalin ke clipboard!');
            }
        }
    }
}
</script>
@endpush