@extends('layouts.app')

@section('title', $institution->name . ' - Profil Publik')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- header profil --}}
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl shadow-lg p-8 mb-8 text-white">
            <div class="flex items-start gap-6">
                @if($institution->logo_path)
                <img src="{{ Storage::url($institution->logo_path) }}" 
                     alt="{{ $institution->name }}" 
                     class="w-32 h-32 rounded-xl object-cover border-4 border-white shadow-lg">
                @else
                <div class="w-32 h-32 bg-white rounded-xl flex items-center justify-center shadow-lg">
                    <span class="text-blue-600 text-5xl font-bold">{{ substr($institution->name, 0, 1) }}</span>
                </div>
                @endif

                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <h1 class="text-3xl font-bold">{{ $institution->name }}</h1>
                        @if($institution->is_verified)
                        <svg class="w-8 h-8 text-blue-200" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        @endif
                    </div>
                    <p class="text-blue-100 text-lg mb-4">{{ $institution->type }}</p>
                    
                    @if($institution->description)
                    <p class="text-blue-50 mb-4">{{ $institution->description }}</p>
                    @endif

                    <div class="flex flex-wrap gap-4 text-sm">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>{{ $institution->regency->name }}, {{ $institution->province->name }}</span>
                        </div>
                        @if($institution->website)
                        <a href="{{ $institution->website }}" target="_blank" class="flex items-center gap-2 hover:text-blue-200 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                            </svg>
                            <span>Website</span>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- statistik --}}
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 text-center">
                <div class="text-3xl font-bold text-blue-600">{{ $stats['total_problems'] }}</div>
                <div class="text-sm text-gray-600 mt-1">Masalah Dipublikasi</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 text-center">
                <div class="text-3xl font-bold text-green-600">{{ $stats['active_projects'] }}</div>
                <div class="text-sm text-gray-600 mt-1">Proyek Berjalan</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 text-center">
                <div class="text-3xl font-bold text-purple-600">{{ $stats['completed_projects'] }}</div>
                <div class="text-sm text-gray-600 mt-1">Proyek Selesai</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 text-center">
                <div class="flex items-center justify-center gap-1">
                    <span class="text-3xl font-bold text-yellow-600">{{ number_format($stats['average_rating'], 1) }}</span>
                    <svg class="w-6 h-6 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                </div>
                <div class="text-sm text-gray-600 mt-1">Rating Rata-rata</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 text-center">
                <div class="text-3xl font-bold text-gray-900">{{ $stats['total_reviews'] }}</div>
                <div class="text-sm text-gray-600 mt-1">Total Reviews</div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            {{-- main content --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- masalah terbuka --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Masalah Terbuka</h2>
                    
                    @forelse($recentProblems as $problem)
                    <div class="mb-6 pb-6 border-b border-gray-200 last:border-0">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-lg font-bold text-gray-900 hover:text-blue-600">
                                <a href="{{ route('student.problems.show', $problem->id) }}">{{ $problem->title }}</a>
                            </h3>
                            <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">Terbuka</span>
                        </div>
                        
                        <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $problem->description }}</p>
                        
                        <div class="flex flex-wrap gap-4 text-sm text-gray-500">
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                </svg>
                                {{ $problem->regency->name }}
                            </div>
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                                {{ $problem->required_students }} mahasiswa
                            </div>
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Deadline: {{ $problem->application_deadline->format('d M Y') }}
                            </div>
                        </div>
                    </div>
                    @empty
                    <p class="text-gray-600 text-center py-8">Belum ada masalah yang terbuka saat ini</p>
                    @endforelse
                </div>

                {{-- proyek selesai --}}
                @if($completedProjects->isNotEmpty())
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Proyek yang Telah Diselesaikan</h2>
                    
                    <div class="space-y-6">
                        @foreach($completedProjects as $project)
                        <div class="border-l-4 border-blue-500 pl-4">
                            <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $project->title }}</h3>
                            <p class="text-sm text-gray-600 mb-2">
                                Oleh: {{ $project->student->user->name }} ({{ $project->student->university->name }})
                            </p>
                            
                            @if($project->rating)
                            <div class="flex items-center gap-2 mb-2">
                                <div class="flex">
                                    @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-5 h-5 {{ $i <= $project->rating ? 'text-yellow-500' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    @endfor
                                </div>
                                <span class="text-sm font-semibold text-gray-700">{{ $project->rating }}/5</span>
                            </div>
                            @endif
                            
                            @if($project->institution_review)
                            <p class="text-sm text-gray-600 italic">"{{ Str::limit($project->institution_review, 150) }}"</p>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

            </div>

            {{-- sidebar --}}
            <div class="space-y-6">
                
                {{-- kontak --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Kontak</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <div>
                                <p class="text-gray-600">Email</p>
                                <p class="text-gray-900 font-semibold break-all">{{ $institution->user->email }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <div>
                                <p class="text-gray-600">Telepon</p>
                                <p class="text-gray-900 font-semibold">{{ $institution->phone }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <div>
                                <p class="text-gray-600">Alamat</p>
                                <p class="text-gray-900">{{ $institution->address }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- penanggung jawab --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Penanggung Jawab</h3>
                    <div class="space-y-2 text-sm">
                        <div>
                            <p class="text-gray-600">Nama</p>
                            <p class="text-gray-900 font-semibold">{{ $institution->pic_name }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Jabatan</p>
                            <p class="text-gray-900 font-semibold">{{ $institution->pic_position }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Telepon</p>
                            <p class="text-gray-900 font-semibold">{{ $institution->pic_phone }}</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection