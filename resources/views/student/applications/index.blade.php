{{-- resources/views/student/applications/index.blade.php --}}
{{-- halaman daftar aplikasi mahasiswa --}}

@extends('layouts.app')

@section('title', 'Aplikasi Saya')

@push('styles')
<style>
/* application card animations */
.application-card {
    opacity: 0;
    transform: translateY(20px);
    animation: fadeInUp 0.5s ease-out forwards;
}

@keyframes fadeInUp {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.application-card:nth-child(1) { animation-delay: 0s; }
.application-card:nth-child(2) { animation-delay: 0.1s; }
.application-card:nth-child(3) { animation-delay: 0.2s; }
.application-card:nth-child(4) { animation-delay: 0.3s; }
.application-card:nth-child(5) { animation-delay: 0.4s; }

/* hover effects */
.application-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 24px -4px rgba(0, 0, 0, 0.15);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* status badge animations */
.status-badge {
    transition: all 0.3s ease;
}

.status-badge:hover {
    transform: scale(1.05);
}

/* stats card entrance */
.stats-card {
    opacity: 0;
    transform: scale(0.95);
    animation: scaleIn 0.4s ease-out forwards;
}

@keyframes scaleIn {
    to {
        opacity: 1;
        transform: scale(1);
    }
}

.stats-card:nth-child(1) { animation-delay: 0s; }
.stats-card:nth-child(2) { animation-delay: 0.05s; }
.stats-card:nth-child(3) { animation-delay: 0.1s; }
.stats-card:nth-child(4) { animation-delay: 0.15s; }

/* button transitions */
.btn-action {
    transition: all 0.3s ease;
}

.btn-action:hover {
    transform: translateX(4px);
}

/* search and filter animations */
.filter-container {
    opacity: 0;
    animation: fadeIn 0.4s ease-out 0.2s forwards;
}

@keyframes fadeIn {
    to {
        opacity: 1;
    }
}

/* reduced motion support */
@media (prefers-reduced-motion: reduce) {
    *,
    *::before,
    *::after {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-green-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- header --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Aplikasi Saya</h1>
            <p class="text-gray-600">Kelola dan pantau status aplikasi KKN Anda</p>
        </div>

        {{-- statistics cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="stats-card bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Total Aplikasi</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                    </div>
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="stats-card bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Pending</p>
                        <p class="text-3xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
                    </div>
                    <div class="p-3 bg-yellow-100 rounded-lg">
                        <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="stats-card bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Direview</p>
                        <p class="text-3xl font-bold text-blue-600">{{ $stats['reviewed'] }}</p>
                    </div>
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="stats-card bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Diterima</p>
                        <p class="text-3xl font-bold text-green-600">{{ $stats['accepted'] }}</p>
                    </div>
                    <div class="p-3 bg-green-100 rounded-lg">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- filter section --}}
        <div class="filter-container bg-white rounded-xl shadow-sm p-6 mb-6 border border-gray-100">
            <form action="{{ route('student.applications.index') }}" method="GET" class="flex flex-wrap gap-4">
                {{-- search --}}
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Cari masalah..."
                       class="flex-1 min-w-[250px] px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                
                {{-- status filter --}}
                <select name="status" class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="reviewed" {{ request('status') == 'reviewed' ? 'selected' : '' }}>Direview</option>
                    <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>Diterima</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>
                
                {{-- sort --}}
                <select name="sort" class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                </select>
                
                {{-- submit button --}}
                <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                    Filter
                </button>
                
                {{-- reset --}}
                @if(request()->hasAny(['search', 'status', 'sort']))
                <a href="{{ route('student.applications.index') }}" 
                   class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-medium text-center">
                    Reset
                </a>
                @endif
            </form>
        </div>

        {{-- applications list --}}
        <div class="space-y-4">
            @forelse($applications as $application)
            <div class="application-card bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-all duration-300">
                <div class="flex items-start gap-4">
                    {{-- PERBAIKAN BUG: gunakan accessor image_url yang sudah support Supabase --}}
                    @if($application->problem->images->where('is_cover', true)->first())
                    <img src="{{ $application->problem->images->where('is_cover', true)->first()->image_url }}" 
                         alt="{{ $application->problem->title }}"
                         class="w-24 h-24 object-cover rounded-lg flex-shrink-0">
                    @else
                    <div class="w-24 h-24 bg-gradient-to-br from-blue-500 to-green-500 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    @endif
                    
                    {{-- application info --}}
                    <div class="flex-1">
                        <div class="flex items-start justify-between mb-2">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $application->problem->title }}</h3>
                                <p class="text-gray-600 mb-1">{{ $application->problem->institution->name }}</p>
                                <p class="text-sm text-gray-500">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    {{ $application->problem->location_regency }}
                                </p>
                            </div>
                            
                            {{-- status badge --}}
                            <span class="status-badge inline-flex px-4 py-2 text-sm font-semibold rounded-full
                                {{ $application->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $application->status === 'reviewed' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $application->status === 'accepted' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $application->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                                @if($application->status === 'pending')
                                    Pending
                                @elseif($application->status === 'reviewed')
                                    Direview
                                @elseif($application->status === 'accepted')
                                    Diterima
                                @elseif($application->status === 'rejected')
                                    Ditolak
                                @endif
                            </span>
                        </div>

                        {{-- tanggal apply --}}
                        <div class="flex items-center text-sm text-gray-500 mb-3">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Diajukan: {{ \Carbon\Carbon::parse($application->applied_at)->format('d M Y') }}
                        </div>

                        {{-- notes dari institusi (jika ada) --}}
                        @if($application->institution_notes && in_array($application->status, ['accepted', 'rejected']))
                        <div class="bg-gray-50 rounded-lg p-3 mb-3 border border-gray-200">
                            <p class="text-sm font-semibold text-gray-700 mb-1">Catatan Dari Instansi:</p>
                            <p class="text-sm text-gray-600">{{ $application->institution_notes }}</p>
                        </div>
                        @endif

                        {{-- action buttons --}}
                        <div class="flex items-center gap-3 mt-4">
                            <a href="{{ route('student.applications.show', $application->id) }}" 
                               class="btn-action inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Lihat Detail
                            </a>

                            @if($application->status === 'pending')
                            <form action="{{ route('student.applications.withdraw', $application->id) }}" 
                                  method="POST" 
                                  onsubmit="return confirm('Anda yakin ingin membatalkan aplikasi ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors font-medium">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Batalkan Aplikasi
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-xl shadow-sm p-12 text-center border border-gray-100">
                <svg class="w-24 h-24 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">Belum Ada Aplikasi</h3>
                <p class="text-gray-500 mb-6">Mulai jelajahi masalah KKN dan ajukan aplikasi Anda</p>
                <a href="{{ route('student.browse-problems.index') }}" 
                   class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Jelajahi Masalah
                </a>
            </div>
            @endforelse
        </div>

        {{-- pagination --}}
        @if($applications->hasPages())
        <div class="mt-8">
            {{ $applications->links() }}
        </div>
        @endif

    </div>
</div>
@endsection