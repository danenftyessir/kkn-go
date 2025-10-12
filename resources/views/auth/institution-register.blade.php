{{-- resources/views/auth/institution-register.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daftar Sebagai Instansi - KKN-GO</title>
    
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth-institution.css') }}">
    
    {{-- CRITICAL FIX: tambahkan Alpine.js CDN untuk membuat dropdown dinamis bekerja --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    
    <style>
        /* tambahan style untuk background image */
        .register-container.institution-register::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url('{{ asset('institution-register-background.jpeg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            opacity: 0.15;
            z-index: 0;
        }
        
        .register-container.institution-register > * {
            position: relative;
            z-index: 1;
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50">
    {{-- navbar tetap --}}
    <nav class="fixed top-0 left-0 right-0 bg-white border-b border-gray-200 z-50">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <a href="{{ route('home') }}" class="inline-flex items-center text-gray-700 hover:text-gray-900 transition-colors font-semibold">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span class="font-medium">Kembali Ke Beranda</span>
            </a>
            
            <div class="flex items-center space-x-6">
                <a href="#" class="text-gray-600 hover:text-gray-900 font-medium transition-colors">Tentang</a>
                <a href="#" class="text-gray-600 hover:text-gray-900 font-medium transition-colors">Kontak</a>
            </div>
        </div>
    </nav>

    <div class="register-container institution-register" style="padding-top: 2rem;">
        <div class="relative z-10 flex items-center justify-center min-h-screen py-12 px-4">
            <div class="w-full max-w-4xl">
                {{-- logo & header --}}
                <div class="text-center mb-8 fade-in-up">
                    <a href="{{ route('home') }}" class="inline-block mb-6">
                        <img src="{{ asset('kkn-go-logo.png') }}" alt="KKN-GO Logo" class="h-16 mx-auto">
                    </a>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">Daftarkan Instansi Anda</h1>
                    <p class="text-lg text-gray-600">Bergabunglah dengan KKN-GO dan temukan mahasiswa terbaik untuk proyek Anda</p>
                    
                    {{-- sudah punya akun --}}
                    <div class="mt-6">
                        <span class="text-gray-600">Sudah punya akun? </span>
                        <a href="{{ route('login') }}" class="text-green-600 hover:text-green-700 font-semibold transition-colors">Masuk di sini</a>
                    </div>
                </div>

                {{-- main form card --}}
                <div class="register-card fade-in-up">
                    {{-- step indicator --}}
                    <div class="step-indicator">
                        <div class="step-track"></div>
                        <div class="flex justify-between relative z-10">
                            <div class="step active" data-step="1">
                                <div class="step-number-wrapper">
                                    <div class="step-number">1</div>
                                </div>
                                <span class="step-label">Data Instansi</span>
                            </div>
                            <div class="step" data-step="2">
                                <div class="step-number-wrapper">
                                    <div class="step-number">2</div>
                                </div>
                                <span class="step-label">Lokasi</span>
                            </div>
                            <div class="step" data-step="3">
                                <div class="step-number-wrapper">
                                    <div class="step-number">3</div>
                                </div>
                                <span class="step-label">Penanggung Jawab</span>
                            </div>
                            <div class="step" data-step="4">
                                <div class="step-number-wrapper">
                                    <div class="step-number">4</div>
                                </div>
                                <span class="step-label">Akun & Verifikasi</span>
                            </div>
                        </div>
                    </div>

                    {{-- form content --}}
                    <form method="POST" action="{{ route('register.institution.submit') }}" 
                            enctype="multipart/form-data" 
                            id="institutionRegisterForm"
                            class="p-8"
                            x-data="institutionForm('{{ route('api.public.regencies', ['provinceId' => 'PLACEHOLDER']) }}')">
                            @csrf

                        {{-- step 1: data instansi --}}
                        <div id="step1-content" class="step-content">
                            <div class="mb-8">
                                <h2 class="text-2xl font-bold text-gray-800 mb-2">Data Instansi</h2>
                                <p class="text-gray-600">Informasi lengkap tentang instansi Anda</p>
                            </div>

                            <div class="space-y-6">
                                {{-- nama instansi --}}
                                <div class="form-field-group">
                                    <label for="institution_name" class="form-label required">Nama Instansi</label>
                                    <div class="form-input-wrapper">
                                        <input type="text" 
                                               id="institution_name" 
                                               name="institution_name" 
                                               value="{{ old('institution_name') }}"
                                               placeholder="Contoh: Desa Sukamaju"
                                               class="form-input @error('institution_name') error @enderror"
                                               required>
                                        <svg class="form-input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                    </div>
                                    @error('institution_name')
                                        <p class="error-message">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                {{-- jenis instansi --}}
                                <div class="form-field-group">
                                    <label for="institution_type" class="form-label required">Jenis Instansi</label>
                                    <div class="form-input-wrapper">
                                        <select id="institution_type" 
                                                name="institution_type" 
                                                class="form-input form-select @error('institution_type') error @enderror"
                                                required>
                                            <option value="">-- Pilih Jenis Instansi --</option>
                                            <option value="pemerintah_desa" {{ old('institution_type') == 'pemerintah_desa' ? 'selected' : '' }}>Pemerintah Desa</option>
                                            <option value="dinas" {{ old('institution_type') == 'dinas' ? 'selected' : '' }}>Dinas</option>
                                            <option value="ngo" {{ old('institution_type') == 'ngo' ? 'selected' : '' }}>NGO / Lembaga Non-profit</option>
                                            <option value="puskesmas" {{ old('institution_type') == 'puskesmas' ? 'selected' : '' }}>Puskesmas</option>
                                            <option value="sekolah" {{ old('institution_type') == 'sekolah' ? 'selected' : '' }}>Sekolah</option>
                                            <option value="perguruan_tinggi" {{ old('institution_type') == 'perguruan_tinggi' ? 'selected' : '' }}>Perguruan Tinggi</option>
                                            <option value="lainnya" {{ old('institution_type') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                                        </select>
                                        <svg class="form-input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                    </div>
                                    @error('institution_type')
                                        <p class="error-message">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                {{-- email instansi --}}
                                <div class="form-field-group">
                                    <label for="official_email" class="form-label required">Email Resmi Instansi</label>
                                    <div class="form-input-wrapper">
                                        <input type="email" 
                                               id="official_email" 
                                               name="official_email" 
                                               value="{{ old('official_email') }}"
                                               placeholder="Contoh: info@desasukamaju.go.id"
                                               class="form-input @error('official_email') error @enderror"
                                               required>
                                        <svg class="form-input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    @error('official_email')
                                        <p class="error-message">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                {{-- no telepon --}}
                                <div class="form-field-group">
                                    <label for="phone_number" class="form-label required">Nomor Telepon</label>
                                    <div class="form-input-wrapper">
                                        <input type="tel" 
                                               id="phone_number" 
                                               name="phone_number" 
                                               value="{{ old('phone_number') }}"
                                               placeholder="Contoh: 081234567890"
                                               class="form-input @error('phone_number') error @enderror"
                                               required>
                                        <svg class="form-input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                        </svg>
                                    </div>
                                    @error('phone_number')
                                        <p class="error-message">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>

                            <div class="flex justify-end mt-8">
                                <button type="button" onclick="nextStep(2)" class="btn-primary">
                                    Selanjutnya
                                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        {{-- step 2: lokasi --}}
                        <div id="step2-content" class="step-content" style="display: none;">
                            <div class="mb-8">
                                <h2 class="text-2xl font-bold text-gray-800 mb-2">Lokasi</h2>
                                <p class="text-gray-600">Informasi lokasi instansi Anda</p>
                            </div>

                            <div class="space-y-6">
                                {{-- alamat lengkap --}}
                                <div class="form-field-group">
                                    <label for="address" class="form-label required">Alamat Lengkap</label>
                                    <div class="form-input-wrapper">
                                        <textarea id="address" 
                                                  name="address" 
                                                  rows="3"
                                                  placeholder="Contoh: Jl. Raya Sukamaju No. 123"
                                                  class="form-input @error('address') error @enderror"
                                                  required>{{ old('address') }}</textarea>
                                    </div>
                                    @error('address')
                                        <p class="error-message">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                {{-- provinsi & kabupaten --}}
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="form-field-group">
                                        <label for="province_id" class="form-label required">Provinsi</label>
                                        <div class="form-input-wrapper">
                                            {{-- CRITICAL FIX: tambahkan x-model dan @change untuk trigger loadRegencies --}}
                                            <select id="province_id" 
                                                    name="province_id" 
                                                    class="form-input form-select @error('province_id') error @enderror"
                                                    x-model="provinceId"
                                                    @change="loadRegencies()"
                                                    required>
                                                <option value="">-- Pilih Provinsi --</option>
                                                @foreach($provinces as $province)
                                                    <option value="{{ $province->id }}">{{ $province->name }}</option>
                                                @endforeach
                                            </select>
                                            <svg class="form-input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                        </div>
                                        @error('province_id')
                                            <p class="error-message">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <div class="form-field-group">
                                        <label for="regency_id" class="form-label required">Kabupaten/Kota</label>
                                        <div class="form-input-wrapper">
                                            {{-- CRITICAL FIX: gunakan Alpine.js template untuk render options secara dinamis --}}
                                            <select id="regency_id" 
                                                    name="regency_id" 
                                                    class="form-input form-select @error('regency_id') error @enderror"
                                                    x-model="regencyId"
                                                    :disabled="loadingRegencies || !provinceId"
                                                    required>
                                                <template x-if="loadingRegencies">
                                                    <option value="">Memuat...</option>
                                                </template>
                                                <template x-if="!loadingRegencies && !provinceId">
                                                    <option value="">-- Pilih Provinsi Terlebih Dahulu --</option>
                                                </template>
                                                <template x-if="!loadingRegencies && provinceId">
                                                    <option value="">-- Pilih Kabupaten/Kota --</option>
                                                </template>
                                                <template x-for="regency in regencies" :key="regency.id">
                                                    <option :value="regency.id" x-text="regency.name"></option>
                                                </template>
                                            </select>
                                            <svg class="form-input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                        </div>
                                        @error('regency_id')
                                            <p class="error-message">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-between mt-8">
                                <button type="button" onclick="prevStep(1)" class="btn-secondary">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                    </svg>
                                    Kembali
                                </button>
                                <button type="button" onclick="nextStep(3)" class="btn-primary">
                                    Selanjutnya
                                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        {{-- step 3: penanggung jawab --}}
                        <div id="step3-content" class="step-content" style="display: none;">
                            <div class="mb-8">
                                <h2 class="text-2xl font-bold text-gray-800 mb-2">Penanggung Jawab</h2>
                                <p class="text-gray-600">Informasi person in charge (PIC) instansi</p>
                            </div>

                            <div class="space-y-6">
                                {{-- nama PIC --}}
                                <div class="form-field-group">
                                    <label for="pic_name" class="form-label required">Nama Lengkap PIC</label>
                                    <div class="form-input-wrapper">
                                        <input type="text" 
                                               id="pic_name" 
                                               name="pic_name" 
                                               value="{{ old('pic_name') }}"
                                               placeholder="Contoh: Budi Santoso"
                                               class="form-input @error('pic_name') error @enderror"
                                               required>
                                        <svg class="form-input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    @error('pic_name')
                                        <p class="error-message">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                {{-- jabatan PIC --}}
                                <div class="form-field-group">
                                    <label for="pic_position" class="form-label required">Jabatan PIC</label>
                                    <div class="form-input-wrapper">
                                        <input type="text" 
                                               id="pic_position" 
                                               name="pic_position" 
                                               value="{{ old('pic_position') }}"
                                               placeholder="Contoh: Kepala Desa"
                                               class="form-input @error('pic_position') error @enderror"
                                               required>
                                        <svg class="form-input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    @error('pic_position')
                                        <p class="error-message">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>

                            <div class="flex justify-between mt-8">
                                <button type="button" onclick="prevStep(2)" class="btn-secondary">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                    </svg>
                                    Kembali
                                </button>
                                <button type="button" onclick="nextStep(4)" class="btn-primary">
                                    Selanjutnya
                                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        {{-- step 4: akun & verifikasi --}}
                        <div id="step4-content" class="step-content" style="display: none;">
                            <div class="mb-8">
                                <h2 class="text-2xl font-bold text-gray-800 mb-2">Akun & Verifikasi</h2>
                                <p class="text-gray-600">Buat akun dan upload dokumen verifikasi</p>
                            </div>

                            <div class="space-y-6">
                                {{-- username --}}
                                <div class="form-field-group">
                                    <label for="username" class="form-label required">Username</label>
                                    <div class="form-input-wrapper">
                                        <input type="text" 
                                               id="username" 
                                               name="username" 
                                               value="{{ old('username') }}"
                                               placeholder="Contoh: desasukamaju"
                                               class="form-input @error('username') error @enderror"
                                               required>
                                        <svg class="form-input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    @error('username')
                                        <p class="error-message">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                {{-- password --}}
                                <div class="form-field-group">
                                    <label for="password" class="form-label required">Password</label>
                                    <div class="form-input-wrapper">
                                        <input type="password" 
                                               id="password" 
                                               name="password" 
                                               placeholder="Minimal 8 karakter"
                                               class="form-input @error('password') error @enderror"
                                               required>
                                        <button type="button" 
                                                onclick="togglePassword('password')" 
                                                class="form-input-icon cursor-pointer hover:text-gray-900"
                                                tabindex="-1">
                                            <svg class="eye-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    @error('password')
                                        <p class="error-message">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                {{-- konfirmasi password --}}
                                <div class="form-field-group">
                                    <label for="password_confirmation" class="form-label required">Konfirmasi Password</label>
                                    <div class="form-input-wrapper">
                                        <input type="password" 
                                               id="password_confirmation" 
                                               name="password_confirmation" 
                                               placeholder="Ulangi password"
                                               class="form-input"
                                               required>
                                        <button type="button" 
                                                onclick="togglePassword('password_confirmation')" 
                                                class="form-input-icon cursor-pointer hover:text-gray-900"
                                                tabindex="-1">
                                            <svg class="eye-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                {{-- upload logo --}}
                                <div class="form-field-group">
                                    <label for="logo" class="form-label">Logo Instansi</label>
                                    <div class="file-upload-area">
                                        <input type="file" 
                                               id="logo" 
                                               name="logo" 
                                               accept="image/*"
                                               class="file-input"
                                               onchange="previewLogo(event)">
                                        <div class="file-upload-content">
                                            <svg class="w-12 h-12 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <p class="file-upload-text">
                                                <label for="logo" class="file-upload-label" id="logoLabel">
                                                    Klik untuk upload atau drag & drop
                                                </label>
                                            </p>
                                            <p class="file-upload-hint">PNG, JPG, atau JPEG (Maks. 2MB)</p>
                                        </div>
                                        <div id="logoPreview" class="hidden mt-4">
                                            <img src="" alt="Logo preview" class="max-h-32 mx-auto rounded">
                                        </div>
                                    </div>
                                    @error('logo')
                                        <p class="error-message">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                {{-- upload dokumen verifikasi --}}
                                <div class="form-field-group">
                                    <label for="verification_document" class="form-label required">Dokumen Verifikasi</label>
                                    <div class="file-upload-area">
                                        <input type="file" 
                                               id="verification_document" 
                                               name="verification_document" 
                                               accept=".pdf,.doc,.docx"
                                               class="file-input"
                                               onchange="previewDocument(event)"
                                               required>
                                        <div class="file-upload-content">
                                            <svg class="w-12 h-12 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            <p class="file-upload-text">
                                                <label for="verification_document" class="file-upload-label" id="docLabel">
                                                    Klik untuk upload atau drag & drop
                                                </label>
                                            </p>
                                            <p class="file-upload-hint">PDF atau DOC (Surat Keterangan Resmi)</p>
                                        </div>
                                    </div>
                                    @error('verification_document')
                                        <p class="error-message">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                {{-- terms & conditions --}}
                                <div class="form-field-group">
                                    <label class="flex items-start">
                                        <input type="checkbox" 
                                               name="terms" 
                                               class="mt-1 rounded border-gray-300 text-green-600 focus:ring-green-500"
                                               required>
                                        <span class="ml-3 text-sm text-gray-700">
                                            Saya setuju dengan <a href="#" class="text-green-600 hover:text-green-700 font-semibold">syarat dan ketentuan</a> serta <a href="#" class="text-green-600 hover:text-green-700 font-semibold">kebijakan privasi</a> KKN-GO
                                        </span>
                                    </label>
                                </div>
                            </div>

                            <div class="flex justify-between mt-8">
                                <button type="button" onclick="prevStep(3)" class="btn-secondary">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                    </svg>
                                    Kembali
                                </button>
                                <button type="submit" class="btn-primary">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Daftar Sekarang
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- loading overlay --}}
    <div id="loadingOverlay" class="loading-overlay">
        <div class="loading-spinner"></div>
        <p class="loading-text">Sedang memproses pendaftaran...</p>
    </div>

    <script>
    // multi-step form navigation
    let currentStep = 1;

    function showStep(step) {
        // sembunyikan semua step content
        document.querySelectorAll('.step-content').forEach(content => {
            content.style.display = 'none';
        });
        
        // tampilkan step yang dipilih
        document.getElementById(`step${step}-content`).style.display = 'block';
        
        // update step indicator
        document.querySelectorAll('.step').forEach((stepEl, index) => {
            if (index + 1 <= step) {
                stepEl.classList.add('active');
            } else {
                stepEl.classList.remove('active');
            }
            
            if (index + 1 < step) {
                stepEl.classList.add('completed');
            } else {
                stepEl.classList.remove('completed');
            }
        });
        
        currentStep = step;
    }

    function nextStep(step) {
        showStep(step);
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function prevStep(step) {
        showStep(step);
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // toggle password visibility
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        field.type = field.type === 'password' ? 'text' : 'password';
    }

    // preview logo upload
    function previewLogo(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('logoPreview');
        const label = document.getElementById('logoLabel');
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.querySelector('img').src = e.target.result;
                preview.classList.remove('hidden');
                label.textContent = file.name;
            }
            reader.readAsDataURL(file);
        }
    }

    // preview document upload
    function previewDocument(event) {
        const file = event.target.files[0];
        const label = document.getElementById('docLabel');
        
        if (file) {
            label.textContent = file.name;
        }
    }

    // handle form submission
    document.getElementById('institutionRegisterForm')?.addEventListener('submit', function(e) {
        const loadingOverlay = document.getElementById('loadingOverlay');
        if (loadingOverlay) {
            loadingOverlay.classList.add('active');
        }
    });

    // CRITICAL FIX: Alpine.js component untuk handle dropdown dinamis provinsi-kabupaten
    function institutionForm(regenciesUrlTemplate) {
        return {
            provinceId: '{{ old("province_id") }}',
            regencyId: '{{ old("regency_id") }}',
            regencies: {!! $regencies->toJson() !!},
            loadingRegencies: false,

            init() {
                // memastikan dropdown regency tetap terisi setelah validasi gagal
                this.$watch('regencies', () => {
                    setTimeout(() => {
                        if (this.regencyId) {
                            this.$el.querySelector('#regency_id').value = this.regencyId;
                        }
                    }, 50);
                });
                
                // jika sudah ada provinceId dari old input, load regencies-nya
                if (this.provinceId) {
                    this.loadRegencies();
                }
            },

            async loadRegencies() {
                // reset pilihan regency
                this.regencyId = '';
                
                if (!this.provinceId) {
                    this.regencies = [];
                    return;
                }

                this.loadingRegencies = true;
                
                // replace PLACEHOLDER dengan provinceId yang dipilih
                const url = regenciesUrlTemplate.replace('PLACEHOLDER', this.provinceId);

                try {
                    const response = await fetch(url);
                    
                    if (!response.ok) {
                        throw new Error('Gagal mengambil data kabupaten/kota');
                    }
                    
                    const data = await response.json();
                    this.regencies = data;
                } catch (error) {
                    console.error('Error:', error);
                    alert('Gagal memuat data kabupaten/kota. Silakan coba lagi.');
                    this.regencies = [];
                } finally {
                    this.loadingRegencies = false;
                }
            }
        };
    }
    </script>

    @vite(['resources/js/app.js'])
</body>
</html>