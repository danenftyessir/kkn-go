{{-- resources/views/auth/student-register.blade.php --}}
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

    <div class="register-container student-register" style="padding-top: 2rem;">
        <div class="relative z-10 flex items-center justify-center min-h-screen py-12 px-4">
            <div class="w-full max-w-4xl">
                {{-- logo & header --}}
                <div class="text-center mb-8 fade-in-up">
                    <a href="{{ route('home') }}" class="inline-block mb-6">
                        <img src="{{ asset('kkn-go-logo.png') }}" alt="KKN-GO" class="h-16 w-auto">
                    </a>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">bergabung bersama KKN-GO!</h1>
                    <p class="text-gray-600 text-lg">wujudkan dampak positif untuk Indonesia</p>
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
                                <span class="step-label">data pribadi</span>
                            </div>
                            
                            <div class="step-connector" id="connector1"></div>
                            
                            <div class="step-item" id="step2-item">
                                <div class="step-circle inactive" id="step2-circle">
                                    <span class="step-number">2</span>
                                </div>
                                <span class="step-label">data akademik</span>
                            </div>
                            
                            <div class="step-connector" id="connector2"></div>
                            
                            <div class="step-item" id="step3-item">
                                <div class="step-circle inactive" id="step3-circle">
                                    <span class="step-number">3</span>
                                </div>
                                <span class="step-label">buat akun</span>
                            </div>
                        </div>
                    </div>

                    {{-- form content --}}
                    <form method="POST" action="{{ route('register.student') }}" 
                          enctype="multipart/form-data" 
                          id="studentRegisterForm"
                          class="p-8">
                        @csrf

                        {{-- step 1: data pribadi --}}
                        <div id="step1-content" class="step-content">
                            <div class="mb-8">
                                <h2 class="text-2xl font-bold text-gray-800 mb-2">data pribadi</h2>
                                <p class="text-gray-600">isi data diri kamu dengan lengkap ya!</p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- nama depan --}}
                                <div class="form-field-group">
                                    <label for="first_name" class="form-label required">nama depan</label>
                                    <div class="form-input-wrapper">
                                        <input type="text" 
                                               id="first_name" 
                                               name="first_name" 
                                               value="{{ old('first_name') }}"
                                               placeholder="contoh: Budi"
                                               class="form-input @error('first_name') error @enderror"
                                               required>
                                        <svg class="form-input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    <p id="error-first_name" class="error-message hidden"></p>
                                </div>

                                {{-- nama belakang --}}
                                <div class="form-field-group">
                                    <label for="last_name" class="form-label required">nama belakang</label>
                                    <div class="form-input-wrapper">
                                        <input type="text" 
                                               id="last_name" 
                                               name="last_name" 
                                               value="{{ old('last_name') }}"
                                               placeholder="contoh: Santoso"
                                               class="form-input @error('last_name') error @enderror"
                                               required>
                                        <svg class="form-input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    <p id="error-last_name" class="error-message mt-2 text-sm text-red-600 hidden"></p>
                                </div>

                                {{-- email --}}
                                <div class="form-field-group">
                                    <label for="email" class="form-label required">email universitas</label>
                                    <div class="form-input-wrapper">
                                        <input type="email" 
                                               id="email" 
                                               name="email" 
                                               value="{{ old('email') }}"
                                               placeholder="contoh: budisantoso@student.ac.id"
                                               class="form-input @error('email') error @enderror"
                                               required>
                                        <svg class="form-input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <p id="error-email" class="error-message mt-2 text-sm text-red-600 hidden"></p>
                                </div>

                                {{-- no whatsapp --}}
                                <div class="form-field-group">
                                    <label for="whatsapp_number" class="form-label required">no. WhatsApp</label>
                                    <div class="form-input-wrapper">
                                        <input type="tel" 
                                               id="whatsapp_number" 
                                               name="whatsapp_number" 
                                               value="{{ old('whatsapp_number') }}"
                                               placeholder="contoh: 081234567890"
                                               class="form-input @error('whatsapp_number') error @enderror"
                                               required>
                                        <svg class="form-input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                        </svg>
                                    </div>
                                    <p id="error-whatsapp_number" class="error-message mt-2 text-sm text-red-600 hidden"></p>
                                </div>
                            </div>

                            {{-- upload foto profil --}}
                            <div class="mt-6">
                                <label class="form-label">foto profil (opsional)</label>
                                <div class="file-upload-area mt-2">
                                    <input type="file" 
                                           id="profile_photo" 
                                           name="profile_photo" 
                                           accept="image/jpeg,image/jpg,image/png"
                                           class="hidden"
                                           onchange="previewImage(event)">
                                    <label for="profile_photo" class="cursor-pointer">
                                        <svg class="file-upload-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                        </svg>
                                        <p class="text-gray-600 font-medium" id="fileLabel">klik untuk upload foto</p>
                                        <p class="text-sm text-gray-500 mt-1">JPG, JPEG atau PNG (max. 2MB)</p>
                                    </label>
                                    <div id="imagePreview" class="mt-4 hidden">
                                        <img src="" alt="preview" class="mx-auto h-32 w-32 rounded-full object-cover">
                                    </div>
                                </div>
                                @error('profile_photo')
                                    <p class="error-message mt-2">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
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

                        {{-- step 2: data akademik --}}
                        <div id="step2-content" class="step-content hidden">
                            <div class="mb-8">
                                <h2 class="text-2xl font-bold text-gray-800 mb-2">data akademik</h2>
                                <p class="text-gray-600">informasi terkait kampus dan perkuliahan kamu</p>
                            </div>

                            <div class="space-y-6">
                                {{-- universitas --}}
                                <div class="form-field-group">
                                    <label for="university_id" class="form-label required">universitas</label>
                                    <div class="form-input-wrapper">
                                        <select id="university_id" 
                                                name="university_id" 
                                                class="form-input form-select @error('university_id') error @enderror"
                                                required>
                                            <option value="">-- pilih universitas --</option>
                                            {{-- TODO: ambil dari database --}}
                                            <option value="1" {{ old('university_id') == 1 ? 'selected' : '' }}>Universitas Indonesia</option>
                                            <option value="2" {{ old('university_id') == 2 ? 'selected' : '' }}>Institut Teknologi Bandung</option>
                                            <option value="3" {{ old('university_id') == 3 ? 'selected' : '' }}>Universitas Gadjah Mada</option>
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

                                {{-- jurusan --}}
                                <div class="form-field-group">
                                    <label for="major" class="form-label required">jurusan</label>
                                    <div class="form-input-wrapper">
                                        <input type="text" 
                                               id="major" 
                                               name="major" 
                                               value="{{ old('major') }}"
                                               placeholder="contoh: Teknik Informatika"
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

                                {{-- nim & semester --}}
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="form-field-group">
                                        <label for="nim" class="form-label required">NIM</label>
                                        <div class="form-input-wrapper">
                                            <input type="text" 
                                                   id="nim" 
                                                   name="nim" 
                                                   value="{{ old('nim') }}"
                                                   placeholder="contoh: 2021xxxx"
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
                                        <label for="semester" class="form-label required">semester</label>
                                        <div class="form-input-wrapper">
                                            <select id="semester" 
                                                    name="semester" 
                                                    class="form-input form-select @error('semester') error @enderror"
                                                    required>
                                                <option value="">-- pilih semester --</option>
                                                @for($i = 1; $i <= 14; $i++)
                                                    <option value="{{ $i }}" {{ old('semester') == $i ? 'selected' : '' }}>semester {{ $i }}</option>
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

                        {{-- step 3: buat akun --}}
                        <div id="step3-content" class="step-content hidden">
                            <div class="mb-8">
                                <h2 class="text-2xl font-bold text-gray-800 mb-2">buat akun</h2>
                                <p class="text-gray-600">buat username dan password untuk akun kamu</p>
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
                                               placeholder="contoh: budisantoso123"
                                               class="form-input @error('username') error @enderror"
                                               required>
                                        <svg class="form-input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    <p id="error-username" class="error-message mt-2 text-sm text-red-600 hidden"></p>
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
                                    <p id="error-password" class="error-message mt-2 text-sm text-red-600 hidden"></p>
                                </div>

                                {{-- konfirmasi password --}}
                                <div class="form-field-group">
                                    <label for="password_confirmation" class="form-label required">konfirmasi password</label>
                                    <div class="form-input-wrapper">
                                        <p id="error-password_confirmation" class="error-message mt-2 text-sm text-red-600 hidden"></p>
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

                                {{-- terms --}}
                                <div class="form-field-group">
                                    <label class="flex items-start space-x-3">
                                        <input type="checkbox" 
                                               name="terms" 
                                               class="mt-1 h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                               required>
                                        <span class="text-sm text-gray-700">
                                            saya setuju dengan <a href="#" class="text-blue-600 hover:text-blue-700 font-medium">syarat dan ketentuan</a> serta <a href="#" class="text-blue-600 hover:text-blue-700 font-medium">kebijakan privasi</a>
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
                            <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-700 font-medium">
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
                <p class="text-gray-700 font-medium">mendaftarkan akun kamu...</p>
                <p class="text-sm text-gray-500 mt-1">mohon tunggu sebentar</p>
            </div>
        </div>
    </div>

    <script>
    let currentStep = 1;

    // ==========================================================
    // FUNGSI BARU: Menampilkan/Menyembunyikan Error Secara Dinamis
    // ==========================================================
    function handleValidationErrors(errors) {
        // Sembunyikan semua error lama terlebih dahulu
        document.querySelectorAll('.error-message').forEach(el => {
            el.textContent = '';
            el.classList.add('hidden');
        });
        document.querySelectorAll('.form-input, .form-select').forEach(el => el.classList.remove('error'));

        // Tampilkan error baru yang diterima dari backend
        for (const field in errors) {
            const errorElement = document.getElementById(`error-${field}`);
            const inputElement = document.getElementById(field);

            if (errorElement) {
                errorElement.textContent = errors[field][0]; // Tampilkan hanya pesan error pertama
                errorElement.classList.remove('hidden');
            }
            if (inputElement) {
                inputElement.classList.add('error');
                // Scroll ke input pertama yang error
                if (Object.keys(errors)[0] === field) {
                    inputElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        }
    }

    // ==========================================================
    // FUNGSI NAVIGASI YANG DIMODIFIKASI DENGAN VALIDASI AJAX
    // ==========================================================
    async function nextStep(step) {
        const form = document.getElementById('studentRegisterForm');
        const formData = new FormData(form);
        formData.append('step', currentStep); // Menambahkan informasi langkah saat ini ke request

        const loadingOverlay = document.getElementById('loadingOverlay');
        loadingOverlay.classList.add('active'); // Tampilkan loading

        try {
            const response = await fetch("{{ route('api.public.validate.student.step') }}", {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: formData
            });

            if (!response.ok) {
                if (response.status === 422) { // Kode 422 berarti error validasi
                    const data = await response.json();
                    handleValidationErrors(data.errors);
                } else {
                    alert('Terjadi kesalahan pada server. Silakan coba lagi.');
                }
                return; // Hentikan fungsi jika validasi gagal
            }

            // --- Jika validasi berhasil, lanjutkan alur visual ---
            handleValidationErrors({}); // Bersihkan error lama
            
            // Logika visual untuk pindah step (sama seperti sebelumnya)
            const currentCircle = document.getElementById(`step${currentStep}-circle`);
            const currentContent = document.getElementById(`step${currentStep}-content`);
            
            currentContent.classList.add('hidden');
            currentCircle.classList.remove('active');
            currentCircle.classList.add('completed');
            
            const nextCircle = document.getElementById(`step${step}-circle`);
            const nextContent = document.getElementById(`step${step}-content`);
            
            nextContent.classList.remove('hidden');
            nextCircle.classList.remove('inactive');
            nextCircle.classList.add('active');
            
            if (currentStep < step) {
                document.getElementById(`connector${currentStep}`).classList.add('completed');
            }
            
            currentStep = step;
            window.scrollTo({ top: 0, behavior: 'smooth' });

        } catch (error) {
            console.error('Terjadi kesalahan saat validasi:', error);
            alert('Tidak dapat terhubung ke server. Periksa koneksi internet Anda.');
        } finally {
            loadingOverlay.classList.remove('active'); // Selalu sembunyikan loading
        }
    }

    // Fungsi prevStep tidak perlu diubah
    function prevStep(step) {
        handleValidationErrors({}); // Bersihkan error saat kembali
        const currentCircle = document.getElementById(`step${currentStep}-circle`);
        const currentContent = document.getElementById(`step${currentStep}-content`);
        
        currentContent.classList.add('hidden');
        currentCircle.classList.remove('active');
        currentCircle.classList.add('inactive');
        
        const prevCircle = document.getElementById(`step${step}-circle`);
        const prevContent = document.getElementById(`step${step}-content`);
        
        prevContent.classList.remove('hidden');
        prevCircle.classList.remove('completed');
        prevCircle.classList.add('active');
        
        if (currentStep > step) {
            document.getElementById(`connector${step}`).classList.remove('completed');
        }
        
        currentStep = step;
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // ==========================================================
    // FUNGSI UTILITAS (TIDAK BERUBAH)
    // ==========================================================
    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        input.type = input.type === 'password' ? 'text' : 'password';
    }

    function previewImage(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('imagePreview');
        const label = document.getElementById('fileLabel');
        
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

    // ==========================================================
    // EVENT LISTENER UNTUK SUBMIT FORM (DIMODIFIKASI)
    // ==========================================================
    document.getElementById('studentRegisterForm')?.addEventListener('submit', async function(e) {
        e.preventDefault(); // Selalu batalkan submit bawaan terlebih dahulu

        const loadingOverlay = document.getElementById('loadingOverlay');
        loadingOverlay.classList.add('active');
        
        // Lakukan validasi terakhir untuk semua data sebelum submit
        const formData = new FormData(this);
        // Kita tidak perlu mengirim `step` karena kita ingin backend memvalidasi semua
        
        try {
            const response = await fetch("{{ route('register.student.submit') }}", {
                method: 'POST',
                headers: { 
                    'Accept': 'application/json' 
                    // CSRF token sudah otomatis ditangani oleh FormData
                },
                body: formData
            });

            // Jika backend mengembalikan error validasi saat submit akhir
            if (response.status === 422) {
                const data = await response.json();
                
                // Cari tahu di langkah mana error pertama terjadi
                const errorFields = Object.keys(data.errors);
                const step1Fields = ['first_name', 'last_name', 'email', 'whatsapp_number', 'profile_photo'];
                const step2Fields = ['university_id', 'major', 'nim', 'semester'];

                let errorStep = 3;
                if (errorFields.some(field => step1Fields.includes(field))) {
                    errorStep = 1;
                } else if (errorFields.some(field => step2Fields.includes(field))) {
                    errorStep = 2;
                }
                
                // Pindah ke step yang error dan tampilkan pesan
                if (currentStep !== errorStep) {
                    // Reset semua step indicator
                    for(let i=1; i<=3; i++) {
                        document.getElementById(`step${i}-content`).classList.add('hidden');
                        document.getElementById(`step${i}-circle`).className = 'step-circle inactive';
                        if(i < 3) document.getElementById(`connector${i}`).classList.remove('completed');
                    }
                    // Setup ulang ke step yang error
                    for(let i=1; i<errorStep; i++) {
                        document.getElementById(`step${i}-circle`).classList.add('completed');
                        if(i < 3) document.getElementById(`connector${i}`).classList.add('completed');
                    }
                    document.getElementById(`step${errorStep}-content`).classList.remove('hidden');
                    document.getElementById(`step${errorStep}-circle`).classList.add('active');
                    currentStep = errorStep;
                }

                handleValidationErrors(data.errors);
                loadingOverlay.classList.remove('active');
                return;
            }

            // Jika sukses, Laravel akan me-redirect. Kita cek jika ada URL redirect
            if (response.redirected) {
                window.location.href = response.url;
            } else {
                 // Fallback jika tidak ada redirect, reload halaman
                window.location.reload();
            }

        } catch(error) {
            console.error('Submit error:', error);
            alert('Terjadi kesalahan saat mengirimkan formulir.');
            loadingOverlay.classList.remove('active');
        }
    });
</script>

    @vite(['resources/js/app.js'])
</body>
</html>