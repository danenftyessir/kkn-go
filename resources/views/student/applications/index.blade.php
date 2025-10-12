{{-- resources/views/student/applications/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Aplikasi Saya')

@push('styles')
<style>
    .hero-application-background {
        position: relative;
        background-image: url('/application-student.jpg');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
    }
    
    .hero-application-background::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        /* Gradient transparan seperti browse problems */
        background: linear-gradient(
            135deg, 
            rgba(37, 99, 235, 0.50) 0%,
            rgba(59, 130, 246, 0.45) 35%,
            rgba(16, 185, 129, 0.45) 65%,
            rgba(5, 150, 105, 0.50) 100%
        );
        backdrop-filter: blur(1px);
    }
    
    .stats-card-application {
        background: rgba(255, 255, 255, 0.20);
        backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .stats-card-application:hover {
        background: rgba(255, 255, 255, 0.30);
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3);
    }
    
    .text-shadow-strong {
        text-shadow: 
            0 2px 4px rgba(0, 0, 0, 0.4),
            0 4px 8px rgba(0, 0, 0, 0.3),
            0 1px 2px rgba(0, 0, 0, 0.5);
    }
    
    .application-fade-in {
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
    <div class="hero-application-background text-white py-16 md:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="application-fade-in">
                <h1 class="text-4xl md:text-5xl font-bold mb-4 text-shadow-strong">
                    Aplikasi Saya
                </h1>
                <p class="text-xl md:text-2xl text-white text-shadow-strong max-w-3xl">
                    Kelola dan pantau status aplikasi proyek KKN Anda
                </p>
            </div>
            
            {{-- stats cards --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 md:gap-6 mt-10 application-fade-in" style="animation-delay: 0.2s;">
                <div class="stats-card-application rounded-xl p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-4xl md:text-5xl font-bold text-white text-shadow-strong">
                                {{ $stats['total'] ?? 0 }}
                            </div>
                            <div class="text-sm md:text-base text-white font-medium text-shadow-strong mt-2">
                                Total Aplikasi
                            </div>
                        </div>
                        <div class="w-14 h-14 md:w-16 md:h-16 bg-white/25 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-7 h-7 md:w-8 md:h-8 text-white drop-shadow-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    </div>
                </div>
                
                <div class="stats-card-application rounded-xl p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-4xl md:text-5xl font-bold text-white text-shadow-strong">
                                {{ $stats['pending'] ?? 0 }}
                            </div>
                            <div class="text-sm md:text-base text-white font-medium text-shadow-strong mt-2">
                                Pending
                            </div>
                        </div>
                        <div class="w-14 h-14 md:w-16 md:h-16 bg-white/25 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-7 h-7 md:w-8 md:h-8 text-white drop-shadow-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
                
                <div class="stats-card-application rounded-xl p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-4xl md:text-5xl font-bold text-white text-shadow-strong">
                                {{ $stats['accepted'] ?? 0 }}
                            </div>
                            <div class="text-sm md:text-base text-white font-medium text-shadow-strong mt-2">
                                Diterima
                            </div>
                        </div>
                        <div class="w-14 h-14 md:w-16 md:h-16 bg-white/25 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-7 h-7 md:w-8 md:h-8 text-white drop-shadow-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                            </svg>
                        </div>
                    </div>
                </div>
                
                <div class="stats-card-application rounded-xl p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-4xl md:text-5xl font-bold text-white text-shadow-strong">
                                {{ $stats['rejected'] ?? 0 }}
                            </div>
                            <div class="text-sm md:text-base text-white font-medium text-shadow-strong mt-2">
                                Ditolak
                            </div>
                        </div>
                        <div class="w-14 h-14 md:w-16 md:h-16 bg-white/25 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-7 h-7 md:w-8 md:h-8 text-white drop-shadow-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        {{-- filter bar --}}
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6 border border-gray-100">
            <form method="GET" action="{{ route('student.applications.index') }}" class="flex flex-col md:flex-row gap-4">
                
                {{-- search --}}
                <div class="flex-1">
                    <div class="relative">
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="Cari berdasarkan judul proyek atau instansi..."
                               class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        <svg class="w-5 h-5 text-gray-400 absolute left-4 top-1/2 transform -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>
                
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
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-all duration-300">
                <div class="flex items-start gap-4">
                    {{-- problem image or placeholder --}}
                    @if($application->problem->images->where('is_cover', true)->first())
                    <img src="{{ asset('storage/' . $application->problem->images->where('is_cover', true)->first()->image_path) }}" 
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
                            <span class="inline-flex px-4 py-2 text-sm font-semibold rounded-full
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
                                @else
                                    Ditolak
                                @endif
                            </span>
                        </div>
                        
                        {{-- timeline info --}}
                        <div class="flex items-center gap-4 text-sm text-gray-500 mb-3">
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Diajukan: {{ $application->applied_at ? \Carbon\Carbon::parse($application->applied_at)->format('d M Y') : '-' }}
                            </span>
                            
                            @if($application->reviewed_at)
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Direview: {{ \Carbon\Carbon::parse($application->reviewed_at)->format('d M Y') }}
                            </span>
                            @endif
                        </div>
                        
                        {{-- feedback --}}
                        @if($application->feedback)
                        <div class="bg-gray-50 border-l-4 {{ $application->status === 'accepted' ? 'border-green-500' : 'border-red-500' }} p-3 mb-3 rounded">
                            <p class="text-sm font-semibold text-gray-700 mb-1">Feedback dari Instansi:</p>
                            <p class="text-sm text-gray-600">{{ $application->feedback }}</p>
                        </div>
                        @endif
                        
                        {{-- actions --}}
                        <div class="flex items-center gap-3">
                            <a href="{{ route('student.applications.show', $application->id) }}" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Lihat Detail
                            </a>
                            
                            @if($application->status === 'pending')
                            <form method="POST" action="{{ route('student.applications.withdraw', $application->id) }}" 
                                  onsubmit="return confirm('Apakah Anda yakin ingin membatalkan aplikasi ini?')" 
                                  class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="inline-flex items-center px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors text-sm font-medium">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
            <div class="bg-white rounded-xl shadow-sm p-12 text-center">
                <svg class="w-24 h-24 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Belum Ada Aplikasi</h3>
                <p class="text-gray-600 mb-4">Anda belum mengajukan aplikasi untuk proyek manapun.</p>
                <a href="{{ route('student.browse-problems.index') }}" 
                   class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Jelajahi Proyek
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