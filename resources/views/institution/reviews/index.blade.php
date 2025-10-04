@extends('layouts.app')

@section('title', 'Daftar Review')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-green-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- header section --}}
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Daftar Review</h1>
                    <p class="mt-2 text-gray-600">Kelola review yang telah Anda berikan kepada mahasiswa</p>
                </div>
            </div>
        </div>

        {{-- statistik cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 transition-all duration-300 hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Total Review</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                    </div>
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 transition-all duration-300 hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Rating Rata-rata</p>
                        <div class="flex items-center gap-2">
                            <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['average_rating'], 1) }}</p>
                            <div class="flex text-yellow-400">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-5 h-5 {{ $i <= round($stats['average_rating']) ? 'fill-current' : 'fill-gray-300' }}" viewBox="0 0 20 20">
                                        <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                    </svg>
                                @endfor
                            </div>
                        </div>
                    </div>
                    <div class="p-3 bg-yellow-100 rounded-lg">
                        <svg class="w-8 h-8 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 transition-all duration-300 hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Review Bintang 5</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['five_star'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $stats['total'] > 0 ? round(($stats['five_star'] / $stats['total']) * 100, 1) : 0 }}% dari total</p>
                    </div>
                    <div class="p-3 bg-green-100 rounded-lg">
                        <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- filter & search section --}}
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6 border border-gray-100">
            <form method="GET" action="{{ route('institution.reviews.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                
                {{-- search --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cari Mahasiswa</label>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Nama mahasiswa..." 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                </div>

                {{-- filter rating --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                    <select name="rating" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                        <option value="">Semua Rating</option>
                        <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>⭐⭐⭐⭐⭐</option>
                        <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>⭐⭐⭐⭐</option>
                        <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>⭐⭐⭐</option>
                        <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>⭐⭐</option>
                        <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>⭐</option>
                    </select>
                </div>

                {{-- sort --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Urutkan</label>
                    <select name="sort" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                        <option value="rating_high" {{ request('sort') == 'rating_high' ? 'selected' : '' }}>Rating Tertinggi</option>
                        <option value="rating_low" {{ request('sort') == 'rating_low' ? 'selected' : '' }}>Rating Terendah</option>
                    </select>
                </div>

                <div class="md:col-span-4 flex gap-3">
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-200 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Terapkan Filter
                    </button>
                    <a href="{{ route('institution.reviews.index') }}" class="px-6 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all duration-200">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        {{-- reviews list --}}
        <div class="space-y-4">
            @forelse($reviews as $review)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 transition-all duration-300 hover:shadow-md {{ !$review->is_read ? 'border-l-4 border-l-blue-500' : '' }}">
                    <div class="p-6">
                        <div class="flex items-start justify-between">
                            <div class="flex gap-4 flex-1">
                                {{-- student avatar --}}
                                <img src="{{ $review->student->user->profile_picture ? asset('storage/' . $review->student->user->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($review->student->user->name) }}" 
                                     alt="{{ $review->student->user->name }}"
                                     class="w-16 h-16 rounded-full object-cover border-2 border-gray-200">
                                
                                <div class="flex-1">
                                    {{-- student info --}}
                                    <div class="mb-3">
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $review->student->user->name }}</h3>
                                        <p class="text-sm text-gray-600">{{ $review->student->university->name }}</p>
                                        <p class="text-xs text-gray-500 mt-1">Proyek: {{ $review->project->problem->title }}</p>
                                    </div>

                                    {{-- rating --}}
                                    <div class="flex items-center gap-2 mb-3">
                                        <div class="flex text-yellow-400">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-5 h-5 {{ $i <= $review->rating ? 'fill-current' : 'fill-gray-300' }}" viewBox="0 0 20 20">
                                                    <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                                </svg>
                                            @endfor
                                        </div>
                                        <span class="text-sm font-medium text-gray-700">{{ $review->rating }}/5</span>
                                        <span class="text-xs text-gray-500">• {{ $review->created_at->diffForHumans() }}</span>
                                    </div>

                                    {{-- review text --}}
                                    <p class="text-gray-700 leading-relaxed mb-3">{{ Str::limit($review->review, 200) }}</p>

                                    {{-- strengths & improvements --}}
                                    @if($review->strengths || $review->improvements)
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-3">
                                            @if($review->strengths)
                                                <div class="bg-green-50 rounded-lg p-3 border border-green-100">
                                                    <p class="text-xs font-semibold text-green-700 mb-1">Kelebihan:</p>
                                                    <p class="text-sm text-green-600">{{ Str::limit($review->strengths, 100) }}</p>
                                                </div>
                                            @endif
                                            @if($review->improvements)
                                                <div class="bg-blue-50 rounded-lg p-3 border border-blue-100">
                                                    <p class="text-xs font-semibold text-blue-700 mb-1">Saran Perbaikan:</p>
                                                    <p class="text-sm text-blue-600">{{ Str::limit($review->improvements, 100) }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    @endif

                                    {{-- would collaborate again --}}
                                    @if($review->would_collaborate_again)
                                        <div class="inline-flex items-center gap-2 bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-medium">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            Bersedia Berkolaborasi Lagi
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- actions dropdown --}}
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" @click.away="open = false" class="p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                                    </svg>
                                </button>
                                
                                <div x-show="open" 
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-150"
                                     x-transition:leave-start="opacity-100 scale-100"
                                     x-transition:leave-end="opacity-0 scale-95"
                                     class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-10">
                                    <div class="py-1">
                                        <a href="{{ route('institution.reviews.show', $review->id) }}" 
                                           class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Lihat Detail
                                        </a>
                                        
                                        @if($review->created_at->addDays(30)->isFuture())
                                            <a href="{{ route('institution.reviews.edit', $review->id) }}" 
                                               class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                                Edit Review
                                            </a>
                                        @endif

                                        @if($review->created_at->addDays(7)->isFuture())
                                            <form action="{{ route('institution.reviews.destroy', $review->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus review ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors duration-200">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                    Hapus Review
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl shadow-sm p-12 text-center border border-gray-100">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum Ada Review</h3>
                    <p class="text-gray-600">Anda belum memberikan review kepada mahasiswa.</p>
                </div>
            @endforelse
        </div>

        {{-- pagination --}}
        @if($reviews->hasPages())
            <div class="mt-8">
                {{ $reviews->links() }}
            </div>
        @endif

    </div>
</div>

{{-- alpine.js untuk dropdown --}}
@push('scripts')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush
@endsection