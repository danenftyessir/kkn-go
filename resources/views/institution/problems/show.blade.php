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
                        <span class="text-sm text-gray-600">
                            <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            {{ $problem->views_count }} views
                        </span>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ route('institution.problems.edit', $problem->id) }}" 
                       class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-200">
                        Edit
                    </a>
                    <form action="{{ route('institution.problems.destroy', $problem->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus masalah ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-all duration-200">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- statistik aplikasi --}}
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <p class="text-sm text-gray-600 mb-1">Total Aplikasi</p>
                <p class="text-3xl font-bold text-gray-900">{{ $applicationStats['total'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <p class="text-sm text-gray-600 mb-1">Pending</p>
                <p class="text-3xl font-bold text-yellow-600">{{ $applicationStats['pending'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <p class="text-sm text-gray-600 mb-1">Under Review</p>
                <p class="text-3xl font-bold text-blue-600">{{ $applicationStats['under_review'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <p class="text-sm text-gray-600 mb-1">Accepted</p>
                <p class="text-3xl font-bold text-green-600">{{ $applicationStats['accepted'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <p class="text-sm text-gray-600 mb-1">Rejected</p>
                <p class="text-3xl font-bold text-red-600">{{ $applicationStats['rejected'] }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            {{-- main content --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- deskripsi --}}
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Deskripsi</h2>
                    <p class="text-gray-700 whitespace-pre-line">{{ $problem->description }}</p>
                </div>

                {{-- background --}}
                @if($problem->background)
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Latar Belakang</h2>
                    <p class="text-gray-700 whitespace-pre-line">{{ $problem->background }}</p>
                </div>
                @endif

                {{-- objectives --}}
                @if($problem->objectives)
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Tujuan</h2>
                    <p class="text-gray-700 whitespace-pre-line">{{ $problem->objectives }}</p>
                </div>
                @endif

                {{-- gambar --}}
                @if($problem->images->count() > 0)
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Galeri Foto</h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($problem->images as $image)
                            <img src="{{ asset('storage/' . $image->image_path) }}" 
                                 alt="{{ $image->caption }}"
                                 class="w-full h-48 object-cover rounded-lg">
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- aplikasi list --}}
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold text-gray-900">Daftar Aplikasi</h2>
                        <a href="{{ route('institution.applications.index', ['problem_id' => $problem->id]) }}" 
                           class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                            Lihat Semua →
                        </a>
                    </div>

                    @forelse($problem->applications->take(5) as $application)
                        <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0">
                            <div class="flex items-center gap-3">
                                <img src="{{ $application->student->profile_photo_path ? asset('storage/' . $application->student->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode($application->student->user->name) }}" 
                                     alt="{{ $application->student->user->name }}"
                                     class="w-10 h-10 rounded-full object-cover">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $application->student->user->name }}</p>
                                    <p class="text-sm text-gray-600">{{ $application->student->university->name }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                    {{ $application->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $application->status === 'under_review' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $application->status === 'accepted' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $application->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ ucfirst($application->status) }}
                                </span>
                                <a href="{{ route('institution.applications.show', $application->id) }}" 
                                   class="text-blue-600 hover:text-blue-700">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-600 text-center py-8">Belum ada aplikasi</p>
                    @endforelse
                </div>

            </div>

            {{-- sidebar --}}
            <div class="lg:col-span-1 space-y-6">
                
                {{-- informasi dasar --}}
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Informasi Dasar</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-600">Lokasi</p>
                            <p class="font-medium text-gray-900">{{ $problem->regency->name }}, {{ $problem->province->name }}</p>
                        </div>
                        @if($problem->village)
                        <div>
                            <p class="text-sm text-gray-600">Desa/Kelurahan</p>
                            <p class="font-medium text-gray-900">{{ $problem->village }}</p>
                        </div>
                        @endif
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

                {{-- timeline --}}
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Timeline</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-600">Deadline Aplikasi</p>
                            <p class="font-medium text-gray-900">{{ $problem->application_deadline->format('d M Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Tanggal Mulai</p>
                            <p class="font-medium text-gray-900">{{ $problem->start_date->format('d M Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Tanggal Selesai</p>
                            <p class="font-medium text-gray-900">{{ $problem->end_date->format('d M Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Durasi</p>
                            <p class="font-medium text-gray-900">{{ $problem->duration_months }} bulan</p>
                        </div>
                    </div>
                </div>

                {{-- kategori SDG --}}
                @if($problem->sdg_categories)
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Kategori SDG</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($problem->sdg_categories as $sdg)
                            <span class="inline-flex px-3 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                                {{ $sdg }}
                            </span>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- skills required --}}
                @if($problem->required_skills)
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Skills Dibutuhkan</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($problem->required_skills as $skill)
                            <span class="inline-flex px-3 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                {{ $skill }}
                            </span>
                        @endforeach
                    </div>
                </div>
                @endif

            </div>
        </div>

    </div>
</div>
@endsection