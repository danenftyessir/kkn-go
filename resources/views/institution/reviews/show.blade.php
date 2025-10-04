@extends('layouts.app')

@section('title', 'Detail Review')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-green-50 py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- breadcrumb --}}
        <nav class="mb-6">
            <ol class="flex items-center gap-2 text-sm">
                <li><a href="{{ route('institution.dashboard') }}" class="text-gray-500 hover:text-gray-700 transition-colors duration-200">Dashboard</a></li>
                <li class="text-gray-400">/</li>
                <li><a href="{{ route('institution.reviews.index') }}" class="text-gray-500 hover:text-gray-700 transition-colors duration-200">Review</a></li>
                <li class="text-gray-400">/</li>
                <li class="text-gray-900 font-medium">Detail</li>
            </ol>
        </nav>

        {{-- header section --}}
        <div class="bg-white rounded-xl shadow-sm p-8 mb-6 border border-gray-100">
            <div class="flex items-start justify-between mb-6">
                <div class="flex items-start gap-6">
                    {{-- student avatar --}}
                    <img src="{{ $review->student->user->profile_picture ? asset('storage/' . $review->student->user->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($review->student->user->name) }}" 
                         alt="{{ $review->student->user->name }}"
                         class="w-24 h-24 rounded-full object-cover border-4 border-gray-200 shadow-md">
                    
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $review->student->user->name }}</h1>
                        <div class="space-y-2 text-gray-600">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                <span>{{ $review->student->university->name }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <span>{{ $review->student->major }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span>Direview {{ $review->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- action buttons --}}
                <div class="flex items-center gap-2">
                    @if($review->created_at->addDays(30)->isFuture())
                        <a href="{{ route('institution.reviews.edit', $review->id) }}" 
                           class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-200 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit Review
                        </a>
                    @endif
                    
                    @if($review->created_at->addDays(7)->isFuture())
                        <form action="{{ route('institution.reviews.destroy', $review->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus review ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-all duration-200 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Hapus
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            {{-- edit/delete notice --}}
            <div class="flex gap-3">
                @if($review->created_at->addDays(30)->isFuture())
                    <div class="flex-1 bg-blue-50 border border-blue-200 rounded-lg p-3">
                        <p class="text-sm text-blue-700">
                            <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            Review dapat diedit hingga {{ $review->created_at->addDays(30)->format('d M Y') }}
                        </p>
                    </div>
                @endif
                
                @if($review->created_at->addDays(7)->isFuture())
                    <div class="flex-1 bg-amber-50 border border-amber-200 rounded-lg p-3">
                        <p class="text-sm text-amber-700">
                            <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            Review dapat dihapus hingga {{ $review->created_at->addDays(7)->format('d M Y') }}
                        </p>
                    </div>
                @endif
            </div>
        </div>

        {{-- project info --}}
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6 border border-gray-100">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Informasi Proyek</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Judul Proyek</p>
                    <p class="font-semibold text-gray-900">{{ $review->project->problem->title }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Periode Proyek</p>
                    <p class="font-semibold text-gray-900">{{ $review->project->start_date->format('d M Y') }} - {{ $review->project->end_date->format('d M Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Status Proyek</p>
                    <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full 
                        {{ $review->project->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                        {{ ucfirst($review->project->status) }}
                    </span>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Lokasi</p>
                    <p class="font-semibold text-gray-900">{{ $review->project->problem->location }}</p>
                </div>
            </div>
        </div>

        {{-- rating section --}}
        <div class="bg-gradient-to-r from-blue-500 to-green-500 rounded-xl shadow-lg p-8 mb-6 text-white">
            <div class="text-center">
                <p class="text-lg font-medium mb-3 text-blue-100">Rating Keseluruhan</p>
                <div class="flex items-center justify-center gap-3 mb-4">
                    <span class="text-6xl font-bold">{{ $review->rating }}</span>
                    <span class="text-3xl text-blue-100">/5</span>
                </div>
                <div class="flex justify-center gap-2">
                    @for($i = 1; $i <= 5; $i++)
                        <svg class="w-10 h-10 {{ $i <= $review->rating ? 'text-yellow-300 fill-current' : 'text-blue-200 fill-current' }}" viewBox="0 0 20 20">
                            <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                        </svg>
                    @endfor
                </div>
            </div>
        </div>

        {{-- review content --}}
        <div class="bg-white rounded-xl shadow-sm p-8 mb-6 border border-gray-100">
            <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                </svg>
                Review
            </h2>
            <p class="text-gray-700 leading-relaxed text-lg whitespace-pre-line">{{ $review->review }}</p>
        </div>

        {{-- strengths & improvements --}}
        @if($review->strengths || $review->improvements)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                @if($review->strengths)
                    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500">
                        <h3 class="text-lg font-bold text-green-700 mb-3 flex items-center gap-2">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Kelebihan
                        </h3>
                        <p class="text-gray-700 leading-relaxed">{{ $review->strengths }}</p>
                    </div>
                @endif

                @if($review->improvements)
                    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-500">
                        <h3 class="text-lg font-bold text-blue-700 mb-3 flex items-center gap-2">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            Saran Perbaikan
                        </h3>
                        <p class="text-gray-700 leading-relaxed">{{ $review->improvements }}</p>
                    </div>
                @endif
            </div>
        @endif

        {{-- collaboration status --}}
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6 border border-gray-100">
            <div class="flex items-center gap-4">
                <div class="p-4 rounded-full {{ $review->would_collaborate_again ? 'bg-green-100' : 'bg-gray-100' }}">
                    <svg class="w-8 h-8 {{ $review->would_collaborate_again ? 'text-green-600' : 'text-gray-400' }}" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">Status Kolaborasi</h3>
                    <p class="text-gray-600">
                        @if($review->would_collaborate_again)
                            <span class="text-green-600 font-semibold">Bersedia berkolaborasi lagi</span> dengan mahasiswa ini di proyek mendatang
                        @else
                            <span class="text-gray-600 font-semibold">Tidak ada preferensi</span> untuk kolaborasi mendatang
                        @endif
                    </p>
                </div>
            </div>
        </div>

        {{-- back button --}}
        <div class="flex justify-between items-center">
            <a href="{{ route('institution.reviews.index') }}" 
               class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all duration-200 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Daftar Review
            </a>

            <a href="{{ route('institution.projects.show', $review->project->id) }}" 
               class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-200 flex items-center gap-2">
                Lihat Proyek
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </a>
        </div>

    </div>
</div>
@endsection