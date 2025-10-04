@extends('layouts.app')

@section('title', 'Detail Aplikasi')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-green-50 py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- breadcrumb --}}
        <nav class="mb-6">
            <ol class="flex items-center gap-2 text-sm">
                <li><a href="{{ route('institution.dashboard') }}" class="text-gray-500 hover:text-gray-700">Dashboard</a></li>
                <li class="text-gray-400">/</li>
                <li><a href="{{ route('institution.applications.index') }}" class="text-gray-500 hover:text-gray-700">Aplikasi</a></li>
                <li class="text-gray-400">/</li>
                <li class="text-gray-900 font-medium">Detail</li>
            </ol>
        </nav>

        {{-- header section --}}
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6 border border-gray-100">
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-start gap-4">
                    <img src="{{ $application->student->profile_photo_path ? asset('storage/' . $application->student->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode($application->student->user->name) }}" 
                         alt="{{ $application->student->user->name }}"
                         class="w-20 h-20 rounded-full object-cover border-2 border-gray-200">
                    
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 mb-1">{{ $application->student->user->name }}</h1>
                        <p class="text-gray-600">{{ $application->student->university->name }}</p>
                        <p class="text-sm text-gray-500">{{ $application->student->major }}</p>
                    </div>
                </div>

                <span class="inline-flex px-4 py-2 text-sm font-semibold rounded-full
                    {{ $application->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                    {{ $application->status === 'under_review' ? 'bg-blue-100 text-blue-800' : '' }}
                    {{ $application->status === 'accepted' ? 'bg-green-100 text-green-800' : '' }}
                    {{ $application->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                    {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-4 border-t border-gray-200">
                <div>
                    <p class="text-sm text-gray-600">Email</p>
                    <p class="font-medium text-gray-900">{{ $application->student->user->email }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">NIM</p>
                    <p class="font-medium text-gray-900">{{ $application->student->nim }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Semester</p>
                    <p class="font-medium text-gray-900">{{ $application->student->semester }}</p>
                </div>
            </div>
        </div>

        {{-- problem info --}}
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6 border border-gray-100">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Masalah yang Dilamar</h2>
            <div class="bg-blue-50 rounded-lg p-4 border border-blue-100">
                <h3 class="font-bold text-blue-900 mb-2">{{ $application->problem->title }}</h3>
                <p class="text-blue-700 text-sm">{{ Str::limit($application->problem->description, 150) }}</p>
                <a href="{{ route('institution.problems.show', $application->problem->id) }}" 
                   class="inline-flex items-center gap-1 mt-3 text-sm text-blue-600 hover:text-blue-700">
                    Lihat Detail Masalah
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>

        {{-- motivasi --}}
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6 border border-gray-100">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Motivasi</h2>
            <p class="text-gray-700 whitespace-pre-line">{{ $application->motivation }}</p>
        </div>

        {{-- cover letter --}}
        @if($application->cover_letter)
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6 border border-gray-100">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Cover Letter</h2>
            <p class="text-gray-700 whitespace-pre-line">{{ $application->cover_letter }}</p>
        </div>
        @endif

        {{-- proposal --}}
        @if($application->proposal_path)
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6 border border-gray-100">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Proposal</h2>
            <a href="{{ asset('storage/' . $application->proposal_path) }}" 
               target="_blank"
               class="flex items-center gap-3 p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-medium text-gray-900">Lihat Proposal</p>
                    <p class="text-sm text-gray-500">Klik untuk membuka dokumen</p>
                </div>
            </a>
        </div>
        @endif

        {{-- feedback dari instansi (jika ada) --}}
        @if($application->feedback)
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6 border border-gray-100">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Feedback</h2>
            <div class="bg-blue-50 rounded-lg p-4 border border-blue-100">
                <p class="text-blue-700">{{ $application->feedback }}</p>
            </div>
        </div>
        @endif

        {{-- institution notes (internal) --}}
        @if($application->institution_notes)
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6 border border-gray-100">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Catatan Internal</h2>
            <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-100">
                <p class="text-yellow-700">{{ $application->institution_notes }}</p>
            </div>
        </div>
        @endif

        {{-- timeline --}}
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6 border border-gray-100">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Timeline</h2>
            <div class="space-y-3">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Aplikasi Diajukan</p>
                        <p class="text-sm text-gray-500">{{ $application->applied_at ? $application->applied_at->format('d M Y, H:i') : $application->created_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>

                @if($application->reviewed_at)
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Direview</p>
                        <p class="text-sm text-gray-500">{{ $application->reviewed_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
                @endif

                @if($application->accepted_at)
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Diterima</p>
                        <p class="text-sm text-gray-500">{{ $application->accepted_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
                @endif

                @if($application->rejected_at)
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Ditolak</p>
                        <p class="text-sm text-gray-500">{{ $application->rejected_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- action buttons --}}
        <div class="flex items-center justify-between">
            <a href="{{ route('institution.applications.index') }}" 
               class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all duration-200">
                Kembali
            </a>

            @if(in_array($application->status, ['pending', 'under_review']))
            <div class="flex items-center gap-3">
                <form action="{{ route('institution.applications.reject', $application->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menolak aplikasi ini?')">
                    @csrf
                    <button type="submit" class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-all duration-200">
                        Tolak
                    </button>
                </form>

                <a href="{{ route('institution.applications.review', $application->id) }}" 
                   class="px-6 py-3 bg-gradient-to-r from-blue-600 to-green-600 text-white rounded-lg hover:from-blue-700 hover:to-green-700 transition-all duration-200">
                    Review & Terima
                </a>
            </div>
            @endif
        </div>

    </div>
</div>
@endsection