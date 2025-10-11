@extends('layouts.app')

@section('title', 'Detail Review - ' . config('app.name'))

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- breadcrumb --}}
        <nav class="mb-8 flex items-center gap-2 text-sm text-gray-600">
            <a href="{{ route('institution.dashboard') }}" class="hover:text-blue-600 transition-colors">Dashboard</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <a href="{{ route('institution.reviews.index') }}" class="hover:text-blue-600 transition-colors">Reviews</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-gray-900 font-medium">Detail Review</span>
        </nav>

        {{-- header section --}}
        <div class="bg-white rounded-xl shadow-sm p-8 mb-6 border border-gray-100">
            @if($review->reviewee && $review->reviewee->student)
                <div class="flex items-start justify-between mb-6">
                    <div class="flex items-start gap-6">
                        {{-- student avatar - FIXED: gunakan profile_photo_url --}}
                        <img src="{{ $review->reviewee->profile_photo_url }}" 
                             alt="{{ $review->reviewee->name }}"
                             class="w-24 h-24 rounded-full object-cover border-4 border-gray-200 shadow-md">
                        
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $review->reviewee->name }}</h1>
                            <div class="space-y-2 text-gray-600">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    <span>{{ $review->reviewee->student->university->name }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    <span>{{ $review->reviewee->student->major }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- action buttons --}}
                    @if($review->can_edit)
                    <div class="flex gap-3">
                        <a href="{{ route('institution.reviews.edit', $review->id) }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit Review
                        </a>
                    </div>
                    @endif
                </div>
            @else
                {{-- fallback jika data reviewee atau student tidak ada --}}
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-yellow-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <h3 class="font-semibold text-yellow-800 mb-1">Data Mahasiswa Tidak Tersedia</h3>
                            <p class="text-sm text-yellow-700">Informasi mahasiswa untuk review ini tidak dapat ditemukan.</p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- rating section --}}
            <div class="mt-6 pt-6 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-2">Rating Keseluruhan</h3>
                        <div class="flex items-center gap-3">
                            <div class="flex text-yellow-400">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-8 h-8" fill="{{ $i <= $review->rating ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                    </svg>
                                @endfor
                            </div>
                            <span class="text-3xl font-bold text-gray-900">{{ number_format($review->rating, 1) }}</span>
                        </div>
                    </div>

                    <div class="text-right">
                        <p class="text-sm text-gray-500">Direview pada</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $review->created_at->format('d M Y') }}</p>
                        <p class="text-sm text-gray-500">{{ $review->created_at->format('H:i') }} WIB</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- review content --}}
        <div class="bg-white rounded-xl shadow-sm p-8 mb-6 border border-gray-100">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Ulasan</h2>
            <div class="prose max-w-none">
                <p class="text-gray-700 whitespace-pre-wrap">{{ $review->comment }}</p>
            </div>

            {{-- project info --}}
            <div class="mt-8 pt-8 border-t border-gray-200">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Informasi Proyek</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Judul Proyek</p>
                        <p class="font-semibold text-gray-900">{{ $review->project->problem->title }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Lokasi</p>
                        <p class="font-semibold text-gray-900">
                            {{ $review->project->problem->regency->name }}, {{ $review->project->problem->province->name }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Periode Pelaksanaan</p>
                        <p class="font-semibold text-gray-900">
                            {{ $review->project->start_date->format('d M Y') }} - {{ $review->project->end_date->format('d M Y') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Durasi</p>
                        <p class="font-semibold text-gray-900">{{ $review->project->problem->duration_months }} bulan</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- visibility info --}}
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0">
                    @if($review->is_public)
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </div>
                    @else
                        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </div>
                    @endif
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-gray-900 mb-1">
                        {{ $review->is_public ? 'Review Publik' : 'Review Privat' }}
                    </h3>
                    <p class="text-sm text-gray-600">
                        @if($review->is_public)
                            Review ini ditampilkan di portofolio publik mahasiswa dan dapat dilihat oleh siapa saja.
                        @else
                            Review ini hanya dapat dilihat oleh Anda dan mahasiswa yang bersangkutan.
                        @endif
                    </p>
                </div>
            </div>
        </div>

        {{-- back button --}}
        <div class="mt-8 flex justify-between">
            <a href="{{ route('institution.reviews.index') }}" 
               class="inline-flex items-center px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Daftar Review
            </a>

            @if($review->project)
            <a href="{{ route('institution.projects.show', $review->project->id) }}" 
               class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                Lihat Proyek
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </a>
            @endif
        </div>

    </div>
</div>
@endsection