{{-- resources/views/student/repository/show.blade.php --}}
@extends('layouts.app')

@section('title', $document->title)

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- breadcrumb --}}
        <nav class="mb-6 fade-in-up">
            <ol class="flex items-center space-x-2 text-sm text-gray-600">
                <li><a href="{{ route('student.repository.index') }}" class="hover:text-blue-600 transition-colors">Repository</a></li>
                <li><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg></li>
                <li class="text-gray-900 font-semibold truncate">{{ Str::limit($document->title, 50) }}</li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- main content --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- document header --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 fade-in-up" style="animation-delay: 0.1s;">
                    <div class="flex items-start gap-4 mb-6">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-green-500 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $document->title }}</h1>
                            <div class="flex flex-wrap gap-3 text-sm text-gray-600">
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    {{ $document->author_name ?? 'Unknown Author' }}
                                </span>
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    {{ $document->year ?? 'N/A' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- metadata --}}
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 py-4 border-y border-gray-200">
                        <div class="text-center">
                            <p class="text-2xl font-bold text-blue-600">{{ $document->download_count }}</p>
                            <p class="text-sm text-gray-600">Downloads</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-green-600">{{ $document->view_count }}</p>
                            <p class="text-sm text-gray-600">Views</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-purple-600">{{ $document->citation_count }}</p>
                            <p class="text-sm text-gray-600">Citations</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-orange-600">{{ $document->formatted_file_size }}</p>
                            <p class="text-sm text-gray-600">File Size</p>
                        </div>
                    </div>

                    {{-- description --}}
                    @if($document->description)
                    <div class="mt-6">
                        <h2 class="text-lg font-bold text-gray-900 mb-3">Deskripsi</h2>
                        <p class="text-gray-700 leading-relaxed">{{ $document->description }}</p>
                    </div>
                    @endif

                    {{-- categories & tags --}}
                    <div class="mt-6">
                        @php
                            // parse categories dengan aman
                            $categories = [];
                            if ($document->categories) {
                                if (is_array($document->categories)) {
                                    $categories = $document->categories;
                                } elseif (is_string($document->categories)) {
                                    $categories = json_decode($document->categories, true) ?? [];
                                }
                            }

                            // parse tags dengan aman
                            $tags = [];
                            if ($document->tags) {
                                if (is_array($document->tags)) {
                                    $tags = $document->tags;
                                } elseif (is_string($document->tags)) {
                                    $tags = json_decode($document->tags, true) ?? [];
                                }
                            }
                        @endphp

                        @if(count($categories) > 0)
                        <div class="mb-4">
                            <h3 class="text-sm font-semibold text-gray-700 mb-2">Kategori SDG:</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach($categories as $category)
                                    <span class="px-3 py-1 bg-green-100 text-green-700 text-sm font-medium rounded-full">
                                        {{ ucfirst(str_replace('_', ' ', $category)) }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        @if(count($tags) > 0)
                        <div>
                            <h3 class="text-sm font-semibold text-gray-700 mb-2">Tags:</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach($tags as $tag)
                                    <span class="px-3 py-1 bg-blue-100 text-blue-700 text-sm font-medium rounded">
                                        #{{ $tag }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- document info --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 fade-in-up" style="animation-delay: 0.2s;">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Informasi Dokumen</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @if($document->institution_name)
                        <div>
                            <p class="text-sm text-gray-600">Instansi</p>
                            <p class="font-semibold text-gray-900">{{ $document->institution_name }}</p>
                        </div>
                        @endif

                        @if($document->university_name)
                        <div>
                            <p class="text-sm text-gray-600">Universitas</p>
                            <p class="font-semibold text-gray-900">{{ $document->university_name }}</p>
                        </div>
                        @endif

                        @if($document->province)
                        <div>
                            <p class="text-sm text-gray-600">Provinsi</p>
                            <p class="font-semibold text-gray-900">{{ $document->province->name }}</p>
                        </div>
                        @endif

                        @if($document->regency)
                        <div>
                            <p class="text-sm text-gray-600">Kabupaten/Kota</p>
                            <p class="font-semibold text-gray-900">{{ $document->regency->name }}</p>
                        </div>
                        @endif

                        <div>
                            <p class="text-sm text-gray-600">Format File</p>
                            <p class="font-semibold text-gray-900">{{ strtoupper($document->file_type) }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600">Tanggal Unggah</p>
                            <p class="font-semibold text-gray-900">{{ $document->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- sidebar --}}
            <div class="space-y-6">
                
                {{-- download card --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-8 fade-in-up" style="animation-delay: 0.15s;">
                    <h3 class="font-semibold text-gray-900 mb-4">Download Dokumen</h3>
                    <div class="space-y-3">
                        {{-- download button - langsung save tanpa buka tab baru --}}
                        <a href="{{ route('student.repository.download', $document->id) }}" 
                           download
                           class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-gradient-to-r from-blue-600 to-green-600 text-white rounded-lg hover:shadow-lg transition-all font-semibold">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            Download {{ strtoupper($document->file_type) }}
                        </a>
                    </div>

                    <div class="mt-6 pt-6 border-t">
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Ukuran: {{ $document->formatted_file_size }}</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-3">
                            Dengan mendownload, Anda setuju untuk menggunakan dokumen ini sesuai dengan etika akademis
                        </p>
                    </div>
                </div>

                {{-- citation card --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 fade-in-up" style="animation-delay: 0.2s;">
                    <h3 class="font-semibold text-gray-900 mb-4">Sitasi</h3>
                    <div class="space-y-3">
                        <select id="citationStyle" 
                                onchange="loadCitation()" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="apa">APA Style</option>
                            <option value="mla">MLA Style</option>
                            <option value="ieee">IEEE Style</option>
                        </select>
                        <div class="relative">
                            <div id="citationText" 
                                 class="p-4 bg-gray-50 rounded-lg text-sm text-gray-700 font-mono min-h-[80px]">
                                <span class="text-gray-400">Memuat sitasi...</span>
                            </div>
                            <button onclick="copyCitation()" 
                                    class="absolute top-2 right-2 p-2 bg-white rounded-lg hover:bg-gray-100 transition-colors shadow-sm">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- related documents --}}
                @if($relatedDocuments->isNotEmpty())
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 fade-in-up" style="animation-delay: 0.3s;">
                    <h3 class="font-semibold text-gray-900 mb-4">Dokumen Terkait</h3>
                    <div class="space-y-3">
                        @foreach($relatedDocuments as $related)
                            <a href="{{ route('student.repository.show', $related->id) }}" 
                               class="block p-4 border border-gray-200 rounded-lg hover:shadow-md transition-all group">
                                <h4 class="font-semibold text-gray-900 group-hover:text-blue-600 transition-colors mb-1 line-clamp-2">
                                    {{ $related->title }}
                                </h4>
                                <p class="text-sm text-gray-600">
                                    {{ $related->author_name ?? 'Unknown Author' }} • {{ $related->year }}
                                </p>
                            </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// load citation saat halaman load
document.addEventListener('DOMContentLoaded', function() {
    loadCitation();
});

// function untuk load citation
async function loadCitation() {
    const style = document.getElementById('citationStyle').value;
    const citationText = document.getElementById('citationText');
    
    citationText.innerHTML = '<span class="text-gray-400">Memuat...</span>';
    
    try {
        const response = await fetch(`/student/repository/{{ $document->id }}/citation?style=${style}`);
        const data = await response.json();
        
        if (data.success) {
            citationText.textContent = data.citation;
        } else {
            citationText.innerHTML = '<span class="text-red-600">Gagal memuat sitasi</span>';
        }
    } catch (error) {
        console.error('Error loading citation:', error);
        citationText.innerHTML = '<span class="text-red-600">Error: ' + error.message + '</span>';
    }
}

// function untuk copy citation
function copyCitation() {
    const citationText = document.getElementById('citationText').textContent;
    
    navigator.clipboard.writeText(citationText).then(() => {
        // show success notification
        alert('Sitasi berhasil disalin!');
    }).catch(err => {
        console.error('Error copying citation:', err);
        alert('Gagal menyalin sitasi');
    });
}
</script>
@endpush

@push('styles')
<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

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
</style>
@endpush
@endsection