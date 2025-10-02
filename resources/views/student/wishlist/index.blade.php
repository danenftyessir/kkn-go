{{-- resources/views/student/wishlist/index.blade.php --}}
{{-- halaman wishlist/saved problems --}}

@extends('layouts.app')

@section('title', 'Wishlist Saya')

@push('styles')
<style>
/* wishlist card animations */
.wishlist-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    will-change: transform;
}

.wishlist-card:hover {
    transform: translateY(-4px) scale3d(1.01, 1.01, 1);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
}

/* notes textarea */
.notes-textarea {
    transition: all 0.3s ease;
}

.notes-textarea:focus {
    max-height: 200px;
}

/* delete button animation */
.delete-btn {
    transition: all 0.2s ease;
}

.delete-btn:hover {
    transform: scale(1.1);
}

/* smooth entrance animation */
@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translate3d(0, 30px, 0);
    }
    to {
        opacity: 1;
        transform: translate3d(0, 0, 0);
    }
}

.slide-in-up {
    animation: slideInUp 0.6s cubic-bezier(0.4, 0, 0.2, 1);
}

/* saved badge animation */
@keyframes heartBeat {
    0%, 100% { transform: scale(1); }
    10%, 30% { transform: scale(0.9); }
    20%, 40%, 60%, 80% { transform: scale(1.1); }
    50%, 70% { transform: scale(1.05); }
}

.heart-beat {
    animation: heartBeat 1.3s ease-in-out;
}
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50 py-8" x-data="wishlistPage()">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- header section -->
        <div class="mb-8 slide-in-up">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Wishlist Saya</h1>
                    <p class="text-gray-600 mt-1">Proyek yang Anda simpan untuk dilihat nanti</p>
                </div>
                
                <div class="flex items-center space-x-4">
                    <span class="px-4 py-2 bg-blue-100 text-blue-800 rounded-lg font-semibold">
                        {{ $wishlists->total() }} Proyek Tersimpan
                    </span>
                    <a href="{{ route('student.browse-problems') }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-200 hover:shadow-lg">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Cari Proyek Lain
                    </a>
                </div>
            </div>
        </div>

        <!-- wishlist content -->
        @if($wishlists->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($wishlists as $index => $wishlist)
                <div class="wishlist-card bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden" 
                     style="animation-delay: {{ $index * 0.1 }}s;"
                     x-data="wishlistCard({{ $wishlist->id }}, {{ $wishlist->problem_id }})">
                    
                    <!-- problem image -->
                    <div class="relative h-48 overflow-hidden bg-gray-100">
                        @if($wishlist->problem->images->isNotEmpty())
                            <img src="{{ asset('storage/' . $wishlist->problem->images->first()->image_path) }}" 
                                 alt="{{ $wishlist->problem->title }}"
                                 class="w-full h-full object-cover"
                                 loading="lazy">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-500 to-green-500">
                                <svg class="w-16 h-16 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                        @endif
                        
                        <!-- saved badge -->
                        <div class="absolute top-3 left-3">
                            <span class="heart-beat inline-flex items-center px-3 py-1 bg-red-500 text-white text-xs font-semibold rounded-full shadow-lg">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                                </svg>
                                Tersimpan
                            </span>
                        </div>

                        <!-- quick actions -->
                        <div class="absolute top-3 right-3 flex space-x-2">
                            <button @click="confirmDelete" 
                                    class="delete-btn p-2 bg-white rounded-full shadow-lg hover:bg-red-50 transition-colors"
                                    title="Hapus dari wishlist">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>

                        <!-- status badges -->
                        @if($wishlist->problem->is_urgent || $wishlist->problem->is_featured)
                        <div class="absolute bottom-3 left-3 flex space-x-2">
                            @if($wishlist->problem->is_featured)
                            <span class="px-2 py-1 bg-yellow-500 text-white text-xs font-semibold rounded-full shadow-lg">
                                ⭐ Unggulan
                            </span>
                            @endif
                            @if($wishlist->problem->is_urgent)
                            <span class="px-2 py-1 bg-red-500 text-white text-xs font-semibold rounded-full shadow-lg animate-pulse">
                                🔥 Mendesak
                            </span>
                            @endif
                        </div>
                        @endif
                    </div>

                    <!-- content -->
                    <div class="p-5">
                        <!-- institution -->
                        <div class="flex items-center mb-3">
                            @if($wishlist->problem->institution->logo_path)
                            <img src="{{ asset('storage/' . $wishlist->problem->institution->logo_path) }}" 
                                 alt="{{ $wishlist->problem->institution->name }}"
                                 class="w-8 h-8 rounded-full object-cover mr-2">
                            @else
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-green-500 flex items-center justify-center mr-2">
                                <span class="text-white text-xs font-bold">
                                    {{ strtoupper(substr($wishlist->problem->institution->name, 0, 1)) }}
                                </span>
                            </div>
                            @endif
                            <span class="text-sm text-gray-600 truncate">{{ $wishlist->problem->institution->name }}</span>
                        </div>

                        <!-- title -->
                        <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2">
                            {{ $wishlist->problem->title }}
                        </h3>

                        <!-- details -->
                        <div class="space-y-2 mb-4">
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                </svg>
                                {{ $wishlist->problem->regency->name }}, {{ $wishlist->problem->province->name }}
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                {{ $wishlist->problem->required_students }} mahasiswa
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $wishlist->problem->duration_months }} bulan
                            </div>
                            @php
                                $daysLeft = now()->diffInDays($wishlist->problem->application_deadline, false);
                            @endphp
                            <div class="flex items-center text-sm {{ $daysLeft <= 7 ? 'text-red-600 font-semibold' : 'text-gray-600' }}">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Deadline: {{ abs($daysLeft) }} hari {{ $daysLeft >= 0 ? 'lagi' : 'yang lalu' }}
                            </div>
                        </div>

                        <!-- personal notes -->
                        @if($wishlist->notes)
                        <div class="mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <p class="text-sm text-gray-700">
                                <span class="font-semibold text-yellow-800">Catatan:</span>
                                {{ $wishlist->notes }}
                            </p>
                        </div>
                        @endif

                        <!-- notes textarea (collapsible) -->
                        <div x-show="showNotesInput" 
                             x-transition
                             class="mb-4">
                            <textarea x-model="notes"
                                      @blur="saveNotes"
                                      placeholder="Tambahkan catatan pribadi..."
                                      rows="2"
                                      class="notes-textarea w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"></textarea>
                        </div>

                        <!-- saved date -->
                        <p class="text-xs text-gray-500 mb-4">
                            Disimpan {{ $wishlist->created_at->diffForHumans() }}
                        </p>

                        <!-- actions -->
                        <div class="flex space-x-2">
                            <a href="{{ route('student.problems.show', $wishlist->problem->id) }}" 
                               class="flex-1 px-4 py-2 bg-blue-600 text-white text-sm font-semibold text-center rounded-lg hover:bg-blue-700 transition-all duration-200 hover:shadow-lg">
                                Lihat Detail
                            </a>
                            <button @click="showNotesInput = !showNotesInput"
                                    class="px-4 py-2 bg-gray-100 text-gray-700 text-sm rounded-lg hover:bg-gray-200 transition-colors"
                                    title="Tambah catatan">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- pagination -->
            @if($wishlists->hasPages())
            <div class="mt-8">
                {{ $wishlists->links() }}
            </div>
            @endif
        @else
            <!-- empty state -->
            <div class="slide-in-up bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                <svg class="mx-auto h-24 w-24 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Wishlist Kosong</h3>
                <p class="text-gray-600 mb-6">Anda belum menyimpan proyek apapun. Mulai jelajahi dan simpan proyek yang menarik!</p>
                <a href="{{ route('student.browse-problems') }}" 
                   class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-200 hover:shadow-lg">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Jelajahi Proyek
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
// alpine.js component untuk wishlist page
function wishlistPage() {
    return {
        init() {
            // animasi entrance
            this.animateCards();
        },
        
        animateCards() {
            const cards = document.querySelectorAll('.wishlist-card');
            cards.forEach((card, index) => {
                card.classList.add('slide-in-up');
            });
        }
    };
}

// alpine.js component untuk individual wishlist card
function wishlistCard(wishlistId, problemId) {
    return {
        notes: '',
        showNotesInput: false,
        
        confirmDelete() {
            if (confirm('Apakah Anda yakin ingin menghapus proyek ini dari wishlist?')) {
                this.deleteWishlist();
            }
        },
        
        async deleteWishlist() {
            try {
                const response = await fetch(`/student/wishlist/${problemId}/toggle`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // animasi fade out
                    this.$el.style.transition = 'all 0.3s ease';
                    this.$el.style.opacity = '0';
                    this.$el.style.transform = 'scale(0.9)';
                    
                    // reload setelah animasi
                    setTimeout(() => {
                        window.location.reload();
                    }, 300);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan. Silakan coba lagi.');
            }
        },
        
        async saveNotes() {
            if (!this.notes.trim()) return;
            
            try {
                const response = await fetch(`/student/wishlist/${problemId}/notes`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ notes: this.notes })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // tutup input setelah save
                    this.showNotesInput = false;
                    
                    // reload untuk update tampilan
                    setTimeout(() => {
                        window.location.reload();
                    }, 300);
                }
            } catch (error) {
                console.error('Error:', error);
            }
        }
    };
}
</script>
@endpush