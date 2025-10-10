{{-- resources/views/student/browse-problems/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Browse Problems')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/browse-problems.css') }}">
@endpush

@section('content')
<div class="min-h-screen bg-gray-50">
    
    {{-- header section --}}
    <div class="bg-gradient-to-r from-blue-600 to-green-600 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-bold mb-4">Jelajahi Proyek KKN</h1>
            <p class="text-xl text-blue-100">
                Temukan proyek KKN yang sesuai dengan minat dan keahlian Anda
            </p>
            
            {{-- stats --}}
            <div class="grid grid-cols-3 gap-6 mt-8">
                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
                    <div class="text-3xl font-bold">{{ $totalProblems ?? 0 }}</div>
                    <div class="text-sm text-blue-100">Total Proyek</div>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
                    <div class="text-3xl font-bold">{{ $openProblems ?? 0 }}</div>
                    <div class="text-sm text-blue-100">Proyek Terbuka</div>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
                    <div class="text-3xl font-bold">{{ $totalInstitutions ?? 0 }}</div>
                    <div class="text-sm text-blue-100">Instansi Partner</div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        {{-- search & filter bar --}}
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <form method="GET" action="{{ route('student.browse-problems.index') }}" class="space-y-4">
                
                {{-- search bar --}}
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <div class="relative">
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Cari proyek berdasarkan judul, deskripsi, atau lokasi..."
                                   class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                            <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <button type="submit" class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-200 font-semibold flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Cari
                    </button>
                </div>

                {{-- advanced filters toggle --}}
                <div class="flex items-center justify-between">
                    <button type="button" 
                            onclick="toggleFilter()" 
                            class="text-blue-600 hover:text-blue-700 font-medium flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                        Filter Lanjutan
                    </button>
                    @if(request()->hasAny(['search', 'province_id', 'regency_id', 'sdg_categories', 'duration', 'difficulty', 'sort']))
                    <a href="{{ route('student.browse-problems.index') }}" class="text-gray-600 hover:text-gray-800 font-medium flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Reset Filter
                    </a>
                    @endif
                </div>

                {{-- advanced filters (hidden by default) --}}
                <div id="advancedFilters" class="hidden space-y-4 pt-4 border-t border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        {{-- province filter --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Provinsi</label>
                            <select name="province_id" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Semua Provinsi</option>
                                @foreach($provinces ?? [] as $province)
                                    <option value="{{ $province->id }}" {{ request('province_id') == $province->id ? 'selected' : '' }}>
                                        {{ $province->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- duration filter --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Durasi</label>
                            <select name="duration" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Semua Durasi</option>
                                <option value="1-2" {{ request('duration') == '1-2' ? 'selected' : '' }}>1-2 Bulan</option>
                                <option value="3-4" {{ request('duration') == '3-4' ? 'selected' : '' }}>3-4 Bulan</option>
                                <option value="5-6" {{ request('duration') == '5-6' ? 'selected' : '' }}>5-6 Bulan</option>
                            </select>
                        </div>

                        {{-- difficulty filter --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tingkat Kesulitan</label>
                            <select name="difficulty" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Semua Tingkat</option>
                                <option value="beginner" {{ request('difficulty') == 'beginner' ? 'selected' : '' }}>Beginner</option>
                                <option value="intermediate" {{ request('difficulty') == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                <option value="advanced" {{ request('difficulty') == 'advanced' ? 'selected' : '' }}>Advanced</option>
                            </select>
                        </div>

                        {{-- sort filter --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Urutkan</label>
                            <select name="sort" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                                <option value="deadline" {{ request('sort') == 'deadline' ? 'selected' : '' }}>Deadline Terdekat</option>
                                <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Paling Populer</option>
                            </select>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        {{-- view toggle & results count --}}
        <div class="flex items-center justify-between mb-6">
            <p class="text-gray-600">
                Menampilkan <span class="font-semibold">{{ $problems->count() }}</span> dari <span class="font-semibold">{{ $problems->total() }}</span> proyek
            </p>
            <div class="flex items-center gap-2">
                <button onclick="setView('grid')" 
                        class="p-2 rounded-lg transition-all duration-200 {{ request('view', 'grid') === 'grid' ? 'bg-blue-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                    </svg>
                </button>
                <button onclick="setView('list')" 
                        class="p-2 rounded-lg transition-all duration-200 {{ request('view') === 'list' ? 'bg-blue-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>

        {{-- problems list/grid --}}
        @if(request('view') === 'list')
            <div class="space-y-4">
                @forelse($problems as $problem)
                    @include('student.browse-problems.components.problem-card-list', ['problem' => $problem])
                @empty
                    <div class="bg-white rounded-xl shadow-sm p-12 text-center">
                        <svg class="w-24 h-24 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Tidak Ada Proyek Ditemukan</h3>
                        <p class="text-gray-600">Maaf, tidak ada proyek yang sesuai dengan kriteria pencarian Anda.</p>
                    </div>
                @endforelse
            </div>
        @else
            @include('student.browse-problems.components.problems-grid', ['problems' => $problems])
        @endif

        {{-- pagination --}}
        @if($problems->hasPages())
        <div class="mt-8">
            {{ $problems->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/pages/browse-problems.js') }}"></script>
<script>
function toggleFilter() {
    const filters = document.getElementById('advancedFilters');
    filters.classList.toggle('hidden');
}

function setView(view) {
    const url = new URL(window.location.href);
    url.searchParams.set('view', view);
    window.location.href = url.toString();
}
</script>
@endpush