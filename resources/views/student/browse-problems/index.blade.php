{{-- resources/views/student/browse-problems/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8" x-data="browseProblemsPage()">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- header dengan stats --}}
        <div class="mb-8 fade-in-up">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Jelajahi Proyek KKN</h1>
            <p class="text-gray-600">Temukan proyek KKN yang sesuai dengan minat dan keahlian Anda</p>
            
            {{-- quick stats --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                <div class="bg-white rounded-lg p-4 border border-gray-200">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">Total Proyek</p>
                            <p id="total-problems-stat" class="text-2xl font-bold text-gray-900">{{ $stats['total_problems'] }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg p-4 border border-gray-200">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">Total Slot</p>
                            <p id="total-slots-stat" class="text-2xl font-bold text-gray-900">{{ $stats['total_slots'] }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg p-4 border border-gray-200">
                    <div class="flex items-center">
                        <div class="p-3 bg-red-100 rounded-lg">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">Proyek Mendesak</p>
                            <p id="urgent-count-stat" class="text-2xl font-bold text-gray-900">{{ $stats['urgent_count'] }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-6">
            {{-- sidebar filter --}}
            @include('student.browse-problems.components.filter-sidebar')

            {{-- main content --}}
            <main class="flex-1 space-y-6">
                {{-- search & view toggle --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 fade-in-up" style="animation-delay: 0.1s;">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        {{-- search bar --}}
                        <div class="flex-1">
                            <div class="relative">
                                <input type="text" 
                                       id="search-input"
                                       name="search"
                                       value="{{ request('search') }}"
                                       placeholder="Cari proyek berdasarkan judul, deskripsi..."
                                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>

                        {{-- view toggle & sort --}}
                        <div class="flex items-center space-x-3">
                            {{-- view toggle buttons --}}
                            <div class="flex items-center bg-gray-100 rounded-lg p-1">
                                <button @click="viewMode = 'grid'" 
                                        :class="viewMode === 'grid' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-600'"
                                        class="p-2 rounded-md transition-all duration-200 transform hover:scale-105"
                                        title="Grid View">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                                    </svg>
                                </button>
                                <button @click="viewMode = 'list'" 
                                        :class="viewMode === 'list' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-600'"
                                        class="p-2 rounded-md transition-all duration-200 transform hover:scale-105"
                                        title="List View">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                    </svg>
                                </button>
                            </div>

                            {{-- sort dropdown --}}
                            <select id="sort-select" 
                                    name="sort"
                                    class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Terbaru</option>
                                <option value="deadline" {{ request('sort') === 'deadline' ? 'selected' : '' }}>Deadline Terdekat</option>
                                <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>Terpopuler</option>
                                <option value="most_applied" {{ request('sort') === 'most_applied' ? 'selected' : '' }}>Paling Banyak Dilamar</option>
                            </select>
                        </div>
                    </div>

                    {{-- active filters tags --}}
                    @if(request()->hasAny(['search', 'province_id', 'sdg', 'difficulty', 'duration']))
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-sm font-medium text-gray-700">Filter Aktif:</p>
                            <button id="reset-filters" class="text-sm text-blue-600 hover:text-blue-800 font-medium transition-colors">
                                Hapus Semua
                            </button>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            @if(request('search'))
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-blue-100 text-blue-800 border border-blue-200">
                                <span>Pencarian: "{{ request('search') }}"</span>
                            </span>
                            @endif
                            @if(request('province_id'))
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-blue-100 text-blue-800 border border-blue-200">
                                <span>Provinsi</span>
                            </span>
                            @endif
                            @if(request('sdg'))
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-blue-100 text-blue-800 border border-blue-200">
                                <span>SDG {{ request('sdg') }}</span>
                            </span>
                            @endif
                            @if(request('difficulty'))
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-blue-100 text-blue-800 border border-blue-200">
                                <span>{{ ucfirst(request('difficulty')) }}</span>
                            </span>
                            @endif
                            @if(request('duration'))
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-blue-100 text-blue-800 border border-blue-200">
                                <span>{{ request('duration') }} bulan</span>
                            </span>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>

                {{-- problems grid/list container --}}
                <div id="problems-container" class="transition-opacity duration-300">
                    {{-- grid view --}}
                    <div x-show="viewMode === 'grid'" 
                         class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
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
                                    <button onclick="window.location.href='{{ route('student.browse-problems') }}'" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-200 hover:shadow-lg font-semibold">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                        </svg>
                                        Reset Filter
                                    </button>
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
                                <p class="text-gray-600">Coba ubah filter atau kata kunci pencarian Anda</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- pagination --}}
                @if($problems->hasPages())
                <div id="pagination-container" class="mt-8">
                    {{ $problems->links() }}
                </div>
                @endif
            </main>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// alpine component untuk browse problems page
function browseProblemsPage() {
    return {
        viewMode: localStorage.getItem('problemsViewMode') || 'grid',
        
        init() {
            // watch viewMode changes dan simpan ke localStorage
            this.$watch('viewMode', value => {
                localStorage.setItem('problemsViewMode', value);
            });
            
            // preload images untuk smooth transition
            this.preloadImages();
        },
        
        preloadImages() {
            const images = document.querySelectorAll('.problem-card img');
            images.forEach(img => {
                if (img.dataset.src) {
                    img.src = img.dataset.src;
                }
            });
        }
    };
}
</script>
@endpush