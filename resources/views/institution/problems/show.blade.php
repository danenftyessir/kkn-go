{{-- resources/views/institution/problems/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Detail Masalah')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-green-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- breadcrumb --}}
        <nav class="mb-6">
            <ol class="flex items-center gap-2 text-sm">
                <li><a href="{{ route('institution.dashboard') }}" class="text-gray-500 hover:text-gray-700">Dashboard</a></li>
                <li class="text-gray-400">/</li>
                <li><a href="{{ route('institution.problems.index') }}" class="text-gray-500 hover:text-gray-700">Problems</a></li>
                <li class="text-gray-400">/</li>
                <li class="text-gray-900 font-medium">Detail</li>
            </ol>
        </nav>

        {{-- header section --}}
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6 border border-gray-100">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $problem->title }}</h1>
                    <div class="flex items-center gap-3">
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full
                            {{ $problem->status === 'draft' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $problem->status === 'open' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $problem->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $problem->status === 'completed' ? 'bg-purple-100 text-purple-800' : '' }}
                            {{ $problem->status === 'closed' ? 'bg-gray-100 text-gray-800' : '' }}">
                            {{ ucfirst($problem->status) }}
                        </span>
                        @if($problem->is_featured)
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            Featured
                        </span>
                        @endif
                        @if($problem->is_urgent)
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800">
                            Urgent
                        </span>
                        @endif
                    </div>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('institution.problems.edit', $problem->id) }}" 
                       class="inline-flex items-center gap-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-semibold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit
                    </a>
                    <form action="{{ route('institution.problems.destroy', $problem->id) }}" method="POST" class="inline"
                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus masalah ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="inline-flex items-center gap-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-semibold">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Hapus
                        </button>
                    </form>
                </div>
            </div>

            {{-- statistik singkat --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 pt-4 border-t border-gray-200">
                <div>
                    <p class="text-sm text-gray-600">Views</p>
                    <p class="text-xl font-bold text-gray-900">{{ number_format($problem->views_count) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Aplikasi</p>
                    <p class="text-xl font-bold text-gray-900">{{ number_format($problem->applications_count) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Mahasiswa Diterima</p>
                    <p class="text-xl font-bold text-gray-900">{{ number_format($problem->accepted_students) }} / {{ number_format($problem->required_students) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Deadline</p>
                    <p class="text-xl font-bold text-gray-900">{{ $problem->application_deadline->format('d M Y') }}</p>
                </div>
            </div>
        </div>

        {{-- konten utama --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- kolom kiri (2/3) --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- galeri foto --}}
                @if($problem->images->isNotEmpty())
                <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
                    <div class="grid grid-cols-2 gap-2 p-4">
                        @foreach($problem->images as $image)
                        <div class="aspect-video rounded-lg overflow-hidden">
                            <img src="{{ asset('storage/' . $image->image_path) }}" 
                                 alt="{{ $image->caption }}"
                                 class="w-full h-full object-cover">
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- deskripsi --}}
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Deskripsi</h3>
                    <div class="prose max-w-none text-gray-700">
                        {{ $problem->description }}
                    </div>
                </div>

                {{-- latar belakang --}}
                @if($problem->background)
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Latar Belakang</h3>
                    <div class="prose max-w-none text-gray-700">
                        {{ $problem->background }}
                    </div>
                </div>
                @endif

                {{-- tujuan --}}
                @if($problem->objectives)
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Tujuan</h3>
                    <div class="prose max-w-none text-gray-700">
                        {{ $problem->objectives }}
                    </div>
                </div>
                @endif

                {{-- ruang lingkup --}}
                @if($problem->scope)
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Ruang Lingkup</h3>
                    <div class="prose max-w-none text-gray-700">
                        {{ $problem->scope }}
                    </div>
                </div>
                @endif

                {{-- expected outcomes --}}
                @if($problem->expected_outcomes)
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Expected Outcomes</h3>
                    <div class="prose max-w-none text-gray-700">
                        {{ $problem->expected_outcomes }}
                    </div>
                </div>
                @endif

            </div>

            {{-- kolom kanan (1/3) --}}
            <div class="space-y-6">
                
                {{-- info lokasi --}}
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Lokasi</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-600">Provinsi</p>
                            <p class="font-medium text-gray-900">{{ $problem->province->name ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Kabupaten/Kota</p>
                            <p class="font-medium text-gray-900">{{ $problem->regency->name ?? '-' }}</p>
                        </div>
                        @if($problem->village)
                        <div>
                            <p class="text-sm text-gray-600">Desa</p>
                            <p class="font-medium text-gray-900">{{ $problem->village }}</p>
                        </div>
                        @endif
                        @if($problem->detailed_location)
                        <div>
                            <p class="text-sm text-gray-600">Detail Lokasi</p>
                            <p class="font-medium text-gray-900">{{ $problem->detailed_location }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- info timeline --}}
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Timeline</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-600">Deadline Aplikasi</p>
                            <p class="font-medium text-gray-900">{{ $problem->application_deadline->format('d M Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Mulai</p>
                            <p class="font-medium text-gray-900">{{ $problem->start_date->format('d M Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Selesai</p>
                            <p class="font-medium text-gray-900">{{ $problem->end_date->format('d M Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Durasi</p>
                            <p class="font-medium text-gray-900">{{ $problem->duration_months }} bulan</p>
                        </div>
                    </div>
                </div>

                {{-- kategori SDG --}}
                @php
                    // parsing sdg_categories dengan aman
                    $sdgCategories = [];
                    if ($problem->sdg_categories) {
                        if (is_array($problem->sdg_categories)) {
                            $sdgCategories = $problem->sdg_categories;
                        } elseif (is_string($problem->sdg_categories)) {
                            $sdgCategories = json_decode($problem->sdg_categories, true) ?? [];
                        }
                    }
                @endphp
                @if(!empty($sdgCategories))
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Kategori SDG</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($sdgCategories as $sdg)
                            <span class="inline-flex px-3 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                                {{ $sdg }}
                            </span>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- skills required --}}
                @php
                    // parsing required_skills dengan aman
                    $requiredSkills = [];
                    if ($problem->required_skills) {
                        if (is_array($problem->required_skills)) {
                            $requiredSkills = $problem->required_skills;
                        } elseif (is_string($problem->required_skills)) {
                            $requiredSkills = json_decode($problem->required_skills, true) ?? [];
                        }
                    }
                @endphp
                @if(!empty($requiredSkills))
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Skills Dibutuhkan</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($requiredSkills as $skill)
                            <span class="inline-flex px-3 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                {{ $skill }}
                            </span>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- required majors --}}
                @php
                    // parsing required_majors dengan aman
                    $requiredMajors = [];
                    if ($problem->required_majors) {
                        if (is_array($problem->required_majors)) {
                            $requiredMajors = $problem->required_majors;
                        } elseif (is_string($problem->required_majors)) {
                            $requiredMajors = json_decode($problem->required_majors, true) ?? [];
                        }
                    }
                @endphp
                @if(!empty($requiredMajors))
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Jurusan Dibutuhkan</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($requiredMajors as $major)
                            <span class="inline-flex px-3 py-1 text-xs font-medium bg-purple-100 text-purple-800 rounded-full">
                                {{ $major }}
                            </span>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- deliverables --}}
                @php
                    // parsing deliverables dengan aman
                    $deliverables = [];
                    if ($problem->deliverables) {
                        if (is_array($problem->deliverables)) {
                            $deliverables = $problem->deliverables;
                        } elseif (is_string($problem->deliverables)) {
                            $deliverables = json_decode($problem->deliverables, true) ?? [];
                        }
                    }
                @endphp
                @if(!empty($deliverables))
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Deliverables</h3>
                    <ul class="space-y-2">
                        @foreach($deliverables as $deliverable)
                            <li class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-green-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-gray-700">{{ $deliverable }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                {{-- facilities provided --}}
                @php
                    // parsing facilities_provided dengan aman
                    $facilitiesProvided = [];
                    if ($problem->facilities_provided) {
                        if (is_array($problem->facilities_provided)) {
                            $facilitiesProvided = $problem->facilities_provided;
                        } elseif (is_string($problem->facilities_provided)) {
                            $facilitiesProvided = json_decode($problem->facilities_provided, true) ?? [];
                        }
                    }
                @endphp
                @if(!empty($facilitiesProvided))
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Fasilitas</h3>
                    <ul class="space-y-2">
                        @foreach($facilitiesProvided as $facility)
                            <li class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-gray-700">{{ $facility }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                {{-- info lainnya --}}
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Informasi Lainnya</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-600">Mahasiswa Dibutuhkan</p>
                            <p class="font-medium text-gray-900">{{ $problem->required_students }} orang</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Tingkat Kesulitan</p>
                            <p class="font-medium text-gray-900">{{ ucfirst($problem->difficulty_level) }}</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- section aplikasi --}}
        @if($problem->applications_count > 0)
        <div class="mt-8">
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-900">Aplikasi yang Masuk</h3>
                    <a href="{{ route('institution.applications.index', ['problem' => $problem->id]) }}" 
                       class="text-blue-600 hover:text-blue-700 text-sm font-semibold">
                        Lihat Semua →
                    </a>
                </div>
                <div class="text-center py-4">
                    <p class="text-gray-600">
                        Total <span class="font-bold text-blue-600">{{ $problem->applications_count }}</span> aplikasi diterima
                    </p>
                    <a href="{{ route('institution.applications.index', ['problem' => $problem->id]) }}" 
                       class="inline-flex items-center gap-1 mt-3 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-semibold">
                        Review Aplikasi
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>
@endsection