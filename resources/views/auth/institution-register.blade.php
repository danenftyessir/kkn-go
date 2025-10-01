@extends('layouts.auth')

@section('title', 'Daftar Sebagai Instansi - KKN-GO')

@push('styles')
<style>
    .register-container {
        min-height: 100vh;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 30%, #48c6ef 70%, #6f86d6 100%);
        position: relative;
        overflow: hidden;
    }
    
    .register-container::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, transparent 50%, rgba(72, 198, 239, 0.1) 100%);
    }
    
    .form-container {
        max-width: 1000px;
        margin: 0 auto;
    }
    
    .progress-bar {
        background: linear-gradient(90deg, #667eea 0%, #48c6ef 100%);
        height: 4px;
        transition: width 0.5s ease;
    }
</style>
@endpush

@section('content')
<div class="register-container institution-register gpu-accelerated">
    <div class="relative z-10 flex items-center justify-center min-h-screen py-12 px-4">
        <div class="form-container w-full">
            <!-- header -->
            <div class="text-center mb-8 fade-in-up">
                <a href="{{ route('home') }}" class="inline-block mb-4">
                    <img src="{{ asset('kkn-go-logo.png') }}" alt="KKN-GO" class="h-14 w-auto">
                </a>
                <h1 class="text-3xl font-semibold text-gray-800 mb-2">Registrasi Instansi</h1>
                <p class="text-gray-600">Bergabung sebagai mitra KKN-GO untuk pembangunan daerah</p>
            </div>

            <!-- form container -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden fade-in-scale" style="animation-delay: 0.1s;">
                <!-- progress indicator -->
                <div class="bg-gray-50 px-8 py-4 border-b">
                    <div class="relative">
                        <div class="absolute top-0 left-0 h-1 bg-gray-200 w-full rounded"></div>
                        <div class="progress-bar absolute top-0 left-0 h-1 rounded" id="progressBar" style="width: 33.33%"></div>
                    </div>
                    <div class="step-indicator-container mt-4">
                        <div class="step-item">
                            <div class="step-circle active" id="step1-circle">
                                <span>1</span>
                            </div>
                            <span class="step-label">Data Instansi</span>
                        </div>
                        
                        <div class="step-connector" id="connector1"></div>
                        
                        <div class="step-item">
                            <div class="step-circle inactive" id="step2-circle">
                                <span>2</span>
                            </div>
                            <span class="step-label">Penanggung Jawab</span>
                        </div>
                        
                        <div class="step-connector" id="connector2"></div>
                        
                        <div class="step-item">
                            <div class="step-circle inactive" id="step3-circle">
                                <span>3</span>
                            </div>
                            <span class="step-label">Akun & Verifikasi</span>
                        </div>
                    </div>
                </div>

                <!-- form content -->
                <form method="POST" action="{{ route('register.institution.store') }}" 
                      enctype="multipart/form-data" 
                      id="institutionRegisterForm">
                    @csrf

                    <!-- step 1: data instansi -->
                    <div id="step1-content" class="step-content">
                        <div class="form-section">
                            <h2 class="section-title">
                                <svg class="section-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                Informasi Instansi
                            </h2>

                            <!-- info box -->
                            <div class="bg-gradient-to-r from-blue-50 to-teal-50 border border-blue-200 rounded-lg p-4 mb-6">
                                <p class="text-gray-800">Pastikan data instansi yang Anda masukkan sesuai dengan dokumen resmi.</p>
                            </div>

                            <div class="space-y-6">
                                <!-- nama instansi -->
                                <div class="form-field-group">
                                    <label for="institution_name" class="form-label required">Nama Instansi</label>
                                    <div class="form-input-wrapper">
                                        <input type="text" 
                                               id="institution_name" 
                                               name="institution_name" 
                                               value="{{ old('institution_name') }}"
                                               placeholder="Contoh: Pemerintah Desa Sukamaju"
                                               class="form-input @error('institution_name') error @enderror"
                                               required>
                                        <svg class="form-input-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

                                <!-- jenis instansi -->
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
                                            <option value="ngo" {{ old('institution_type') == 'ngo' ? 'selected' : '' }}>NGO / Lembaga Non-Profit</option>
                                            <option value="puskesmas" {{ old('institution_type') == 'puskesmas' ? 'selected' : '' }}>Puskesmas</option>
                                            <option value="sekolah" {{ old('institution_type') == 'sekolah' ? 'selected' : '' }}>Sekolah</option>
                                            <option value="perguruan_tinggi" {{ old('institution_type') == 'perguruan_tinggi' ? 'selected' : '' }}>Perguruan Tinggi</option>
                                            <option value="lainnya" {{ old('institution_type') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                                        </select>
                                        <svg class="form-input-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

                                <!-- alamat lengkap -->
                                <div class="form-field-group">
                                    <label for="address" class="form-label required">Alamat Lengkap</label>
                                    <div class="form-input-wrapper">
                                        <textarea id="address" 
                                                  name="address" 
                                                  rows="3"
                                                  placeholder="Jalan, Nomor, RT/RW, Kelurahan, Kecamatan"
                                                  class="form-input @error('address') error @enderror"
                                                  required>{{ old('address') }}</textarea>
                                        <svg class="form-input-icon w-5 h-5" style="top: 1.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="help-text mt-1">
                                        <svg class="help-icon" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span>Tulis alamat lengkap sesuai dengan dokumen resmi instansi</span>
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

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- provinsi -->
                                    <div class="form-field-group">
                                        <label for="province_id" class="form-label required">Provinsi</label>
                                        <div class="form-input-wrapper">
                                            <select id="province_id" 
                                                    name="province_id" 
                                                    class="form-input form-select @error('province_id') error @enderror"
                                                    required>
                                                <option value="">-- Pilih Provinsi --</option>
                                                @foreach($provinces ?? [] as $province)
                                                    <option value="{{ $province->id }}" {{ old('province_id') == $province->id ? 'selected' : '' }}>
                                                        {{ $province->name }}
                                                    </option>
                                                @endforeach
                                            </select>
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

                                    <!-- kabupaten/kota -->
                                    <div class="form-field-group">
                                        <label for="regency_id" class="form-label required">Kabupaten/Kota</label>
                                        <div class="form-input-wrapper">
                                            <select id="regency_id" 
                                                    name="regency_id" 
                                                    class="form-input form-select @error('regency_id') error @enderror"
                                                    required>
                                                <option value="">-- Pilih Kabupaten/Kota --</option>
                                            </select>
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

                                <!-- website (opsional) -->
                                <div class="form-field-group">
                                    <label for="website" class="form-label">Website Instansi (Opsional)</label>
                                    <div class="form-input-wrapper">
                                        <input type="url" 
                                               id="website" 
                                               name="website" 
                                               value="{{ old('website') }}"
                                               placeholder="https://www.instansi.go.id"
                                               class="form-input @error('website') error @enderror">
                                        <svg class="form-input-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

                                <!-- deskripsi (opsional) -->
                                <div class="form-field-group">
                                    <label for="description" class="form-label">Deskripsi Singkat (Opsional)</label>
                                    <div class="form-input-wrapper">
                                        <textarea id="description" 
                                                  name="description" 
                                                  rows="3"
                                                  placeholder="Ceritakan tentang instansi Anda..."
                                                  class="form-input @error('description') error @enderror">{{ old('description') }}</textarea>
                                    </div>
                                    <p class="help-text mt-1">
                                        <svg class="help-icon" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span>Maksimal 1000 karakter</span>
                                    </p>
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

                            <!-- navigation buttons -->
                            <div class="flex justify-between mt-8">
                                <a href="{{ route('register') }}" class="btn btn-secondary">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                    </svg>
                                    Kembali
                                </a>
                                <button type="button" onclick="nextStep(2)" class="btn btn-primary">
                                    Lanjutkan
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- step 2: penanggung jawab -->
                    <div id="step2-content" class="step-content hidden">
                        <div class="form-section">
                            <h2 class="section-title">
                                <svg class="section-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Data Penanggung Jawab
                            </h2>

                            <!-- info box -->
                            <div class="bg-gradient-to-r from-blue-50 to-teal-50 border border-blue-200 rounded-lg p-4 mb-6">
                                <p class="text-gray-800">Penanggung jawab akan menjadi koordinator komunikasi antara instansi dengan mahasiswa KKN.</p>
                            </div>

                            <div class="space-y-6">
                                <!-- nama penanggung jawab -->
                                <div class="form-field-group">
                                    <label for="pic_name" class="form-label required">Nama Penanggung Jawab</label>
                                    <div class="form-input-wrapper">
                                        <input type="text" 
                                               id="pic_name" 
                                               name="pic_name" 
                                               value="{{ old('pic_name') }}"
                                               placeholder="Contoh: Dr. Ahmad Suryono"
                                               class="form-input @error('pic_name') error @enderror"
                                               required>
                                        <svg class="form-input-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

                                <!-- jabatan -->
                                <div class="form-field-group">
                                    <label for="pic_position" class="form-label required">Jabatan</label>
                                    <div class="form-input-wrapper">
                                        <input type="text" 
                                               id="pic_position" 
                                               name="pic_position" 
                                               value="{{ old('pic_position') }}"
                                               placeholder="Contoh: Kepala Desa / Kepala Dinas"
                                               class="form-input @error('pic_position') error @enderror"
                                               required>
                                        <svg class="form-input-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

                                <!-- nomor telepon -->
                                <div class="form-field-group">
                                    <label for="phone_number" class="form-label required">Nomor Telepon</label>
                                    <div class="form-input-wrapper">
                                        <input type="tel" 
                                               id="phone_number" 
                                               name="phone_number" 
                                               value="{{ old('phone_number') }}"
                                               placeholder="08123456789"
                                               class="form-input @error('phone_number') error @enderror"
                                               required>
                                        <svg class="form-input-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                        </svg>
                                    </div>
                                    <p class="help-text mt-1">
                                        <svg class="help-icon" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span>Nomor yang dapat dihubungi untuk koordinasi</span>
                                    </p>
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

                            <!-- navigation buttons -->
                            <div class="flex justify-between mt-8">
                                <button type="button" onclick="prevStep(1)" class="btn btn-secondary">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                    </svg>
                                    Kembali
                                </button>
                                <button type="button" onclick="nextStep(3)" class="btn btn-primary">
                                    Lanjutkan
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- step 3: akun & verifikasi -->
                    <div id="step3-content" class="step-content hidden">
                        <div class="form-section">
                            <h2 class="section-title">
                                <svg class="section-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                Akun & Verifikasi
                            </h2>

                            <!-- info box -->
                            <div class="bg-gradient-to-r from-blue-50 to-teal-50 border border-blue-200 rounded-lg p-4 mb-6">
                                <p class="text-gray-800"><strong>Penting:</strong> Akun instansi akan diverifikasi oleh admin KKN-GO sebelum dapat mempublikasikan program.</p>
                            </div>

                            <div class="space-y-6">
                                <!-- email resmi -->
                                <div class="form-field-group">
                                    <label for="official_email" class="form-label required">Email Resmi Instansi</label>
                                    <div class="form-input-wrapper">
                                        <input type="email" 
                                               id="official_email" 
                                               name="official_email" 
                                               value="{{ old('official_email') }}"
                                               placeholder="info@instansi.go.id"
                                               class="form-input @error('official_email') error @enderror"
                                               required>
                                        <svg class="form-input-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <p class="help-text mt-1">
                                        <svg class="help-icon" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span>Gunakan email resmi instansi (domain .go.id, .ac.id, dll)</span>
                                    </p>
                                    @error('official_email')
                                        <p class="error-message">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- username -->
                                <div class="form-field-group">
                                    <label for="username" class="form-label required">Username</label>
                                    <div class="form-input-wrapper">
                                        <input type="text" 
                                               id="username" 
                                               name="username" 
                                               value="{{ old('username') }}"
                                               placeholder="desasukamaju"
                                               class="form-input @error('username') error @enderror"
                                               required
                                               pattern="[a-zA-Z0-9._-]+"
                                               minlength="4">
                                        <svg class="form-input-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                        </svg>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Minimal 4 karakter, hanya huruf, angka, titik, underscore, dan strip</p>
                                    @error('username')
                                        <p class="error-message">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- password -->
                                <div class="form-field-group">
                                    <label for="password" class="form-label required">Password</label>
                                    <div class="form-input-wrapper">
                                        <input type="password" 
                                               id="password" 
                                               name="password" 
                                               placeholder="••••••••"
                                               class="form-input @error('password') error @enderror"
                                               required
                                               minlength="8">
                                        <svg class="form-input-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                        </svg>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Minimal 8 karakter dengan kombinasi huruf besar, kecil, angka, dan simbol</p>
                                    @error('password')
                                        <p class="error-message">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- konfirmasi password -->
                                <div class="form-field-group">
                                    <label for="password_confirmation" class="form-label required">Konfirmasi Password</label>
                                    <div class="form-input-wrapper">
                                        <input type="password" 
                                               id="password_confirmation" 
                                               name="password_confirmation"
                                               placeholder="••••••••"
                                               class="form-input"
                                               required
                                               minlength="8">
                                        <svg class="form-input-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                        </svg>
                                    </div>
                                </div>

                                <!-- logo instansi (opsional) -->
                                <div class="form-field-group">
                                    <label class="form-label">Logo Instansi (Opsional)</label>
                                    <div class="file-upload-wrapper">
                                        <div class="file-upload-area" onclick="document.getElementById('logo').click()">
                                            <svg class="file-upload-icon mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <p class="font-medium text-gray-700 mb-1">Klik untuk upload logo</p>
                                            <p class="text-xs text-gray-500">JPG, PNG max 2MB</p>
                                        </div>
                                        <input type="file" 
                                               id="logo"
                                               name="logo" 
                                               accept="image/jpeg,image/jpg,image/png"
                                               class="hidden"
                                               onchange="previewLogo(event)">
                                        
                                        <div id="logoPreview" class="file-preview hidden mt-4">
                                            <img src="" alt="Preview" class="w-20 h-20 rounded-lg object-cover">
                                            <div class="flex-1">
                                                <p class="font-medium text-gray-700" id="logoName"></p>
                                                <p class="text-xs text-gray-500" id="logoSize"></p>
                                            </div>
                                            <button type="button" 
                                                    onclick="removeLogo()"
                                                    class="text-red-500 hover:text-red-700">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                            </button>
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

                                <!-- dokumen verifikasi (WAJIB) -->
                                <div class="form-field-group">
                                    <label class="form-label required">Dokumen Verifikasi</label>
                                    <div class="file-upload-wrapper">
                                        <div class="file-upload-area" onclick="document.getElementById('verification_document').click()">
                                            <svg class="file-upload-icon mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            <p class="font-medium text-gray-700 mb-1">Klik untuk upload dokumen</p>
                                            <p class="text-xs text-gray-500">PDF max 5MB</p>
                                        </div>
                                        <input type="file" 
                                               id="verification_document"
                                               name="verification_document" 
                                               accept="application/pdf"
                                               class="hidden"
                                               onchange="previewDocument(event)"
                                               required>
                                        
                                        <div id="documentPreview" class="file-preview hidden mt-4">
                                            <svg class="w-12 h-12 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                                            </svg>
                                            <div class="flex-1">
                                                <p class="font-medium text-gray-700" id="documentName"></p>
                                                <p class="text-xs text-gray-500" id="documentSize"></p>
                                            </div>
                                            <button type="button" 
                                                    onclick="removeDocument()"
                                                    class="text-red-500 hover:text-red-700">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    <p class="help-text mt-1">
                                        <svg class="help-icon" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span>Surat resmi, SK, atau dokumen legalitas instansi</span>
                                    </p>
                                    @error('verification_document')
                                        <p class="error-message">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- terms & conditions -->
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <label class="flex items-start cursor-pointer">
                                        <input type="checkbox" 
                                               name="terms"
                                               required
                                               class="mt-1 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        <span class="ml-2 text-sm text-gray-700">
                                            Saya menyatakan bahwa data yang saya berikan adalah benar dan saya setuju dengan 
                                            <a href="#" class="text-blue-600 hover:text-blue-700 font-medium">syarat dan ketentuan</a> 
                                            serta 
                                            <a href="#" class="text-blue-600 hover:text-blue-700 font-medium">kebijakan privasi</a> 
                                            KKN-GO
                                        </span>
                                    </label>
                                </div>
                            </div>

                            <!-- navigation buttons -->
                            <div class="flex justify-between mt-8">
                                <button type="button" onclick="prevStep(2)" class="btn btn-secondary">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                    </svg>
                                    Kembali
                                </button>
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    <span class="spinner hidden"></span>
                                    <span class="btn-text">Daftar Sekarang</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- already have account -->
                <div class="px-8 pb-8">
                    <div class="text-center text-sm text-gray-600 mt-4">
                        Sudah punya akun? 
                        <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-700 font-medium">
                            Login di sini
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- loading overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="bg-white rounded-lg p-8 text-center">
            <div class="spinner mx-auto mb-4" style="width: 3rem; height: 3rem;"></div>
            <p class="text-gray-700 font-medium">Mendaftarkan instansi...</p>
            <p class="text-sm text-gray-500 mt-1">Mohon tunggu sebentar</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// multi-step form logic
let currentStep = 1;

function nextStep(step) {
    if (!validateStep(currentStep)) {
        return;
    }
    
    // hide current step
    document.getElementById(`step${currentStep}-content`).classList.add('hidden');
    document.getElementById(`step${currentStep}-circle`).classList.remove('active');
    document.getElementById(`step${currentStep}-circle`).classList.add('completed');
    
    // show next step
    document.getElementById(`step${step}-content`).classList.remove('hidden');
    document.getElementById(`step${step}-circle`).classList.remove('inactive');
    document.getElementById(`step${step}-circle`).classList.add('active');
    
    // update connector
    if (currentStep < step) {
        document.getElementById(`connector${currentStep}`).classList.add('completed');
    }
    
    // update progress bar
    const progress = (step / 3) * 100;
    document.getElementById('progressBar').style.width = progress + '%';
    
    currentStep = step;
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function prevStep(step) {
    document.getElementById(`step${currentStep}-content`).classList.add('hidden');
    document.getElementById(`step${currentStep}-circle`).classList.remove('active');
    document.getElementById(`step${currentStep}-circle`).classList.add('inactive');
    
    document.getElementById(`step${step}-content`).classList.remove('hidden');
    document.getElementById(`step${step}-circle`).classList.remove('completed');
    document.getElementById(`step${step}-circle`).classList.add('active');
    
    if (step < currentStep) {
        document.getElementById(`connector${step}`).classList.remove('completed');
    }
    
    const progress = (step / 3) * 100;
    document.getElementById('progressBar').style.width = progress + '%';
    
    currentStep = step;
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function validateStep(step) {
    let isValid = true;
    const currentContent = document.getElementById(`step${step}-content`);
    const requiredInputs = currentContent.querySelectorAll('[required]');
    
    requiredInputs.forEach(input => {
        if (!input.value) {
            input.classList.add('error');
            isValid = false;
        } else {
            input.classList.remove('error');
        }
    });
    
    if (!isValid) {
        alert('Mohon lengkapi semua field yang wajib diisi');
    }
    
    return isValid;
}

// preview logo
function previewLogo(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('logoPreview');
            preview.querySelector('img').src = e.target.result;
            document.getElementById('logoName').textContent = file.name;
            document.getElementById('logoSize').textContent = (file.size / 1024 / 1024).toFixed(2) + ' MB';
            preview.classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    }
}

function removeLogo() {
    document.getElementById('logo').value = '';
    document.getElementById('logoPreview').classList.add('hidden');
}

// preview document
function previewDocument(event) {
    const file = event.target.files[0];
    if (file) {
        const preview = document.getElementById('documentPreview');
        document.getElementById('documentName').textContent = file.name;
        document.getElementById('documentSize').textContent = (file.size / 1024 / 1024).toFixed(2) + ' MB';
        preview.classList.remove('hidden');
    }
}

function removeDocument() {
    document.getElementById('verification_document').value = '';
    document.getElementById('documentPreview').classList.add('hidden');
}

// form submission
document.getElementById('institutionRegisterForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    if (!validateStep(1) || !validateStep(2) || !validateStep(3)) {
        alert('Mohon lengkapi semua field yang wajib diisi');
        return;
    }
    
    document.getElementById('loadingOverlay').classList.add('active');
    document.getElementById('submitBtn').disabled = true;
    
    this.submit();
});
</script>
@endpush