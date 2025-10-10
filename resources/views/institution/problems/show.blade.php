@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-green-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- header dengan tombol kembali --}}
        <div class="mb-6">
            <a href="{{ route('institution.problems.index') }}" 
               class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                <span class="font-medium">Kembali ke Daftar Problems</span>
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            {{-- main content --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- header problem --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-3">
                                <span class="px-3 py-1 bg-{{ $problem->status === 'published' ? 'green' : ($problem->status === 'draft' ? 'gray' : 'blue') }}-100 text-{{ $problem->status === 'published' ? 'green' : ($problem->status === 'draft' ? 'gray' : 'blue') }}-700 text-sm font-semibold rounded-full">
                                    {{ ucfirst($problem->status) }}
                                </span>
                                @if($problem->is_urgent)
                                <span class="px-3 py-1 bg-red-100 text-red-700 text-sm font-semibold rounded-full flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    Urgent
                                </span>
                                @endif
                                @if($problem->is_featured)
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-700 text-sm font-semibold rounded-full">
                                    Featured
                                </span>
                                @endif
                            </div>
                            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $problem->title }}</h1>
                            <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600">
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>{{ $problem->regency->name }}, {{ $problem->province->name }}</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>{{ $problem->views_count }} views</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                                    </svg>
                                    <span>{{ $problem->applications_count }} aplikasi</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('institution.problems.edit', $problem) }}" 
                               class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                                Edit
                            </a>
                            <form action="{{ route('institution.problems.destroy', $problem) }}" 
                                  method="POST" 
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus masalah ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- sdg categories --}}
                    @php
                        $sdgCategories = is_array($problem->sdg_categories) ? $problem->sdg_categories : json_decode($problem->sdg_categories, true) ?? [];
                    @endphp
                    @if(count($sdgCategories) > 0)
                    <div class="flex flex-wrap gap-2">
                        @foreach($sdgCategories as $sdg)
                            <span class="px-3 py-1 bg-gradient-to-r from-blue-500 to-green-500 text-white text-sm font-semibold rounded-full">
                                {{ $sdg }}
                            </span>
                        @endforeach
                    </div>
                    @endif
                </div>

                {{-- galeri foto --}}
                @if($problem->images && $problem->images->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Galeri Foto</h2>
                    
                    {{-- main image --}}
                    @php
                        $coverImage = $problem->images->where('is_cover', true)->first() ?? $problem->images->first();
                    @endphp
                    @if($coverImage)
                    <div class="mb-4 rounded-lg overflow-hidden">
                        <img id="mainImage" 
                             src="{{ $coverImage->image_url }}" 
                             alt="{{ $coverImage->caption }}"
                             class="w-full h-96 object-cover">
                    </div>
                    @endif

                    {{-- thumbnail gallery --}}
                    @if($problem->images->count() > 1)
                    <div class="grid grid-cols-4 gap-4">
                        @foreach($problem->images as $image)
                        <button type="button" 
                                onclick="document.getElementById('mainImage').src='{{ $image->image_url }}'"
                                class="aspect-square rounded-lg overflow-hidden border-2 transition-all {{ $loop->first ? 'border-blue-500 ring-2 ring-blue-200' : 'border-transparent hover:border-blue-300' }}">
                            <img src="{{ $image->image_url }}" 
                                 alt="{{ $image->caption }}"
                                 class="w-full h-full object-cover"
                                 loading="lazy">
                        </button>
                        @endforeach
                    </div>
                    @endif
                </div>
                @endif

                {{-- description section --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Deskripsi Masalah</h2>
                    <div class="prose prose-sm max-w-none text-gray-600">
                        {!! nl2br(e($problem->description)) !!}
                    </div>
                </div>

                {{-- background --}}
                @if($problem->background)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Latar Belakang</h2>
                    <div class="prose prose-sm max-w-none text-gray-600">
                        {!! nl2br(e($problem->background)) !!}
                    </div>
                </div>
                @endif

                {{-- objectives --}}
                @if($problem->objectives)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Tujuan</h2>
                    <div class="prose prose-sm max-w-none text-gray-600">
                        {!! nl2br(e($problem->objectives)) !!}
                    </div>
                </div>
                @endif

                {{-- scope --}}
                @if($problem->scope)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Ruang Lingkup</h2>
                    <div class="prose prose-sm max-w-none text-gray-600">
                        {!! nl2br(e($problem->scope)) !!}
                    </div>
                </div>
                @endif

                {{-- expected outcomes --}}
                @if($problem->expected_outcomes)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Output yang Diharapkan</h2>
                    <div class="prose prose-sm max-w-none text-gray-600">
                        {!! nl2br(e($problem->expected_outcomes)) !!}
                    </div>
                </div>
                @endif

                {{-- deliverables --}}
                @php
                    // pastikan deliverables adalah array sebelum digunakan
                    $deliverables = is_array($problem->deliverables) ? $problem->deliverables : json_decode($problem->deliverables, true) ?? [];
                @endphp
                @if(count($deliverables) > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Deliverables</h2>
                    <ul class="space-y-2">
                        @foreach($deliverables as $deliverable)
                            <li class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
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
                    // pastikan facilities_provided adalah array sebelum digunakan
                    $facilitiesProvided = is_array($problem->facilities_provided) ? $problem->facilities_provided : json_decode($problem->facilities_provided, true) ?? [];
                @endphp
                @if(count($facilitiesProvided) > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Fasilitas yang Disediakan</h2>
                    <ul class="space-y-2">
                        @foreach($facilitiesProvided as $facility)
                            <li class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-green-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-gray-700">{{ $facility }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
                @endif

            </div>

            {{-- sidebar --}}
            <div class="space-y-6">
                
                {{-- quick stats --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Statistik</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Total Aplikasi</span>
                            <span class="text-xl font-bold text-gray-900">{{ $problem->applications_count }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Diterima</span>
                            <span class="text-xl font-bold text-green-600">{{ $problem->accepted_students }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Slot Tersisa</span>
                            <span class="text-xl font-bold text-blue-600">{{ max(0, $problem->required_students - $problem->accepted_students) }}</span>
                        </div>
                    </div>
                </div>

                {{-- timeline --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Timeline</h3>
                    <div class="space-y-3 text-sm">
                        <div>
                            <p class="text-gray-600 mb-1">Deadline Aplikasi</p>
                            <p class="font-semibold text-gray-900">{{ $problem->application_deadline->format('d M Y') }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 mb-1">Mulai Proyek</p>
                            <p class="font-semibold text-gray-900">{{ $problem->start_date->format('d M Y') }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 mb-1">Selesai Proyek</p>
                            <p class="font-semibold text-gray-900">{{ $problem->end_date->format('d M Y') }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 mb-1">Durasi</p>
                            <p class="font-semibold text-gray-900">{{ $problem->duration_months }} bulan</p>
                        </div>
                    </div>
                </div>

                {{-- requirements --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Requirements</h3>
                    <div class="space-y-3 text-sm">
                        <div>
                            <p class="text-gray-600 mb-1">Mahasiswa Dibutuhkan</p>
                            <p class="font-semibold text-gray-900">{{ $problem->required_students }} orang</p>
                        </div>
                        <div>
                            <p class="text-gray-600 mb-1">Tingkat Kesulitan</p>
                            <p class="font-semibold text-gray-900">{{ ucfirst($problem->difficulty_level) }}</p>
                        </div>
                        
                        @php
                            // pastikan required_skills adalah array sebelum digunakan
                            $requiredSkills = is_array($problem->required_skills) ? $problem->required_skills : json_decode($problem->required_skills, true) ?? [];
                        @endphp
                        @if(count($requiredSkills) > 0)
                        <div>
                            <p class="text-gray-600 mb-2">Skills yang Dibutuhkan</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($requiredSkills as $skill)
                                    <span class="inline-flex px-2 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded">
                                        {{ $skill }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        @php
                            // pastikan required_majors adalah array sebelum digunakan
                            $requiredMajors = is_array($problem->required_majors) ? $problem->required_majors : json_decode($problem->required_majors, true) ?? [];
                        @endphp
                        @if(count($requiredMajors) > 0)
                        <div>
                            <p class="text-gray-600 mb-2">Jurusan yang Dibutuhkan</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($requiredMajors as $major)
                                    <span class="inline-flex px-2 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded">
                                        {{ $major }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- quick actions --}}
                <div class="bg-gradient-to-br from-blue-600 to-green-600 rounded-xl shadow-sm p-6 text-white">
                    <h3 class="text-lg font-bold mb-4">Quick Actions</h3>
                    <div class="space-y-3">
                        <a href="{{ route('institution.applications.index', ['problem_id' => $problem->id]) }}" 
                           class="block w-full py-2 px-4 bg-white/20 hover:bg-white/30 rounded-lg text-center font-medium transition-colors">
                            Lihat Aplikasi
                        </a>
                        @if($problem->status === 'draft')
                        <form action="{{ route('institution.problems.publish', $problem) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" 
                                    class="block w-full py-2 px-4 bg-white/20 hover:bg-white/30 rounded-lg text-center font-medium transition-colors">
                                Publikasikan
                            </button>
                        </form>
                        @endif
                        @if($problem->status === 'published')
                        <form action="{{ route('institution.problems.close', $problem) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" 
                                    class="block w-full py-2 px-4 bg-white/20 hover:bg-white/30 rounded-lg text-center font-medium transition-colors">
                                Tutup Aplikasi
                            </button>
                        </form>
                        @endif
                    </div>
                </div>

            </div>

        </div>

    </div>
</div>
@endsection