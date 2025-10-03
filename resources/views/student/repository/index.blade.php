{{-- resources/views/student/repository/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- header --}}
        <div class="mb-8 fade-in-up">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Knowledge Repository</h1>
            <p class="text-gray-600">Akses dan pelajari dari dokumentasi proyek KKN sebelumnya</p>
        </div>

        {{-- statistics cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 fade-in-up" style="animation-delay: 0.05s;">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Total Dokumen</p>
                        <p class="text-3xl font-bold text-blue-600">{{ $stats['total_documents'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 fade-in-up" style="animation-delay: 0.1s;">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Total Downloads</p>
                        <p class="text-3xl font-bold text-green-600">{{ $stats['total_downloads'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 fade-in-up" style="animation-delay: 0.15s;">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Institusi Kontributor</p>
                        <p class="text-3xl font-bold text-purple-600">{{ $stats['total_institutions'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- featured documents --}}
        @if($featuredDocuments->isNotEmpty())
            <div class="mb-8 fade-in-up" style="animation-delay: 0.2s;">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Dokumen Unggulan</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($featuredDocuments as $doc)
                        <div class="bg-gradient-to-br from-blue-600 to-green-600 rounded-xl shadow-lg overflow-hidden group">
                            <div class="p-6 text-white">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <span class="px-2 py-1 bg-white/20 rounded text-xs">Featured</span>
                                </div>
                                <h3 class="font-bold text-lg mb-2 line-clamp-2">{{ $doc->title }}</h3>
                                <p class="text-sm text-white/80 mb-4">{{ $doc->institution_name }}</p>
                                <div class="flex items-center justify-between text-sm">
                                    <span>{{ $doc->download_count }} downloads</span>
                                    <a href="{{ route('student.repository.show', $doc->id) }}" 
                                       class="px-4 py-2 bg-white text-blue-600 rounded-lg hover:bg-white/90 transition-colors font-semibold">
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            
            {{-- filters sidebar --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-8 fade-in-up" style="animation-delay: 0.25s;">
                    <h3 class="font-semibold text-gray-900 mb-4">Filter & Pencarian</h3>
                    
                    <form method="GET" action="{{ route('student.repository.index') }}" class="space-y-4">
                        {{-- search --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kata Kunci</label>
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Cari dokumen..."
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                        </div>

                        {{-- category filter --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kategori SDG</label>
                            <select name="category" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                                <option value="">Semua Kategori</option>
                                @for($i = 1; $i <= 17; $i++)
                                    <option value="{{ $i }}" {{ request('category') == $i ? 'selected' : '' }}>SDG {{ $i }}</option>
                                @endfor
                            </select>
                        </div>

                        {{-- year filter --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                            <select name="year" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                                <option value="">Semua Tahun</option>
                                @foreach($years as $year)
                                    <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- province filter --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Provinsi</label>
                            <select name="province_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                                <option value="">Semua Provinsi</option>
                                @foreach($provinces as $province)
                                    <option value="{{ $province->id }}" {{ request('province_id') == $province->id ? 'selected' : '' }}>
                                        {{ $province->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- university filter --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Universitas</label>
                            <input type="text" 
                                   name="university" 
                                   value="{{ request('university') }}"
                                   placeholder="Nama universitas..."
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                        </div>

                        {{-- sort --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Urutkan</label>
                            <select name="sort" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                                <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Terpopuler</option>
                                <option value="most_viewed" {{ request('sort') == 'most_viewed' ? 'selected' : '' }}>Paling Dilihat</option>
                                <option value="most_cited" {{ request('sort') == 'most_cited' ? 'selected' : '' }}>Paling Disitasi</option>
                            </select>
                        </div>

                        <div class="flex gap-2">
                            <button type="submit" 
                                    class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-semibold">
                                Filter
                            </button>
                            <a href="{{ route('student.repository.index') }}" 
                               class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors text-sm font-semibold">
                                Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- documents list --}}
            <div class="lg:col-span-3">
                @if($documents->isEmpty())
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center fade-in-up" style="animation-delay: 0.3s;">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Tidak Ada Dokumen</h3>
                        <p class="text-gray-600">Tidak ditemukan dokumen yang sesuai dengan filter Anda</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($documents as $index => $document)
                            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-lg transition-all fade-in-up" 
                                 style="animation-delay: {{ 0.3 + ($index * 0.05) }}s;">
                                <div class="flex items-start gap-4">
                                    {{-- file icon --}}
                                    <div class="w-16 h-16 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>

                                    {{-- content --}}
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-start justify-between mb-2">
                                            <div class="flex-1">
                                                <a href="{{ route('student.repository.show', $document->id) }}" 
                                                   class="block">
                                                    <h3 class="text-lg font-bold text-gray-900 hover:text-blue-600 transition-colors mb-1">
                                                        {{ $document->title }}
                                                    </h3>
                                                </a>
                                                <div class="flex flex-wrap gap-2 items-center text-sm text-gray-600">
                                                    @if($document->author_name)
                                                        <span>{{ $document->author_name }}</span>
                                                        <span>•</span>
                                                    @endif
                                                    @if($document->university_name)
                                                        <span>{{ $document->university_name }}</span>
                                                        <span>•</span>
                                                    @endif
                                                    @if($document->year)
                                                        <span>{{ $document->year }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        @if($document->description)
                                            <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $document->description }}</p>
                                        @endif

                                        {{-- metadata --}}
                                        <div class="flex flex-wrap items-center gap-4 text-xs text-gray-500 mb-3">
                                            <span class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                {{ $document->view_count }} views
                                            </span>
                                            <span class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                                </svg>
                                                {{ $document->download_count }} downloads
                                            </span>
                                            <span class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                                </svg>
                                                {{ $document->citation_count }} citations
                                            </span>
                                            <span class="px-2 py-1 bg-gray-100 rounded">{{ strtoupper($document->file_type) }}</span>
                                            <span>{{ $document->readable_file_size }}</span>
                                        </div>

                                        {{-- categories --}}
                                        @if($document->categories)
                                            <div class="flex flex-wrap gap-2 mb-3">
                                                @foreach($document->categories as $category)
                                                    <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs">SDG {{ $category }}</span>
                                                @endforeach
                                            </div>
                                        @endif

                                        {{-- actions --}}
                                        <div class="flex gap-2">
                                            <a href="{{ route('student.repository.show', $document->id) }}" 
                                               class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-semibold">
                                                Lihat Detail
                                            </a>
                                            <a href="{{ route('student.repository.download', $document->id) }}" 
                                               class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm font-semibold">
                                                Download
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- pagination --}}
                    <div class="mt-8 fade-in-up" style="animation-delay: 0.5s;">
                        {{ $documents->withQueryString()->links() }}
                    </div>
                @endif
            </div>

        </div>

    </div>
</div>

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