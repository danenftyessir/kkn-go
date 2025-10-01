<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daftar Sebagai Mahasiswa - KKN-GO</title>
    
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth-student.css') }}">
</head>
<body class="font-sans antialiased bg-gray-50">
    <!-- navbar fixed -->
    <nav class="fixed top-0 left-0 right-0 bg-white border-b border-gray-200 z-50">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <a href="{{ route('home') }}" class="inline-flex items-center text-gray-700 hover:text-gray-900 transition-colors font-semibold">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span class="font-medium">Back to Home</span>
            </a>
            
            <div class="flex items-center space-x-6">
                <a href="#" class="text-gray-600 hover:text-gray-900 font-medium transition-colors">About</a>
                <a href="#" class="text-gray-600 hover:text-gray-900 font-medium transition-colors">Contact</a>
            </div>
        </div>
    </nav>

    <div class="register-container student-register gpu-accelerated" style="padding-top: 5rem;">
        <div class="relative z-10 flex items-center justify-center min-h-screen py-12 px-4">
            <div class="w-full max-w-4xl">
                <!-- logo & header -->
                <div class="text-center mb-8 fade-in-up">
                    <a href="{{ route('home') }}" class="inline-block mb-6">
                        <h2 class="text-3xl font-bold text-blue-600">KKN-GO</h2>
                    </a>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">Bergabung Bersama KKN-GO!</h1>
                    <p class="text-gray-600 text-lg">Wujudkan dampak positif untuk Indonesia</p>
                </div>

                <!-- form container -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden fade-in-scale" style="animation-delay: 0.1s;">
                    <!-- progress steps -->
                    <div class="bg-gradient-to-br from-gray-50 to-white border-b-2 border-gray-100 px-8 py-6">
                        <div class="step-indicator-container">
                            <div class="step-item">
                                <div class="step-circle active" id="step1-circle">
                                    <span class="step-number">1</span>
                                </div>
                                <span class="step-label">Data Pribadi</span>
                            </div>
                            
                            <div class="step-connector" id="connector1"></div>
                            
                            <div class="step-item">
                                <div class="step-circle inactive" id="step2-circle">
                                    <span class="step-number">2</span>
                                </div>
                                <span class="step-label">Data Akademik</span>
                            </div>
                            
                            <div class="step-connector" id="connector2"></div>
                            
                            <div class="step-item">
                                <div class="step-circle inactive" id="step3-circle">
                                    <span class="step-number">3</span>
                                </div>
                                <span class="step-label">Buat Akun</span>
                            </div>
                        </div>
                    </div>

                    <!-- form content -->
                    <form method="POST" action="{{ route('register.student') }}" 
                          enctype="multipart/form-data" 
                          id="studentRegisterForm"
                          class="p-8">
                        @csrf

                        <!-- step 1: data pribadi -->
                        <div id="step1-content" class="step-content">
                            <div class="mb-8">
                                <h2 class="text-2xl font-bold text-gray-800 mb-2">Data Pribadi</h2>
                                <p class="text-gray-600">Isi data diri kamu dengan lengkap ya!</p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- nama depan -->
                                <div class="form-field-group">
                                    <label for="first_name" class="form-label required">Nama Depan</label>
                                    <div class="form-input-wrapper">
                                        <input type="text" 
                                               id="first_name" 
                                               name="first_name" 
                                               value="{{ old('first_name') }}"
                                               placeholder="Contoh: Budi"
                                               class="form-input @error('first_name') error @enderror"
                                               required>
                                        <svg class="form-input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    @error('first_name')
                                        <p class="error-message">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- nama belakang -->
                                <div class="form-field-group">
                                    <label for="last_name" class="form-label required">Nama Belakang</label>
                                    <div class="form-input-wrapper">
                                        <input type="text" 
                                               id="last_name" 
                                               name="last_name" 
                                               value="{{ old('last_name') }}"
                                               placeholder="Contoh: Santoso"
                                               class="form-input @error('last_name') error @enderror"
                                               required>
                                        <svg class="form-input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    @error('last_name')
                                        <p class="error-message">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- email -->
                                <div class="form-field-group">
                                    <label for="email" class="form-label required">Email Universitas</label>
                                    <div class="form-input-wrapper">
                                        <input type="email" 
                                               id="email" 
                                               name="email" 
                                               value="{{ old('email') }}"
                                               placeholder="nama@university.ac.id"
                                               class="form-input @error('email') error @enderror"
                                               required>
                                        <svg class="form-input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    @error('email')
                                        <p class="error-message">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                    <p class="text-xs text-gray-500 mt-1">Gunakan email universitas (.ac.id atau .edu)</p>
                                </div>

                                <!-- no whatsapp -->
                                <div class="form-field-group">
                                    <label for="whatsapp" class="form-label required">No. WhatsApp</label>
                                    <div class="form-input-wrapper">
                                        <input type="tel" 
                                               id="whatsapp" 
                                               name="whatsapp" 
                                               value="{{ old('whatsapp') }}"
                                               placeholder="08xxxxxxxxxx"
                                               class="form-input @error('whatsapp') error @enderror"
                                               required>
                                        <svg class="form-input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                        </svg>
                                    </div>
                                    @error('whatsapp')
                                        <p class="error-message">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>

                            <!-- foto profil -->
                            <div class="mt-6">
                                <label class="form-label">Foto Profil <span class="text-xs text-gray-500">(opsional)</span></label>
                                <div class="file-upload-area" id="profilePhotoArea">
                                    <input type="file" 
                                           id="profile_photo" 
                                           name="profile_photo" 
                                           accept="image/*"
                                           class="file-input"
                                           onchange="handleFileSelect(this, 'profilePhotoArea', 'profilePhotoPreview')">
                                    <div class="file-upload-content" id="profilePhotoPreview">
                                        <svg class="file-upload-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <p class="file-upload-text">Klik atau drag foto profil ke sini</p>
                                        <p class="file-upload-subtext">PNG, JPG hingga 2MB</p>
                                    </div>
                                </div>
                                @error('profile_photo')
                                    <p class="error-message">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- navigation -->
                            <div class="mt-8 flex justify-end">
                                <button type="button" onclick="nextStep(2)" class="btn btn-primary">
                                    Lanjutkan
                                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- step 2: data akademik -->
                        <div id="step2-content" class="step-content hidden">
                            <div class="mb-8">
                                <h2 class="text-2xl font-bold text-gray-800 mb-2">Data Akademik</h2>
                                <p class="text-gray-600">Informasi kampus dan jurusan kamu</p>
                            </div>

                            <div class="space-y-6">
                                <!-- universitas -->
                                <div class="form-field-group">
                                    <label for="university_id" class="form-label required">Universitas</label>
                                    <div class="form-input-wrapper">
                                        <select id="university_id" 
                                                name="university_id" 
                                                class="form-input form-select @error('university_id') error @enderror"
                                                required>
                                            <option value="">-- Pilih Universitas --</option>
                                            <!-- TODO: loop universitas dari database -->
                                        </select>
                                        <svg class="form-input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                    </div>
                                    @error('university_id')
                                        <p class="error-message">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- jurusan -->
                                <div class="form-field-group">
                                    <label for="major" class="form-label required">Jurusan</label>
                                    <div class="form-input-wrapper">
                                        <input type="text" 
                                               id="major" 
                                               name="major" 
                                               value="{{ old('major') }}"
                                               placeholder="Contoh: Teknik Informatika"
                                               class="form-input @error('major') error @enderror"
                                               required>
                                        <svg class="form-input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                        </svg>
                                    </div>
                                    @error('major')
                                        <p class="error-message">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- nim & semester -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="form-field-group">
                                        <label for="nim" class="form-label required">NIM</label>
                                        <div class="form-input-wrapper">
                                            <input type="text" 
                                                   id="nim" 
                                                   name="nim" 
                                                   value="{{ old('nim') }}"
                                                   placeholder="Contoh: 2021xxxx"
                                                   class="form-input @error('nim') error @enderror"
                                                   required>
                                            <svg class="form-input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                                            </svg>
                                        </div>
                                        @error('nim')
                                            <p class="error-message">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <div class="form-field-group">
                                        <label for="semester" class="form-label required">Semester</label>
                                        <div class="form-input-wrapper">
                                            <select id="semester" 
                                                    name="semester" 
                                                    class="form-input form-select @error('semester') error @enderror"
                                                    required>
                                                <option value="">-- Pilih Semester --</option>
                                                @for($i = 1; $i <= 14; $i++)
                                                    <option value="{{ $i }}">Semester {{ $i }}</option>
                                                @endfor
                                            </select>
                                            <svg class="form-input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                        @error('semester')
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

                            <!-- navigation -->
                            <div class="mt-8 flex justify-between">
                                <button type="button" onclick="prevStep(1)" class="btn btn-secondary">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                    </svg>
                                    Kembali
                                </button>
                                <button type="button" onclick="nextStep(3)" class="btn btn-primary">
                                    Lanjutkan
                                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- step 3: buat akun -->
                        <div id="step3-content" class="step-content hidden">
                            <div class="mb-8">
                                <h2 class="text-2xl font-bold text-gray-800 mb-2">Buat Akun</h2>
                                <p class="text-gray-600">Buat username dan password untuk akun kamu</p>
                            </div>

                            <div class="space-y-6">
                                <!-- username -->
                                <div class="form-field-group">
                                    <label for="username" class="form-label required">Username</label>
                                    <div class="form-input-wrapper">
                                        <input type="text" 
                                               id="username" 
                                               name="username" 
                                               value="{{ old('username') }}"
                                               placeholder="Contoh: budisantoso123"
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
                                    <p class="text-xs text-gray-500 mt-1">Username harus unik dan minimal 3 karakter</p>
                                </div>

                                <!-- password -->
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
                                                class="password-toggle">
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

                                <!-- konfirmasi password -->
                                <div class="form-field-group">
                                    <label for="password_confirmation" class="form-label required">Konfirmasi Password</label>
                                    <div class="form-input-wrapper">
                                        <input type="password" 
                                               id="password_confirmation" 
                                               name="password_confirmation" 
                                               placeholder="Ulangi password"
                                               class="form-input @error('password_confirmation') error @enderror"
                                               required>
                                        <button type="button" 
                                                onclick="togglePassword('password_confirmation')" 
                                                class="password-toggle">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    @error('password_confirmation')
                                        <p class="error-message">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- terms -->
                                <div class="form-field-group">
                                    <label class="flex items-start space-x-3">
                                        <input type="checkbox" 
                                               name="terms" 
                                               class="mt-1 h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                               required>
                                        <span class="text-sm text-gray-700">
                                            Saya setuju dengan <a href="#" class="text-blue-600 hover:text-blue-700 font-medium">Syarat dan Ketentuan</a> serta <a href="#" class="text-blue-600 hover:text-blue-700 font-medium">Kebijakan Privasi</a>
                                        </span>
                                    </label>
                                </div>
                            </div>

                            <!-- navigation -->
                            <div class="mt-8 flex justify-between">
                                <button type="button" onclick="prevStep(2)" class="btn btn-secondary">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                    </svg>
                                    Kembali
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    Daftar Sekarang
                                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- sudah punya akun -->
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
                <p class="text-gray-700 font-medium">Mendaftarkan akun kamu...</p>
                <p class="text-sm text-gray-500 mt-1">Mohon tunggu sebentar</p>
            </div>
        </div>
    </div>

    <script>
    let currentStep = 1;

    function nextStep(step) {
        if (!validateStep(currentStep)) return;
        
        // update step label styling
        document.querySelector(`#step${currentStep}-circle + .step-label`).style.fontWeight = 'normal';
        document.querySelector(`#step${step}-circle + .step-label`).style.fontWeight = 'bold';
        
        document.getElementById(`step${currentStep}-content`).classList.add('hidden');
        document.getElementById(`step${currentStep}-circle`).classList.remove('active');
        document.getElementById(`step${currentStep}-circle`).classList.add('completed');
        
        document.getElementById(`step${step}-content`).classList.remove('hidden');
        document.getElementById(`step${step}-circle`).classList.remove('inactive');
        document.getElementById(`step${step}-circle`).classList.add('active');
        
        if (currentStep < step) {
            document.getElementById(`connector${currentStep}`).classList.add('completed');
        }
        
        currentStep = step;
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function prevStep(step) {
        // update step label styling
        document.querySelector(`#step${currentStep}-circle + .step-label`).style.fontWeight = 'normal';
        document.querySelector(`#step${step}-circle + .step-label`).style.fontWeight = 'bold';
        
        document.getElementById(`step${currentStep}-content`).classList.add('hidden');
        document.getElementById(`step${currentStep}-circle`).classList.remove('active');
        document.getElementById(`step${currentStep}-circle`).classList.add('inactive');
        
        document.getElementById(`step${step}-content`).classList.remove('hidden');
        document.getElementById(`step${step}-circle`).classList.remove('completed');
        document.getElementById(`step${step}-circle`).classList.add('active');
        
        if (step < currentStep) {
            document.getElementById(`connector${step}`).classList.remove('completed');
        }
        
        currentStep = step;
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function validateStep(step) {
        const content = document.getElementById(`step${step}-content`);
        const requiredInputs = content.querySelectorAll('[required]');
        let isValid = true;
        
        requiredInputs.forEach(input => {
            if (!input.value) {
                input.classList.add('error');
                isValid = false;
            } else {
                input.classList.remove('error');
            }
        });
        
        if (!isValid) alert('Mohon lengkapi semua field yang wajib diisi');
        return isValid;
    }

    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        input.type = input.type === 'password' ? 'text' : 'password';
    }

    function handleFileSelect(input, areaId, previewId) {
        const area = document.getElementById(areaId);
        const preview = document.getElementById(previewId);
        
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.innerHTML = `
                    <div class="relative">
                        <img src="${e.target.result}" class="max-h-48 rounded-lg mx-auto">
                        <button type="button" onclick="clearFile('${input.id}', '${areaId}', '${previewId}')" class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-2 hover:bg-red-600">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                `;
            };
            
            reader.readAsDataURL(file);
        }
    }

    function clearFile(inputId, areaId, previewId) {
        document.getElementById(inputId).value = '';
        document.getElementById(previewId).innerHTML = `
            <svg class="file-upload-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <p class="file-upload-text">Klik atau drag foto profil ke sini</p>
            <p class="file-upload-subtext">PNG, JPG hingga 2MB</p>
        `;
    }

    // set step 1 label bold on page load
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelector('#step1-circle + .step-label').style.fontWeight = 'bold';
    });
    </script>
</body>
</html>