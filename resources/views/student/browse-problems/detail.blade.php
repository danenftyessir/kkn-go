{{-- resources/views/student/browse-problems/detail.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- breadcrumb --}}
        <nav class="mb-6 fade-in-up" style="animation-delay: 0.1s;">
            <ol class="flex items-center space-x-2 text-sm text-gray-600">
                <li><a href="{{ route('student.browse-problems') }}" class="hover:text-blue-600 transition-colors">Browse Problems</a></li>
                <li><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg></li>
                <li class="text-gray-900 font-semibold truncate">{{ Str::limit($problem->title, 50) }}</li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- konten utama --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- header dengan action buttons --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden fade-in-up" style="animation-delay: 0.2s;">
                    <div class="p-6">
                        {{-- badges status --}}
                        <div class="flex flex-wrap gap-2 mb-4">
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded-full">
                                {{ ucfirst($problem->status) }}
                            </span>
                            <span class="px-3 py-1 bg-gray-100 text-gray-700 text-xs font-semibold rounded-full">
                                {{ ucfirst($problem->difficulty_level) }}
                            </span>
                            @if($problem->is_featured)
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-700 text-xs font-semibold rounded-full">
                                Unggulan
                            </span>
                            @endif
                            @if($problem->is_urgent)
                            <span class="px-3 py-1 bg-red-100 text-red-700 text-xs font-semibold rounded-full animate-pulse">
                                Mendesak
                            </span>
                            @endif
                        </div>

                        {{-- judul --}}
                        <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $problem->title }}</h1>

                        {{-- info instansi --}}
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center space-x-3">
                                @if($problem->institution->logo_path)
                                <img src="{{ asset('storage/' . $problem->institution->logo_path) }}" 
                                     alt="{{ $problem->institution->name }}"
                                     class="w-12 h-12 rounded-full object-cover">
                                @else
                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-green-500 flex items-center justify-center">
                                    <span class="text-white text-lg font-bold">
                                        {{ strtoupper(substr($problem->institution->name, 0, 1)) }}
                                    </span>
                                </div>
                                @endif
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $problem->institution->name }}</p>
                                    <p class="text-sm text-gray-600">{{ $problem->institution->type }}</p>
                                </div>
                            </div>
                            
                            {{-- action buttons: share & wishlist --}}
                            <div class="flex items-center space-x-2">
                                {{-- share button --}}
                                <div x-data="shareButton()" class="relative">
                                    <button @click="toggleDropdown()" 
                                            class="p-2 rounded-lg border border-gray-300 hover:bg-gray-50 transition-all duration-200 hover:border-blue-500 group">
                                        <svg class="w-5 h-5 text-gray-600 group-hover:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-5.368m0 5.368l6.632 3.316m-6.632-6.632l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path>
                                        </svg>
                                    </button>

                                    {{-- dropdown share options --}}
                                    <div x-show="isOpen" 
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0 scale-95"
                                         x-transition:enter-end="opacity-100 scale-100"
                                         x-transition:leave="transition ease-in duration-150"
                                         x-transition:leave-start="opacity-100 scale-100"
                                         x-transition:leave-end="opacity-0 scale-95"
                                         @click.away="isOpen = false"
                                         class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50"
                                         style="display: none;">
                                        <div class="px-3 py-2 border-b border-gray-100">
                                            <p class="text-xs font-semibold text-gray-900">Bagikan ke:</p>
                                        </div>
                                        <button @click="shareToWhatsApp()" class="w-full px-4 py-2 text-left hover:bg-gray-50 transition-colors flex items-center space-x-3">
                                            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                            </svg>
                                            <span class="text-sm text-gray-700">WhatsApp</span>
                                        </button>
                                        <button @click="shareToTwitter()" class="w-full px-4 py-2 text-left hover:bg-gray-50 transition-colors flex items-center space-x-3">
                                            <svg class="w-5 h-5 text-blue-400" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                            </svg>
                                            <span class="text-sm text-gray-700">Twitter</span>
                                        </button>
                                        <button @click="shareToFacebook()" class="w-full px-4 py-2 text-left hover:bg-gray-50 transition-colors flex items-center space-x-3">
                                            <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                            </svg>
                                            <span class="text-sm text-gray-700">Facebook</span>
                                        </button>
                                        <button @click="shareToLinkedIn()" class="w-full px-4 py-2 text-left hover:bg-gray-50 transition-colors flex items-center space-x-3">
                                            <svg class="w-5 h-5 text-blue-700" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                            </svg>
                                            <span class="text-sm text-gray-700">LinkedIn</span>
                                        </button>
                                        <div class="border-t border-gray-100 mt-2 pt-2 px-4">
                                            <button @click="copyLink()" class="w-full px-2 py-2 text-left hover:bg-gray-50 transition-colors flex items-center space-x-3 rounded">
                                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                                </svg>
                                                <span class="text-sm text-gray-700">Salin Link</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                {{-- wishlist/save button --}}
                                @auth
                                    @if(auth()->user()->user_type === 'student')
                                    <div x-data="wishlistButton({{ $problem->id }}, {{ $problem->isSavedBy(auth()->user()->student) ? 'true' : 'false' }})">
                                        <button @click="toggle()" 
                                                :class="isSaved ? 'bg-red-50 border-red-300 hover:bg-red-100' : 'bg-white border-gray-300 hover:bg-gray-50'"
                                                class="p-2 rounded-lg border transition-all duration-200 group">
                                            <svg :class="isSaved ? 'text-red-600' : 'text-gray-600 group-hover:text-red-600'" 
                                                 class="w-5 h-5 transition-colors" 
                                                 :fill="isSaved ? 'currentColor' : 'none'" 
                                                 stroke="currentColor" 
                                                 viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    @endif
                                @endauth
                            </div>
                        </div>

                        {{-- statistik cepat --}}
                        <div class="grid grid-cols-4 gap-4 py-4 border-t border-b border-gray-200">
                            <div class="text-center">
                                <p class="text-2xl font-bold text-blue-600">{{ $problem->required_students }}</p>
                                <p class="text-xs text-gray-600 mt-1">Mahasiswa Dibutuhkan</p>
                            </div>
                            <div class="text-center">
                                <p class="text-2xl font-bold text-green-600">{{ $problem->duration_months }}</p>
                                <p class="text-xs text-gray-600 mt-1">Bulan</p>
                            </div>
                            <div class="text-center">
                                <p class="text-2xl font-bold text-purple-600">{{ $problem->applications_count ?? 0 }}</p>
                                <p class="text-xs text-gray-600 mt-1">Pelamar</p>
                            </div>
                            <div class="text-center">
                                @php
                                    $daysLeft = now()->diffInDays($problem->application_deadline, false);
                                @endphp
                                <p class="text-2xl font-bold {{ $daysLeft <= 7 ? 'text-red-600' : 'text-gray-900' }}">{{ max(0, $daysLeft) }}</p>
                                <p class="text-xs text-gray-600 mt-1">Hari Tersisa</p>
                            </div>
                        </div>
                    </div>

                    {{-- galeri gambar --}}
                    @if($problem->images->isNotEmpty())
                    <div class="p-6 bg-gray-50">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Dokumentasi</h3>
                        <div class="grid grid-cols-3 gap-3">
                            @foreach($problem->images as $image)
                            <div class="aspect-video rounded-lg overflow-hidden group cursor-pointer">
                                <img src="{{ asset('storage/' . $image->image_path) }}" 
                                     alt="{{ $image->caption }}"
                                     class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500">
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- deskripsi --}}
                    <div class="p-6 border-t border-gray-200">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Deskripsi Masalah</h2>
                        <div class="prose max-w-none text-gray-700">
                            {{ $problem->description }}
                        </div>
                    </div>

                    {{-- latar belakang --}}
                    @if($problem->background)
                    <div class="p-6 border-t border-gray-200">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Latar Belakang</h2>
                        <div class="prose max-w-none text-gray-700">
                            {{ $problem->background }}
                        </div>
                    </div>
                    @endif

                    {{-- tujuan --}}
                    @if($problem->objectives)
                    <div class="p-6 border-t border-gray-200">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Tujuan</h2>
                        <div class="prose max-w-none text-gray-700">
                            {{ $problem->objectives }}
                        </div>
                    </div>
                    @endif

                    {{-- ruang lingkup --}}
                    @if($problem->scope)
                    <div class="p-6 border-t border-gray-200">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Ruang Lingkup</h2>
                        <div class="prose max-w-none text-gray-700">
                            {{ $problem->scope }}
                        </div>
                    </div>
                    @endif

                    {{-- requirements --}}
                    <div class="p-6 border-t border-gray-200">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Keahlian yang Dibutuhkan</h2>
                        <div class="flex flex-wrap gap-2">
                            @php
                                $requiredSkills = is_array($problem->required_skills) 
                                    ? $problem->required_skills 
                                    : json_decode($problem->required_skills, true) ?? [];
                            @endphp
                            @foreach($requiredSkills as $skill)
                            <span class="px-3 py-2 bg-blue-100 text-blue-700 text-sm font-semibold rounded-lg">
                                {{ $skill }}
                            </span>
                            @endforeach
                        </div>
                    </div>

                    {{-- fasilitas --}}
                    @if($problem->facilities_provided)
                    <div class="p-6 border-t border-gray-200">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Fasilitas yang Disediakan</h2>
                        <div class="prose max-w-none text-gray-700">
                            {{ $problem->facilities_provided }}
                        </div>
                    </div>
                    @endif
                </div>

                {{-- similar problems --}}
                @if($similarProblems->isNotEmpty())
                <div class="fade-in-up" style="animation-delay: 0.3s;">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Proyek Serupa</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach($similarProblems as $index => $similar)
                            @include('student.browse-problems.components.problem-card', ['problem' => $similar, 'index' => $index])
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            {{-- sidebar --}}
            <div class="lg:col-span-1">
                <div class="sticky top-24 space-y-6">
                    {{-- action card --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 fade-in-up" style="animation-delay: 0.2s;">
                        @if($hasApplied)
                        <div class="text-center">
                            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <p class="text-lg font-bold text-gray-900 mb-2">Sudah Melamar</p>
                            <p class="text-sm text-gray-600 mb-4">Anda telah mengajukan aplikasi untuk proyek ini</p>
                            <a href="{{ route('student.applications.index') }}" 
                               class="block w-full px-6 py-3 bg-gray-100 text-gray-700 text-center rounded-lg hover:bg-gray-200 transition-colors font-semibold">
                                Lihat Status Aplikasi
                            </a>
                        </div>
                        @else
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Tertarik dengan Proyek Ini?</h3>
                        <a href="{{ route('student.applications.create', $problem->id) }}" 
                           class="block w-full px-6 py-3 bg-blue-600 text-white text-center rounded-lg hover:bg-blue-700 transition-all duration-200 hover:shadow-lg font-semibold transform hover:-translate-y-0.5">
                            Ajukan Aplikasi
                        </a>
                        <p class="text-xs text-gray-500 text-center mt-3">
                            Deadline: {{ $problem->application_deadline->format('d M Y') }}
                        </p>
                        @endif
                    </div>

                    {{-- kategori SDG --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 fade-in-up" style="animation-delay: 0.25s;">
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

                    {{-- lokasi --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 fade-in-up" style="animation-delay: 0.3s;">
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
                        
                        {{-- map preview dengan leaflet --}}
                        <div class="mt-4 h-48 bg-gray-200 rounded-lg overflow-hidden" id="map-preview"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<script>
// alpine component untuk share functionality
function shareButton() {
    return {
        isOpen: false,
        currentUrl: window.location.href,
        title: '{{ addslashes($problem->title) }}',
        
        toggleDropdown() {
            this.isOpen = !this.isOpen;
        },
        
        shareToWhatsApp() {
            const text = encodeURIComponent(`Lihat proyek KKN ini: ${this.title}\n${this.currentUrl}`);
            window.open(`https://wa.me/?text=${text}`, '_blank');
            this.isOpen = false;
        },
        
        shareToTwitter() {
            const text = encodeURIComponent(this.title);
            const url = encodeURIComponent(this.currentUrl);
            window.open(`https://twitter.com/intent/tweet?text=${text}&url=${url}`, '_blank');
            this.isOpen = false;
        },
        
        shareToFacebook() {
            const url = encodeURIComponent(this.currentUrl);
            window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank');
            this.isOpen = false;
        },
        
        shareToLinkedIn() {
            const url = encodeURIComponent(this.currentUrl);
            window.open(`https://www.linkedin.com/sharing/share-offsite/?url=${url}`, '_blank');
            this.isOpen = false;
        },
        
        async copyLink() {
            try {
                await navigator.clipboard.writeText(this.currentUrl);
                alert('Link berhasil disalin!');
                this.isOpen = false;
            } catch (err) {
                console.error('Failed to copy:', err);
            }
        }
    };
}

// alpine component untuk wishlist button
function wishlistButton(problemId, initialSaved) {
    return {
        isSaved: initialSaved,
        
        async toggle() {
            try {
                const response = await fetch(`/student/wishlist/${problemId}/toggle`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    this.isSaved = data.saved;
                    
                    // tampilkan notifikasi
                    const message = data.saved ? 'Ditambahkan ke wishlist' : 'Dihapus dari wishlist';
                    this.showNotification(message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan. Silakan coba lagi.');
            }
        },
        
        showNotification(message) {
            // TODO: implementasi toast notification yang lebih baik
            const notification = document.createElement('div');
            notification.className = 'fixed top-4 right-4 bg-gray-900 text-white px-4 py-3 rounded-lg shadow-lg z-50 transition-all duration-300';
            notification.textContent = message;
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.opacity = '0';
                setTimeout(() => notification.remove(), 300);
            }, 2000);
        }
    };
}

// inisialisasi map dengan leaflet
document.addEventListener('DOMContentLoaded', function() {
    // TODO: ganti dengan koordinat real dari database
    // sementara gunakan koordinat tengah Indonesia
    const defaultLat = {{ $problem->latitude ?? -2.5 }};
    const defaultLng = {{ $problem->longitude ?? 118 }};
    
    const map = L.map('map-preview', {
        center: [defaultLat, defaultLng],
        zoom: 10,
        scrollWheelZoom: false
    });
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    
    // marker untuk lokasi proyek
    const marker = L.marker([defaultLat, defaultLng]).addTo(map);
    marker.bindPopup('<b>{{ $problem->title }}</b><br>{{ $problem->regency->name }}').openPopup();
    
    // refresh map size setelah render
    setTimeout(() => {
        map.invalidateSize();
    }, 100);
});
</script>
@endpush