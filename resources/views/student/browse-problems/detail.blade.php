@extends('layouts.app')

@section('title', $problem->title)

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- breadcrumb --}}
        <nav class="mb-6" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2 text-sm text-gray-600">
                <li>
                    <a href="{{ route('student.browse-problems.index') }}" class="hover:text-blue-600 transition-colors">
                        Browse Problems
                    </a>
                </li>
                <li>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </li>
                <li class="text-gray-900 font-medium truncate max-w-md">
                    {{ $problem->title }}
                </li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            {{-- main content --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- header section --}}
                <div class="bg-white rounded-xl shadow-sm p-6">
                    {{-- status badge --}}
                    @php
                        $statusConfig = [
                            'open' => ['bg' => 'bg-green-500', 'text' => 'Terbuka'],
                            'in_progress' => ['bg' => 'bg-blue-500', 'text' => 'Berlangsung'],
                            'closed' => ['bg' => 'bg-gray-500', 'text' => 'Ditutup'],
                            'completed' => ['bg' => 'bg-purple-500', 'text' => 'Selesai'],
                        ];
                        $status = $statusConfig[$problem->status] ?? $statusConfig['open'];
                    @endphp
                    
                    <div class="flex items-center justify-between mb-4">
                        <span class="{{ $status['bg'] }} text-white text-sm font-semibold px-4 py-1.5 rounded-full">
                            {{ $status['text'] }}
                        </span>
                        
                        {{-- wishlist & share --}}
                        <div class="flex items-center gap-2">
                            <button onclick="toggleWishlist({{ $problem->id }}, this)" 
                                    class="px-4 py-2 border border-gray-300 rounded-lg hover:border-red-500 hover:text-red-500 transition-all duration-200 flex items-center gap-2 {{ $problem->isWishlisted ? 'border-red-500 text-red-500 bg-red-50' : 'text-gray-600' }}"
                                    data-wishlisted="{{ $problem->isWishlisted ? 'true' : 'false' }}">
                                <svg class="w-5 h-5 {{ $problem->isWishlisted ? 'fill-current' : '' }}" 
                                     fill="{{ $problem->isWishlisted ? 'currentColor' : 'none' }}" 
                                     stroke="currentColor" 
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                                <span class="font-medium">{{ $problem->isWishlisted ? 'Tersimpan' : 'Simpan' }}</span>
                            </button>
                            
                            <button onclick="shareProject()" 
                                    class="px-4 py-2 border border-gray-300 rounded-lg hover:border-blue-500 hover:text-blue-500 transition-all duration-200 flex items-center gap-2 text-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path>
                                </svg>
                                <span class="font-medium">Bagikan</span>
                            </button>
                        </div>
                    </div>

                    {{-- title --}}
                    <h1 class="text-3xl font-bold text-gray-900 mb-4">
                        {{ $problem->title }}
                    </h1>

                    {{-- institution info --}}
                    <div class="flex items-center gap-4 mb-4 pb-4 border-b border-gray-200">
                        @if($problem->institution->logo_path)
                            <img src="{{ storage_url($problem->institution->logo_path) }}" 
                                 alt="{{ $problem->institution->name }}"
                                 class="w-16 h-16 rounded-full object-cover border-2 border-gray-200">
                        @else
                            <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center">
                                <span class="text-blue-600 font-bold text-2xl">
                                    {{ strtoupper(substr($problem->institution->name, 0, 1)) }}
                                </span>
                            </div>
                        @endif
                        
                        <div>
                            <h3 class="font-semibold text-gray-900 text-lg">{{ $problem->institution->name }}</h3>
                            <p class="text-sm text-gray-600">{{ ucfirst($problem->institution->type) }}</p>
                        </div>
                    </div>

                    {{-- lokasi & deadline --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div class="flex items-center gap-3 text-gray-700">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Lokasi</p>
                                <p class="font-semibold">{{ $problem->location }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-3 text-gray-700">
                            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Deadline Aplikasi</p>
                                <p class="font-semibold">{{ \Carbon\Carbon::parse($problem->application_deadline)->format('d M Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- galeri foto dokumentasi --}}
                @if($problem->images->isNotEmpty())
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Galeri Foto Dokumentasi
                    </h2>

                    {{-- main image --}}
                    @php
                        $mainImage = $problem->images->where('is_cover', true)->first() ?? $problem->images->first();
                    @endphp
                    
                    <div class="mb-4">
                        <div class="relative aspect-video bg-gray-100 rounded-lg overflow-hidden">
                            <img id="mainGalleryImage" 
                                 src="{{ storage_url($mainImage->image_path) }}" 
                                 alt="{{ $mainImage->caption }}"
                                 class="w-full h-full object-cover"
                                 style="transition: opacity 0.15s ease-in-out;">
                            
                            {{-- caption --}}
                            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-4">
                                <p id="mainGalleryCaption" class="text-white text-sm font-medium">
                                    {{ $mainImage->caption }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- thumbnail grid --}}
                    @if($problem->images->count() > 1)
                    <div class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 gap-2">
                        @foreach($problem->images as $index => $image)
                        <button type="button"
                                onclick="changeGalleryImage('{{ storage_url($image->image_path) }}', '{{ $image->caption }}')"
                                class="gallery-thumb aspect-square rounded-lg overflow-hidden border-2 transition-all duration-200 {{ $loop->first ? 'border-blue-500 ring-2 ring-blue-200' : 'border-transparent hover:border-blue-300' }}">
                            <img src="{{ storage_url($image->image_path) }}" 
                                 alt="{{ $image->caption }}"
                                 class="w-full h-full object-cover">
                        </button>
                        @endforeach
                    </div>
                    @endif
                </div>
                @endif

                {{-- deskripsi masalah --}}
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Deskripsi Masalah</h2>
                    <div class="prose prose-blue max-w-none">
                        <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $problem->description }}</p>
                    </div>
                </div>

                {{-- data pendukung --}}
                @if($problem->supporting_data)
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Data Pendukung & Statistik</h2>
                    <div class="prose prose-blue max-w-none">
                        <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $problem->supporting_data }}</p>
                    </div>
                </div>
                @endif

                {{-- requirements --}}
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Requirements & Deliverables</h2>
                    
                    {{-- skills required --}}
                    @if($problem->required_skills)
                    <div class="mb-6">
                        <h3 class="font-semibold text-gray-900 mb-3">Skills yang Dibutuhkan</h3>
                        <div class="flex flex-wrap gap-2">
                            @php
                                $skills = is_array($problem->required_skills) 
                                    ? $problem->required_skills 
                                    : json_decode($problem->required_skills, true) ?? [];
                            @endphp
                            @foreach($skills as $skill)
                            <span class="bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1.5 rounded-md">
                                {{ $skill }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- expected outcomes --}}
                    @if($problem->expected_outcomes)
                    <div class="mb-6">
                        <h3 class="font-semibold text-gray-900 mb-3">Expected Outcomes</h3>
                        <div class="prose prose-blue max-w-none">
                            <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $problem->expected_outcomes }}</p>
                        </div>
                    </div>
                    @endif

                    {{-- fasilitas --}}
                    @if($problem->provided_facilities)
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-3">Fasilitas yang Disediakan</h3>
                        <div class="prose prose-blue max-w-none">
                            <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $problem->provided_facilities }}</p>
                        </div>
                    </div>
                    @endif
                </div>

            </div>

            {{-- sidebar --}}
            <div class="lg:col-span-1 space-y-6">
                
                {{-- quick info --}}
                <div class="bg-white rounded-xl shadow-sm p-6 sticky top-6">
                    <h3 class="font-bold text-gray-900 mb-4">Informasi Cepat</h3>
                    
                    <div class="space-y-4">
                        <div class="flex items-center justify-between py-3 border-b border-gray-200">
                            <span class="text-gray-600">Mahasiswa Dibutuhkan</span>
                            <span class="font-bold text-gray-900">{{ $problem->students_needed }} orang</span>
                        </div>
                        
                        <div class="flex items-center justify-between py-3 border-b border-gray-200">
                            <span class="text-gray-600">Durasi Proyek</span>
                            <span class="font-bold text-gray-900">{{ $problem->duration_months }} bulan</span>
                        </div>
                        
                        <div class="flex items-center justify-between py-3 border-b border-gray-200">
                            <span class="text-gray-600">Tingkat Kesulitan</span>
                            @php
                                $difficultyConfig = [
                                    'beginner' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'label' => 'Pemula'],
                                    'intermediate' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'label' => 'Menengah'],
                                    'advanced' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'label' => 'Lanjut'],
                                ];
                                $difficulty = $difficultyConfig[$problem->difficulty_level] ?? $difficultyConfig['beginner'];
                            @endphp
                            <span class="{{ $difficulty['bg'] }} {{ $difficulty['text'] }} text-sm font-semibold px-3 py-1 rounded-md">
                                {{ $difficulty['label'] }}
                            </span>
                        </div>
                        
                        <div class="flex items-center justify-between py-3 border-b border-gray-200">
                            <span class="text-gray-600">Total Aplikasi</span>
                            <span class="font-bold text-gray-900">{{ $problem->applications_count ?? 0 }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between py-3">
                            <span class="text-gray-600">Deadline</span>
                            <span class="font-bold text-red-600">
                                {{ \Carbon\Carbon::parse($problem->application_deadline)->diffForHumans() }}
                            </span>
                        </div>
                    </div>

                    {{-- kategori SDG --}}
                    <div class="mt-6">
                        <h4 class="font-semibold text-gray-900 mb-3">Kategori SDG</h4>
                        <div class="flex flex-wrap gap-2">
                            @php
                                $categories = is_array($problem->sdg_categories) 
                                    ? $problem->sdg_categories 
                                    : json_decode($problem->sdg_categories, true) ?? [];
                            @endphp
                            
                            @foreach($categories as $category)
                            <span class="{{ sdg_color($category) }} text-white text-xs font-semibold px-3 py-1.5 rounded-md">
                                {{ sdg_label($category) }}
                            </span>
                            @endforeach
                        </div>
                    </div>

                    {{-- apply button --}}
                    @if($problem->status === 'open')
                    <div class="mt-6">
                        <a href="{{ route('student.applications.create', $problem->id) }}" 
                           class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg text-center transition-colors duration-200 shadow-lg hover:shadow-xl">
                            Apply untuk Proyek Ini
                        </a>
                    </div>
                    @else
                    <div class="mt-6">
                        <button disabled 
                                class="block w-full bg-gray-400 text-white font-bold py-3 px-4 rounded-lg text-center cursor-not-allowed">
                            Proyek Tidak Terbuka
                        </button>
                    </div>
                    @endif
                </div>

                {{-- similar problems --}}
                @if($similarProblems && $similarProblems->isNotEmpty())
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="font-bold text-gray-900 mb-4">Proyek Serupa</h3>
                    <div class="space-y-4">
                        @foreach($similarProblems as $similar)
                        <a href="{{ route('student.browse-problems.detail', $similar->id) }}" 
                           class="block group">
                            <div class="border border-gray-200 rounded-lg p-3 hover:border-blue-500 transition-all duration-200">
                                <h4 class="font-semibold text-gray-900 text-sm mb-2 line-clamp-2 group-hover:text-blue-600">
                                    {{ $similar->title }}
                                </h4>
                                <div class="flex items-center gap-2 text-xs text-gray-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    </svg>
                                    <span>{{ $similar->location }}</span>
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

{{-- javascript --}}
@push('scripts')
<script>
// gallery image changer
function changeGalleryImage(imageUrl, caption) {
    const mainImage = document.getElementById('mainGalleryImage');
    const mainCaption = document.getElementById('mainGalleryCaption');
    const thumbs = document.querySelectorAll('.gallery-thumb');
    
    // smooth transition
    mainImage.style.opacity = '0';
    
    setTimeout(() => {
        mainImage.src = imageUrl;
        mainCaption.textContent = caption;
        mainImage.style.opacity = '1';
    }, 150);
    
    // update active thumbnail
    thumbs.forEach(thumb => {
        thumb.classList.remove('border-blue-500', 'ring-2', 'ring-blue-200');
        thumb.classList.add('border-transparent');
    });
    
    event.currentTarget.classList.remove('border-transparent');
    event.currentTarget.classList.add('border-blue-500', 'ring-2', 'ring-blue-200');
}

// share project
function shareProject() {
    const url = window.location.href;
    const title = '{{ $problem->title }}';
    
    if (navigator.share) {
        navigator.share({
            title: title,
            url: url
        }).catch(err => console.log('Error sharing:', err));
    } else {
        // fallback: copy to clipboard
        navigator.clipboard.writeText(url).then(() => {
            alert('Link berhasil disalin!');
        });
    }
}

// wishlist toggle (gunakan fungsi global dari wishlist.js)
</script>
@endpush
@endsection