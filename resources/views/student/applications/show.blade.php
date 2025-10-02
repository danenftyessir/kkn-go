{{-- resources/views/student/applications/show.blade.php --}}
{{-- halaman detail aplikasi dengan timeline tracking --}}

@extends('layouts.app')

@section('title', 'Detail Aplikasi')

@push('styles')
<style>
/* timeline styles */
.timeline {
    position: relative;
    padding-left: 2rem;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 0.5rem;
    top: 0;
    bottom: 0;
    width: 2px;
    background: linear-gradient(to bottom, #3b82f6, #10b981);
}

.timeline-item {
    position: relative;
    margin-bottom: 2rem;
    padding-left: 1.5rem;
}

.timeline-dot {
    position: absolute;
    left: -1.75rem;
    top: 0.25rem;
    width: 1rem;
    height: 1rem;
    border-radius: 50%;
    border: 2px solid white;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.timeline-dot.active {
    width: 1.25rem;
    height: 1.25rem;
    left: -1.875rem;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% {
        box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.7);
    }
    50% {
        box-shadow: 0 0 0 8px rgba(59, 130, 246, 0);
    }
}

.timeline-content {
    opacity: 0;
    transform: translateX(-20px);
    animation: slideIn 0.5s ease-out forwards;
}

@keyframes slideIn {
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

/* smooth transitions */
.detail-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.detail-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
}

/* document preview */
.document-preview {
    transition: all 0.3s ease;
}

.document-preview:hover {
    transform: scale(1.02);
}
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- back button -->
        <div class="mb-6">
            <a href="{{ route('student.applications.index') }}" 
               class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Kembali ke Daftar Aplikasi
            </a>
        </div>

        <!-- header card -->
        <div class="detail-card bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-start flex-1">
                    @if($application->problem->institution->logo_path)
                    <img src="{{ asset('storage/' . $application->problem->institution->logo_path) }}" 
                         alt="{{ $application->problem->institution->name }}"
                         class="w-16 h-16 rounded-lg object-cover mr-4">
                    @else
                    <div class="w-16 h-16 rounded-lg bg-gradient-to-br from-blue-500 to-green-500 flex items-center justify-center mr-4">
                        <span class="text-white font-bold text-2xl">
                            {{ strtoupper(substr($application->problem->institution->name, 0, 1)) }}
                        </span>
                    </div>
                    @endif

                    <div class="flex-1">
                        <h1 class="text-2xl font-bold text-gray-900">{{ $application->problem->title }}</h1>
                        <p class="text-gray-600 mt-1">{{ $application->problem->institution->name }}</p>
                        <div class="flex flex-wrap gap-2 mt-3">
                            <span class="inline-flex items-center px-3 py-1 bg-gray-100 text-gray-700 text-sm rounded-md">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                </svg>
                                {{ $application->problem->regency->name }}, {{ $application->problem->province->name }}
                            </span>
                            <span class="inline-flex items-center px-3 py-1 bg-gray-100 text-gray-700 text-sm rounded-md">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $application->problem->duration_months }} bulan
                            </span>
                        </div>
                    </div>
                </div>

                <!-- status badge -->
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold {{ $application->getStatusBadgeColor() }}">
                    {{ $application->getStatusLabel() }}
                </span>
            </div>

            <div class="pt-4 border-t border-gray-200">
                <a href="{{ route('student.problems.show', $application->problem->id) }}" 
                   class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 transition-colors">
                    Lihat Detail Proyek
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- main content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- timeline -->
                <div class="detail-card bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">Timeline Aplikasi</h2>
                    
                    <div class="timeline">
                        <!-- applied -->
                        <div class="timeline-item">
                            <div class="timeline-dot bg-blue-500 active" style="animation-delay: 0.1s;"></div>
                            <div class="timeline-content" style="animation-delay: 0.2s;">
                                <p class="text-sm font-semibold text-gray-900">Aplikasi Diajukan</p>
                                <p class="text-sm text-gray-600 mt-1">{{ $application->applied_at->format('d M Y, H:i') }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $application->applied_at->diffForHumans() }}</p>
                            </div>
                        </div>

                        <!-- reviewed -->
                        @if($application->reviewed_at)
                        <div class="timeline-item">
                            <div class="timeline-dot bg-blue-500" style="animation-delay: 0.2s;"></div>
                            <div class="timeline-content" style="animation-delay: 0.3s;">
                                <p class="text-sm font-semibold text-gray-900">Sedang Ditinjau</p>
                                <p class="text-sm text-gray-600 mt-1">{{ $application->reviewed_at->format('d M Y, H:i') }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $application->reviewed_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        @endif

                        <!-- accepted/rejected -->
                        @if($application->accepted_at || $application->rejected_at)
                        <div class="timeline-item">
                            <div class="timeline-dot {{ $application->accepted_at ? 'bg-green-500' : 'bg-red-500' }}" style="animation-delay: 0.3s;"></div>
                            <div class="timeline-content" style="animation-delay: 0.4s;">
                                <p class="text-sm font-semibold {{ $application->accepted_at ? 'text-green-900' : 'text-red-900' }}">
                                    {{ $application->accepted_at ? 'Aplikasi Diterima' : 'Aplikasi Ditolak' }}
                                </p>
                                <p class="text-sm text-gray-600 mt-1">
                                    {{ ($application->accepted_at ?? $application->rejected_at)->format('d M Y, H:i') }}
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ ($application->accepted_at ?? $application->rejected_at)->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- motivation & cover letter -->
                <div class="detail-card bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Motivasi</h2>
                    <div class="prose prose-sm max-w-none text-gray-700">
                        {!! nl2br(e($application->motivation)) !!}
                    </div>
                </div>

                @if($application->cover_letter)
                <div class="detail-card bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Cover Letter</h2>
                    <div class="prose prose-sm max-w-none text-gray-700">
                        {!! nl2br(e($application->cover_letter)) !!}
                    </div>
                </div>
                @endif

                <!-- proposal document -->
                @if($application->proposal_path)
                <div class="detail-card bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Dokumen Proposal</h2>
                    <a href="{{ asset('storage/' . $application->proposal_path) }}" 
                       target="_blank"
                       class="document-preview flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <div class="flex items-center">
                            <div class="p-3 bg-blue-100 rounded-lg mr-4">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">proposal.pdf</p>
                                <p class="text-xs text-gray-500">Klik untuk melihat</p>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                        </svg>
                    </a>
                </div>
                @endif

                <!-- feedback from institution -->
                @if($application->feedback)
                <div class="detail-card bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Feedback dari Instansi</h2>
                    <div class="p-4 {{ $application->status === 'accepted' ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200' }} border rounded-lg">
                        <p class="text-sm {{ $application->status === 'accepted' ? 'text-green-700' : 'text-red-700' }}">
                            {!! nl2br(e($application->feedback)) !!}
                        </p>
                    </div>
                </div>
                @endif
            </div>

            <!-- sidebar -->
            <div class="lg:col-span-1">
                <div class="sticky top-8 space-y-6">
                    <!-- info card -->
                    <div class="detail-card bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Informasi Aplikasi</h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm text-gray-600">ID Aplikasi</dt>
                                <dd class="text-sm font-semibold text-gray-900 mt-1">#{{ str_pad($application->id, 6, '0', STR_PAD_LEFT) }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-gray-600">Tanggal Diajukan</dt>
                                <dd class="text-sm font-semibold text-gray-900 mt-1">{{ $application->applied_at->format('d M Y') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-gray-600">Status</dt>
                                <dd class="mt-1">
                                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold {{ $application->getStatusBadgeColor() }}">
                                        {{ $application->getStatusLabel() }}
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm text-gray-600">Durasi Proyek</dt>
                                <dd class="text-sm font-semibold text-gray-900 mt-1">{{ $application->problem->duration_months }} bulan</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-gray-600">Periode</dt>
                                <dd class="text-sm font-semibold text-gray-900 mt-1">
                                    {{ $application->problem->start_date->format('M Y') }} - {{ $application->problem->end_date->format('M Y') }}
                                </dd>
                            </div>
                        </dl>
                    </div>

                    <!-- actions -->
                    @if(in_array($application->status, ['pending', 'reviewed']))
                    <div class="detail-card bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Aksi</h3>
                        <form action="{{ route('student.applications.withdraw', $application->id) }}" 
                              method="POST" 
                              onsubmit="return confirm('Apakah Anda yakin ingin membatalkan aplikasi ini? Tindakan ini tidak dapat dibatalkan.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-all duration-200 hover:shadow-lg">
                                Batalkan Aplikasi
                            </button>
                        </form>
                        <p class="text-xs text-gray-500 mt-2 text-center">
                            Anda dapat membatalkan aplikasi selama masih dalam proses review
                        </p>
                    </div>
                    @endif

                    @if($application->status === 'accepted')
                    <div class="detail-card bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-sm p-6 text-white">
                        <div class="text-center">
                            <svg class="w-16 h-16 mx-auto mb-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <h3 class="text-lg font-bold mb-2">Selamat! 🎉</h3>
                            <p class="text-sm opacity-90">Aplikasi Anda diterima. Instansi akan segera menghubungi Anda.</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection