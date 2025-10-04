@extends('layouts.app')

@section('title', 'Profil Instansi')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- header --}}
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Profil Instansi</h1>
                <p class="text-gray-600 mt-1">Kelola informasi profil instansi Anda</p>
            </div>
            <a href="{{ route('institution.profile.edit') }}" 
               class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                Edit Profil
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            {{-- main content --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- informasi dasar --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-start gap-6 mb-6">
                        @if($institution->logo_path)
                        <img src="{{ Storage::url($institution->logo_path) }}" 
                             alt="{{ $institution->name }}" 
                             class="w-24 h-24 rounded-lg object-cover border-2 border-gray-200">
                        @else
                        <div class="w-24 h-24 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                            <span class="text-white text-3xl font-bold">{{ substr($institution->name, 0, 1) }}</span>
                        </div>
                        @endif

                        <div class="flex-1">
                            <h2 class="text-2xl font-bold text-gray-900">{{ $institution->name }}</h2>
                            <p class="text-gray-600 mt-1">{{ $institution->type }}</p>
                            <div class="flex items-center gap-2 mt-3">
                                @if($institution->is_verified)
                                <span class="px-3 py-1 bg-green-100 text-green-700 text-sm font-semibold rounded-full flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Terverifikasi
                                </span>
                                @else
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-700 text-sm font-semibold rounded-full">
                                    Menunggu Verifikasi
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($institution->description)
                    <div class="mb-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Deskripsi</h3>
                        <p class="text-gray-700">{{ $institution->description }}</p>
                    </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-700 mb-1">Alamat</h3>
                            <p class="text-gray-900">{{ $institution->address }}</p>
                            <p class="text-gray-600 text-sm">{{ $institution->regency->name }}, {{ $institution->province->name }}</p>
                        </div>

                        <div>
                            <h3 class="text-sm font-semibold text-gray-700 mb-1">Kontak</h3>
                            <p class="text-gray-900">{{ $institution->phone }}</p>
                            @if($institution->website)
                            <a href="{{ $institution->website }}" target="_blank" class="text-blue-600 hover:text-blue-700 text-sm">
                                {{ $institution->website }}
                            </a>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- person in charge --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Penanggung Jawab</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Nama</p>
                            <p class="text-gray-900 font-semibold">{{ $institution->pic_name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Jabatan</p>
                            <p class="text-gray-900 font-semibold">{{ $institution->pic_position }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Telepon</p>
                            <p class="text-gray-900 font-semibold">{{ $institution->pic_phone }}</p>
                        </div>
                    </div>
                </div>

                {{-- akun --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Informasi Akun</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-600">Email</p>
                            <p class="text-gray-900 font-semibold">{{ $institution->user->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Username</p>
                            <p class="text-gray-900 font-semibold">{{ $institution->user->username }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Profil Publik</p>
                            <a href="{{ route('institution.profile.public', $institution->user->username) }}" 
                               target="_blank"
                               class="text-blue-600 hover:text-blue-700 font-semibold flex items-center gap-1">
                                Lihat Profil Publik
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

            </div>

            {{-- sidebar --}}
            <div class="space-y-6">
                
                {{-- statistik --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Statistik</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Total Masalah</span>
                            <span class="text-2xl font-bold text-blue-600">{{ $stats['total_problems'] }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Total Aplikasi</span>
                            <span class="text-2xl font-bold text-green-600">{{ $stats['total_applications'] }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Total Proyek</span>
                            <span class="text-2xl font-bold text-purple-600">{{ $stats['total_projects'] }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Selesai</span>
                            <span class="text-2xl font-bold text-gray-900">{{ $stats['completed_projects'] }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Rating Rata-rata</span>
                            <div class="flex items-center gap-1">
                                <span class="text-2xl font-bold text-yellow-600">{{ number_format($stats['average_rating'], 1) }}</span>
                                <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- quick actions --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Quick Actions</h3>
                    <div class="space-y-3">
                        <a href="{{ route('institution.problems.create') }}" 
                           class="block w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold text-center">
                            Buat Masalah Baru
                        </a>
                        <a href="{{ route('institution.problems.index') }}" 
                           class="block w-full px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-semibold text-center">
                            Kelola Masalah
                        </a>
                        <a href="{{ route('institution.applications.index') }}" 
                           class="block w-full px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-semibold text-center">
                            Review Aplikasi
                        </a>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection