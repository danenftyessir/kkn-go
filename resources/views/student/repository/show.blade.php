{{-- resources/views/student/repository/show.blade.php --}}
@extends('layouts.app')

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
                                @if($document->author_name)
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        {{ $document->author_name }}
                                    </span>
                                @endif
                                @if($document->year)
                                    <span>•</span>
                                    <span>{{ $document->year }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($document->description)
                        <div class="mb-6 pb-6 border-b">
                            <h3 class="font-semibold text-gray-900 mb-2">Deskripsi</h3>
                            <p class="text-gray-600 leading-relaxed">{{ $document->description }}</p>
                        </div>
                    @endif

                    {{-- metadata grid --}}
                    <div class="grid grid-cols-2 gap-6 mb-6 pb-6 border-b">
                        @if($document->institution_name)
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Institusi</p>
                                <p class="font-semibold text-gray-900">{{ $document->institution_name }}</p>
                            </div>
                        @endif
                        @if($document->university_name)
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Universitas</p>
                                <p class="font-semibold text-gray-900">{{ $document->university_name }}</p>
                            </div>
                        @endif
                        @if($document->province)
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Lokasi</p>
                                <p class="font-semibold text-gray-900">
                                    @if($document->regency)
                                        {{ $document->regency->name }}, 
                                    @endif
                                    {{ $document->province->name }}
                                </p>
                            </div>
                        @endif
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Ukuran File</p>
                            <p class="font-semibold text-gray-900">{{ $document->readable_file_size }}</p>
                        </div>
                    </div>

                    {{-- categories --}}
                    @if($document->categories)
                        <div class="mb-6 pb-6 border-b">
                            <h3 class="font-semibold text-gray-900 mb-3">Kategori SDG</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach($document->categories as $category)
                                    <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-medium">
                                        SDG {{ $category }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- tags --}}
                    @if($document->tags)
                        <div class="mb-6">
                            <h3 class="font-semibold text-gray-900 mb-3">Tags</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach($document->tags as $tag)
                                    <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm">
                                        #{{ $tag }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- statistics --}}
                    <div class="grid grid-cols-3 gap-4 p-4 bg-gray-50 rounded-lg">
                        <div class="text-center">
                            <p class="text-2xl font-bold text-blue-600">{{ $document->view_count }}</p>
                            <p class="text-xs text-gray-600 mt-1">Views</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-green-600">{{ $document->download_count }}</p>
                            <p class="text-xs text-gray-600 mt-1">Downloads</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-purple-600">{{ $document->citation_count }}</p>
                            <p class="text-xs text-gray-600 mt-1">Citations</p>
                        </div>
                    </div>
                </div>

                {{-- citation section --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 fade-in-up" style="animation-delay: 0.2s;">
                    <h3 class="font-semibold text-gray-900 mb-4">Sitasi</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Format</label>
                            <select id="citationStyle" 
                                    onchange="updateCitation()"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="apa">APA</option>
                                <option value="mla">MLA</option>
                                <option value="ieee">IEEE</option>
                            </select>
                        </div>
                        <div class="relative">
                            <div id="citationText" 
                                 class="p-4 bg-gray-50 rounded-lg text-sm text-gray-700 font-mono">
                                Loading...
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
                                    <h4 class="font-semibold text-gray-900 group-hover:text-blue-600 transition-colors mb-1">
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

            {{-- sidebar --}}
            <div class="space-y-6">
                
                {{-- download card --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-8 fade-in-up" style="animation-delay: 0.15s;">
                    <h3 class="font-semibold text-gray-900 mb-4">Download</h3>
                    <div class="space-y-3">
                        <a href="{{ route('student.repository.download', $document->id) }}" 
                           class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-gradient-to-r from-blue-600 to-green-600 text-white rounded-lg hover:shadow-lg transition-all font-semibold">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            Download {{ strtoupper($document->file_type) }}
                        </a>
                        
                        {{-- TODO: bookmark button --}}
                        <button class="w-full px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-semibold">
                            Bookmark
                        </button>
                    </div>

                    <div class="mt-6 pt-6 border-t">
                        <p class="text-xs text-gray-600 text-center">
                            Dengan mendownload, Anda setuju untuk menggunakan dokumen ini sesuai dengan etika akademis
                        </p>
                    </div>
                </div>

                {{-- uploader info --}}
                @if($document->project && $document->project->student)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 fade-in-up" style="animation-delay: 0.2s;">
                        <h3 class="font-semibold text-gray-900 mb-4">Diunggah Oleh</h3>
                        <div class="flex items-center space-x-3">
                            @if($document->project->student->profile_photo_path)
                                <img src="{{ asset('storage/' . $document->project->student->profile_photo_path) }}" 
                                     alt="{{ $document->project->student->user->name }}"
                                     class="w-12 h-12 rounded-full object-cover">
                            @else
                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-green-500 flex items-center justify-center">
                                    <span class="text-white font-bold">{{ strtoupper(substr($document->project->student->first_name, 0, 1)) }}</span>
                                </div>
                            @endif
                            <div>
                                <p class="font-semibold text-gray-900">
                                    {{ $document->project->student->first_name }} {{ $document->project->student->last_name }}
                                </p>
                                <p class="text-sm text-gray-600">{{ $document->project->student->university->name }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- report document --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 fade-in-up" style="animation-delay: 0.25s;">
                    <h3 class="font-semibold text-gray-900 mb-3">Laporkan Masalah</h3>
                    <p class="text-sm text-gray-600 mb-4">Temukan konten yang tidak sesuai? Laporkan kepada kami.</p>
                    <button onclick="reportDocument()" 
                            class="w-full px-4 py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors text-sm font-semibold">
                        Laporkan Dokumen
                    </button>
                </div>

            </div>

        </div>

    </div>
</div>

<script>
let currentCitation = '';

// load citation on page load
document.addEventListener('DOMContentLoaded', function() {
    updateCitation();
});

function updateCitation() {
    const style = document.getElementById('citationStyle').value;
    const documentId = {{ $document->id }};
    
    fetch(`/student/repository/${documentId}/citation?style=${style}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            currentCitation = data.citation;
            document.getElementById('citationText').textContent = data.citation;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('citationText').textContent = 'Gagal memuat sitasi';
    });
}

function copyCitation() {
    navigator.clipboard.writeText(currentCitation).then(() => {
        alert('Sitasi berhasil disalin ke clipboard!');
    }).catch(err => {
        console.error('Error copying citation:', err);
        alert('Gagal menyalin sitasi');
    });
}

function reportDocument() {
    const reason = prompt('Alasan melaporkan dokumen ini:');
    if (reason) {
        fetch('/student/repository/{{ $document->id }}/report', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ reason: reason })
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal mengirim laporan');
        });
    }
}
</script>

<style>
@keyframes fadeInUp {
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
    animation: fadeInUp 0.6s ease-out forwards;
    opacity: 0;
}
</style>
@endsection