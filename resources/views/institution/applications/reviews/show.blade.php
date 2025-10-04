@extends('layouts.app')

@section('title', 'Detail Aplikasi')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- back button --}}
        <a href="{{ route('institution.applications.index') }}" class="text-blue-600 hover:text-blue-700 flex items-center gap-2 mb-6">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Daftar Aplikasi
        </a>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- main content --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- header --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-start gap-4 mb-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-full flex items-center justify-center font-bold text-2xl">
                            {{ substr($application->student->user->name, 0, 1) }}
                        </div>
                        <div class="flex-1">
                            <h1 class="text-2xl font-bold text-gray-900">{{ $application->student->user->name }}</h1>
                            <p class="text-gray-600">{{ $application->student->university->name }}</p>
                            <p class="text-sm text-gray-500">{{ $application->student->major }} - Semester {{ $application->student->semester }}</p>
                        </div>
                        
                        {{-- status badge --}}
                        @if($application->status == 'pending')
                        <span class="px-4 py-2 bg-yellow-100 text-yellow-700 font-semibold rounded-full">Pending</span>
                        @elseif($application->status == 'under_review')
                        <span class="px-4 py-2 bg-blue-100 text-blue-700 font-semibold rounded-full">Under Review</span>
                        @elseif($application->status == 'accepted')
                        <span class="px-4 py-2 bg-green-100 text-green-700 font-semibold rounded-full">Diterima</span>
                        @else
                        <span class="px-4 py-2 bg-red-100 text-red-700 font-semibold rounded-full">Ditolak</span>
                        @endif
                    </div>

                    <div class="bg-blue-50 rounded-lg p-4">
                        <p class="text-sm font-semibold text-blue-900 mb-1">Melamar untuk:</p>
                        <p class="text-blue-900 font-bold">{{ $application->problem->title }}</p>
                        <p class="text-xs text-blue-700 mt-1">{{ $application->problem->regency->name }}, {{ $application->problem->province->name }}</p>
                    </div>
                </div>

                {{-- motivasi --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Motivasi</h2>
                    <p class="text-gray-700 whitespace-pre-line">{{ $application->motivation }}</p>
                </div>

                {{-- pengalaman relevan --}}
                @if($application->relevant_experience)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Pengalaman Relevan</h2>
                    <p class="text-gray-700 whitespace-pre-line">{{ $application->relevant_experience }}</p>
                </div>
                @endif

                {{-- proposal --}}
                @if($application->proposal_file)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Proposal</h2>
                    <a href="{{ Storage::url($application->proposal_file) }}" 
                       target="_blank"
                       class="flex items-center gap-3 p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">Lihat Proposal</p>
                            <p class="text-sm text-gray-600">Klik untuk membuka PDF</p>
                        </div>
                    </a>
                </div>
                @endif

                {{-- feedback jika sudah direview --}}
                @if($application->feedback)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Feedback Anda</h2>
                    <p class="text-gray-700 whitespace-pre-line">{{ $application->feedback }}</p>
                    @if($application->rejection_reason)
                    <div class="mt-4 p-4 bg-red-50 rounded-lg">
                        <p class="text-sm font-semibold text-red-900 mb-1">Alasan Penolakan:</p>
                        <p class="text-red-900">{{ $application->rejection_reason }}</p>
                    </div>
                    @endif
                </div>
                @endif

                {{-- action buttons --}}
                @if(in_array($application->status, ['pending', 'under_review']))
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Aksi</h2>
                    <div class="flex gap-3">
                        <form method="POST" action="{{ route('institution.applications.accept', $application->id) }}" class="flex-1">
                            @csrf
                            <button type="submit" 
                                    onclick="return confirm('Yakin ingin menerima aplikasi ini? Proyek akan otomatis dibuat.')"
                                    class="w-full px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold">
                                Terima Aplikasi
                            </button>
                        </form>

                        <a href="{{ route('institution.applications.review', $application->id) }}" 
                           class="flex-1 text-center px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-semibold">
                            Tolak dengan Feedback
                        </a>
                    </div>
                </div>
                @endif

            </div>

            {{-- sidebar --}}
            <div class="space-y-6">
                
                {{-- timeline --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Timeline</h3>
                    <div class="space-y-3">
                        <div class="flex items-start gap-3">
                            <div class="w-2 h-2 bg-blue-600 rounded-full mt-2"></div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">Aplikasi Diajukan</p>
                                <p class="text-xs text-gray-600">{{ $application->created_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                        
                        @if($application->reviewed_at)
                        <div class="flex items-start gap-3">
                            <div class="w-2 h-2 bg-green-600 rounded-full mt-2"></div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">Direview</p>
                                <p class="text-xs text-gray-600">{{ $application->reviewed_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- info mahasiswa --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Informasi Mahasiswa</h3>
                    <div class="space-y-3 text-sm">
                        <div>
                            <p class="text-gray-600">Email</p>
                            <p class="font-semibold text-gray-900">{{ $application->student->user->email }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">WhatsApp</p>
                            <p class="font-semibold text-gray-900">{{ $application->student->whatsapp }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">NIM</p>
                            <p class="font-semibold text-gray-900">{{ $application->student->nim }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">IPK</p>
                            <p class="font-semibold text-gray-900">{{ $application->student->gpa ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                {{-- skill mahasiswa --}}
                @if($application->student->skills)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Skill</h3>
                    <div class="flex flex-wrap gap-2">
                        @php
                            $skills = is_array($application->student->skills) ? $application->student->skills : json_decode($application->student->skills, true) ?? [];
                        @endphp
                        @foreach($skills as $skill)
                        <span class="px-3 py-1 bg-blue-100 text-blue-700 text-sm font-semibold rounded-full">{{ $skill }}</span>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- info problem --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Info Masalah</h3>
                    <div class="space-y-2 text-sm">
                        <p><span class="font-semibold">Durasi:</span> {{ $application->problem->duration_months }} bulan</p>
                        <p><span class="font-semibold">Mahasiswa Dibutuhkan:</span> {{ $application->problem->required_students }}</p>
                        <p><span class="font-semibold">Sudah Diterima:</span> {{ $application->problem->accepted_students }}</p>
                        <p><span class="font-semibold">Sisa Slot:</span> {{ $application->problem->required_students - $application->problem->accepted_students }}</p>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection