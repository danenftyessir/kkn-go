{{-- resources/views/student/browse-problems/detail.blade.php --}}
@extends('layouts.app')

@section('title', $problem->title)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-green-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- breadcrumb --}}
        <nav class="mb-6">
            <ol class="flex items-center gap-2 text-sm">
                <li><a href="{{ route('student.dashboard') }}" class="text-gray-500 hover:text-gray-700">Dashboard</a></li>
                <li class="text-gray-400">/</li>
                <li><a href="{{ route('student.browse-problems.index') }}" class="text-gray-500 hover:text-gray-700">Browse Problems</a></li>
                <li class="text-gray-400">/</li>
                <li class="text-gray-900 font-medium">{{ Str::limit($problem->title, 30) }}</li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- main content --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- header card --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
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
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                    <span>{{ $problem->required_students }} mahasiswa</span>
                                </div>
                            </div>
                        </div>

                        {{-- action buttons --}}
                        <div class="flex items-center gap-2 ml-4">
                            {{-- wishlist button --}}
                            @auth
                                @if(Auth::user()->user_type === 'student')
                                <button onclick="toggleWishlist({{ $problem->id }}, this)" 
                                        class="w-10 h-10 bg-white border-2 rounded-full flex items-center justify-center hover:bg-gray-50 transition-all duration-200"
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
                                    class="w-10 h-10 bg-white border-2 rounded-full flex items-center justify-center hover:bg-gray-50 transition-all duration-200">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- badges --}}
                    <div class="flex flex-wrap gap-2">
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full
                            {{ $problem->status === 'open' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $problem->status === 'in_progress' ? 'bg-blue-100 text-blue-700' : '' }}
                            {{ $problem->status === 'closed' ? 'bg-gray-100 text-gray-700' : '' }}
                            {{ $problem->status === 'completed' ? 'bg-purple-100 text-purple-700' : '' }}">
                            {{ ucfirst($problem->status) }}
                        </span>
                        
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full
                            {{ $problem->difficulty_level === 'beginner' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $problem->difficulty_level === 'intermediate' ? 'bg-yellow-100 text-yellow-700' : '' }}
                            {{ $problem->difficulty_level === 'advanced' ? 'bg-red-100 text-red-700' : '' }}">
                            {{ ucfirst($problem->difficulty_level) }}
                        </span>
                        
                        @if($problem->sdg_goals)
                            @foreach(json_decode($problem->sdg_goals) as $sdg)
                                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-700">
                                    SDG {{ $sdg }}
                                </span>
                            @endforeach
                        @endif
                    </div>
                </div>

                {{-- gallery section --}}
                @if($problem->images && $problem->images->isNotEmpty())
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Galeri</h2>
                    
                    @php
                        // ambil cover image atau image pertama sebagai main image
                        $mainImage = $problem->images->where('is_cover', true)->first() ?? $problem->images->first();
                    @endphp
                    
                    <div class="mb-4">
                        <div class="relative aspect-video bg-gray-100 rounded-lg overflow-hidden">
                            <img id="mainGalleryImage" 
                                 src="{{ $mainImage->image_url }}" 
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
                                onclick="changeGalleryImage('{{ $image->image_url }}', '{{ $image->caption }}')"
                                class="gallery-thumb aspect-square rounded-lg overflow-hidden border-2 transition-all duration-200 {{ $loop->first ? 'border-blue-500 ring-2 ring-blue-200' : 'border-transparent hover:border-blue-300' }}">
                            <img src="{{ $image->image_url }}" 
                                 alt="{{ $image->caption }}"
                                 class="w-full h-full object-cover"
                                 loading="lazy">
                        </button>
                        @endforeach
                    </div>
                    @endif
                </div>
                @endif

                {{-- description section --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Deskripsi Masalah</h2>
                    <div class="prose prose-sm max-w-none text-gray-600">
                        {!! nl2br(e($problem->description)) !!}
                    </div>
                </div>

                {{-- requirements section --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Requirements</h2>
                    
                    @if($problem->required_skills)
                    <div class="mb-6">
                        <h3 class="font-semibold text-gray-900 mb-2">Skills yang Dibutuhkan</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach(json_decode($problem->required_skills) as $skill)
                                <span class="px-3 py-1 bg-blue-50 text-blue-700 text-sm rounded-full">{{ $skill }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-2">Durasi Proyek</h3>
                            <p class="text-gray-600">{{ $problem->duration_months }} bulan</p>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-2">Jumlah Mahasiswa</h3>
                            <p class="text-gray-600">{{ $problem->required_students }} mahasiswa</p>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-2">Deadline Aplikasi</h3>
                            <p class="text-gray-600">{{ \Carbon\Carbon::parse($problem->application_deadline)->format('d M Y') }}</p>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-2">Tingkat Kesulitan</h3>
                            <p class="text-gray-600">{{ ucfirst($problem->difficulty_level) }}</p>
                        </div>
                    </div>

                    @if($problem->expected_outcomes)
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-2">Expected Outcomes</h3>
                        <div class="prose prose-sm max-w-none text-gray-600">
                            {!! nl2br(e($problem->expected_outcomes)) !!}
                        </div>
                    </div>
                    @endif
                </div>

                {{-- facilities section --}}
                @if($problem->provided_facilities)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Fasilitas yang Disediakan</h2>
                    <div class="prose prose-sm max-w-none text-gray-600">
                        {!! nl2br(e($problem->provided_facilities)) !!}
                    </div>
                </div>
                @endif
            </div>

            {{-- sidebar --}}
            <div class="space-y-6">
                
                {{-- institution info --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-bold text-gray-900 mb-4">Instansi</h3>
                    
                    <div class="flex items-start gap-3 mb-4">
                        @if($problem->institution->logo_path)
                            <img src="{{ supabase_url($problem->institution->logo_path) }}" 
                                 alt="{{ $problem->institution->name }}"
                                 class="w-12 h-12 rounded-full object-cover">
                        @else
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-green-500 flex items-center justify-center">
                                <span class="text-white text-lg font-bold">
                                    {{ substr($problem->institution->name, 0, 1) }}
                                </span>
                            </div>
                        @endif
                        
                        <div class="flex-1 min-w-0">
                            <h4 class="font-semibold text-gray-900 mb-1">{{ $problem->institution->name }}</h4>
                            <p class="text-sm text-gray-600">{{ ucfirst($problem->institution->type) }}</p>
                        </div>
                    </div>

                    <div class="space-y-2 text-sm text-gray-600 mb-4">
                        <div class="flex items-start gap-2">
                            <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            </svg>
                            <span>{{ $problem->institution->address }}</span>
                        </div>
                        
                        @if($problem->institution->phone)
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <span>{{ $problem->institution->phone }}</span>
                        </div>
                        @endif
                    </div>

                    <a href="{{ route('institution.profile.public', $problem->institution->id) }}" 
                       class="block w-full text-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-900 rounded-lg transition-colors">
                        Lihat Profile
                    </a>
                </div>

                {{-- apply card --}}
                @auth
                    @if(Auth::user()->user_type === 'student' && $problem->status === 'open')
                    <div class="bg-gradient-to-br from-blue-500 to-green-500 rounded-xl shadow-sm p-6 text-white">
                        <h3 class="font-bold text-lg mb-2">Tertarik dengan proyek ini?</h3>
                        <p class="text-sm mb-4 text-blue-50">Aplikasikan dirimu sekarang dan mulai berkontribusi!</p>
                        
                        @if($hasApplied)
                            <div class="bg-white/20 backdrop-blur-sm rounded-lg p-3 mb-3">
                                <p class="text-sm">✓ Anda sudah melamar proyek ini</p>
                            </div>
                            <a href="{{ route('student.applications.index') }}" 
                               class="block w-full text-center px-4 py-3 bg-white text-blue-600 font-semibold rounded-lg hover:bg-blue-50 transition-colors">
                                Lihat Status Aplikasi
                            </a>
                        @else
                            <a href="{{ route('student.applications.create', ['problem_id' => $problem->id]) }}" 
                               class="block w-full text-center px-4 py-3 bg-white text-blue-600 font-semibold rounded-lg hover:bg-blue-50 transition-colors">
                                Apply Sekarang
                            </a>
                        @endif
                    </div>
                    @endif
                @else
                    <div class="bg-gradient-to-br from-blue-500 to-green-500 rounded-xl shadow-sm p-6 text-white">
                        <h3 class="font-bold text-lg mb-2">Tertarik dengan proyek ini?</h3>
                        <p class="text-sm mb-4 text-blue-50">Login untuk dapat melamar proyek ini</p>
                        <a href="{{ route('login') }}" 
                           class="block w-full text-center px-4 py-3 bg-white text-blue-600 font-semibold rounded-lg hover:bg-blue-50 transition-colors">
                            Login / Register
                        </a>
                    </div>
                @endauth

                {{-- similar problems --}}
                @if(isset($similarProblems) && $similarProblems->isNotEmpty())
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
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
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    </svg>
                                    <span>{{ $similar->regency->name ?? $similar->location }}</span>
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