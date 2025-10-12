{{-- resources/views/student/browse-problems/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Browse Problems')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/browse-problems.css') }}">
<style>
    .hero-browse-background {
        position: relative;
        background-image: url('/dashboard-student.jpg');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
    }
    
    .hero-browse-background::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        /* Gradient lebih transparan - opacity dikurangi dari 0.75-0.85 jadi 0.45-0.55 */
        background: linear-gradient(
            135deg, 
            rgba(37, 99, 235, 0.50) 0%,     /* Biru lebih transparan */
            rgba(59, 130, 246, 0.45) 35%,   /* Biru medium transparan */
            rgba(16, 185, 129, 0.45) 65%,   /* Hijau medium transparan */
            rgba(5, 150, 105, 0.50) 100%    /* Hijau transparan */
        );
        backdrop-filter: blur(1px);  /* Blur dikurangi dari 2px ke 1px */
    }
    
    .stats-card-modern {
        background: rgba(255, 255, 255, 0.20);  /* Sedikit lebih solid dari sebelumnya */
        backdrop-filter: blur(16px);             /* Blur diperkuat untuk clarity */
        border: 1px solid rgba(255, 255, 255, 0.3);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .stats-card-modern:hover {
        background: rgba(255, 255, 255, 0.30);
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3);
    }
    
    .text-shadow-strong {
        text-shadow: 
            0 2px 4px rgba(0, 0, 0, 0.4),
            0 4px 8px rgba(0, 0, 0, 0.3),
            0 1px 2px rgba(0, 0, 0, 0.5);  /* Multiple layers untuk readability maksimal */
    }
    
    .browse-fade-in {
        animation: fadeInUp 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50">
    
    {{-- header section dengan background image --}}
    <div class="hero-browse-background text-white py-16 md:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="browse-fade-in">
                <h1 class="text-4xl md:text-5xl font-bold mb-4 text-shadow-strong">
                    Jelajahi Proyek KKN
                </h1>
                <p class="text-xl md:text-2xl text-white text-shadow-strong max-w-3xl">
                    Temukan proyek KKN yang sesuai dengan minat dan keahlian Anda
                </p>
            </div>
            
            {{-- stats cards dengan modern glassmorphism effect --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6 mt-10 browse-fade-in" style="animation-delay: 0.2s;">
                <div class="stats-card-modern rounded-xl p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-4xl md:text-5xl font-bold text-white text-shadow-strong">
                                {{ $totalProblems ?? 0 }}
                            </div>
                            <div class="text-sm md:text-base text-white font-medium text-shadow-strong mt-2">
                                Total Proyek
                            </div>
                        </div>
                        <div class="w-14 h-14 md:w-16 md:h-16 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-7 h-7 md:w-8 md:h-8 text-white drop-shadow-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    </div>
                </div>
                
                <div class="stats-card-modern rounded-xl p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-4xl md:text-5xl font-bold text-white text-shadow-strong">
                                {{ $openProblems ?? 0 }}
                            </div>
                            <div class="text-sm md:text-base text-white font-medium text-shadow-strong mt-2">
                                Proyek Terbuka
                            </div>
                        </div>
                        <div class="w-14 h-14 md:w-16 md:h-16 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-7 h-7 md:w-8 md:h-8 text-white drop-shadow-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                        </div>
                    </div>
                </div>
                
                <div class="stats-card-modern rounded-xl p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-4xl md:text-5xl font-bold text-white text-shadow-strong">
                                {{ $totalInstitutions ?? 0 }}
                            </div>
                            <div class="text-sm md:text-base text-white font-medium text-shadow-strong mt-2">
                                Instansi Partner
                            </div>
                        </div>
                        <div class="w-14 h-14 md:w-16 md:h-16 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-7 h-7 md:w-8 md:h-8 text-white drop-shadow-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        {{-- search & filter bar --}}
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6 border border-gray-100">
            <form method="GET" action="{{ route('student.browse-problems.index') }}" class="space-y-4">
                
                {{-- search bar --}}
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <div class="relative">
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Cari proyek berdasarkan judul, atau, deskripsi..."
                                   class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                            <svg class="w-5 h-5 text-gray-400 absolute left-4 top-1/2 transform -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </div>
                    
                    <div class="flex gap-2">
                        {{-- toggle view --}}
                        <button type="button" 
                                id="toggle-view"
                                class="px-4 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
                                title="Toggle View">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                            </svg>
                        </button>
                        
                        {{-- filter button --}}
                        <button type="button" 
                                id="toggle-filter"
                                class="px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                            <span class="hidden sm:inline">Filter</span>
                        </button>
                        
                        {{-- submit button --}}
                        <button type="submit" 
                                class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium">
                            Cari
                        </button>
                    </div>
                </div>

                {{-- filter section (collapsible) --}}
                <div id="filter-section" class="hidden">
                    @include('student.browse-problems.components.filter-sidebar')
                </div>
            </form>
        </div>

        {{-- view mode toggle --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <p class="text-gray-600">
                    Menampilkan <span class="font-semibold text-gray-900">{{ $problems->count() }}</span> dari 
                    <span class="font-semibold text-gray-900">{{ $problems->total() }}</span> proyek
                </p>
            </div>
            
            <div class="flex items-center gap-2">
                <button id="grid-view-btn" 
                        class="p-2 rounded-lg border border-gray-300 hover:bg-gray-50 transition-colors active-view"
                        onclick="switchView('grid')">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                </button>
                <button id="list-view-btn" 
                        class="p-2 rounded-lg border border-gray-300 hover:bg-gray-50 transition-colors"
                        onclick="switchView('list')">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                </button>
                <button id="map-view-btn" 
                        class="p-2 rounded-lg border border-gray-300 hover:bg-gray-50 transition-colors"
                        onclick="switchView('map')">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

@push('scripts')
<script src="{{ asset('js/pages/browse-problems.js') }}"></script>
<script>
    // toggle filter section
    document.getElementById('toggle-filter').addEventListener('click', function() {
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
@endsection