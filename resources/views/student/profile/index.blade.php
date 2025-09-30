@extends('layouts.student')

@section('title', 'Profil Saya')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 page-transition">
    <!-- header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">profil saya</h1>
        <p class="text-gray-600 mt-1">kelola informasi profil dan pengaturan akun anda</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm p-6 sticky top-24">
                <!-- foto profil -->
                <div class="text-center">
                    @if($student->profile_photo_url)
                        <img src="{{ asset('storage/' . $student->profile_photo_url) }}" 
                             alt="foto profil"
                             class="w-32 h-32 rounded-full mx-auto object-cover border-4 border-gray-100">
                    @else
                        <div class="w-32 h-32 rounded-full mx-auto bg-primary-100 flex items-center justify-center">
                            <span class="text-4xl font-bold text-primary-600">
                                {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                            </span>
                        </div>
                    @endif
                    
                    <h3 class="text-xl font-bold text-gray-900 mt-4">
                        {{ $student->first_name }} {{ $student->last_name }}
                    </h3>
                    <p class="text-gray-600 text-sm">@<span>{{ $user->username }}</span></p>
                    <p class="text-gray-500 text-sm mt-1">{{ $student->nim }}</p>
                </div>

                <!-- quick stats -->
                <div class="mt-6 pt-6 border-t space-y-3">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">semester</span>
                        <span class="font-semibold text-gray-900">{{ $student->semester }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">proyek selesai</span>
                        <span class="font-semibold text-gray-900">0</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">portfolio</span>
                        <span class="text-sm {{ $student->portfolio_visible ? 'text-green-600' : 'text-gray-500' }}">
                            {{ $student->portfolio_visible ? 'publik' : 'privat' }}
                        </span>
                    </div>
                </div>

                <!-- actions -->
                <div class="mt-6 space-y-2">
                    <a href="{{ route('student.profile.edit') }}" 
                       class="btn-primary w-full text-center">
                        edit profil
                    </a>
                    <a href="{{ route('student.profile.public', $user->username) }}" 
                       class="btn-secondary w-full text-center"
                       target="_blank">
                        lihat profil publik
                    </a>
                </div>
            </div>
        </div>

        <!-- main content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- informasi pribadi -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6">informasi pribadi</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-sm font-medium text-gray-700">nama lengkap</label>
                        <p class="text-gray-900 mt-1">{{ $student->first_name }} {{ $student->last_name }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">email</label>
                        <p class="text-gray-900 mt-1">{{ $user->email }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">nomor whatsapp</label>
                        <p class="text-gray-900 mt-1">{{ $student->whatsapp_number }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">nim</label>
                        <p class="text-gray-900 mt-1">{{ $student->nim }}</p>
                    </div>
                </div>
            </div>

            <!-- informasi akademik -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6">informasi akademik</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-sm font-medium text-gray-700">universitas</label>
                        <p class="text-gray-900 mt-1">
                            {{-- TODO: tampilkan nama universitas dari relasi --}}
                            universitas placeholder
                        </p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">jurusan</label>
                        <p class="text-gray-900 mt-1">{{ $student->major }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">semester</label>
                        <p class="text-gray-900 mt-1">semester {{ $student->semester }}</p>
                    </div>
                </div>
            </div>

            <!-- bio -->
            @if($student->bio)
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">tentang saya</h2>
                <p class="text-gray-700 whitespace-pre-line">{{ $student->bio }}</p>
            </div>
            @endif

            <!-- skills -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">keahlian</h2>
                
                {{-- TODO: tampilkan skills dari database --}}
                <div class="flex flex-wrap gap-2">
                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                        belum ada keahlian
                    </span>
                </div>
                
                <p class="text-sm text-gray-500 mt-4">
                    tambahkan keahlian anda di halaman edit profil
                </p>
            </div>

            <!-- pengaturan akun -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6">pengaturan akun</h2>
                
                <!-- change password -->
                <form method="POST" action="{{ route('student.profile.password') }}" class="mb-6">
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

                <!-- privacy settings -->
                <form method="POST" action="{{ route('student.profile.privacy') }}" class="pt-6 border-t">
                    @csrf
                    @method('PUT')
                    
                    <h3 class="font-semibold text-gray-900 mb-4">pengaturan privasi</h3>
                    
                    <div class="space-y-3">
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="portfolio_visible" 
                                   value="1"
                                   {{ $student->portfolio_visible ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                            <span class="ml-2 text-sm text-gray-700">tampilkan portfolio publik</span>
                        </label>
                        
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="show_email" 
                                   value="1"
                                   {{ $student->show_email ?? false ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                            <span class="ml-2 text-sm text-gray-700">tampilkan email di profil publik</span>
                        </label>
                        
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="show_phone" 
                                   value="1"
                                   {{ $student->show_phone ?? false ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                            <span class="ml-2 text-sm text-gray-700">tampilkan nomor telepon di profil publik</span>
                        </label>
                    </div>
                    
                    <button type="submit" class="btn-primary mt-4">
                        simpan pengaturan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection