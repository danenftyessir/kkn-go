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
</head>
<body class="institution-register">
    <div class="container">
        {{-- header section --}}
        <div class="header-section">
            <h1>Daftar Sebagai Instansi</h1>
            <p>Mulai posting masalah dan temukan mahasiswa KKN terbaik</p>
            <div class="login-link">
                <span>Sudah punya akun?</span>
                <a href="{{ route('login') }}">Masuk di sini</a>
            </div>
        </div>

        {{-- main form card --}}
        <div class="register-card fade-in-up">
            {{-- step indicator --}}
            <div class="step-indicator">
                <div class="step-indicator-wrapper">
                    <div class="step-item" id="step1-item">
                        <div class="step-circle active" id="step1-circle">
                            <span class="step-number">1</span>
                        </div>
                        <span class="step-label">Data Instansi</span>
                    </div>
                    
                    <div class="step-connector" id="connector1"></div>
                    
                    <div class="step-item" id="step2-item">
                        <div class="step-circle inactive" id="step2-circle">
                            <span class="step-number">2</span>
                        </div>
                        <span class="step-label">Lokasi</span>
                    </div>
                    
                    <div class="step-connector" id="connector2"></div>
                    
                    <div class="step-item" id="step3-item">
                        <div class="step-circle inactive" id="step3-circle">
                            <span class="step-number">3</span>
                        </div>
                        <span class="step-label">Penanggung Jawab</span>
                    </div>
                    
                    <div class="step-connector" id="connector3"></div>
                    
                    <div class="step-item" id="step4-item">
                        <div class="step-circle inactive" id="step4-circle">
                            <span class="step-number">4</span>
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
                  x-data="institutionForm()">
                @csrf

                {{-- step 1: data instansi --}}
                <div id="step1-content" class="step-content">
                    <div class="mb-8">
                        <h2>Data Instansi</h2>
                        <p>Informasi lengkap tentang instansi Anda</p>
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
                            <select id="institution_type" 
                                    name="institution_type" 
                                    class="form-select @error('institution_type') error @enderror"
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
                            <label for="official_email" class="form-label required">Email Resmi Instansi</label>
                            <div class="form-input-wrapper">
                                <input type="email" 
                                       id="official_email" 
                                       name="official_email" 
                                       value="{{ old('official_email') }}"
                                       placeholder="Contoh: info@desasukamaju.go.id"
                                       class="form-input @error('official_email') error @enderror"
                                       required>
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
                <div id="step2-content" class="step-content hidden">
                    <div class="mb-8">
                        <h2>Lokasi</h2>
                        <p>Informasi lokasi instansi Anda</p>
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

                        {{-- provinsi --}}
                        <div class="form-field-group">
                            <label for="province_id" class="form-label required">Provinsi</label>
                            <select id="province_id" 
                                    name="province_id" 
                                    class="form-select @error('province_id') error @enderror"
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
                                <p class="error-message">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- kabupaten/kota --}}
                        <div class="form-field-group">
                            <label for="regency_id" class="form-label required">Kabupaten/Kota</label>
                            <select id="regency_id" 
                                    name="regency_id" 
                                    class="form-select @error('regency_id') error @enderror"
                                    x-model="selectedRegency"
                                    :disabled="!selectedProvince"
                                    required>
                                <option value="">Pilih Kabupaten/Kota</option>
                                <template x-for="regency in regencies" :key="regency.id">
                                    <option :value="regency.id" x-text="regency.name"></option>
                                </template>
                            </select>
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
                <div id="step3-content" class="step-content hidden">
                    <div class="mb-8">
                        <h2>Penanggung Jawab</h2>
                        <p>Informasi person in charge (PIC) instansi</p>
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

                        {{-- nomor telepon --}}
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

                        {{-- logo instansi (optional) --}}
                        <div class="form-field-group">
                            <label for="logo" class="form-label">Logo Instansi (Opsional)</label>
                            <div class="file-upload-wrapper">
                                <input type="file" 
                                       id="logo" 
                                       name="logo" 
                                       accept="image/jpeg,image/jpg,image/png"
                                       class="file-upload-input"
                                       onchange="previewLogo(event)">
                                <label for="logo" class="file-upload-label">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <div class="file-upload-text">
                                        <p id="logoLabel">Upload logo instansi (JPG, PNG, max 2MB)</p>
                                    </div>
                                </label>
                            </div>
                            <div id="logoPreview" class="hidden image-preview mt-4">
                                <img src="" alt="Logo preview">
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

                        {{-- dokumen verifikasi --}}
                        <div class="form-field-group">
                            <label for="verification_document" class="form-label required">Dokumen Verifikasi</label>
                            <div class="file-upload-wrapper">
                                <input type="file" 
                                       id="verification_document" 
                                       name="verification_document" 
                                       accept="application/pdf"
                                       class="file-upload-input"
                                       onchange="previewDocument(event)"
                                       required>
                                <label for="verification_document" class="file-upload-label">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                    <div class="file-upload-text">
                                        <p id="documentLabel">Upload surat keterangan resmi (PDF, max 5MB)</p>
                                    </div>
                                </label>
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

                        {{-- website (optional) --}}
                        <div class="form-field-group">
                            <label for="website" class="form-label">Website (Opsional)</label>
                            <div class="form-input-wrapper">
                                <input type="url" 
                                       id="website" 
                                       name="website" 
                                       value="{{ old('website') }}"
                                       placeholder="https://www.contoh.go.id"
                                       class="form-input @error('website') error @enderror">
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

                        {{-- deskripsi (optional) --}}
                        <div class="form-field-group">
                            <label for="description" class="form-label">Deskripsi Instansi (Opsional)</label>
                            <div class="form-input-wrapper">
                                <textarea id="description" 
                                          name="description" 
                                          rows="4"
                                          placeholder="Ceritakan singkat tentang instansi Anda..."
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
                <div id="step4-content" class="step-content hidden">
                    <div class="mb-8">
                        <h2>Akun & Verifikasi</h2>
                        <p>Buat akun untuk login ke sistem</p>
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
                                        class="form-input-icon"
                                        style="cursor: pointer;">
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

                        {{-- password confirmation --}}
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
                                        class="form-input-icon"
                                        style="cursor: pointer;">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        {{-- terms agreement --}}
                        <div class="form-field-group">
                            <div class="checkbox-wrapper">
                                <input type="checkbox" 
                                       id="terms" 
                                       name="terms" 
                                       class="checkbox-input"
                                       required>
                                <label for="terms" class="checkbox-label">
                                    Saya setuju dengan <a href="#" class="text-green-600 hover:text-green-700 font-semibold">syarat dan ketentuan</a> serta <a href="#" class="text-green-600 hover:text-green-700 font-semibold">kebijakan privasi</a> KKN-GO
                                </label>
                            </div>
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

    {{-- loading overlay --}}
    <div id="loadingOverlay" class="loading-overlay">
        <div class="loading-spinner"></div>
        <p class="loading-text">Sedang memproses pendaftaran...</p>
    </div>

    <script>
    // ==========================================================
    // MULTI-STEP FORM dengan validasi AJAX per step
    // ==========================================================
    let currentStep = 1;

    // inisialisasi saat halaman load
    window.addEventListener('DOMContentLoaded', function() {
        // jika ada error dari server saat submit, tampilkan di step yang sesuai
        @if($errors->any())
            const errorFields = @json($errors->keys());
            
            // tentukan step mana yang error
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
            
            // tampilkan step yang error
            showStepVisual(errorStep);
        @endif
    });

    // ==========================================================
    // FUNGSI VALIDASI & NAVIGASI STEP
    // ==========================================================
    async function nextStep(step) {
        const form = document.getElementById('institutionRegisterForm');
        const formData = new FormData(form);
        formData.append('step', currentStep);

        const loadingOverlay = document.getElementById('loadingOverlay');
        loadingOverlay.classList.add('active');

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
                    handleValidationErrors(data.errors);
                } else {
                    alert('Terjadi kesalahan pada server. Silakan coba lagi.');
                }
                return;
            }

            // validasi berhasil, pindah ke step selanjutnya
            handleValidationErrors({});
            showStepVisual(step);
            currentStep = step;
            window.scrollTo({ top: 0, behavior: 'smooth' });

        } catch (error) {
            console.error('Validation error:', error);
            alert('Tidak dapat terhubung ke server. Periksa koneksi internet Anda.');
        } finally {
            loadingOverlay.classList.remove('active');
        }
    }

    function prevStep(step) {
        handleValidationErrors({});
        showStepVisual(step);
        currentStep = step;
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function showStepVisual(step) {
        // hide semua content
        for (let i = 1; i <= 4; i++) {
            const content = document.getElementById(`step${i}-content`);
            const circle = document.getElementById(`step${i}-circle`);
            
            if (content) content.classList.add('hidden');
            if (circle) {
                circle.classList.remove('active', 'completed');
                circle.classList.add('inactive');
            }
        }

        // update connector lines
        for (let i = 1; i <= 3; i++) {
            const connector = document.getElementById(`connector${i}`);
            if (connector) connector.classList.remove('completed');
        }

        // tampilkan step saat ini
        const currentContent = document.getElementById(`step${step}-content`);
        const currentCircle = document.getElementById(`step${step}-circle`);
        
        if (currentContent) currentContent.classList.remove('hidden');
        if (currentCircle) {
            currentCircle.classList.remove('inactive');
            currentCircle.classList.add('active');
        }

        // mark completed steps
        for (let i = 1; i < step; i++) {
            const circle = document.getElementById(`step${i}-circle`);
            const connector = document.getElementById(`connector${i}`);
            
            if (circle) {
                circle.classList.remove('active', 'inactive');
                circle.classList.add('completed');
            }
            if (connector) connector.classList.add('completed');
        }
    }

    // ==========================================================
    // FUNGSI UTILITAS
    // ==========================================================
    function handleValidationErrors(errors) {
        // hapus semua error message yang ada
        document.querySelectorAll('.error-message').forEach(el => {
            if (!el.closest('.form-field-group')?.querySelector('.error')) {
                el.remove();
            }
        });
        
        // hapus class error dari input
        document.querySelectorAll('.form-input, .form-select').forEach(el => {
            el.classList.remove('error');
        });

        // tampilkan error baru
        Object.keys(errors).forEach(fieldName => {
            const input = document.querySelector(`[name="${fieldName}"]`);
            if (input) {
                input.classList.add('error');
                
                const fieldGroup = input.closest('.form-field-group');
                if (fieldGroup && !fieldGroup.querySelector('.error-message')) {
                    const errorDiv = document.createElement('p');
                    errorDiv.className = 'error-message';
                    errorDiv.innerHTML = `
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        ${errors[fieldName][0]}
                    `;
                    fieldGroup.appendChild(errorDiv);
                }
            }
        });
    }

    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        field.type = field.type === 'password' ? 'text' : 'password';
    }

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

    function previewDocument(event) {
        const file = event.target.files[0];
        const label = document.getElementById('documentLabel');
        
        if (file) {
            label.textContent = file.name;
        }
    }

    // ==========================================================
    // ALPINE.JS: Dynamic Province-Regency Dropdown
    // ==========================================================
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

    // ==========================================================
    // FORM SUBMIT HANDLER
    // ==========================================================
    document.getElementById('institutionRegisterForm')?.addEventListener('submit', function(e) {
        const loadingOverlay = document.getElementById('loadingOverlay');
        loadingOverlay.classList.add('active');
    });
    </script>
</body>
</html>