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
                <div class="text-center mb-8 fade-in-up">
                    <a href="{{ route('home') }}" class="inline-block mb-6">
                        <img src="{{ asset('kkn-go-logo.png') }}" alt="KKN-GO" class="h-16 w-auto">
                    </a>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">daftarkan instansi anda!</h1>
                    <p class="text-gray-600 text-lg">bergabung dengan ekosistem KKN Indonesia</p>
                </div>

                {{-- form container --}}
                <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden fade-in-scale" style="animation-delay: 0.1s;">
                    {{-- progress steps --}}
                    <div class="bg-gradient-to-br from-gray-50 to-white border-b-2 border-gray-100 px-8 py-6">
                        <div class="step-indicator-container">
                            <div class="step-item" id="step1-item">
                                <div class="step-circle active" id="step1-circle">
                                    <span class="step-number">1</span>
                                </div>
                                <span class="step-label">data instansi</span>
                            </div>
                            
                            <div class="step-connector" id="connector1"></div>
                            
                            <div class="step-item" id="step2-item">
                                <div class="step-circle inactive" id="step2-circle">
                                    <span class="step-number">2</span>
                                </div>
                                <span class="step-label">penanggung jawab</span>
                            </div>
                            
                            <div class="step-connector" id="connector2"></div>
                            
                            <div class="step-item" id="step3-item">
                                <div class="step-circle inactive" id="step3-circle">
                                    <span class="step-number">3</span>
                                </div>
                                <span class="step-label">akun & verifikasi</span>
                            </div>
                        </div>
                    </div>

                    {{-- form content --}}
                    <form method="POST" action="{{ route('register.institution') }}" 
                          enctype="multipart/form-data" 
                          id="institutionRegisterForm"
                          class="p-8">
                        @csrf

                        {{-- step 1: data instansi --}}
                        <div id="step1-content" class="step-content">
                            <div class="mb-8">
                                <h2 class="text-2xl font-bold text-gray-800 mb-2">data instansi</h2>
                                <p class="text-gray-600">informasi lengkap tentang instansi anda</p>
                            </div>

                            <div class="space-y-6">
                                {{-- nama instansi --}}
                                <div class="form-field-group">
                                    <label for="institution_name" class="form-label required">nama instansi</label>
                                    <div class="form-input-wrapper">
                                        <input type="text" 
                                               id="institution_name" 
                                               name="institution_name" 
                                               value="{{ old('institution_name') }}"
                                               placeholder="contoh: Desa Sukamaju"
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
                                    <label for="institution_type" class="form-label required">jenis instansi</label>
                                    <div class="form-input-wrapper">
                                        <select id="institution_type" 
                                                name="institution_type" 
                                                class="form-input form-select @error('institution_type') error @enderror"
                                                required>
                                            <option value="">-- pilih jenis instansi --</option>
                                            <option value="pemerintah_desa" {{ old('institution_type') == 'pemerintah_desa' ? 'selected' : '' }}>pemerintah desa</option>
                                            <option value="dinas" {{ old('institution_type') == 'dinas' ? 'selected' : '' }}>dinas</option>
                                            <option value="ngo" {{ old('institution_type') == 'ngo' ? 'selected' : '' }}>NGO / lembaga non-profit</option>
                                            <option value="puskesmas" {{ old('institution_type') == 'puskesmas' ? 'selected' : '' }}>puskesmas</option>
                                            <option value="sekolah" {{ old('institution_type') == 'sekolah' ? 'selected' : '' }}>sekolah</option>
                                            <option value="perguruan_tinggi" {{ old('institution_type') == 'perguruan_tinggi' ? 'selected' : '' }}>perguruan tinggi</option>
                                            <option value="lainnya" {{ old('institution_type') == 'lainnya' ? 'selected' : '' }}>lainnya</option>
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

                                {{-- provinsi & kabupaten --}}
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="form-field-group">
                                        <label for="province_id" class="form-label required">provinsi</label>
                                        <div class="form-input-wrapper">
                                            <select id="province_id" 
                                                    name="province_id" 
                                                    class="form-input form-select @error('province_id') error @enderror"
                                                    required>
                                                <option value="">-- pilih provinsi --</option>
                                                {{-- TODO: ambil dari database --}}
                                                <option value="1" {{ old('province_id') == 1 ? 'selected' : '' }}>Jawa Barat</option>
                                                <option value="2" {{ old('province_id') == 2 ? 'selected' : '' }}>Jawa Tengah</option>
                                                <option value="3" {{ old('province_id') == 3 ? 'selected' : '' }}>Jawa Timur</option>
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
                                        <label for="regency_id" class="form-label required">kabupaten/kota</label>
                                        <div class="form-input-wrapper">
                                            <select id="regency_id" 
                                                    name="regency_id" 
                                                    class="form-input form-select @error('regency_id') error @enderror"
                                                    required>
                                                <option value="">-- pilih kabupaten/kota --</option>
                                                {{-- TODO: ambil dari database berdasarkan provinsi --}}
                                                <option value="1" {{ old('regency_id') == 1 ? 'selected' : '' }}>Bandung</option>
                                                <option value="2" {{ old('regency_id') == 2 ? 'selected' : '' }}>Sumedang</option>
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

                                {{-- alamat lengkap --}}
                                <div class="form-field-group">
                                    <label for="address" class="form-label required">alamat lengkap</label>
                                    <div class="form-input-wrapper">
                                        <textarea id="address" 
                                                  name="address" 
                                                  rows="3"
                                                  placeholder="contoh: Jl. Raya Desa No. 123"
                                                  class="form-input @error('address') error @enderror"
                                                  required>{{ old('address') }}</textarea>
                                        <svg class="form-input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                        </svg>
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

                                {{-- email resmi --}}
                                <div class="form-field-group">
                                    <label for="official_email" class="form-label required">email resmi instansi</label>
                                    <div class="form-input-wrapper">
                                        <input type="email" 
                                               id="official_email" 
                                               name="official_email" 
                                               value="{{ old('official_email') }}"
                                               placeholder="contoh: info@desasukamaju.go.id"
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
                            </div>

                            {{-- navigation --}}
                            <div class="mt-8 flex justify-end">
                                <button type="button" onclick="nextStep(2)" class="btn btn-primary">
                                    lanjutkan
                                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        {{-- step 2: penanggung jawab --}}
                        <div id="step2-content" class="step-content hidden">
                            <div class="mb-8">
                                <h2 class="text-2xl font-bold text-gray-800 mb-2">penanggung jawab</h2>
                                <p class="text-gray-600">informasi kontak person in charge</p>
                            </div>

                            <div class="space-y-6">
                                {{-- nama pic --}}
                                <div class="form-field-group">
                                    <label for="pic_name" class="form-label required">nama penanggung jawab</label>
                                    <div class="form-input-wrapper">
                                        <input type="text" 
                                               id="pic_name" 
                                               name="pic_name" 
                                               value="{{ old('pic_name') }}"
                                               placeholder="contoh: Budi Santoso"
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

                                {{-- jabatan --}}
                                <div class="form-field-group">
                                    <label for="pic_position" class="form-label required">jabatan</label>
                                    <div class="form-input-wrapper">
                                        <input type="text" 
                                               id="pic_position" 
                                               name="pic_position" 
                                               value="{{ old('pic_position') }}"
                                               placeholder="contoh: Kepala Desa"
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

                                {{-- no telepon --}}
                                <div class="form-field-group">
                                    <label for="phone_number" class="form-label required">no. telepon</label>
                                    <div class="form-input-wrapper">
                                        <input type="tel" 
                                               id="phone_number" 
                                               name="phone_number" 
                                               value="{{ old('phone_number') }}"
                                               placeholder="contoh: 081234567890"
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

                                {{-- website (opsional) --}}
                                <div class="form-field-group">
                                    <label for="website" class="form-label">website (opsional)</label>
                                    <div class="form-input-wrapper">
                                        <input type="url" 
                                               id="website" 
                                               name="website" 
                                               value="{{ old('website') }}"
                                               placeholder="contoh: https://desasukamaju.go.id"
                                               class="form-input @error('website') error @enderror">
                                        <svg class="form-input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                                        </svg>
                                    </div>
                                    @error('website')
                                        <p class="error-message">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                {{-- deskripsi (opsional) --}}
                                <div class="form-field-group">
                                    <label for="description" class="form-label">deskripsi singkat (opsional)</label>
                                    <div class="form-input-wrapper">
                                        <textarea id="description" 
                                                  name="description" 
                                                  rows="4"
                                                  placeholder="ceritakan tentang instansi anda..."
                                                  class="form-input @error('description') error @enderror">{{ old('description') }}</textarea>
                                    </div>
                                    @error('description')
                                        <p class="error-message">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>

                            {{-- navigation --}}
                            <div class="mt-8 flex justify-between">
                                <button type="button" onclick="prevStep(1)" class="btn btn-secondary">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                    </svg>
                                    kembali
                                </button>
                                <button type="button" onclick="nextStep(3)" class="btn btn-primary">
                                    lanjutkan
                                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        {{-- step 3: akun & verifikasi --}}
                        <div id="step3-content" class="step-content hidden">
                            <div class="mb-8">
                                <h2 class="text-2xl font-bold text-gray-800 mb-2">akun & verifikasi</h2>
                                <p class="text-gray-600">buat akun dan upload dokumen verifikasi</p>
                            </div>

                            <div class="space-y-6">
                                {{-- username --}}
                                <div class="form-field-group">
                                    <label for="username" class="form-label required">username</label>
                                    <div class="form-input-wrapper">
                                        <input type="text" 
                                               id="username" 
                                               name="username" 
                                               value="{{ old('username') }}"
                                               placeholder="contoh: desa_sukamaju"
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
                                    <label for="password" class="form-label required">password</label>
                                    <div class="form-input-wrapper">
                                        <input type="password" 
                                               id="password" 
                                               name="password" 
                                               placeholder="minimal 8 karakter"
                                               class="form-input @error('password') error @enderror"
                                               required>
                                        <svg class="form-input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                        </svg>
                                        <button type="button" 
                                                onclick="togglePassword('password')" 
                                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                    <label for="password_confirmation" class="form-label required">konfirmasi password</label>
                                    <div class="form-input-wrapper">
                                        <input type="password" 
                                               id="password_confirmation" 
                                               name="password_confirmation" 
                                               placeholder="ketik ulang password"
                                               class="form-input"
                                               required>
                                        <svg class="form-input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                        </svg>
                                        <button type="button" 
                                                onclick="togglePassword('password_confirmation')" 
                                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                {{-- upload logo --}}
                                <div class="form-field-group">
                                    <label class="form-label">logo instansi (opsional)</label>
                                    <div class="file-upload-area mt-2">
                                        <input type="file" 
                                               id="logo" 
                                               name="logo" 
                                               accept="image/jpeg,image/jpg,image/png"
                                               class="hidden"
                                               onchange="previewLogo(event)">
                                        <label for="logo" class="cursor-pointer">
                                            <svg class="file-upload-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <p class="text-gray-600 font-medium" id="logoLabel">klik untuk upload logo</p>
                                            <p class="text-sm text-gray-500 mt-1">JPG, JPEG atau PNG (max. 2MB)</p>
                                        </label>
                                        <div id="logoPreview" class="mt-4 hidden">
                                            <img src="" alt="preview" class="mx-auto h-32 w-32 object-contain rounded-lg border">
                                        </div>
                                    </div>
                                    @error('logo')
                                        <p class="error-message mt-2">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                {{-- upload dokumen verifikasi --}}
                                <div class="form-field-group">
                                    <label class="form-label required">dokumen verifikasi</label>
                                    <div class="file-upload-area mt-2">
                                        <input type="file" 
                                               id="verification_document" 
                                               name="verification_document" 
                                               accept="application/pdf"
                                               class="hidden"
                                               onchange="previewDocument(event)"
                                               required>
                                        <label for="verification_document" class="cursor-pointer">
                                            <svg class="file-upload-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            <p class="text-gray-600 font-medium" id="docLabel">klik untuk upload dokumen</p>
                                            <p class="text-sm text-gray-500 mt-1">PDF (max. 5MB) - Surat keterangan resmi</p>
                                        </label>
                                    </div>
                                    @error('verification_document')
                                        <p class="error-message mt-2">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                {{-- terms --}}
                                <div class="form-field-group">
                                    <label class="flex items-start space-x-3">
                                        <input type="checkbox" 
                                               name="terms" 
                                               class="mt-1 h-4 w-4 text-green-600 border-gray-300 rounded focus:ring-green-500"
                                               required>
                                        <span class="text-sm text-gray-700">
                                            saya setuju dengan <a href="#" class="text-green-600 hover:text-green-700 font-medium">syarat dan ketentuan</a> serta <a href="#" class="text-green-600 hover:text-green-700 font-medium">kebijakan privasi</a>
                                        </span>
                                    </label>
                                </div>
                            </div>

                            {{-- navigation --}}
                            <div class="mt-8 flex justify-between">
                                <button type="button" onclick="prevStep(2)" class="btn btn-secondary">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                    </svg>
                                    kembali
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    daftar sekarang
                                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </form>

                    {{-- sudah punya akun --}}
                    <div class="px-8 pb-8">
                        <div class="text-center text-sm text-gray-600 mt-4">
                            sudah punya akun?
                            <a href="{{ route('login') }}" class="text-green-600 hover:text-green-700 font-medium">
                                login di sini
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- loading overlay --}}
        <div class="loading-overlay" id="loadingOverlay">
            <div class="bg-white rounded-lg p-8 text-center">
                <div class="spinner mx-auto mb-4" style="width: 3rem; height: 3rem;"></div>
                <p class="text-gray-700 font-medium">mendaftarkan instansi...</p>
                <p class="text-sm text-gray-500 mt-1">mohon tunggu sebentar</p>
            </div>
        </div>
    </div>

    <script>
    let currentStep = 1;

    // fungsi navigasi step
    function nextStep(step) {
        if (!validateStep(currentStep)) return;
        
        // hapus class active dari step saat ini
        const currentCircle = document.getElementById(`step${currentStep}-circle`);
        const currentContent = document.getElementById(`step${currentStep}-content`);
        
        currentContent.classList.add('hidden');
        currentCircle.classList.remove('active');
        currentCircle.classList.add('completed');
        
        // tambahkan class active ke step berikutnya
        const nextCircle = document.getElementById(`step${step}-circle`);
        const nextContent = document.getElementById(`step${step}-content`);
        
        nextContent.classList.remove('hidden');
        nextCircle.classList.remove('inactive');
        nextCircle.classList.add('active');
        
        // update connector jika maju
        if (currentStep < step) {
            document.getElementById(`connector${currentStep}`).classList.add('completed');
        }
        
        currentStep = step;
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function prevStep(step) {
        // hapus class dari step saat ini
        const currentCircle = document.getElementById(`step${currentStep}-circle`);
        const currentContent = document.getElementById(`step${currentStep}-content`);
        
        currentContent.classList.add('hidden');
        currentCircle.classList.remove('active');
        currentCircle.classList.add('inactive');
        
        // tambahkan class active ke step sebelumnya
        const prevCircle = document.getElementById(`step${step}-circle`);
        const prevContent = document.getElementById(`step${step}-content`);
        
        prevContent.classList.remove('hidden');
        prevCircle.classList.remove('completed');
        prevCircle.classList.add('active');
        
        // hapus completed dari connector jika mundur
        if (currentStep > step) {
            document.getElementById(`connector${step}`).classList.remove('completed');
        }
        
        currentStep = step;
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // validasi step
    function validateStep(step) {
        const form = document.getElementById('institutionRegisterForm');
        
        if (step === 1) {
            // validasi data instansi
            const requiredFields = ['institution_name', 'institution_type', 'province_id', 'regency_id', 'address', 'official_email'];
            for (let field of requiredFields) {
                const input = form.querySelector(`[name="${field}"]`);
                if (!input || !input.value.trim()) {
                    alert(`mohon lengkapi field ${field.replace('_', ' ')}`);
                    input?.focus();
                    return false;
                }
            }
        } else if (step === 2) {
            // validasi penanggung jawab
            const requiredFields = ['pic_name', 'pic_position', 'phone_number'];
            for (let field of requiredFields) {
                const input = form.querySelector(`[name="${field}"]`);
                if (!input || !input.value.trim()) {
                    alert(`mohon lengkapi field ${field.replace('_', ' ')}`);
                    input?.focus();
                    return false;
                }
            }
        }
        
        return true;
    }

    // toggle password visibility
    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        input.type = input.type === 'password' ? 'text' : 'password';
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
    </script>

    @vite(['resources/js/app.js'])
</body>
</html>