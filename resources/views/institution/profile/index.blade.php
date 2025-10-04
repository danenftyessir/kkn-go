@extends('layouts.app')

@section('title', 'Profil Instansi')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-green-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- header --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Profil Instansi</h1>
            <p class="mt-2 text-gray-600">Kelola informasi instansi Anda</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            {{-- sidebar card --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    {{-- logo --}}
                    <div class="text-center mb-6">
                        @if($institution->logo_path)
                            <img src="{{ asset('storage/' . $institution->logo_path) }}" 
                                 alt="{{ $institution->name }}"
                                 class="w-32 h-32 mx-auto rounded-full object-cover border-4 border-gray-200">
                        @else
                            <div class="w-32 h-32 mx-auto rounded-full bg-gradient-to-br from-blue-500 to-green-500 flex items-center justify-center border-4 border-gray-200">
                                <span class="text-4xl font-bold text-white">{{ strtoupper(substr($institution->name, 0, 1)) }}</span>
                            </div>
                        @endif
                        
                        <h2 class="mt-4 text-xl font-bold text-gray-900">{{ $institution->name }}</h2>
                        <p class="text-sm text-gray-600">{{ ucwords(str_replace('_', ' ', $institution->type)) }}</p>
                    </div>

                    {{-- quick info --}}
                    <div class="space-y-3 border-t border-gray-200 pt-4">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-700 mb-1">Lokasi</h3>
                            <p class="text-gray-900">{{ $institution->regency->name }}, {{ $institution->province->name }}</p>
                        </div>

                        <div>
                            <h3 class="text-sm font-semibold text-gray-700 mb-1">Kontak</h3>
                            <p class="text-gray-900">{{ $institution->phone }}</p>
                        </div>
                    </div>

                    {{-- actions --}}
                    <div class="mt-6 space-y-2">
                        <a href="{{ route('institution.profile.edit') }}" 
                           class="block w-full px-4 py-2 bg-blue-600 text-white text-center rounded-lg hover:bg-blue-700 transition-all duration-200">
                            Edit Profil
                        </a>
                    </div>
                </div>
            </div>

            {{-- main content --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- informasi instansi --}}
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">Informasi Instansi</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-700 mb-1">Nama Instansi</h3>
                            <p class="text-gray-900">{{ $institution->name }}</p>
                        </div>

                        <div>
                            <h3 class="text-sm font-semibold text-gray-700 mb-1">Jenis Instansi</h3>
                            <p class="text-gray-900">{{ ucwords(str_replace('_', ' ', $institution->type)) }}</p>
                        </div>

                        <div>
                            <h3 class="text-sm font-semibold text-gray-700 mb-1">Email</h3>
                            <p class="text-gray-900">{{ $institution->email }}</p>
                        </div>

                        <div>
                            <h3 class="text-sm font-semibold text-gray-700 mb-1">Telepon</h3>
                            <p class="text-gray-900">{{ $institution->phone }}</p>
                        </div>

                        <div class="md:col-span-2">
                            <h3 class="text-sm font-semibold text-gray-700 mb-1">Alamat Lengkap</h3>
                            <p class="text-gray-900">{{ $institution->address }}</p>
                        </div>

                        @if($institution->description)
                        <div class="md:col-span-2">
                            <h3 class="text-sm font-semibold text-gray-700 mb-1">Deskripsi</h3>
                            <p class="text-gray-900">{{ $institution->description }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- person in charge --}}
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">Penanggung Jawab</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-700 mb-1">Nama</h3>
                            <p class="text-gray-900">{{ $institution->pic_name }}</p>
                        </div>

                        <div>
                            <h3 class="text-sm font-semibold text-gray-700 mb-1">Jabatan</h3>
                            <p class="text-gray-900">{{ $institution->pic_position }}</p>
                        </div>
                    </div>
                </div>

                {{-- informasi akun --}}
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">Informasi Akun</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-700 mb-1">Email</h3>
                            <p class="text-gray-900">{{ $user->email }}</p>
                        </div>

                        <div>
                            <h3 class="text-sm font-semibold text-gray-700 mb-1">Username</h3>
                            <p class="text-gray-900">{{ $user->username }}</p>
                        </div>

                        <div>
                            <h3 class="text-sm font-semibold text-gray-700 mb-1">Status Verifikasi</h3>
                            @if($institution->is_verified)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Terverifikasi
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                    Belum Terverifikasi
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection