{{-- resources/views/student/applications/index.blade.php --}}
{{-- halaman my applications dengan status tracking --}}

@extends('layouts.app')

@section('title', 'Aplikasi Saya')

@push('styles')
<style>
/* custom styles untuk my applications page */
.application-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    will-change: transform;
}

.application-card:hover {
    transform: translateY(-2px) scale3d(1.01, 1.01, 1);
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

/* status badge animations */
.status-badge {
    animation: fadeIn 0.4s ease-in-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-5px); }
    to { opacity: 1; transform: translateY(0); }
}

/* stat card animations */
.stat-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 20px -5px rgba(0, 0, 0, 0.15);
}

/* timeline animations */
.timeline-item {
    position: relative;
    opacity: 0;
    animation: slideInLeft 0.5s ease-out forwards;
}

.timeline-item:nth-child(1) { animation-delay: 0.1s; }
.timeline-item:nth-child(2) { animation-delay: 0.2s; }
.timeline-item:nth-child(3) { animation-delay: 0.3s; }
.timeline-item:nth-child(4) { animation-delay: 0.4s; }

@keyframes slideInLeft {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

/* loading skeleton */
.skeleton-card {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: skeleton-loading 1.5s infinite;
}

@keyframes skeleton-loading {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}

/* smooth scrolling */
html {
    scroll-behavior: smooth;
}

/* filter transition */
.filter-dropdown {
    transition: all 0.2s ease;
}

/* prefers reduced motion */
@media (prefers-reduced-motion: reduce) {
    * {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50 py-8" x-data="applicationsPage()">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- header section -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Aplikasi Saya</h1>
            <p class="text-gray-600 mt-1">Pantau status aplikasi proyek KKN Anda</p>
        </div>

        <!-- statistics cards -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8">
            <div class="stat-card bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Total</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                    </div>
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="stat-card bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Menunggu</p>
                        <p class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
                    </div>
                    <div class="p-3 bg-yellow-100 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="stat-card bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Direview</p>
                        <p class="text-2xl font-bold text-blue-600">{{ $stats['reviewed'] }}</p>
                    </div>
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="stat-card bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Diterima</p>
                        <p class="text-2xl font-bold text-green-600">{{ $stats['accepted'] }}</p>
                    </div>
                    <div class="p-3 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="stat-card bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Ditolak</p>
                        <p class="text-2xl font-bold text-red-600">{{ $stats['rejected'] }}</p>
                    </div>
                    <div class="p-3 bg-red-100 rounded-lg">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- filter & sort section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
            <form method="GET" action="{{ route('student.applications.index') }}" class="flex flex-col md:flex-row gap-4">
                <!-- status filter -->
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Filter Status</label>
                    <select name="status" 
                            class="filter-dropdown w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu Review</option>
                        <option value="reviewed" {{ request('status') === 'reviewed' ? 'selected' : '' }}>Sedang Direview</option>
                        <option value="accepted" {{ request('status') === 'accepted' ? 'selected' : '' }}>Diterima</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>

                <!-- sort -->
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Urutkan</label>
                    <select name="sort" 
                            class="filter-dropdown w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Terlama</option>
                        <option value="status" {{ request('sort') === 'status' ? 'selected' : '' }}>Status</option>
                    </select>
                </div>

                <div class="flex items-end">
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-200 hover:shadow-lg">
                        Terapkan Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- applications list -->
        @if($applications->count() > 0)
            <div class="space-y-4">
                @foreach($applications as $application)
                <div class="application-card bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6">
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                            <!-- left section: problem info -->
                            <div class="flex-1 mb-4 lg:mb-0">
                                <div class="flex items-start">
                                    <!-- institution logo -->
                                    @if($application->problem->institution->logo_path)
                                    <img src="{{ asset('storage/' . $application->problem->institution->logo_path) }}" 
                                         alt="{{ $application->problem->institution->name }}"
                                         class="w-12 h-12 rounded-lg object-cover mr-4">
                                    @else
                                    <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-blue-500 to-green-500 flex items-center justify-center mr-4">
                                        <span class="text-white font-bold text-lg">
                                            {{ strtoupper(substr($application->problem->institution->name, 0, 1)) }}
                                        </span>
                                    </div>
                                    @endif

                                    <div class="flex-1">
                                        <a href="{{ route('student.applications.show', $application->id) }}" 
                                           class="text-lg font-bold text-gray-900 hover:text-blue-600 transition-colors">
                                            {{ $application->problem->title }}
                                        </a>
                                        <p class="text-sm text-gray-600 mt-1">{{ $application->problem->institution->name }}</p>
                                        <div class="flex flex-wrap gap-2 mt-2">
                                            <span class="inline-flex items-center px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded-md">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                </svg>
                                                {{ $application->problem->regency->name }}, {{ $application->problem->province->name }}
                                            </span>
                                            <span class="inline-flex items-center px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded-md">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                {{ $application->problem->duration_months }} bulan
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- right section: status & actions -->
                            <div class="flex flex-col items-end space-y-3">
                                <!-- status badge -->
                                <span class="status-badge inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold {{ $application->getStatusBadgeColor() }}">
                                    {{ $application->getStatusLabel() }}
                                </span>

                                <!-- applied date -->
                                <p class="text-sm text-gray-500">
                                    Diajukan {{ $application->applied_at->diffForHumans() }}
                                </p>

                                <!-- actions -->
                                <div class="flex gap-2">
                                    <a href="{{ route('student.applications.show', $application->id) }}" 
                                       class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition-all duration-200 hover:shadow-lg">
                                        Lihat Detail
                                    </a>
                                    
                                    @if(in_array($application->status, ['pending', 'reviewed']))
                                    <form action="{{ route('student.applications.withdraw', $application->id) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('Apakah Anda yakin ingin membatalkan aplikasi ini?')"
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="px-4 py-2 bg-red-100 text-red-700 text-sm rounded-lg hover:bg-red-200 transition-all duration-200">
                                            Batalkan
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- feedback section (jika ada) -->
                        @if($application->feedback && in_array($application->status, ['accepted', 'rejected']))
                        <div class="mt-4 p-4 {{ $application->status === 'accepted' ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200' }} border rounded-lg">
                            <p class="text-sm font-semibold {{ $application->status === 'accepted' ? 'text-green-900' : 'text-red-900' }} mb-1">
                                Feedback dari Instansi:
                            </p>
                            <p class="text-sm {{ $application->status === 'accepted' ? 'text-green-700' : 'text-red-700' }}">
                                {{ $application->feedback }}
                            </p>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            <!-- pagination -->
            @if($applications->hasPages())
            <div class="mt-8">
                {{ $applications->links() }}
            </div>
            @endif
        @else
            <!-- empty state -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                <svg class="mx-auto h-24 w-24 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Belum Ada Aplikasi</h3>
                <p class="text-gray-600 mb-6">Anda belum mengajukan aplikasi untuk proyek apapun</p>
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
// alpine.js component untuk applications page
function applicationsPage() {
    return {
        loading: false,
        
        init() {
            // animasi masuk untuk cards
            this.animateCards();
        },
        
        animateCards() {
            const cards = document.querySelectorAll('.application-card');
            cards.forEach((card, index) => {
                setTimeout(() => {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px)';
                    card.style.transition = 'all 0.5s cubic-bezier(0.4, 0, 0.2, 1)';
                    
                    requestAnimationFrame(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    });
                }, index * 100);
            });
        }
    };
}
</script>
@endpush