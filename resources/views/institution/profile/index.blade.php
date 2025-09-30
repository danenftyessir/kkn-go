@extends('layouts.institution')

@section('title', 'Profil Instansi')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 page-transition">
    <!-- header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">profil instansi</h1>
        <p class="text-gray-600 mt-1">kelola informasi profil dan pengaturan akun instansi</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm p-6 sticky top-24">
                <!-- logo instansi -->
                <div class="text-center">
                    @if($institution->logo_url)
                        <img src="{{ asset('storage/' . $institution->logo_url) }}" 
                             alt="logo instansi"
                             class="w-32 h-32 rounded-lg mx-auto object-cover border-4 border-gray-100">
                    @else
                        <div class="w-32 h-32 rounded-lg mx-auto bg-blue-100 flex items-center justify-center">
                            <svg class="w-16 h-16 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                    @endif
                    
                    <h3 class="text-xl font-bold text-gray-900 mt-4">
                        {{ $institution->institution_name }}
                    </h3>
                    <p class="text-gray-600 text-sm">@<span>{{ $user->username }}</span></p>
                    <p class="text-gray-500 text-sm mt-1">
                        {{ ucwords(str_replace('_', ' ', $institution->institution_type)) }}
                    </p>
                </div>

                <!-- status verifikasi -->
                <div class="mt-6 pt-6 border-t">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">status verifikasi</span>
                        @if($institution->is_verified)
                            <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                terverifikasi
                            </span>
                        @else
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded-full flex items-center">
                                <svg class="w-4 h-4 mr-1 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                menunggu
                            </span>
                        @endif
                    </div>
                    @if(!$institution->is_verified)
                        <p class="text-xs text-gray-500 mt-2">
                            akun anda sedang dalam proses verifikasi oleh admin
                        </p>
                    @endif
                </div>

                <!-- quick stats -->
                <div class="mt-4 space-y-3">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">program aktif</span>
                        <span class="font-semibold text-gray-900">0</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">program selesai</span>
                        <span class="font-semibold text-gray-900">0</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">total mahasiswa</span>
                        <span class="font-semibold text-gray-900">0</span>
                    </div>
                </div>

                <!-- actions -->
                <div class="mt-6 space-y-2">
                    <a href="{{ route('institution.profile.edit') }}" 
                       class="btn-primary w-full text-center">
                        edit profil
                    </a>
                    <a href="{{ route('institution.profile.public', $user->username) }}" 
                       class="btn-secondary w-full text-center"
                       target="_blank">
                        lihat profil publik
                    </a>
                </div>
            </div>
        </div>

        <!-- main content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- informasi instansi -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6">informasi instansi</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-sm font-medium text-gray-700">nama instansi</label>
                        <p class="text-gray-900 mt-1">{{ $institution->institution_name }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">jenis instansi</label>
                        <p class="text-gray-900 mt-1">{{ ucwords(str_replace('_', ' ', $institution->institution_type)) }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">email resmi</label>
                        <p class="text-gray-900 mt-1">{{ $user->email }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">nomor telepon</label>
                        <p class="text-gray-900 mt-1">{{ $institution->phone_number }}</p>
                    </div>
                    @if($institution->website)
                    <div class="md:col-span-2">
                        <label class="text-sm font-medium text-gray-700">website</label>
                        <p class="text-gray-900 mt-1">
                            <a href="{{ $institution->website }}" target="_blank" class="text-blue-600 hover:text-blue-700">
                                {{ $institution->website }}
                            </a>
                        </p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- alamat -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6">alamat</h2>
                
                <div class="space-y-4">
                    <div>
                        <label class="text-sm font-medium text-gray-700">alamat lengkap</label>
                        <p class="text-gray-900 mt-1">{{ $institution->address }}</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-sm font-medium text-gray-700">provinsi</label>
                            <p class="text-gray-900 mt-1">
                                {{-- TODO: tampilkan nama provinsi dari relasi --}}
                                provinsi placeholder
                            </p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">kabupaten/kota</label>
                            <p class="text-gray-900 mt-1">
                                {{-- TODO: tampilkan nama regency dari relasi --}}
                                kabupaten placeholder
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- penanggung jawab -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6">penanggung jawab</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-sm font-medium text-gray-700">nama</label>
                        <p class="text-gray-900 mt-1">{{ $institution->pic_name }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">jabatan</label>
                        <p class="text-gray-900 mt-1">{{ $institution->pic_position }}</p>
                    </div>
                </div>
            </div>

            <!-- deskripsi -->
            @if($institution->description)
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">tentang instansi</h2>
                <p class="text-gray-700 whitespace-pre-line">{{ $institution->description }}</p>
            </div>
            @endif

            <!-- dokumen verifikasi -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">dokumen verifikasi</h2>
                
                @if($institution->verification_document_url)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-10 h-10 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                            </svg>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">dokumen verifikasi.pdf</p>
                                <p class="text-xs text-gray-500">dokumen resmi instansi</p>
                            </div>
                        </div>
                        <a href="{{ asset('storage/' . $institution->verification_document_url) }}" 
                           target="_blank"
                           class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                            lihat
                        </a>
                    </div>
                    
                    @if(!$institution->is_verified)
                        <form method="POST" 
                              action="{{ route('institution.profile.verification.upload') }}" 
                              enctype="multipart/form-data"
                              class="mt-4">
                            @csrf
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                upload ulang dokumen verifikasi
                            </label>
                            <input type="file" 
                                   name="verification_document" 
                                   accept="application/pdf"
                                   class="input-field"
                                   required>
                            <button type="submit" class="btn-primary mt-2">
                                upload dokumen baru
                            </button>
                        </form>
                    @endif
                @endif
            </div>

            <!-- pengaturan akun -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6">pengaturan akun</h2>
                
                <!-- change password -->
                <form method="POST" action="{{ route('institution.profile.password') }}">
                    @csrf
                    @method('PUT')
                    
                    <h3 class="font-semibold text-gray-900 mb-4">ubah password</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">
                                password saat ini
                            </label>
                            <input type="password" 
                                   id="current_password" 
                                   name="current_password"
                                   class="input-field"
                                   required>
                            @error('current_password')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                                password baru
                            </label>
                            <input type="password" 
                                   id="password" 
                                   name="password"
                                   class="input-field"
                                   required>
                            @error('password')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                                konfirmasi password
                            </label>
                            <input type="password" 
                                   id="password_confirmation" 
                                   name="password_confirmation"
                                   class="input-field"
                                   required>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn-primary mt-4">
                        update password
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection