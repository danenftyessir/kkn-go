{{-- resources/views/student/browse-problems/detail.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- breadcrumb --}}
        <nav class="mb-6 fade-in-up" style="animation-delay: 0.1s;">
            <ol class="flex items-center space-x-2 text-sm text-gray-600">
                <li><a href="{{ route('student.browse-problems.index') }}" class="hover:text-blue-600 transition-colors">Browse Problems</a></li>
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
                                     class="w-12 h-12 rounded-full object-cover border-2 border-gray-200">
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

                            {{-- action buttons --}}
                            <div class="flex gap-2">
                                @auth
                                    @if(Auth::user()->user_type === 'student')
                                        {{-- wishlist button --}}
                                        <div x-data="wishlistToggle({{ $problem->id }}, {{ $isWishlisted ? 'true' : 'false' }})">
                                            <button @click.prevent="toggle()"
                                                    :disabled="loading"
                                                    :class="saved ? 'bg-red-50 border-red-300' : 'bg-white border-gray-300'"
                                                    class="p-3 rounded-lg border hover:shadow-lg transition-all duration-200">
                                                <svg :class="saved ? 'text-red-600' : 'text-gray-600'" 
                                                     class="w-6 h-6 transition-colors" 
                                                     :fill="saved ? 'currentColor' : 'none'" 
                                                     stroke="currentColor" 
                                                     viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                                </svg>
                                            </button>
                                        </div>

                                        {{-- share button --}}
                                        <button onclick="shareProject()" class="p-3 rounded-lg border border-gray-300 bg-white hover:shadow-lg transition-all duration-200">
                                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path>
                                            </svg>
                                        </button>
                                    @endif
                                @endauth
                            </div>
                        </div>

                        {{-- stats --}}
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 py-4 border-y border-gray-200">
                            <div class="text-center">
                                <p class="text-2xl font-bold text-blue-600">{{ $problem->required_students }}</p>
                                <p class="text-sm text-gray-600">Mahasiswa Dibutuhkan</p>
                            </div>
                            <div class="text-center">
                                <p class="text-2xl font-bold text-green-600">{{ $problem->duration_months }}</p>
                                <p class="text-sm text-gray-600">Bulan</p>
                            </div>
                            <div class="text-center">
                                <p class="text-2xl font-bold text-purple-600">{{ $problem->applications_count }}</p>
                                <p class="text-sm text-gray-600">Aplikasi</p>
                            </div>
                            <div class="text-center">
                                <p class="text-2xl font-bold text-orange-600">{{ $problem->views_count }}</p>
                                <p class="text-sm text-gray-600">Views</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- galeri foto --}}
                @if($problem->images && $problem->images->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden fade-in-up" style="animation-delay: 0.3s;">
                    <div class="p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Galeri Foto</h2>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach($problem->images as $image)
                            <div class="aspect-video rounded-lg overflow-hidden cursor-pointer hover:opacity-75 transition-opacity"
                                 onclick="openImageModal('{{ asset('storage/' . $image->image_path) }}')">
                                <img src="{{ asset('storage/' . $image->image_path) }}" 
                                     alt="{{ $image->caption }}"
                                     class="w-full h-full object-cover">
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                {{-- deskripsi masalah --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden fade-in-up" style="animation-delay: 0.4s;">
                    <div class="p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Deskripsi Masalah</h2>
                        <div class="prose max-w-none text-gray-700">
                            {!! nl2br(e($problem->description)) !!}
                        </div>
                    </div>
                </div>

                {{-- background/latar belakang --}}
                @if($problem->background)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden fade-in-up" style="animation-delay: 0.5s;">
                    <div class="p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Latar Belakang</h2>
                        <div class="prose max-w-none text-gray-700">
                            {!! nl2br(e($problem->background)) !!}
                        </div>
                    </div>
                </div>
                @endif

                {{-- objectives/tujuan --}}
                @if($problem->objectives)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden fade-in-up" style="animation-delay: 0.6s;">
                    <div class="p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Tujuan</h2>
                        <div class="prose max-w-none text-gray-700">
                            {!! nl2br(e($problem->objectives)) !!}
                        </div>
                    </div>
                </div>
                @endif

                {{-- scope/ruang lingkup --}}
                @if($problem->scope)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden fade-in-up" style="animation-delay: 0.7s;">
                    <div class="p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Ruang Lingkup</h2>
                        <div class="prose max-w-none text-gray-700">
                            {!! nl2br(e($problem->scope)) !!}
                        </div>
                    </div>
                </div>
                @endif

                {{-- expected outcomes --}}
                @if($problem->expected_outcomes)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden fade-in-up" style="animation-delay: 0.8s;">
                    <div class="p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Hasil yang Diharapkan</h2>
                        <div class="prose max-w-none text-gray-700">
                            {!! nl2br(e($problem->expected_outcomes)) !!}
                        </div>
                    </div>
                </div>
                @endif

                {{-- deliverables --}}
                @if($problem->deliverables)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden fade-in-up" style="animation-delay: 0.9s;">
                    <div class="p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Deliverables</h2>
                        @php
                            // parse deliverables dengan aman
                            $deliverables = [];
                            if (is_array($problem->deliverables)) {
                                $deliverables = $problem->deliverables;
                            } elseif (is_string($problem->deliverables)) {
                                $deliverables = json_decode($problem->deliverables, true) ?? [];
                            }
                        @endphp
                        @if(count($deliverables) > 0)
                        <ul class="list-disc list-inside space-y-2 text-gray-700">
                            @foreach($deliverables as $deliverable)
                                <li>{{ $deliverable }}</li>
                            @endforeach
                        </ul>
                        @else
                        <div class="prose max-w-none text-gray-700">
                            {!! nl2br(e($problem->deliverables)) !!}
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                {{-- fasilitas --}}
                @if($problem->facilities_provided)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden fade-in-up" style="animation-delay: 1.0s;">
                    <div class="p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Fasilitas yang Disediakan</h2>
                        @php
                            // parse facilities dengan aman
                            $facilities = [];
                            if (is_array($problem->facilities_provided)) {
                                $facilities = $problem->facilities_provided;
                            } elseif (is_string($problem->facilities_provided)) {
                                $facilities = json_decode($problem->facilities_provided, true) ?? [];
                            }
                        @endphp
                        @if(count($facilities) > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach($facilities as $facility)
                                <div class="flex items-center gap-2 p-3 bg-blue-50 rounded-lg">
                                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-gray-700">{{ $facility }}</span>
                                </div>
                            @endforeach
                        </div>
                        @else
                        <div class="prose max-w-none text-gray-700">
                            {!! nl2br(e($problem->facilities_provided)) !!}
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            {{-- sidebar --}}
            <div class="lg:col-span-1">
                <div class="sticky top-24 space-y-6">
                    {{-- info card --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden fade-in-up" style="animation-delay: 0.3s;">
                        <div class="p-6">
                            <h3 class="font-bold text-gray-900 mb-4">Informasi Proyek</h3>
                            
                            <div class="space-y-4">
                                {{-- lokasi --}}
                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm text-gray-600">Lokasi</p>
                                        <p class="font-semibold text-gray-900">{{ $problem->regency->name ?? '' }}, {{ $problem->province->name ?? '' }}</p>
                                    </div>
                                </div>

                                {{-- kategori SDG --}}
                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm text-gray-600">Kategori SDG</p>
                                        <div class="flex flex-wrap gap-1 mt-1">
                                            @php
                                                // parse sdg_categories dengan aman
                                                $sdgCategories = [];
                                                if ($problem->sdg_categories) {
                                                    if (is_array($problem->sdg_categories)) {
                                                        $sdgCategories = $problem->sdg_categories;
                                                    } elseif (is_string($problem->sdg_categories)) {
                                                        $sdgCategories = json_decode($problem->sdg_categories, true) ?? [];
                                                    }
                                                }
                                            @endphp
                                            @forelse($sdgCategories as $sdg)
                                                <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-medium rounded">
                                                    {{ is_numeric($sdg) ? 'SDG ' . $sdg : ucfirst(str_replace('_', ' ', $sdg)) }}
                                                </span>
                                            @empty
                                                <span class="text-sm text-gray-500">Tidak ada kategori</span>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>

                                {{-- skills --}}
                                @if($problem->required_skills)
                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm text-gray-600">Skills yang Dibutuhkan</p>
                                        <div class="flex flex-wrap gap-1 mt-1">
                                            @php
                                                // parse required_skills dengan aman
                                                $skills = [];
                                                if (is_array($problem->required_skills)) {
                                                    $skills = $problem->required_skills;
                                                } elseif (is_string($problem->required_skills)) {
                                                    // coba decode JSON dulu
                                                    $decoded = json_decode($problem->required_skills, true);
                                                    if (is_array($decoded)) {
                                                        $skills = $decoded;
                                                    } else {
                                                        // jika bukan JSON, split by comma
                                                        $skills = array_map('trim', explode(',', $problem->required_skills));
                                                    }
                                                }
                                            @endphp
                                            @foreach($skills as $skill)
                                                @if(!empty(trim($skill)))
                                                <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded">
                                                    {{ trim($skill) }}
                                                </span>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @endif

                                {{-- deadline --}}
                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm text-gray-600">Deadline Aplikasi</p>
                                        <p class="font-semibold text-gray-900">{{ $problem->application_deadline ? \Carbon\Carbon::parse($problem->application_deadline)->format('d M Y') : 'Tidak ditentukan' }}</p>
                                    </div>
                                </div>
                            </div>

                            {{-- apply button --}}
                            @auth
                                @if(Auth::user()->user_type === 'student')
                                    @if($problem->status === 'open')
                                        @if(!$hasApplied)
                                            <a href="{{ route('student.applications.create', $problem->id) }}" 
                                               class="mt-6 w-full block text-center px-6 py-3 bg-gradient-to-r from-blue-600 to-green-600 text-white rounded-lg hover:shadow-lg transition-all duration-200 font-semibold">
                                                Ajukan Aplikasi
                                            </a>
                                        @else
                                            <div class="mt-6 w-full block text-center px-6 py-3 bg-gray-100 text-gray-600 rounded-lg font-semibold">
                                                Sudah Mengajukan
                                            </div>
                                        @endif
                                    @else
                                        <div class="mt-6 w-full block text-center px-6 py-3 bg-gray-100 text-gray-600 rounded-lg font-semibold">
                                            Proyek {{ ucfirst($problem->status) }}
                                        </div>
                                    @endif
                                @endif
                            @else
                                <a href="{{ route('login') }}" 
                                   class="mt-6 w-full block text-center px-6 py-3 bg-gradient-to-r from-blue-600 to-green-600 text-white rounded-lg hover:shadow-lg transition-all duration-200 font-semibold">
                                    Login untuk Melamar
                                </a>
                            @endauth
                        </div>
                    </div>

                    {{-- similar problems --}}
                    @if(isset($similarProblems) && $similarProblems->count() > 0)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden fade-in-up" style="animation-delay: 0.4s;">
                        <div class="p-6">
                            <h3 class="font-bold text-gray-900 mb-4">Proyek Serupa</h3>
                            <div class="space-y-3">
                                @foreach($similarProblems as $similar)
                                <a href="{{ route('student.browse-problems.show', $similar->id) }}" class="block p-3 rounded-lg hover:bg-gray-50 transition-colors">
                                    <h4 class="font-semibold text-sm text-gray-900 line-clamp-2 mb-1">{{ $similar->title }}</h4>
                                    <p class="text-xs text-gray-600">{{ $similar->institution->name }}</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ $similar->regency->name ?? '' }}</p>
                                </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- image modal --}}
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden items-center justify-center" onclick="closeImageModal()">
    <div class="relative max-w-7xl max-h-screen p-4">
        <button onclick="closeImageModal()" class="absolute top-4 right-4 text-white hover:text-gray-300">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        <img id="modalImage" src="" alt="" class="max-w-full max-h-screen object-contain">
    </div>
</div>

@push('styles')
<style>
    @keyframes fade-in-up {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .fade-in-up {
        animation: fade-in-up 0.6s ease-out forwards;
    }

    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endpush

@push('scripts')
<script>
// fungsi untuk membuka modal gambar
function openImageModal(imageSrc) {
    const modal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');
    modalImage.src = imageSrc;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

// fungsi untuk menutup modal gambar
function closeImageModal() {
    const modal = document.getElementById('imageModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// fungsi untuk share project
function shareProject() {
    const url = window.location.href;
    const title = '{{ $problem->title }}';
    
    if (navigator.share) {
        navigator.share({
            title: title,
            text: 'Lihat proyek KKN ini: ' + title,
            url: url
        }).catch(err => console.log('Error sharing:', err));
    } else {
        // fallback: copy to clipboard
        navigator.clipboard.writeText(url).then(() => {
            alert('Link berhasil disalin ke clipboard!');
        }).catch(err => {
            console.error('Error copying to clipboard:', err);
        });
    }
}

// wishlist toggle component
function wishlistToggle(problemId, initialSaved) {
    return {
        saved: initialSaved,
        loading: false,
        
        async toggle() {
            if (this.loading) return;
            
            this.loading = true;
            
            try {
                const response = await fetch(`/student/wishlist/toggle/${problemId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    this.saved = data.wishlisted;
                    
                    // tampilkan notifikasi
                    const message = this.saved ? 'Ditambahkan ke wishlist' : 'Dihapus dari wishlist';
                    showNotification(message);
                }
            } catch (error) {
                console.error('Error toggling wishlist:', error);
                showNotification('Terjadi kesalahan', 'error');
            } finally {
                this.loading = false;
            }
        }
    }
}

// fungsi untuk menampilkan notifikasi
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 ${
        type === 'success' ? 'bg-green-500' : 'bg-red-500'
    } text-white font-semibold`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transition = 'opacity 0.3s ease-out';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// increment view count saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    fetch(`/api/problems/{{ $problem->id }}/view`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    }).catch(err => console.error('Error incrementing view:', err));
});
</script>
@endpush
@endsection