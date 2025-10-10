{{-- resources/views/institution/problems/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Detail Masalah')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-green-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- breadcrumb --}}
        <nav class="mb-6">
            <ol class="flex items-center gap-2 text-sm">
                <li><a href="{{ route('institution.dashboard') }}" class="text-gray-500 hover:text-gray-700">Dashboard</a></li>
                <li class="text-gray-400">/</li>
                <li><a href="{{ route('institution.problems.index') }}" class="text-gray-500 hover:text-gray-700">Problems</a></li>
                <li class="text-gray-400">/</li>
                <li class="text-gray-900 font-medium">Detail</li>
            </ol>
        </nav>

        {{-- header section --}}
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6 border border-gray-100">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $problem->title }}</h1>
                    <div class="flex items-center gap-3">
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full
                            {{ $problem->status === 'draft' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $problem->status === 'open' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $problem->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $problem->status === 'completed' ? 'bg-purple-100 text-purple-800' : '' }}
                            {{ $problem->status === 'closed' ? 'bg-gray-100 text-gray-800' : '' }}">
                            {{ ucfirst($problem->status) }}
                        </span>
                        
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full
                            {{ $problem->difficulty_level === 'beginner' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $problem->difficulty_level === 'intermediate' ? 'bg-yellow-100 text-yellow-700' : '' }}
                            {{ $problem->difficulty_level === 'advanced' ? 'bg-red-100 text-red-700' : '' }}">
                            {{ ucfirst($problem->difficulty_level) }}
                        </span>
                        
                        @if($problem->is_featured)
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-purple-100 text-purple-700">
                            Featured
                        </span>
                        @endif

                        @if($problem->is_urgent)
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-700">
                            Urgent
                        </span>
                        @endif
                    </div>
                </div>

                <div class="flex gap-2">
                    <a href="{{ route('institution.problems.edit', $problem->id) }}" 
                       class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit
                    </a>
                </div>
            </div>

            {{-- stats --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
                <div class="bg-blue-50 rounded-lg p-4">
                    <p class="text-sm text-blue-600 mb-1">Views</p>
                    <p class="text-2xl font-bold text-blue-900">{{ $problem->views_count }}</p>
                </div>
                <div class="bg-green-50 rounded-lg p-4">
                    <p class="text-sm text-green-600 mb-1">Aplikasi</p>
                    <p class="text-2xl font-bold text-green-900">{{ $problem->applications_count }}</p>
                </div>
                <div class="bg-purple-50 rounded-lg p-4">
                    <p class="text-sm text-purple-600 mb-1">Mahasiswa Diterima</p>
                    <p class="text-2xl font-bold text-purple-900">{{ $problem->accepted_students }} / {{ $problem->required_students }}</p>
                </div>
                <div class="bg-orange-50 rounded-lg p-4">
                    <p class="text-sm text-orange-600 mb-1">Deadline</p>
                    <p class="text-2xl font-bold text-orange-900">{{ $problem->application_deadline->format('d M Y') }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            {{-- main content --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- gallery section - FIXED: gunakan image_url accessor --}}
                @if($problem->images && $problem->images->isNotEmpty())
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Galeri</h2>
                    
                    @php
                        // ambil cover image atau image pertama sebagai main image
                        $mainImage = $problem->images->where('is_cover', true)->first() ?? $problem->images->first();
                    @endphp
                    
                    <div class="mb-4">
                        <div class="relative aspect-video bg-gray-100 rounded-lg overflow-hidden">
                            {{-- FIXED: gunakan image_url accessor yang otomatis pakai supabase_url() --}}
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
                            {{-- FIXED: gunakan image_url accessor --}}
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

                {{-- background --}}
                @if($problem->background)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Latar Belakang</h2>
                    <div class="prose prose-sm max-w-none text-gray-600">
                        {!! nl2br(e($problem->background)) !!}
                    </div>
                </div>
                @endif

                {{-- objectives --}}
                @if($problem->objectives)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Tujuan</h2>
                    <div class="prose prose-sm max-w-none text-gray-600">
                        {!! nl2br(e($problem->objectives)) !!}
                    </div>
                </div>
                @endif

                {{-- scope --}}
                @if($problem->scope)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Ruang Lingkup</h2>
                    <div class="prose prose-sm max-w-none text-gray-600">
                        {!! nl2br(e($problem->scope)) !!}
                    </div>
                </div>
                @endif

                {{-- expected outcomes --}}
                @if($problem->expected_outcomes)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Output yang Diharapkan</h2>
                    <div class="prose prose-sm max-w-none text-gray-600">
                        {!! nl2br(e($problem->expected_outcomes)) !!}
                    </div>
                </div>
                @endif

                {{-- deliverables --}}
                @if($problem->deliverables && count($problem->deliverables) > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Deliverables</h2>
                    <ul class="space-y-2">
                        @foreach($problem->deliverables as $deliverable)
                            <li class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-gray-700">{{ $deliverable }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                {{-- facilities provided --}}
                @if($problem->facilities_provided && count($problem->facilities_provided) > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Fasilitas yang Disediakan</h2>
                    <ul class="space-y-2">
                        @foreach($problem->facilities_provided as $facility)
                            <li class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-green-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-gray-700">{{ $facility }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
                @endif

            </div>

            {{-- sidebar --}}
            <div class="space-y-6">
                
                {{-- lokasi --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Lokasi</h3>
                    <div class="space-y-3 text-sm">
                        <div>
                            <p class="text-gray-600 mb-1">Provinsi</p>
                            <p class="font-semibold text-gray-900">{{ $problem->province->name }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 mb-1">Kabupaten/Kota</p>
                            <p class="font-semibold text-gray-900">{{ $problem->regency->name }}</p>
                        </div>
                        @if($problem->village)
                        <div>
                            <p class="text-gray-600 mb-1">Desa</p>
                            <p class="font-semibold text-gray-900">{{ $problem->village }}</p>
                        </div>
                        @endif
                        @if($problem->detailed_location)
                        <div>
                            <p class="text-gray-600 mb-1">Detail Lokasi</p>
                            <p class="font-semibold text-gray-900">{{ $problem->detailed_location }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- timeline --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Timeline</h3>
                    <div class="space-y-3 text-sm">
                        <div>
                            <p class="text-gray-600 mb-1">Deadline Aplikasi</p>
                            <p class="font-semibold text-gray-900">{{ $problem->application_deadline->format('d M Y') }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 mb-1">Mulai Proyek</p>
                            <p class="font-semibold text-gray-900">{{ $problem->start_date->format('d M Y') }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 mb-1">Selesai Proyek</p>
                            <p class="font-semibold text-gray-900">{{ $problem->end_date->format('d M Y') }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 mb-1">Durasi</p>
                            <p class="font-semibold text-gray-900">{{ $problem->duration_months }} bulan</p>
                        </div>
                    </div>
                </div>

                {{-- requirements --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Requirements</h3>
                    <div class="space-y-3 text-sm">
                        <div>
                            <p class="text-gray-600 mb-1">Mahasiswa Dibutuhkan</p>
                            <p class="font-semibold text-gray-900">{{ $problem->required_students }} orang</p>
                        </div>
                        <div>
                            <p class="text-gray-600 mb-1">Tingkat Kesulitan</p>
                            <p class="font-semibold text-gray-900">{{ ucfirst($problem->difficulty_level) }}</p>
                        </div>
                        
                        @if($problem->required_skills && count($problem->required_skills) > 0)
                        <div>
                            <p class="text-gray-600 mb-2">Skills yang Dibutuhkan</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($problem->required_skills as $skill)
                                    <span class="inline-flex px-2 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded">
                                        {{ $skill }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        @if($problem->required_majors && count($problem->required_majors) > 0)
                        <div>
                            <p class="text-gray-600 mb-2">Jurusan yang Dibutuhkan</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($problem->required_majors as $major)
                                    <span class="inline-flex px-2 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded">
                                        {{ $major }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- sdg categories --}}
                @if($problem->sdg_categories && count($problem->sdg_categories) > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">SDG Categories</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($problem->sdg_categories as $sdg)
                            <span class="inline-flex px-3 py-1 bg-blue-100 text-blue-700 text-sm font-semibold rounded-full">
                                SDG {{ $sdg }}
                            </span>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- contact person --}}
                @if($problem->contact_person)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Contact Person</h3>
                    <div class="space-y-2 text-sm">
                        <p class="font-semibold text-gray-900">{{ $problem->contact_person }}</p>
                        @if($problem->contact_phone)
                        <p class="text-gray-600">{{ $problem->contact_phone }}</p>
                        @endif
                        @if($problem->contact_email)
                        <p class="text-gray-600">{{ $problem->contact_email }}</p>
                        @endif
                    </div>
                </div>
                @endif

            </div>
        </div>

        {{-- section aplikasi --}}
        @if($problem->applications_count > 0)
        <div class="mt-8">
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-900">Aplikasi yang Masuk</h3>
                    <a href="{{ route('institution.applications.index', ['problem' => $problem->id]) }}" 
                       class="text-blue-600 hover:text-blue-700 text-sm font-semibold">
                        Lihat Semua →
                    </a>
                </div>
                <div class="text-center py-4">
                    <p class="text-gray-600">
                        Total <span class="font-bold text-blue-600">{{ $problem->applications_count }}</span> aplikasi diterima
                    </p>
                    <a href="{{ route('institution.applications.index', ['problem' => $problem->id]) }}" 
                       class="inline-flex items-center gap-1 mt-3 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-semibold">
                        Review Aplikasi
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>

{{-- javascript untuk gallery --}}
<script>
function changeGalleryImage(imageUrl, caption) {
    const mainImage = document.getElementById('mainGalleryImage');
    const mainCaption = document.getElementById('mainGalleryCaption');
    const thumbs = document.querySelectorAll('.gallery-thumb');
    
    // fade out
    mainImage.style.opacity = '0';
    
    setTimeout(() => {
        mainImage.src = imageUrl;
        mainCaption.textContent = caption;
        // fade in
        mainImage.style.opacity = '1';
    }, 150);
    
    // update active thumbnail
    thumbs.forEach((thumb, index) => {
        if (thumb.querySelector('img').src === imageUrl) {
            thumb.classList.add('border-blue-500', 'ring-2', 'ring-blue-200');
            thumb.classList.remove('border-transparent');
        } else {
            thumb.classList.remove('border-blue-500', 'ring-2', 'ring-blue-200');
            thumb.classList.add('border-transparent');
        }
    });
}
</script>
@endsection