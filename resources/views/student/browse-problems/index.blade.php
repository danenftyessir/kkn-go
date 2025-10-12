{{-- resources/views/student/browse-problems/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Jelajahi Proyek KKN')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/browse-problems.css') }}">
@endpush

<div class="min-h-screen bg-gradient-to-b from-blue-50 to-white py-8">
    <div class="container mx-auto px-4">
        
        {{-- header --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Jelajahi Proyek KKN</h1>
            <p class="text-gray-600">Temukan proyek KKN yang sesuai dengan minat dan keahlian Anda</p>
            
            {{-- statistics --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-2xl font-bold text-gray-900">{{ $totalProblems }}</p>
                            <p class="text-sm text-gray-600">Total Proyek Terbuka</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-2xl font-bold text-gray-900">{{ $totalInstitutions }}</p>
                            <p class="text-sm text-gray-600">Instansi Aktif</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-2xl font-bold text-gray-900">{{ $provinces->count() }}</p>
                            <p class="text-sm text-gray-600">Provinsi Tersedia</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- filter toggle button (mobile) --}}
        <div class="mb-4 lg:hidden">
            <button id="toggle-filter" class="w-full bg-white px-4 py-3 rounded-lg shadow-sm border border-gray-200 flex items-center justify-between hover:bg-gray-50 transition-colors">
                <span class="text-gray-900 font-medium">Filter & Pencarian</span>
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
        </div>

        {{-- filters section --}}
        <div id="filter-section" class="bg-white rounded-xl shadow-sm p-6 mb-6 border border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                
                {{-- search --}}
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cari Proyek</label>
                    <div class="relative">
                        <input type="text" 
                               id="search" 
                               name="search" 
                               placeholder="Cari berdasarkan judul atau deskripsi..."
                               value="{{ request('search') }}"
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <svg class="absolute left-3 top-3 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>

                {{-- province --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Provinsi</label>
                    <select id="province_id" 
                            name="province_id" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Semua Provinsi</option>
                        @foreach($provinces as $province)
                            <option value="{{ $province->id }}" {{ request('province_id') == $province->id ? 'selected' : '' }}>
                                {{ $province->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- regency --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kabupaten/Kota</label>
                    <select id="regency_id" 
                            name="regency_id" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            disabled>
                        <option value="">Pilih Kabupaten/Kota</option>
                        @foreach($regencies as $regency)
                            <option value="{{ $regency->id }}" {{ request('regency_id') == $regency->id ? 'selected' : '' }}>
                                {{ $regency->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- sdg category --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kategori SDG</label>
                    <select id="sdg" 
                            name="sdg" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Semua Kategori</option>
                        <option value="1" {{ request('sdg') == '1' ? 'selected' : '' }}>1. Tanpa Kemiskinan</option>
                        <option value="2" {{ request('sdg') == '2' ? 'selected' : '' }}>2. Tanpa Kelaparan</option>
                        <option value="3" {{ request('sdg') == '3' ? 'selected' : '' }}>3. Kehidupan Sehat dan Sejahtera</option>
                        <option value="4" {{ request('sdg') == '4' ? 'selected' : '' }}>4. Pendidikan Berkualitas</option>
                        <option value="6" {{ request('sdg') == '6' ? 'selected' : '' }}>6. Air Bersih dan Sanitasi Layak</option>
                        <option value="7" {{ request('sdg') == '7' ? 'selected' : '' }}>7. Energi Bersih dan Terjangkau</option>
                        <option value="8" {{ request('sdg') == '8' ? 'selected' : '' }}>8. Pekerjaan Layak dan Pertumbuhan Ekonomi</option>
                        <option value="10" {{ request('sdg') == '10' ? 'selected' : '' }}>10. Berkurangnya Kesenjangan</option>
                        <option value="11" {{ request('sdg') == '11' ? 'selected' : '' }}>11. Kota dan Komunitas Berkelanjutan</option>
                        <option value="13" {{ request('sdg') == '13' ? 'selected' : '' }}>13. Penanganan Perubahan Iklim</option>
                    </select>
                </div>

                {{-- difficulty --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tingkat Kesulitan</label>
                    <select id="difficulty" 
                            name="difficulty" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Semua Tingkat</option>
                        <option value="beginner" {{ request('difficulty') == 'beginner' ? 'selected' : '' }}>Beginner</option>
                        <option value="intermediate" {{ request('difficulty') == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                        <option value="advanced" {{ request('difficulty') == 'advanced' ? 'selected' : '' }}>Advanced</option>
                    </select>
                </div>

                {{-- duration --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Durasi</label>
                    <select id="duration" 
                            name="duration" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Semua Durasi</option>
                        <option value="1-2" {{ request('duration') == '1-2' ? 'selected' : '' }}>1-2 Bulan</option>
                        <option value="3-4" {{ request('duration') == '3-4' ? 'selected' : '' }}>3-4 Bulan</option>
                        <option value="5-6" {{ request('duration') == '5-6' ? 'selected' : '' }}>5-6 Bulan</option>
                    </select>
                </div>

                {{-- reset button --}}
                <div class="flex items-end">
                    <button id="reset-filters" 
                            class="w-full px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        Reset Filter
                    </button>
                </div>
            </div>
        </div>

        {{-- view controls --}}
        <div class="flex items-center justify-between mb-6">
            <p class="text-gray-600">
                Menampilkan <span class="font-semibold">{{ $problems->firstItem() ?? 0 }}</span> 
                dari <span class="font-semibold">{{ $problems->total() }}</span> proyek
            </p>

            <div class="flex items-center gap-2">
                {{-- sort --}}
                <select id="sort" 
                        class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="latest">Terbaru</option>
                    <option value="oldest">Terlama</option>
                    <option value="deadline">Deadline Terdekat</option>
                    <option value="popular">Paling Populer</option>
                </select>

                {{-- view buttons --}}
                <button id="grid-view-btn" 
                        onclick="switchView('grid')" 
                        class="p-2 border border-gray-300 rounded-lg hover:bg-gray-50 active-view bg-blue-50 border-blue-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                    </svg>
                </button>
                <button id="list-view-btn" 
                        onclick="switchView('list')" 
                        class="p-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                <button id="map-view-btn" 
                        onclick="switchView('map')" 
                        class="p-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- problems display --}}
        <div id="grid-view" class="view-content">
            @include('student.browse-problems.components.problems-grid')
        </div>

        <div id="list-view" class="view-content hidden">
            @foreach($problems as $problem)
                @include('student.browse-problems.components.problem-card-list', ['problem' => $problem])
            @endforeach
        </div>

        <div id="map-view" class="view-content hidden">
            @include('student.browse-problems.components.map-view')
        </div>

        {{-- pagination --}}
        <div class="mt-8">
            {{ $problems->links() }}
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('js/pages/browse-problems.js') }}"></script>
<script>
    // toggle filter section
    document.getElementById('toggle-filter')?.addEventListener('click', function() {
        const filterSection = document.getElementById('filter-section');
        filterSection.classList.toggle('hidden');
    });

    // view switcher
    function switchView(view) {
        // hide semua view
        document.querySelectorAll('.view-content').forEach(el => el.classList.add('hidden'));
        
        // remove active class dari semua button
        document.querySelectorAll('[id$="-view-btn"]').forEach(btn => {
            btn.classList.remove('active-view', 'bg-blue-50', 'border-blue-500');
        });
        
        // tampilkan view yang dipilih
        document.getElementById(view + '-view').classList.remove('hidden');
        
        // add active class ke button yang dipilih
        const activeBtn = document.getElementById(view + '-view-btn');
        activeBtn.classList.add('active-view', 'bg-blue-50', 'border-blue-500');
    }
</script>
@endpush