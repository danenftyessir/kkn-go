@extends('layouts.auth')

@section('title', 'Daftar Sebagai Mahasiswa - KKN-GO')

@push('styles')
<style>
    .register-container {
        min-height: 100vh;
        background: linear-gradient(135deg, #93c5fd 0%, #67e8f9 50%, #5eead4 100%);
        position: relative;
        overflow: hidden;
    }
    
    .register-container::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(147, 197, 253, 0.3) 0%, transparent 50%, rgba(94, 234, 212, 0.3) 100%);
    }
</style>
@endpush

@section('content')
<div class="register-container student-register gpu-accelerated">
    <div class="relative z-10 flex items-center justify-center min-h-screen py-12 px-4">
        <div class="w-full max-w-4xl">
            <!-- logo & header -->
            <div class="text-center mb-8 fade-in-up">
                <a href="{{ route('home') }}" class="inline-block mb-6">
                    <img src="{{ asset('kkn-go-logo.png') }}" alt="KKN-GO" class="h-16 w-auto">
                </a>
                <h1 class="text-4xl font-bold text-gray-900 mb-2">Bergabung Bersama KKN-GO! 🚀</h1>
                <p class="text-gray-600 text-lg">Wujudkan dampak positif untuk Indonesia</p>
            </div>

            <!-- form container -->
            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden fade-in-scale" style="animation-delay: 0.1s;">
                <!-- progress steps -->
                <div class="bg-gradient-to-r from-blue-400 to-teal-400 px-8 py-6">
                    <div class="step-indicator-container">
                        <div class="step-item">
                            <div class="step-circle active" id="step1-circle">
                                <span class="step-number">1</span>
                            </div>
                            <span class="step-label text-white">Data Pribadi</span>
                        </div>
                        
                        <div class="step-connector" id="connector1"></div>
                        
                        <div class="step-item">
                            <div class="step-circle inactive" id="step2-circle">
                                <span class="step-number">2</span>
                            </div>
                            <span class="step-label text-white/70">Data Akademik</span>
                        </div>
                        
                        <div class="step-connector" id="connector2"></div>
                        
                        <div class="step-item">
                            <div class="step-circle inactive" id="step3-circle">
                                <span class="step-number">3</span>
                            </div>
                            <span class="step-label text-white/70">Buat Akun</span>
                        </div>
                    </div>
                </div>

                <!-- form content -->
                <form method="POST" action="{{ route('register.student.store') }}" 
                      enctype="multipart/form-data" 
                      id="studentRegisterForm"
                      class="p-8">
                    @csrf

                    <!-- step 1: data pribadi -->
                    <div id="step1-content" class="step-content">
                        <div class="mb-8">
                            <h2 class="text-2xl font-bold text-gray-800 mb-2">Data Pribadi 👤</h2>
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
                                           required
                                           autofocus>
                                    <svg class="form-input-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                    <svg class="form-input-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                            <div class="form-field-group md:col-span-2">
                                <label for="email" class="form-label required">
                                    Email Universitas
                                    <span class="tooltip ml-2">
                                        <svg class="w-4 h-4 inline text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="tooltip-text">Gunakan email dengan domain .ac.id atau .edu</span>
                                    </span>
                                </label>
                                <div class="form-input-wrapper">
                                    <input type="email" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email') }}"
                                           placeholder="nama@student.university.ac.id"
                                           class="form-input @error('email') error @enderror"
                                           required>
                                    <svg class="form-input-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                            </div>

                            <!-- nomor whatsapp -->
                            <div class="form-field-group md:col-span-2">
                                <label for="whatsapp_number" class="form-label required">Nomor WhatsApp</label>
                                <div class="form-input-wrapper">
                                    <input type="tel" 
                                           id="whatsapp_number" 
                                           name="whatsapp_number" 
                                           value="{{ old('whatsapp_number') }}"
                                           placeholder="08123456789"
                                           class="form-input @error('whatsapp_number') error @enderror"
                                           required>
                                    <svg class="form-input-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                </div>
                                @error('whatsapp_number')
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

                    <!-- step 2: data akademik -->
                    <div id="step2-content" class="step-content hidden">
                        <div class="mb-8">
                            <h2 class="text-2xl font-bold text-gray-800 mb-2">Data Akademik 🎓</h2>
                            <p class="text-gray-600">Informasi kampus dan jurusan kamu</p>
                        </div>

                        <!-- fun fact -->
                        <div class="bg-gradient-to-r from-blue-50 to-teal-50 border border-blue-200 rounded-lg p-4 mb-6">
                            <p class="text-sm text-gray-800">
                                💡 Fun Fact: KKN-GO sudah bermitra dengan 50+ universitas di Indonesia!
                            </p>
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
                                        @foreach($universities ?? [] as $university)
                                            <option value="{{ $university->id }}" {{ old('university_id') == $university->id ? 'selected' : '' }}>
                                                {{ $university->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <svg class="form-input-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 14l9-5-9-5-9 5 9 5z"></path>
                                        <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path>
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
                                <label for="major" class="form-label required">Jurusan / Program Studi</label>
                                <div class="form-input-wrapper">
                                    <input type="text" 
                                           id="major" 
                                           name="major" 
                                           value="{{ old('major') }}"
                                           placeholder="Contoh: Teknik Informatika"
                                           class="form-input @error('major') error @enderror"
                                           required>
                                    <svg class="form-input-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- nim -->
                                <div class="form-field-group">
                                    <label for="nim" class="form-label required">NIM</label>
                                    <div class="form-input-wrapper">
                                        <input type="text" 
                                               id="nim" 
                                               name="nim" 
                                               value="{{ old('nim') }}"
                                               placeholder="123456789"
                                               class="form-input @error('nim') error @enderror"
                                               required>
                                        <svg class="form-input-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

                                <!-- semester -->
                                <div class="form-field-group">
                                    <label for="semester" class="form-label required">Semester</label>
                                    <div class="form-input-wrapper">
                                        <select id="semester" 
                                                name="semester" 
                                                class="form-input form-select @error('semester') error @enderror"
                                                required>
                                            <option value="">-- Pilih Semester --</option>
                                            @for($i = 1; $i <= 14; $i++)
                                                <option value="{{ $i }}" {{ old('semester') == $i ? 'selected' : '' }}>
                                                    Semester {{ $i }}
                                                </option>
                                            @endfor
                                        </select>
                                        <svg class="form-input-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

                    <!-- step 3: buat akun -->
                    <div id="step3-content" class="step-content hidden">
                        <div class="mb-8">
                            <h2 class="text-2xl font-bold text-gray-800 mb-2">Buat Akun 🔐</h2>
                            <p class="text-gray-600">Langkah terakhir! Buat username dan password kamu</p>
                        </div>

                        <div class="space-y-6">
                            <!-- username -->
                            <div class="form-field-group">
                                <label for="username" class="form-label required">
                                    Username
                                    <span class="text-xs text-gray-500 ml-2">(untuk login)</span>
                                </label>
                                <div class="form-input-wrapper">
                                    <input type="text" 
                                           id="username" 
                                           name="username" 
                                           value="{{ old('username') }}"
                                           placeholder="budisantoso123"
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
                                           minlength="8"
                                           onkeyup="checkPasswordStrength(this.value)">
                                    <svg class="form-input-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                <div class="password-strength mt-2" id="password-strength">
                                    <div class="strength-bar" id="strength-1"></div>
                                    <div class="strength-bar" id="strength-2"></div>
                                    <div class="strength-bar" id="strength-3"></div>
                                    <div class="strength-bar" id="strength-4"></div>
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

                            <!-- password confirmation -->
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

                            <!-- foto profil -->
                            <div class="form-field-group">
                                <label class="form-label">
                                    Foto Profil
                                    <span class="text-xs text-gray-500 ml-2">(opsional)</span>
                                </label>
                                <div class="file-upload-wrapper">
                                    <div class="file-upload-area" onclick="document.getElementById('profile_photo').click()">
                                        <svg class="file-upload-icon mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <p class="font-medium text-gray-700 mb-1">Klik untuk upload foto</p>
                                        <p class="text-xs text-gray-500">atau drag & drop file kesini</p>
                                        <p class="text-xs text-gray-400 mt-2">JPG, PNG max 2MB</p>
                                    </div>
                                    <input type="file" 
                                           id="profile_photo"
                                           name="profile_photo" 
                                           accept="image/jpeg,image/jpg,image/png"
                                           class="hidden"
                                           onchange="previewImage(event)">
                                    
                                    <div id="imagePreview" class="file-preview hidden mt-4">
                                        <img src="" alt="Preview" class="w-20 h-20 rounded-full object-cover">
                                        <div class="flex-1">
                                            <p class="font-medium text-gray-700" id="fileName"></p>
                                            <p class="text-xs text-gray-500" id="fileSize"></p>
                                        </div>
                                        <button type="button" 
                                                onclick="removeImage()"
                                                class="text-red-500 hover:text-red-700">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                        </button>
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

                            <!-- terms & conditions -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <label class="flex items-start cursor-pointer">
                                    <input type="checkbox" 
                                           name="terms"
                                           required
                                           class="mt-1 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">
                                        Saya setuju dengan 
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
                                <span class="btn-text">Daftar Sekarang! 🎉</span>
                            </button>
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
            <p class="text-gray-700 font-medium">Mendaftarkan akun kamu...</p>
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
    // validasi step sebelumnya
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
    
    currentStep = step;
    
    // smooth scroll to top
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function prevStep(step) {
    // hide current step
    document.getElementById(`step${currentStep}-content`).classList.add('hidden');
    document.getElementById(`step${currentStep}-circle`).classList.remove('active');
    document.getElementById(`step${currentStep}-circle`).classList.add('inactive');
    
    // show previous step
    document.getElementById(`step${step}-content`).classList.remove('hidden');
    document.getElementById(`step${step}-circle`).classList.remove('completed');
    document.getElementById(`step${step}-circle`).classList.add('active');
    
    // update connector
    if (step < currentStep) {
        document.getElementById(`connector${step}`).classList.remove('completed');
    }
    
    currentStep = step;
    
    // smooth scroll to top
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

// password strength checker
function checkPasswordStrength(password) {
    let strength = 0;
    
    // check length
    if (password.length >= 8) strength++;
    if (password.length >= 12) strength++;
    
    // check for lowercase and uppercase
    if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
    
    // check for numbers
    if (/\d/.test(password)) strength++;
    
    // check for special characters
    if (/[^a-zA-Z\d]/.test(password)) strength++;
    
    // update UI
    const bars = document.querySelectorAll('.strength-bar');
    bars.forEach((bar, index) => {
        if (index < strength) {
            bar.classList.add('active');
            if (strength <= 2) bar.classList.add('weak');
            else if (strength <= 3) bar.classList.add('medium');
            else bar.classList.add('strong');
        } else {
            bar.classList.remove('active', 'weak', 'medium', 'strong');
        }
    });
}

// toggle password visibility
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const type = input.type === 'password' ? 'text' : 'password';
    input.type = type;
}

// image preview
function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('imagePreview');
            preview.querySelector('img').src = e.target.result;
            document.getElementById('fileName').textContent = file.name;
            document.getElementById('fileSize').textContent = (file.size / 1024 / 1024).toFixed(2) + ' MB';
            preview.classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    }
}

function removeImage() {
    document.getElementById('profile_photo').value = '';
    document.getElementById('imagePreview').classList.add('hidden');
}

// form submission
document.getElementById('studentRegisterForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // validate all steps
    if (!validateStep(1) || !validateStep(2) || !validateStep(3)) {
        alert('Mohon lengkapi semua field yang wajib diisi');
        return;
    }
    
    // show loading
    document.getElementById('loadingOverlay').classList.add('active');
    document.getElementById('submitBtn').disabled = true;
    
    // submit form
    this.submit();
});

// drag and drop
const fileUploadArea = document.querySelector('.file-upload-area');

['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    fileUploadArea.addEventListener(eventName, preventDefaults, false);
});

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

['dragenter', 'dragover'].forEach(eventName => {
    fileUploadArea.addEventListener(eventName, highlight, false);
});

['dragleave', 'drop'].forEach(eventName => {
    fileUploadArea.addEventListener(eventName, unhighlight, false);
});

function highlight(e) {
    fileUploadArea.classList.add('dragover');
}

function unhighlight(e) {
    fileUploadArea.classList.remove('dragover');
}

fileUploadArea.addEventListener('drop', handleDrop, false);

function handleDrop(e) {
    const dt = e.dataTransfer;
    const files = dt.files;
    
    document.getElementById('profile_photo').files = files;
    previewImage({ target: { files: files } });
}
</script>
@endpush