{{-- resources/views/student/browse-problems/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Browse Problems')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-green-50" x-data="{ 
    viewMode: '{{ request('view', 'grid') }}', 
    showMap: false,
    showMobileFilter: false 
}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        {{-- header section --}}
        <div class="mb-8 fade-in-up">
            <h1 class="text-4xl font-bold text-gray-900 mb-3 bg-gradient-to-r from-blue-600 to-green-600 bg-clip-text text-transparent">
                Jelajahi Proyek
            </h1>
            <p class="text-gray-600 text-lg">Temukan proyek KKN yang sesuai dengan minat dan keahlian Anda</p>
        </div>

        {{-- search & actions bar --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6 fade-in-up" style="animation-delay: 0.1s;">
            <form action="{{ route('student.browse-problems.index') }}" method="GET" class="flex flex-col lg:flex-row gap-3">
                
                {{-- search input --}}
                <div class="flex-1 relative">
                    <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Cari proyek berdasarkan judul, deskripsi, atau lokasi..."
                           class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                </div>

                {{-- view toggle buttons --}}
                <div class="flex gap-2">
                    <button type="button" 
                            @click="viewMode = 'grid'; showMap = false" 
                            :class="viewMode === 'grid' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700'"
                            class="px-4 py-3 rounded-lg hover:shadow-md transition-all duration-200 font-medium">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                        </svg>
                    </button>
                    <button type="button" 
                            @click="viewMode = 'list'; showMap = false" 
                            :class="viewMode === 'list' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700'"
                            class="px-4 py-3 rounded-lg hover:shadow-md transition-all duration-200 font-medium">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                    <button type="button" 
                            @click="showMap = !showMap; viewMode = 'grid'" 
                            :class="showMap ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700'"
                            class="px-4 py-3 rounded-lg hover:shadow-md transition-all duration-200 font-medium">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                    
                    {{-- mobile filter toggle --}}
                    <button type="button" 
                            @click="showMobileFilter = !showMobileFilter"
                            class="lg:hidden px-4 py-3 bg-gray-100 text-gray-700 rounded-lg hover:shadow-md transition-all duration-200 font-medium">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd"></path>
                        </svg>
                    </button>

                    {{-- search button --}}
                    <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-green-600 text-white rounded-lg hover:shadow-lg transition-all duration-200 font-semibold">
                        Cari
                    </button>
                </div>
            </form>
        </div>

        <div class="flex gap-6">
            {{-- sidebar filter (desktop) --}}
            <div class="hidden lg:block w-72 flex-shrink-0">
                @include('student.browse-problems.components.filter-sidebar')
            </div>

            {{-- mobile filter overlay --}}
            <div x-show="showMobileFilter" 
                 x-cloak
                 @click.self="showMobileFilter = false"
                 class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100">
                <div class="fixed inset-y-0 left-0 w-80 bg-white shadow-xl overflow-y-auto"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="-translate-x-full"
                     x-transition:enter-end="translate-x-0">
                    <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                        <h3 class="font-bold text-lg text-gray-900">Filter</h3>
                        <button @click="showMobileFilter = false" class="p-2 hover:bg-gray-100 rounded-lg">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="p-4">
                        @include('student.browse-problems.components.filter-sidebar')
                    </div>
                </div>
            </div>

            {{-- main content area --}}
            <div class="flex-1 min-w-0">
                
                {{-- results info --}}
                <div class="mb-6 flex items-center justify-between fade-in-up" style="animation-delay: 0.2s;">
                    <p class="text-gray-600">
                        Ditemukan <span class="font-bold text-gray-900">{{ $problems->total() }}</span> proyek
                        @if(request()->hasAny(['search', 'province_id', 'regency_id', 'sdg_categories', 'difficulty', 'duration', 'status']))
                            <a href="{{ route('student.browse-problems.index') }}" class="ml-2 text-blue-600 hover:text-blue-700 text-sm font-medium">
                                Reset Filter
                            </a>
                        @endif
                    </p>
                </div>

                {{-- map view --}}
                <div x-show="showMap" 
                     x-cloak
                     class="mb-6 fade-in-up" 
                     style="animation-delay: 0.3s;"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100">
                    @include('student.browse-problems.components.map-view')
                </div>

                {{-- problems content --}}
                <div x-show="!showMap" class="space-y-6">
                    
                    {{-- grid view --}}
                    <div x-show="viewMode === 'grid'" 
                         class="grid grid-cols-1 md:grid-cols-2 gap-6"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100">
                        @forelse($problems as $index => $problem)
                            @include('student.browse-problems.components.problem-card', ['problem' => $problem, 'index' => $index])
                        @empty
                            <div class="col-span-full">
                                <div class="text-center py-16 bg-white rounded-xl border border-gray-200">
                                    <svg class="mx-auto h-24 w-24 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <h3 class="text-xl font-bold text-gray-900 mb-2">Tidak Ada Proyek Ditemukan</h3>
                                    <p class="text-gray-600 mb-6">Coba ubah filter atau kata kunci pencarian Anda</p>
                                    <a href="{{ route('student.browse-problems.index') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-200 hover:shadow-lg font-semibold">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                        </svg>
                                        Reset Filter
                                    </a>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    {{-- list view --}}
                    <div x-show="viewMode === 'list'" 
                         class="space-y-4"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         style="display: none;">
                        @forelse($problems as $index => $problem)
                            @include('student.browse-problems.components.problem-card-list', ['problem' => $problem, 'index' => $index])
                        @empty
                            <div class="text-center py-16 bg-white rounded-xl border border-gray-200">
                                <svg class="mx-auto h-24 w-24 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">Tidak Ada Proyek Ditemukan</h3>
                                <p class="text-gray-600 mb-6">Coba ubah filter atau kata kunci pencarian Anda</p>
                                <a href="{{ route('student.browse-problems.index') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-200 hover:shadow-lg font-semibold">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    Reset Filter
                                </a>
                            </div>
                        @endforelse
                    </div>

                    {{-- pagination --}}
                    @if($problems->hasPages())
                    <div class="mt-8 fade-in-up" style="animation-delay: 0.4s;">
                        {{ $problems->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
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

    [x-cloak] {
        display: none !important;
    }

    /* smooth transitions untuk view mode */
    [x-show] {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* custom scrollbar untuk mobile filter */
    .overflow-y-auto::-webkit-scrollbar {
        width: 6px;
    }

    .overflow-y-auto::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .overflow-y-auto::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 3px;
    }

    .overflow-y-auto::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
</style>
@endpush

@push('scripts')
<script>
// inisialisasi alpine data untuk page
document.addEventListener('alpine:init', () => {
    Alpine.data('browseProblems', () => ({
        viewMode: '{{ request('view', 'grid') }}',
        showMap: false,
        showMobileFilter: false,
        
        init() {
            // preload images untuk smooth transitions
            this.preloadImages();
        },
        
        preloadImages() {
            const images = document.querySelectorAll('img[data-src]');
            images.forEach(img => {
                img.src = img.dataset.src;
            });
        }
    }));
});
</script>
@endpush
@endsection