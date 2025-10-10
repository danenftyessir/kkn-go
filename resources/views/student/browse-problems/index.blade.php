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
                    <button type="submit" class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                        Cari
                    </button>
                    <button type="button" onclick="toggleFilter()" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                        </svg>
                        Filter
                    </button>
                </div>

                {{-- advanced filters (initially hidden) --}}
                <div id="advancedFilters" class="hidden pt-4 border-t border-gray-200 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Provinsi</label>
                            <select name="province_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="">Semua Provinsi</option>
                                @foreach($provinces as $province)
                                    <option value="{{ $province->id }}" {{ request('province_id') == $province->id ? 'selected' : '' }}>
                                        {{ $province->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tingkat Kesulitan</label>
                            <select name="difficulty" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="">Semua Tingkat</option>
                                <option value="beginner" {{ request('difficulty') == 'beginner' ? 'selected' : '' }}>Beginner</option>
                                <option value="intermediate" {{ request('difficulty') == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                <option value="advanced" {{ request('difficulty') == 'advanced' ? 'selected' : '' }}>Advanced</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Durasi</label>
                            <select name="duration" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="">Semua Durasi</option>
                                <option value="1-2" {{ request('duration') == '1-2' ? 'selected' : '' }}>1-2 Bulan</option>
                                <option value="3-4" {{ request('duration') == '3-4' ? 'selected' : '' }}>3-4 Bulan</option>
                                <option value="5-6" {{ request('duration') == '5-6' ? 'selected' : '' }}>5-6 Bulan</option>
                            </select>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        {{-- view toggle --}}
        <div class="flex items-center justify-between mb-6">
            <p class="text-gray-600">
                Menampilkan <span class="font-semibold">{{ $problems->firstItem() ?? 0 }}</span> - 
                <span class="font-semibold">{{ $problems->lastItem() ?? 0 }}</span> dari 
                <span class="font-semibold">{{ $problems->total() }}</span> proyek
            </p>
            <div class="flex items-center gap-2">
                <button onclick="setView('grid')" class="p-2 rounded-lg transition-colors {{ request('view') !== 'list' ? 'bg-blue-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                    </svg>
                </button>
                <button onclick="setView('list')" class="p-2 rounded-lg transition-colors {{ request('view') === 'list' ? 'bg-blue-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-100' }}">
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
<script>
// ✅ TAMBAHAN: fungsi toggleFilter
function toggleFilter() {
    const filters = document.getElementById('advancedFilters');
    filters.classList.toggle('hidden');
}

// ✅ TAMBAHAN: fungsi setView
function setView(view) {
    const url = new URL(window.location.href);
    url.searchParams.set('view', view);
    window.location.href = url.toString();
}

// ✅ FIX: tambahkan fungsi toggleWishlist di sini
async function toggleWishlist(problemId, button) {
    // disable button sementara
    button.disabled = true;
    
    // add loading state
    const originalHTML = button.innerHTML;
    button.innerHTML = `
        <svg class="animate-spin w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    `;
    
    try {
        const response = await fetch(`/student/wishlist/${problemId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });
        
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        
        const data = await response.json();
        
        if (data.success) {
            // update button state dengan animation
            button.setAttribute('data-wishlisted', data.saved ? 'true' : 'false');
            
            // update icon
            const svg = button.querySelector('svg');
            if (data.saved) {
                svg.classList.add('fill-current', 'text-red-500');
                svg.classList.remove('text-gray-400', 'text-gray-600');
                svg.setAttribute('fill', 'currentColor');
                button.classList.add('text-red-500');
                button.classList.remove('text-gray-400');
            } else {
                svg.classList.remove('fill-current', 'text-red-500');
                svg.classList.add('text-gray-400');
                svg.setAttribute('fill', 'none');
                button.classList.add('text-gray-400');
                button.classList.remove('text-red-500');
            }
            
            // restore original SVG path
            svg.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>';
            
            // animation heartbeat
            button.style.transform = 'scale(1.2)';
            setTimeout(() => {
                button.style.transform = 'scale(1)';
            }, 200);
            
            // tampilkan notification
            showNotification(data.message || (data.saved ? 'Ditambahkan ke wishlist' : 'Dihapus dari wishlist'), 'success');
        }
        
    } catch (error) {
        console.error('Error toggling wishlist:', error);
        showNotification('Terjadi kesalahan. Silakan coba lagi.', 'error');
        
        // restore original state
        button.innerHTML = originalHTML;
    } finally {
        button.disabled = false;
    }
}

// fungsi untuk menampilkan notifikasi
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `fixed bottom-4 right-4 px-6 py-3 rounded-lg shadow-lg text-white z-50 transform transition-all duration-300 ${
        type === 'success' ? 'bg-green-500' : 'bg-red-500'
    }`;
    notification.textContent = message;
    notification.style.opacity = '0';
    notification.style.transform = 'translateY(20px)';
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.opacity = '1';
        notification.style.transform = 'translateY(0)';
    }, 10);
    
    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transform = 'translateY(20px)';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}
</script>
@endpush