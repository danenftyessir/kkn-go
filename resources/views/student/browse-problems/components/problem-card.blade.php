{{-- resources/views/student/browse-problems/components/problem-card.blade.php --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 fade-in-up group" 
     style="animation-delay: {{ ($index % 6) * 0.1 }}s;">
    
    {{-- image header --}}
    <a href="{{ route('student.browse-problems.show', $problem->id) }}" class="block relative overflow-hidden aspect-video bg-gradient-to-br from-blue-100 to-green-100">
        @if($problem->images && $problem->images->first())
            <img src="{{ asset('storage/' . $problem->images->first()->image_path) }}" 
                 alt="{{ $problem->title }}"
                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
        @else
            <div class="w-full h-full flex items-center justify-center">
                <svg class="w-20 h-20 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
        @endif
        
        {{-- badges overlay --}}
        <div class="absolute top-3 left-3 flex flex-wrap gap-2">
            @if($problem->is_featured)
            <span class="px-3 py-1 bg-yellow-500 text-white text-xs font-bold rounded-full shadow-lg backdrop-blur-sm">
                ⭐ Unggulan
            </span>
            @endif
            
            @if($problem->is_urgent)
            <span class="px-3 py-1 bg-red-500 text-white text-xs font-bold rounded-full shadow-lg animate-pulse backdrop-blur-sm">
                🔥 Mendesak
            </span>
            @endif
        </div>
        
        {{-- wishlist button --}}
        <div class="absolute top-3 right-3">
            @auth
                @if(Auth::user()->user_type === 'student')
                <div x-data="wishlistToggle({{ $problem->id }}, {{ $problem->wishlisted ? 'true' : 'false' }})">
                    <button @click.prevent="toggle()"
                            :disabled="loading"
                            :class="saved ? 'bg-red-50 border-red-300' : 'bg-white border-gray-300'"
                            class="p-2 rounded-lg border hover:shadow-lg transition-all duration-200 backdrop-blur-sm">
                        <svg :class="saved ? 'text-red-600' : 'text-gray-600'" 
                             class="w-5 h-5 transition-colors" 
                             :fill="saved ? 'currentColor' : 'none'" 
                             stroke="currentColor" 
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </button>
                </div>
                @endif
            @endauth
        </div>
    </a>

    {{-- content --}}
    <div class="p-5">
        {{-- instansi --}}
        <div class="flex items-center space-x-2 mb-3">
            @if($problem->institution->logo_path)
            <img src="{{ asset('storage/' . $problem->institution->logo_path) }}" 
                 alt="{{ $problem->institution->name }}"
                 class="w-8 h-8 rounded-full object-cover">
            @else
            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-green-500 flex items-center justify-center">
                <span class="text-white text-xs font-bold">
                    {{ strtoupper(substr($problem->institution->name, 0, 1)) }}
                </span>
            </div>
            @endif
            <span class="text-sm text-gray-600 truncate">{{ $problem->institution->name }}</span>
        </div>

        {{-- title --}}
        <a href="{{ route('student.browse-problems.show', $problem->id) }}" class="block group">
            <h3 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-blue-600 transition-colors line-clamp-2">
                {{ $problem->title }}
            </h3>
        </a>

        {{-- location --}}
        <div class="flex items-center text-sm text-gray-600 mb-3">
            <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            <span class="truncate">{{ $problem->regency->name ?? '' }}, {{ $problem->province->name ?? '' }}</span>
        </div>

        {{-- SDG categories --}}
        <div class="flex flex-wrap gap-1 mb-4">
            @php
                // parse sdg_categories dengan aman
                $sdgCategories = [];
                if (isset($problem->sdg_categories)) {
                    if (is_array($problem->sdg_categories)) {
                        $sdgCategories = $problem->sdg_categories;
                    } elseif (is_string($problem->sdg_categories)) {
                        $sdgCategories = json_decode($problem->sdg_categories, true) ?? [];
                    }
                }
                $displayCategories = array_slice($sdgCategories, 0, 3);
            @endphp
            @foreach($displayCategories as $sdg)
                <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-medium rounded">
                    {{ is_numeric($sdg) ? 'SDG ' . $sdg : ucfirst(str_replace('_', ' ', $sdg)) }}
                </span>
            @endforeach
            @if(count($sdgCategories) > 3)
                <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs font-medium rounded">
                    +{{ count($sdgCategories) - 3 }}
                </span>
            @endif
        </div>

        {{-- meta info --}}
        <div class="grid grid-cols-3 gap-2 py-3 border-t border-gray-200 text-xs text-gray-600">
            <div class="flex flex-col items-center">
                <svg class="w-4 h-4 mb-1 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <span class="font-semibold text-gray-900">{{ $problem->required_students }}</span>
                <span class="text-gray-500">Mahasiswa</span>
            </div>
            <div class="flex flex-col items-center border-x border-gray-200">
                <svg class="w-4 h-4 mb-1 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="font-semibold text-gray-900">{{ $problem->duration_months }}</span>
                <span class="text-gray-500">Bulan</span>
            </div>
            <div class="flex flex-col items-center">
                <svg class="w-4 h-4 mb-1 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span class="font-semibold text-gray-900">{{ $problem->applications_count }}</span>
                <span class="text-gray-500">Aplikasi</span>
            </div>
        </div>

        {{-- difficulty badge --}}
        <div class="flex items-center justify-between pt-3 border-t border-gray-200">
            <span class="px-3 py-1 text-xs font-semibold rounded-full
                {{ $problem->difficulty_level === 'beginner' ? 'bg-green-100 text-green-700' : '' }}
                {{ $problem->difficulty_level === 'intermediate' ? 'bg-yellow-100 text-yellow-700' : '' }}
                {{ $problem->difficulty_level === 'advanced' ? 'bg-red-100 text-red-700' : '' }}">
                {{ ucfirst($problem->difficulty_level) }}
            </span>
            
            <a href="{{ route('student.browse-problems.show', $problem->id) }}" 
               class="inline-flex items-center text-sm font-semibold text-blue-600 hover:text-blue-700 transition-colors">
                Lihat Detail
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
// wishlist toggle component untuk card
function wishlistToggle(problemId, initialSaved) {
    return {
        saved: initialSaved,
        loading: false,
        
        async toggle() {
            if (this.loading) return;
            
            this.loading = true;
            
            try {
                const response = await fetch(`/student/wishlist/toggle/${problemId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    this.saved = data.wishlisted;
                    
                    // tampilkan notifikasi toast
                    const message = this.saved ? 'Ditambahkan ke wishlist' : 'Dihapus dari wishlist';
                    showToast(message, 'success');
                }
            } catch (error) {
                console.error('Error toggling wishlist:', error);
                showToast('Terjadi kesalahan', 'error');
            } finally {
                this.loading = false;
            }
        }
    }
}

// fungsi untuk menampilkan toast notification
function showToast(message, type = 'success') {
    // cek apakah sudah ada container toast
    let container = document.getElementById('toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toast-container';
        container.className = 'fixed top-4 right-4 z-50 space-y-2';
        document.body.appendChild(container);
    }
    
    const toast = document.createElement('div');
    toast.className = `px-6 py-3 rounded-lg shadow-lg ${
        type === 'success' ? 'bg-green-500' : 'bg-red-500'
    } text-white font-semibold transform transition-all duration-300 translate-x-full`;
    toast.textContent = message;
    
    container.appendChild(toast);
    
    // animasi masuk
    setTimeout(() => {
        toast.classList.remove('translate-x-full');
    }, 10);
    
    // animasi keluar dan hapus
    setTimeout(() => {
        toast.classList.add('translate-x-full', 'opacity-0');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}
</script>
@endpush

@once
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
@endonce