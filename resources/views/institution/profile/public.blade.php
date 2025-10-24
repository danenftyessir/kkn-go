{{-- resources/views/institution/profile/public.blade.php --}}
@extends('layouts.app')

@section('title', $institution->name . ' - Profil Publik')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- header profil --}}
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl shadow-lg p-8 mb-8 text-white">
            <div class="flex items-start gap-6">
                @if($institution->logo_path)
                    <img src="{{ $institution->getLogoUrl() }}" 
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
                    <p class="text-blue-100 text-lg mb-4">{{ ucwords(str_replace('_', ' ', $institution->type)) }}</p>
                    
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
                <div class="text-sm text-gray-600 mt-2">Total Masalah</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 text-center">
                <div class="text-3xl font-bold text-green-600">{{ $stats['active_problems'] }}</div>
                <div class="text-sm text-gray-600 mt-2">Masalah Aktif</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 text-center">
                <div class="text-3xl font-bold text-purple-600">{{ $stats['completed_problems'] }}</div>
                <div class="text-sm text-gray-600 mt-2">Masalah Selesai</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 text-center">
                <div class="text-3xl font-bold text-orange-600">{{ $stats['active_projects'] }}</div>
                <div class="text-sm text-gray-600 mt-2">Proyek Berjalan</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 text-center">
                <div class="text-3xl font-bold text-indigo-600">{{ $stats['completed_projects'] }}</div>
                <div class="text-sm text-gray-600 mt-2">Proyek Selesai</div>
            </div>
        </div>

        {{-- masalah yang dipublikasikan --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Masalah Yang Dipublikasikan</h2>
            
            @if($institution->problems->isEmpty())
                <div class="text-center py-12">
                    <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="text-gray-500">Belum Ada Masalah Yang Dipublikasi</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($institution->problems as $problem)
                    <a href="{{ route('student.browse-problems.show', $problem->id) }}" class="block bg-gray-50 rounded-lg p-4 hover:shadow-md transition-shadow border border-gray-100">
                        @if($problem->coverImage)
                            <img src="{{ $problem->coverImage->image_url }}" 
                                alt="{{ $problem->title }}"
                                onerror="this.onerror=null; this.src='https://via.placeholder.com/400x200?text=No+Image';"
                                class="w-full h-40 object-cover rounded-lg mb-4">
                        @endif
                        
                        <h3 class="font-bold text-gray-900 mb-2 line-clamp-2">{{ $problem->title }}</h3>
                        
                        <div class="flex items-center gap-2 text-sm text-gray-600 mb-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>{{ $problem->regency->name }}</span>
                        </div>

                        <div class="flex items-center justify-between mt-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                {{ $problem->status === 'open' ? 'bg-green-100 text-green-800' : 
                                ($problem->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                {{ ucfirst(str_replace('_', ' ', $problem->status)) }}
                            </span>
                            
                            <span class="text-sm text-gray-500">
                                {{ $problem->applications_count ?? 0 }} aplikasi
                            </span>
                        </div>
                    </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection