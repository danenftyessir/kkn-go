@extends('layouts.app')

@section('title', $problem->title)

@push('styles')
<style>
/* custom styles untuk problem detail */
.detail-container {
    animation: fadeInUp 0.6s cubic-bezier(0.4, 0, 0.2, 1);
}

.image-gallery {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
}

.gallery-image {
    aspect-ratio: 16/9;
    overflow: hidden;
    border-radius: 0.5rem;
    cursor: pointer;
    transition: transform 0.3s ease;
}

.gallery-image:hover {
    transform: scale(1.05);
}

.sticky-sidebar {
    position: sticky;
    top: 5rem;
}
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- breadcrumb -->
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('home') }}" class="text-gray-600 hover:text-blue-600">
                        Beranda
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <a href="{{ route('student.browse-problems') }}" class="ml-1 text-gray-600 hover:text-blue-600">
                            Jelajahi Masalah
                        </a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-gray-500">Detail Masalah</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="lg:grid lg:grid-cols-3 lg:gap-8">
            <!-- main content -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden detail-container">
                    
                    <!-- header dengan badges -->
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex flex-wrap gap-2 mb-4">
                            @if($problem->is_featured)
                            <span class="px-3 py-1 bg-yellow-500 text-white text-sm font-semibold rounded-full">
                                ⭐ Unggulan
                            </span>
                            @endif
                            
                            @if($problem->is_urgent)
                            <span class="px-3 py-1 bg-red-500 text-white text-sm font-semibold rounded-full animate-pulse">
                                🔥 Mendesak
                            </span>
                            @endif
                            
                            <span class="px-3 py-1 {{ $problem->getDifficultyBadgeColor() }} text-sm font-semibold rounded-full">
                                {{ $problem->getDifficultyLabel() }}
                            </span>
                        </div>

                        <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $problem->title }}</h1>
                        
                        <!-- institution info -->
                        <div class="flex items-center">
                            @if($problem->institution->logo_path)
                            <img src="{{ asset('storage/' . $problem->institution->logo_path) }}" 
                                 alt="{{ $problem->institution->name }}"
                                 class="w-12 h-12 rounded-full object-cover mr-3">
                            @else
                            <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center mr-3">
                                <span class="text-lg font-semibold text-gray-600">
                                    {{ substr($problem->institution->name, 0, 1) }}
                                </span>
                            </div>
                            @endif
                            <div>
                                <p class="text-lg font-semibold text-gray-900">{{ $problem->institution->name }}</p>
                                <p class="text-sm text-gray-600">
                                    {{ $problem->regency->name }}, {{ $problem->province->name }}
                                </p>
                            </div>
                        </div>

                        <!-- meta info -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6 pt-6 border-t border-gray-200">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-blue-600">{{ $problem->required_students }}</div>
                                <div class="text-sm text-gray-600">Mahasiswa Dibutuhkan</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-green-600">{{ $problem->getRemainingSlots() }}</div>
                                <div class="text-sm text-gray-600">Slot Tersisa</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-purple-600">{{ $problem->duration_months }}</div>
                                <div class="text-sm text-gray-600">Bulan</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-orange-600">{{ $problem->views_count }}</div>
                                <div class="text-sm text-gray-600">Dilihat</div>
                            </div>
                        </div>
                    </div>

                    <!-- image gallery -->
                    @if($problem->images->isNotEmpty())
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Galeri Foto</h2>
                        <div class="image-gallery">
                            @foreach($problem->images as $image)
                            <div class="gallery-image">
                                <img src="{{ asset('storage/' . $image->image_path) }}" 
                                     alt="{{ $image->caption }}"
                                     class="w-full h-full object-cover">
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- deskripsi -->
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Deskripsi Masalah</h2>
                        <div class="prose max-w-none text-gray-700">
                            {{ $problem->description }}
                        </div>
                    </div>

                    <!-- latar belakang -->
                    @if($problem->background)
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Latar Belakang</h2>
                        <div class="prose max-w-none text-gray-700">
                            {{ $problem->background }}
                        </div>
                    </div>
                    @endif

                    <!-- tujuan -->
                    @if($problem->objectives)
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Tujuan</h2>
                        <div class="prose max-w-none text-gray-700">
                            {{ $problem->objectives }}
                        </div>
                    </div>
                    @endif

                    <!-- ruang lingkup -->
                    @if($problem->scope)
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Ruang Lingkup</h2>
                        <div class="prose max-w-none text-gray-700">
                            {{ $problem->scope }}
                        </div>
                    </div>
                    @endif

                    <!-- requirements -->
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Keahlian yang Dibutuhkan</h2>
                        <div class="flex flex-wrap gap-2">
                            @php
                                $requiredSkills = is_array($problem->required_skills) 
                                    ? $problem->required_skills 
                                    : json_decode($problem->required_skills, true) ?? [];
                            @endphp
                            @foreach($requiredSkills as $skill)
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-medium">
                                {{ $skill }}
                            </span>
                            @endforeach
                        </div>

                        @if($problem->required_majors)
                        <h3 class="text-lg font-semibold text-gray-900 mt-6 mb-3">Jurusan yang Dicari</h3>
                        <ul class="list-disc list-inside space-y-1 text-gray-700">
                            @php
                                $requiredMajors = is_array($problem->required_majors) 
                                    ? $problem->required_majors 
                                    : json_decode($problem->required_majors, true) ?? [];
                            @endphp
                            @foreach($requiredMajors as $major)
                            <li>{{ $major }}</li>
                            @endforeach
                        </ul>
                        @endif
                    </div>

                    <!-- expected outcomes -->
                    @if($problem->expected_outcomes)
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Target Hasil</h2>
                        <div class="prose max-w-none text-gray-700">
                            {{ $problem->expected_outcomes }}
                        </div>
                    </div>
                    @endif

                    <!-- fasilitas -->
                    @if($problem->facilities_provided)
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Fasilitas yang Disediakan</h2>
                        <ul class="space-y-2">
                            @php
                                $facilities = is_array($problem->facilities_provided) 
                                    ? $problem->facilities_provided 
                                    : json_decode($problem->facilities_provided, true) ?? [];
                            @endphp
                            @foreach($facilities as $facility)
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-gray-700">{{ $facility }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <!-- TODO: Q&A section -->
                    <div class="p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Pertanyaan & Jawaban</h2>
                        <p class="text-gray-600">Belum ada pertanyaan. Jadilah yang pertama bertanya!</p>
                        <button class="mt-4 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                            Ajukan Pertanyaan
                        </button>
                    </div>
                </div>

                <!-- similar problems -->
                @if($similarProblems->isNotEmpty())
                <div class="mt-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Masalah Serupa</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($similarProblems as $similar)
                            @include('student.browse-problems.components.problem-card', ['problem' => $similar, 'index' => $loop->index])
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- sidebar -->
            <div class="lg:col-span-1 mt-8 lg:mt-0">
                <div class="sticky-sidebar">
                    <!-- apply card -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Informasi Aplikasi</h3>
                        
                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Deadline</span>
                                <span class="text-sm font-semibold text-red-600">
                                    {{ $problem->application_deadline->format('d M Y') }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Mulai</span>
                                <span class="text-sm font-semibold text-gray-900">
                                    {{ $problem->start_date->format('d M Y') }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Selesai</span>
                                <span class="text-sm font-semibold text-gray-900">
                                    {{ $problem->end_date->format('d M Y') }}
                                </span>
                            </div>
                        </div>

                        @if($hasApplied)
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-sm font-medium text-green-800">Anda sudah melamar</span>
                            </div>
                        </div>
                        <a href="{{ route('student.applications.index') }}" 
                           class="block w-full px-4 py-3 bg-gray-100 text-gray-700 text-center font-semibold rounded-lg hover:bg-gray-200 transition-colors">
                            Lihat Status Lamaran
                        </a>
                        @elseif($problem->getRemainingSlots() > 0 && $problem->isOpenForApplication())
                        <button onclick="alert('TODO: Implementasi form aplikasi')"
                                class="w-full px-4 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                            Lamar Sekarang
                        </button>
                        @else
                        <button disabled
                                class="w-full px-4 py-3 bg-gray-300 text-gray-600 font-semibold rounded-lg cursor-not-allowed">
                            Tidak Tersedia
                        </button>
                        @endif

                        <!-- action buttons -->
                        <div class="flex gap-2 mt-4">
                            <button class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors flex items-center justify-center">
                                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                                </svg>
                                Simpan
                            </button>
                            <button class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors flex items-center justify-center">
                                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path>
                                </svg>
                                Bagikan
                            </button>
                        </div>
                    </div>

                    <!-- SDG categories -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Kategori SDG</h3>
                        <div class="flex flex-wrap gap-2">
                            @php
                                $sdgCategories = is_array($problem->sdg_categories) 
                                    ? $problem->sdg_categories 
                                    : json_decode($problem->sdg_categories, true) ?? [];
                            @endphp
                            @foreach($sdgCategories as $sdg)
                            <span class="px-3 py-2 bg-blue-100 text-blue-700 text-sm font-semibold rounded-lg">
                                SDG {{ $sdg }}
                            </span>
                            @endforeach
                        </div>
                    </div>

                    <!-- lokasi -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Lokasi</h3>
                        <div class="space-y-2 text-sm text-gray-700">
                            @if($problem->village)
                            <p><span class="font-semibold">Desa:</span> {{ $problem->village }}</p>
                            @endif
                            <p><span class="font-semibold">Kabupaten:</span> {{ $problem->regency->name }}</p>
                            <p><span class="font-semibold">Provinsi:</span> {{ $problem->province->name }}</p>
                            @if($problem->detailed_location)
                            <p class="mt-3 text-gray-600">{{ $problem->detailed_location }}</p>
                            @endif
                        </div>
                        
                        <!-- TODO: Map preview -->
                        <div class="mt-4 h-48 bg-gray-200 rounded-lg flex items-center justify-center">
                            <span class="text-gray-500">Map Preview (TODO)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection