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
            <form method="GET" action="{{ route('student.browse-problems') }}" class="space-y-4">
                
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
                    
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-8 rounded-lg transition-colors duration-200 shadow-md hover:shadow-lg">
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Cari
                        </span>
                    </button>
                </div>

                {{-- quick filters --}}
                <div class="flex flex-wrap gap-3">
                    <button type="button" 
                            onclick="toggleFilter()"
                            class="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg hover:border-blue-500 hover:text-blue-600 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                        <span class="font-medium">Filter</span>
                        @if(request()->hasAny(['province_id', 'category', 'difficulty', 'duration', 'status']))
                        <span class="bg-blue-600 text-white text-xs font-bold px-2 py-0.5 rounded-full">
                            {{ collect([request('province_id'), request('category'), request('difficulty'), request('duration'), request('status')])->filter()->count() }}
                        </span>
                        @endif
                    </button>

                    {{-- view toggle --}}
                    <div class="ml-auto flex items-center gap-2">
                        <button type="button" 
                                onclick="setView('grid')"
                                class="view-toggle p-2 border border-gray-300 rounded-lg hover:border-blue-500 hover:text-blue-600 transition-all {{ request('view', 'grid') === 'grid' ? 'bg-blue-50 border-blue-500 text-blue-600' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                            </svg>
                        </button>
                        <button type="button" 
                                onclick="setView('list')"
                                class="view-toggle p-2 border border-gray-300 rounded-lg hover:border-blue-500 hover:text-blue-600 transition-all {{ request('view') === 'list' ? 'bg-blue-50 border-blue-500 text-blue-600' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- advanced filters (collapsible) --}}
                <div id="advancedFilters" class="hidden pt-4 border-t border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        
                        {{-- filter provinsi --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Provinsi</label>
                            <select name="province_id" 
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Semua Provinsi</option>
                                @foreach($provinces as $province)
                                <option value="{{ $province->id }}" {{ request('province_id') == $province->id ? 'selected' : '' }}>
                                    {{ $province->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- filter kategori SDG --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kategori SDG</label>
                            <select name="category" 
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Semua Kategori</option>
                                <option value="no_poverty" {{ request('category') === 'no_poverty' ? 'selected' : '' }}>Tanpa Kemiskinan</option>
                                <option value="zero_hunger" {{ request('category') === 'zero_hunger' ? 'selected' : '' }}>Tanpa Kelaparan</option>
                                <option value="good_health" {{ request('category') === 'good_health' ? 'selected' : '' }}>Kesehatan yang Baik</option>
                                <option value="quality_education" {{ request('category') === 'quality_education' ? 'selected' : '' }}>Pendidikan Berkualitas</option>
                                <option value="gender_equality" {{ request('category') === 'gender_equality' ? 'selected' : '' }}>Kesetaraan Gender</option>
                                <option value="clean_water" {{ request('category') === 'clean_water' ? 'selected' : '' }}>Air Bersih</option>
                                <option value="affordable_energy" {{ request('category') === 'affordable_energy' ? 'selected' : '' }}>Energi Bersih</option>
                            </select>
                        </div>

                        {{-- filter tingkat kesulitan --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tingkat Kesulitan</label>
                            <select name="difficulty" 
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Semua Tingkat</option>
                                <option value="beginner" {{ request('difficulty') === 'beginner' ? 'selected' : '' }}>Pemula</option>
                                <option value="intermediate" {{ request('difficulty') === 'intermediate' ? 'selected' : '' }}>Menengah</option>
                                <option value="advanced" {{ request('difficulty') === 'advanced' ? 'selected' : '' }}>Lanjut</option>
                            </select>
                        </div>

                        {{-- filter durasi --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Durasi Proyek</label>
                            <select name="duration" 
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Semua Durasi</option>
                                <option value="1-2" {{ request('duration') === '1-2' ? 'selected' : '' }}>1-2 Bulan</option>
                                <option value="3-4" {{ request('duration') === '3-4' ? 'selected' : '' }}>3-4 Bulan</option>
                                <option value="5-6" {{ request('duration') === '5-6' ? 'selected' : '' }}>5-6 Bulan</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex gap-3 mt-4">
                        <button type="submit" 
                                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition-colors duration-200">
                            Terapkan Filter
                        </button>
                        <a href="{{ route('student.browse-problems') }}" 
                           class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-6 rounded-lg transition-colors duration-200">
                            Reset Filter
                        </a>
                    </div>
                </div>
            </form>
        </div>

        {{-- results info --}}
        <div class="flex items-center justify-between mb-6">
            <p class="text-gray-600">
                Menampilkan <span class="font-semibold text-gray-900">{{ $problems->count() }}</span> 
                dari <span class="font-semibold text-gray-900">{{ $problems->total() }}</span> proyek
            </p>
            
            {{-- sorting --}}
            <div class="flex items-center gap-3">
                <label class="text-sm font-medium text-gray-700">Urutkan:</label>
                <select onchange="window.location.href=this.value" 
                        class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="{{ route('student.browse-problems', array_merge(request()->except('sort'), ['sort' => 'latest'])) }}" 
                            {{ request('sort', 'latest') === 'latest' ? 'selected' : '' }}>
                        Terbaru
                    </option>
                    <option value="{{ route('student.browse-problems', array_merge(request()->except('sort'), ['sort' => 'deadline'])) }}" 
                            {{ request('sort') === 'deadline' ? 'selected' : '' }}>
                        Deadline Terdekat
                    </option>
                    <option value="{{ route('student.browse-problems', array_merge(request()->except('sort'), ['sort' => 'popular'])) }}" 
                            {{ request('sort') === 'popular' ? 'selected' : '' }}>
                        Paling Populer
                    </option>
                </select>
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
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Tidak Ada Problem Ditemukan</h3>
                        <p class="text-gray-600">Maaf, tidak ada problem yang sesuai dengan kriteria pencarian Anda.</p>
                    </div>
                @endforelse
            </div>
        @else
            @include('student.browse-problems.components.problems-grid', ['problems' => $problems])
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