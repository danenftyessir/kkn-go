@extends('layouts.app')

@section('title', $project->title)

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- back button --}}
        <a href="{{ route('institution.projects.index') }}" class="text-blue-600 hover:text-blue-700 flex items-center gap-2 mb-6">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Daftar Proyek
        </a>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- main content --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- header --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $project->title }}</h1>
                            <div class="flex items-center gap-3">
                                @if($project->status == 'planning')
                                <span class="px-3 py-1 bg-gray-100 text-gray-700 text-sm font-semibold rounded-full">Planning</span>
                                @elseif($project->status == 'active')
                                <span class="px-3 py-1 bg-green-100 text-green-700 text-sm font-semibold rounded-full">Aktif</span>
                                @elseif($project->status == 'review')
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-700 text-sm font-semibold rounded-full">Review</span>
                                @elseif($project->status == 'completed')
                                <span class="px-3 py-1 bg-blue-100 text-blue-700 text-sm font-semibold rounded-full">Selesai</span>
                                @else
                                <span class="px-3 py-1 bg-red-100 text-red-700 text-sm font-semibold rounded-full">Dibatalkan</span>
                                @endif
                            </div>
                        </div>

                        <a href="{{ route('institution.projects.manage', $project->id) }}" 
                           class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                            Kelola Proyek
                        </a>
                    </div>

                    {{-- progress bar --}}
                    <div class="mb-4">
                        <div class="flex justify-between text-sm mb-2">
                            <span class="font-semibold text-gray-700">Progress Keseluruhan</span>
                            <span class="text-gray-600">{{ round($progressPercentage) }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-3 rounded-full transition-all duration-300" 
                                 style="width: {{ $progressPercentage }}%"></div>
                        </div>
                    </div>
                </div>

                {{-- info mahasiswa --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Mahasiswa</h2>
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-full flex items-center justify-center font-bold text-2xl">
                            {{ substr($project->student->user->name, 0, 1) }}
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">{{ $project->student->user->name }}</h3>
                            <p class="text-gray-600">{{ $project->student->university->name }}</p>
                            <p class="text-sm text-gray-500">{{ $project->student->major }} - Semester {{ $project->student->semester }}</p>
                        </div>
                    </div>
                </div>

                {{-- deskripsi --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Deskripsi Proyek</h2>
                    <p class="text-gray-700 whitespace-pre-line">{{ $project->description }}</p>
                </div>

                {{-- milestones --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Milestones</h2>
                    
                    @forelse($project->milestones as $milestone)
                    <div class="mb-4 pb-4 {{ !$loop->last ? 'border-b border-gray-200' : '' }}">
                        <div class="flex items-start gap-3">
                            {{-- status icon --}}
                            <div class="mt-1">
                                @if($milestone->status == 'completed')
                                <div class="w-6 h-6 bg-green-600 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                @elseif($milestone->status == 'in_progress')
                                <div class="w-6 h-6 bg-yellow-600 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3"/>
                                    </svg>
                                </div>
                                @else
                                <div class="w-6 h-6 bg-gray-300 rounded-full"></div>
                                @endif
                            </div>

                            <div class="flex-1">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h3 class="font-bold text-gray-900">{{ $milestone->title }}</h3>
                                        @if($milestone->description)
                                        <p class="text-sm text-gray-600 mt-1">{{ $milestone->description }}</p>
                                        @endif
                                    </div>
                                    
                                    @if($milestone->status == 'completed')
                                    <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded">Selesai</span>
                                    @elseif($milestone->status == 'in_progress')
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-700 text-xs font-semibold rounded">Berjalan</span>
                                    @else
                                    <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs font-semibold rounded">Pending</span>
                                    @endif
                                </div>

                                <div class="flex items-center gap-2 mt-2 text-xs text-gray-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span>Target: {{ $milestone->due_date->format('d M Y') }}</span>
                                    @if($milestone->completed_at)
                                    <span class="ml-2">• Selesai: {{ $milestone->completed_at->format('d M Y') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <p class="text-gray-600 text-center py-4">Belum ada milestone</p>
                    @endforelse
                </div>

                {{-- reports --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Laporan Kemajuan</h2>
                    
                    @forelse($project->reports as $report)
                    <div class="mb-4 pb-4 {{ !$loop->last ? 'border-b border-gray-200' : '' }}">
                        <div class="flex items-start justify-between mb-2">
                            <div>
                                <h3 class="font-bold text-gray-900">{{ $report->title }}</h3>
                                <p class="text-sm text-gray-600">{{ $report->created_at->format('d M Y, H:i') }}</p>
                            </div>
                            
                            @if($report->status == 'approved')
                            <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded">Disetujui</span>
                            @elseif($report->status == 'revision_required')
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-700 text-xs font-semibold rounded">Perlu Revisi</span>
                            @else
                            <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded">Menunggu Review</span>
                            @endif
                        </div>

                        <p class="text-gray-700 text-sm mb-2">{{ Str::limit($report->content, 200) }}</p>

                        @if($report->institution_feedback)
                        <div class="bg-blue-50 rounded-lg p-3 mt-2">
                            <p class="text-xs font-semibold text-blue-900 mb-1">Feedback Anda:</p>
                            <p class="text-sm text-blue-900">{{ $report->institution_feedback }}</p>
                        </div>
                        @endif

                        @if($report->document_path)
                        <a href="{{ Storage::url($report->document_path) }}" 
                           target="_blank"
                           class="inline-flex items-center gap-2 mt-2 text-sm text-blue-600 hover:text-blue-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Download Dokumen
                        </a>
                        @endif
                    </div>
                    @empty
                    <p class="text-gray-600 text-center py-4">Belum ada laporan</p>
                    @endforelse
                </div>

                {{-- review section (jika sudah completed) --}}
                @if($project->status == 'completed')
                    @if($project->rating)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Review Anda</h2>
                        <div class="flex items-center gap-2 mb-3">
                            <div class="flex">
                                @for($i = 1; $i <= 5; $i++)
                                <svg class="w-6 h-6 {{ $i <= $project->rating ? 'text-yellow-500' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                @endfor
                            </div>
                            <span class="text-gray-600 font-semibold">{{ $project->rating }}/5</span>
                        </div>
                        <p class="text-gray-700">{{ $project->institution_review }}</p>
                    </div>
                    @else
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Berikan Review</h2>
                        <p class="text-gray-600 mb-4">Proyek ini telah selesai. Berikan review untuk mahasiswa.</p>
                        <a href="{{ route('institution.reviews.create', $project->id) }}" 
                           class="inline-block px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                            Tulis Review
                        </a>
                    </div>
                    @endif
                @endif

            </div>

            {{-- sidebar --}}
            <div class="space-y-6">
                
                {{-- quick stats --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Ringkasan</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Progress</span>
                            <span class="font-bold text-gray-900">{{ round($progressPercentage) }}%</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Milestones</span>
                            <span class="font-bold text-gray-900">
                                {{ $project->milestones->where('status', 'completed')->count() }}/{{ $project->milestones->count() }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Laporan</span>
                            <span class="font-bold text-gray-900">{{ $project->reports->count() }}</span>
                        </div>
                    </div>
                </div>

                {{-- timeline --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Timeline</h3>
                    <div class="space-y-2 text-sm">
                        <p><span class="font-semibold">Mulai:</span><br>{{ $project->start_date->format('d M Y') }}</p>
                        <p><span class="font-semibold">Selesai:</span><br>{{ $project->end_date->format('d M Y') }}</p>
                        <p><span class="font-semibold">Durasi:</span> {{ $project->start_date->diffInDays($project->end_date) }} hari</p>
                    </div>
                </div>

                {{-- kontak mahasiswa --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Kontak</h3>
                    <div class="space-y-3 text-sm">
                        <div>
                            <p class="text-gray-600 mb-1">Email</p>
                            <a href="mailto:{{ $project->student->user->email }}" class="text-blue-600 hover:text-blue-700 break-all">
                                {{ $project->student->user->email }}
                            </a>
                        </div>
                        <div>
                            <p class="text-gray-600 mb-1">WhatsApp</p>
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $project->student->whatsapp) }}" 
                               target="_blank"
                               class="text-green-600 hover:text-green-700">
                                {{ $project->student->whatsapp }}
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection