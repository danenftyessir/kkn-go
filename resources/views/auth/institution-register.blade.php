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
    
    {{-- tambahkan Alpine.js CDN untuk membuat dropdown dinamis bekerja --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    
    <style>
        /* background image dengan opacity yang lebih tinggi */
        .register-container.institution-register {
            position: relative;
            min-height: 100vh;
        }
        
        .register-container.institution-register::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url('{{ asset('institution-register-background.jpeg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            opacity: 0.35;
            z-index: 0;
            pointer-events: none;
        }
        
        .register-container.institution-register > * {
            position: relative;
            z-index: 1;
        }
        
        /* MODERN STEP INDICATOR - Minimalist Glass Effect */
        .step-indicator {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 1.5rem 1.5rem;
            border-radius: 1rem 1rem 0 0;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
        }
        
        /* Modern Step Numbers */
        .step-number-wrapper {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            border: 2px solid rgba(156, 163, 175, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            backdrop-filter: blur(10px);
        }
        
        .step.active .step-number-wrapper {
            background: linear-gradient(135deg, #10b981 0%, #14b8a6 100%);
            border-color: transparent;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
            transform: scale(1.1);
        }
        
        .step.completed .step-number-wrapper {
            background: #10b981;
            border-color: transparent;
        }
        
        .step-number {
            color: #6B7280;
            font-weight: 600;
            font-size: 0.875rem;
        }
        
        .step.active .step-number,
        .step.completed .step-number {
            color: white;
        }
        
        /* Modern Step Labels */
        .step-label {
            color: #6B7280 !important;
            font-weight: 500;
            font-size: 0.75rem;
            margin-top: 0.5rem;
            transition: all 0.3s ease;
        }
        
        .step.active .step-label {
            color: #10b981 !important;
            font-weight: 600;
        }
        
        /* Step Track Line - PERBAIKAN: Presisi pas dengan lingkaran step */
        .step-track {
            position: absolute;
            top: 1.25rem; /* 2.5rem (height circle) / 2 = 1.25rem */
            left: calc(25% + 1.25rem);
            right: calc(25% + 1.25rem);
            height: 2px;
            background: rgba(156, 163, 175, 0.2);
            z-index: 0;
        }
        
        /* Container step indicator */
        .step-indicator > div:last-child {
            position: relative;
        }
        
        /* Individual step positioning */
        .step {
            position: relative;
            z-index: 1;
        }
        
        /* GLASS MORPHISM CARD */
        .register-card {
            background: rgba(255, 255, 255, 0.25) !important;
            backdrop-filter: blur(30px) saturate(180%);
            -webkit-backdrop-filter: blur(30px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.4);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15) !important;
        }
        
        /* Form content background */
        .register-card .p-8 {
            background: rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(10px);
            border-radius: 0 0 1rem 1rem;
        }
        
        /* MODERN BUTTONS */
        .btn-primary {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
            border: none !important;
            color: white !important;
            font-weight: 600;
            padding: 0.75rem 1.5rem !important;
            border-radius: 0.75rem !important;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3) !important;
            position: relative;
            z-index: 10;
        }
        
        .btn-primary:hover:not(:disabled) {
            background: linear-gradient(135deg, #059669 0%, #047857 100%) !important;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4) !important;
        }
        
        .btn-primary:active:not(:disabled) {
            transform: translateY(0);
        }
        
        .btn-secondary {
            background: rgba(255, 255, 255, 0.3) !important;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(16, 185, 129, 0.5) !important;
            color: #059669 !important;
            font-weight: 600;
            padding: 0.75rem 1.5rem !important;
            border-radius: 0.75rem !important;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            z-index: 10;
        }
        
        .btn-secondary:hover {
            background: rgba(16, 185, 129, 0.1) !important;
            border-color: #10b981 !important;
            color: #047857 !important;
            transform: translateY(-2px);
        }
        
        /* Form inputs dengan glass effect */
        .form-input {
            background: rgba(255, 255, 255, 0.7) !important;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(209, 213, 219, 0.5) !important;
            transition: all 0.3s ease;
        }
        
        .form-input:focus {
            background: rgba(255, 255, 255, 0.9) !important;
            border-color: #10b981 !important;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }
        
        /* Step content title */
        .step-content h2 {
            color: #1F2937;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        
        .step-content p {
            color: #4B5563;
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
                <span class="font-medium">kembali ke beranda</span>
            </a>
            
            <div class="flex items-center space-x-6">
                <a href="#" class="text-gray-600 hover:text-gray-900 font-medium transition-colors">tentang</a>
                <a href="#" class="text-gray-600 hover:text-gray-900 font-medium transition-colors">kontak</a>
            </div>
        </div>
    </nav>

    <div class="register-container institution-register" style="padding-top: 5rem;">
        <div class="relative z-10 flex items-center justify-center min-h-screen py-12 px-4">
            <div class="w-full max-w-4xl">
                {{-- logo & header --}}
                <div class="text-center mb-8">
                    <img src="{{ asset('kkn-go-logo.png') }}" alt="KKN-GO Logo" class="h-24 mx-auto mb-6">
                    <h1 class="text-4xl font-bold text-gray-900 mb-3">Daftar Sebagai Instansi</h1>
                    <p class="text-gray-600 mb-4">Mulai posting masalah dan temukan mahasiswa KKN terbaik</p>
                    <div class="inline-flex items-center gap-2 text-sm">
                        <span class="text-gray-600">Sudah punya akun?</span>
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
                    <form method="POST" 
                          action="{{ route('register.institution.submit') }}" 
                          enctype="multipart/form-data" 
                          id="institutionRegisterForm"
                          class="p-8"
                          x-data="institutionForm()">
                        @csrf

                        {{-- STEP 1: Data Instansi --}}
                        <div id="step1-content" class="step-content">
                            <div class="mb-8">
                                <h2 class="text-2xl font-bold text-gray-800 mb-2">Data Instansi</h2>
                                <p class="text-gray-600">Informasi lengkap tentang instansi Anda</p>
                            </div>

                            <div class="space-y-6">
                                {{-- nama instansi --}}
                                <div>
                                    <label for="institution_name" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Nama Instansi <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           id="institution_name" 
                                           name="institution_name" 
                                           value="{{ old('institution_name') }}"
                                           placeholder="Contoh: Desa Sukamaju"
                                           class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition @error('institution_name') border-red-500 @enderror"
                                           required>
                                    @error('institution_name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- jenis instansi --}}
                                <div>
                                    <label for="institution_type" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Jenis Instansi <span class="text-red-500">*</span>
                                    </label>
                                    <select id="institution_type" 
                                            name="institution_type" 
                                            class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition @error('institution_type') border-red-500 @enderror"
                                            required>
                                        <option value="">Pilih Jenis Instansi</option>
                                        <option value="pemerintah_desa" {{ old('institution_type') == 'pemerintah_desa' ? 'selected' : '' }}>Pemerintah Desa</option>
                                        <option value="dinas" {{ old('institution_type') == 'dinas' ? 'selected' : '' }}>Dinas</option>
                                        <option value="ngo" {{ old('institution_type') == 'ngo' ? 'selected' : '' }}>NGO</option>
                                        <option value="puskesmas" {{ old('institution_type') == 'puskesmas' ? 'selected' : '' }}>Puskesmas</option>
                                        <option value="sekolah" {{ old('institution_type') == 'sekolah' ? 'selected' : '' }}>Sekolah</option>
                                        <option value="perguruan_tinggi" {{ old('institution_type') == 'perguruan_tinggi' ? 'selected' : '' }}>Perguruan Tinggi</option>
                                        <option value="lainnya" {{ old('institution_type') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                                    </select>
                                    @error('institution_type')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- email resmi --}}
                                <div>
                                    <label for="official_email" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Email Resmi Instansi <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email" 
                                           id="official_email" 
                                           name="official_email" 
                                           value="{{ old('official_email') }}"
                                           placeholder="Contoh: info@desasukamaju.go.id"
                                           class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition @error('official_email') border-red-500 @enderror"
                                           required>
                                    @error('official_email')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="flex justify-end mt-8">
                                <button type="button" onclick="nextStep(2)" class="btn-primary">
                                    Selanjutnya
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        {{-- STEP 2: Lokasi --}}
                        <div id="step2-content" class="step-content" style="display: none;">
                            <div class="mb-8">
                                <h2 class="text-2xl font-bold text-gray-800 mb-2">Lokasi</h2>
                                <p class="text-gray-600">Informasi lokasi instansi Anda</p>
                            </div>

                            <div class="space-y-6">
                                {{-- alamat lengkap --}}
                                <div>
                                    <label for="address" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Alamat Lengkap <span class="text-red-500">*</span>
                                    </label>
                                    <textarea id="address" 
                                              name="address" 
                                              rows="3"
                                              placeholder="Contoh: Jl. Raya Sukamaju No. 123"
                                              class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition @error('address') border-red-500 @enderror"
                                              required>{{ old('address') }}</textarea>
                                    @error('address')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- provinsi --}}
                                <div>
                                    <label for="province_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Provinsi <span class="text-red-500">*</span>
                                    </label>
                                    <select id="province_id" 
                                            name="province_id" 
                                            class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition @error('province_id') border-red-500 @enderror"
                                            x-model="selectedProvince"
                                            @change="loadRegencies()"
                                            required>
                                        <option value="">Pilih Provinsi</option>
                                        @foreach($provinces as $province)
                                            <option value="{{ $province->id }}" {{ old('province_id') == $province->id ? 'selected' : '' }}>
                                                {{ $province->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('province_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- kabupaten/kota --}}
                                <div>
                                    <label for="regency_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Kabupaten/Kota <span class="text-red-500">*</span>
                                    </label>
                                    <select id="regency_id" 
                                            name="regency_id" 
                                            class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition @error('regency_id') border-red-500 @enderror"
                                            x-model="selectedRegency"
                                            :disabled="!selectedProvince"
                                            required>
                                        <option value="">Pilih Kabupaten/Kota</option>
                                        <template x-for="regency in regencies" :key="regency.id">
                                            <option :value="regency.id" x-text="regency.name"></option>
                                        </template>
                                    </select>
                                    @error('regency_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="flex justify-between mt-8">
                                <button type="button" onclick="prevStep(1)" class="btn-secondary">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                    </svg>
                                    Kembali
                                </button>
                                <button type="button" onclick="nextStep(3)" class="btn-primary">
                                    Selanjutnya
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        {{-- STEP 3: Penanggung Jawab (disingkat untuk brevity, lengkap di kode asli) --}}
                        <div id="step3-content" class="step-content" style="display: none;">
                            {{-- Content lengkap seperti file asli --}}
                            <div class="mb-8">
                                <h2 class="text-2xl font-bold text-gray-800 mb-2">Penanggung Jawab</h2>
                                <p class="text-gray-600">Informasi person in charge (PIC) instansi</p>
                            </div>
                            <div class="space-y-6">
                                <div>
                                    <label for="pic_name" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Nama Lengkap PIC <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="pic_name" name="pic_name" value="{{ old('pic_name') }}" placeholder="Contoh: Budi Santoso" class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg" required>
                                    @error('pic_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="pic_position" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Jabatan PIC <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="pic_position" name="pic_position" value="{{ old('pic_position') }}" placeholder="Contoh: Kepala Desa" class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg" required>
                                    @error('pic_position')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="phone_number" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Nomor Telepon <span class="text-red-500">*</span>
                                    </label>
                                    <input type="tel" id="phone_number" name="phone_number" value="{{ old('phone_number') }}" placeholder="Contoh: 081234567890" class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg" required>
                                    @error('phone_number')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="logo" class="block text-sm font-semibold text-gray-700 mb-2">Logo Instansi (Opsional)</label>
                                    <input type="file" id="logo" name="logo" accept="image/*" class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg">
                                </div>
                                <div>
                                    <label for="verification_document" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Dokumen Verifikasi <span class="text-red-500">*</span>
                                    </label>
                                    <input type="file" id="verification_document" name="verification_document" accept="application/pdf" class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg" required>
                                </div>
                                <div>
                                    <label for="website" class="block text-sm font-semibold text-gray-700 mb-2">Website (Opsional)</label>
                                    <input type="url" id="website" name="website" value="{{ old('website') }}" placeholder="https://www.contoh.go.id" class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg">
                                </div>
                                <div>
                                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi Instansi (Opsional)</label>
                                    <textarea id="description" name="description" rows="4" placeholder="Ceritakan singkat tentang instansi Anda..." class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg">{{ old('description') }}</textarea>
                                </div>
                            </div>

                            <div class="flex justify-between mt-8">
                                <button type="button" onclick="prevStep(2)" class="btn-secondary">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                    </svg>
                                    Kembali
                                </button>
                                <button type="button" onclick="nextStep(4)" class="btn-primary">
                                    Selanjutnya
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        {{-- STEP 4: Akun & Verifikasi --}}
                        <div id="step4-content" class="step-content" style="display: none;">
                            <div class="mb-8">
                                <h2 class="text-2xl font-bold text-gray-800 mb-2">Akun & Verifikasi</h2>
                                <p class="text-gray-600">Buat akun untuk login ke sistem</p>
                            </div>
                            <div class="space-y-6">
                                <div>
                                    <label for="username" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Username <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="username" name="username" value="{{ old('username') }}" placeholder="Contoh: desasukamaju" class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg" required>
                                    @error('username')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Password <span class="text-red-500">*</span>
                                    </label>
                                    <input type="password" id="password" name="password" placeholder="Minimal 8 karakter" class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg" required>
                                    @error('password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Konfirmasi Password <span class="text-red-500">*</span>
                                    </label>
                                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Ulangi password" class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg" required>
                                </div>
                                <div class="flex items-start">
                                    <input type="checkbox" id="terms" name="terms" class="mt-1 mr-3" required>
                                    <label for="terms" class="text-sm text-gray-700">
                                        Saya setuju dengan <a href="#" class="text-green-600 hover:text-green-700 font-semibold">syarat dan ketentuan</a> serta <a href="#" class="text-green-600 hover:text-green-700 font-semibold">kebijakan privasi</a> KKN-GO
                                    </label>
                                </div>
                            </div>

                            <div class="flex justify-between mt-8">
                                <button type="button" onclick="prevStep(3)" class="btn-secondary">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                    </svg>
                                    Kembali
                                </button>
                                <button type="submit" class="btn-primary">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
    <div id="loadingOverlay" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.7); z-index: 9999; justify-content: center; align-items: center; flex-direction: column;">
        <div style="width: 60px; height: 60px; border: 4px solid rgba(255,255,255,0.3); border-top-color: #10b981; border-radius: 50%; animation: spin 1s linear infinite;"></div>
        <p style="color: white; margin-top: 1rem; font-size: 1.125rem;">Sedang memproses pendaftaran...</p>
    </div>
    <style>@keyframes spin { to { transform: rotate(360deg); } }</style>

    <script>
    // multi-step navigation dengan validasi AJAX
    let currentStep = 1;

    // PERBAIKAN: handle 4 steps dengan validasi AJAX per-step
    async function nextStep(step) {
        const form = document.getElementById('institutionRegisterForm');
        const formData = new FormData(form);
        formData.append('step', currentStep);

        const loadingOverlay = document.getElementById('loadingOverlay');
        loadingOverlay.style.display = 'flex';

        try {
            const response = await fetch("{{ route('api.public.validate.institution.step') }}", {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: formData
            });

            if (!response.ok) {
                if (response.status === 422) {
                    const data = await response.json();
                    alert('Harap lengkapi semua field yang wajib diisi');
                } else {
                    alert('Terjadi kesalahan pada server. Silakan coba lagi.');
                }
                loadingOverlay.style.display = 'none';
                return;
            }

            // validasi berhasil, pindah ke step selanjutnya
            showStep(step);
            currentStep = step;
            window.scrollTo({ top: 0, behavior: 'smooth' });
            loadingOverlay.style.display = 'none';

        } catch (error) {
            console.error('Validation error:', error);
            alert('Tidak dapat terhubung ke server. Periksa koneksi internet Anda.');
            loadingOverlay.style.display = 'none';
        }
    }

    function prevStep(step) {
        showStep(step);
        currentStep = step;
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // PERBAIKAN: showStep untuk handle 4 steps
    function showStep(step) {
        // hide semua step content
        for (let i = 1; i <= 4; i++) {
            const content = document.getElementById(`step${i}-content`);
            if (content) content.style.display = 'none';
        }

        // tampilkan step saat ini
        const currentContent = document.getElementById(`step${step}-content`);
        if (currentContent) currentContent.style.display = 'block';

        // update step indicators
        const steps = document.querySelectorAll('.step');
        steps.forEach((stepEl, index) => {
            const stepNumber = index + 1;
            
            stepEl.classList.remove('active', 'completed');
            
            if (stepNumber < step) {
                stepEl.classList.add('completed');
            } else if (stepNumber === step) {
                stepEl.classList.add('active');
            }
        });
    }

    // Alpine.js untuk dynamic province-regency dropdown
    function institutionForm() {
        return {
            selectedProvince: '{{ old("province_id") }}',
            selectedRegency: '{{ old("regency_id") }}',
            regencies: @json($regencies),
            
            async loadRegencies() {
                if (!this.selectedProvince) {
                    this.regencies = [];
                    this.selectedRegency = '';
                    return;
                }

                try {
                    const response = await fetch(`/api/public/regencies/${this.selectedProvince}`);
                    if (response.ok) {
                        this.regencies = await response.json();
                    }
                } catch (error) {
                    console.error('Failed to load regencies:', error);
                    alert('Gagal memuat data kabupaten/kota. Silakan coba lagi.');
                }
            }
        }
    }

    // form submit handler
    document.getElementById('institutionRegisterForm')?.addEventListener('submit', function(e) {
        const loadingOverlay = document.getElementById('loadingOverlay');
        loadingOverlay.style.display = 'flex';
    });

    // handle error dari server saat page load
    @if($errors->any())
        const errorFields = @json($errors->keys());
        const step1Fields = ['institution_name', 'institution_type', 'official_email'];
        const step2Fields = ['address', 'province_id', 'regency_id'];
        const step3Fields = ['pic_name', 'pic_position', 'phone_number', 'logo', 'verification_document', 'website', 'description'];
        const step4Fields = ['username', 'password', 'password_confirmation'];
        
        let errorStep = 1;
        if (errorFields.some(field => step4Fields.includes(field))) {
            errorStep = 4;
        } else if (errorFields.some(field => step3Fields.includes(field))) {
            errorStep = 3;
        } else if (errorFields.some(field => step2Fields.includes(field))) {
            errorStep = 2;
        }
        
        showStep(errorStep);
        currentStep = errorStep;
    @endif
    </script>
</body>
</html>