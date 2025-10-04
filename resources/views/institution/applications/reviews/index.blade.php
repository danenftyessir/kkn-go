@extends('layouts.app')

@section('title', 'Review Mahasiswa')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Review Mahasiswa</h1>
            <p class="text-gray-600 mt-1">Daftar review yang telah Anda berikan</p>
        </div>

        {{-- statistik --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <p class="text-sm text-gray-600">Total Reviews</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <p class="text-sm text-gray-600">Rating Rata-rata</p>
                <div class="flex items-center gap-1">
                    <p class="text-2xl font-bold text-yellow-600">{{ number_format($stats['average_rating'], 1) }}</p>
                    <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <p class="text-sm text-gray-600">Rating 5 Bintang</p>
                <p class="text-2xl font-bold text-green-600">{{ $stats['five_star'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <p class="text-sm text-gray-600">Rating 4 Bintang</p>
                <p class="text-2xl font-bold text-blue-600">{{ $stats['four_star'] }}</p>
            </div>
        </div>

        {{-- filter --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <form method="GET" class="flex flex-wrap gap-4">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama mahasiswa..." class="flex-1 min-w-[250px] px-4 py-2 border border-gray-300 rounded-lg">
                <select name="rating" class="px-4 py-2 border border-gray-300 rounded-lg">
                    <option value="">Semua Rating</option>
                    @for($i = 5; $i >= 1; $i--)
                    <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>{{ $i }} Bintang</option>
                    @endfor
                </select>
                <select name="sort" class="px-4 py-2 border border-gray-300 rounded-lg">
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                    <option value="rating_high" {{ request('sort') == 'rating_high' ? 'selected' : '' }}>Rating Tertinggi</option>
                    <option value="rating_low" {{ request('sort') == 'rating_low' ? 'selected' : '' }}>Rating Terendah</option>
                </select>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold">Filter</button>
                @if(request()->hasAny(['search', 'rating', 'sort']))
                <a href="{{ route('institution.reviews.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-semibold">Reset</a>
                @endif
            </form>
        </div>

        {{-- daftar reviews --}}
        <div class="space-y-4">
            @forelse($reviews as $review)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-start gap-4">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-full flex items-center justify-center font-bold text-xl flex-shrink-0">
                        {{ substr($review->student->user->name, 0, 1) }}
                    </div>

                    <div class="flex-1">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">{{ $review->student->user->name }}</h3>
                                <p class="text-sm text-gray-600">{{ $review->student->university->name }}</p>
                            </div>
                            <div class="flex items-center gap-1">
                                @for($i = 1; $i <= 5; $i++)
                                <svg class="w-5 h-5 {{ $i <= $review->rating ? 'text-yellow-500' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                @endfor
                                <span class="ml-2 font-semibold text-gray-700">{{ $review->rating }}/5</span>
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4 mb-3">
                            <p class="text-sm font-semibold text-gray-700 mb-1">Untuk Proyek:</p>
                            <p class="text-gray-900">{{ $review->project->problem->title }}</p>
                        </div>

                        <p class="text-gray-700 mb-3">{{ $review->review }}</p>

                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                            <div class="flex gap-2">
                                <a href="{{ route('institution.reviews.show', $review->id) }}" class="text-blue-600 hover:text-blue-700 font-semibold">Lihat Detail</a>
                                @if($review->created_at->addDays(30)->isFuture())
                                <a href="{{ route('institution.reviews.edit', $review->id) }}" class="text-green-600 hover:text-green-700 font-semibold">Edit</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                </svg>
                <p class="text-gray-600 text-lg">Belum ada review yang diberikan</p>
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
@endsection