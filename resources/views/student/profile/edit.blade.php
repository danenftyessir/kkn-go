@extends('layouts.app')

@section('title', 'Edit Profil')

@push('styles')
<style>
.edit-container {
    animation: fadeInUp 0.6s cubic-bezier(0.4, 0, 0.2, 1);
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- header -->
        <div class="mb-8 edit-container">
            <h1 class="text-3xl font-bold text-gray-900">Edit Profil</h1>
            <p class="text-gray-600 mt-2">Perbarui informasi profil dan foto Anda.</p>
        </div>

        <!-- ========================================================== -->
        <!-- FORM 1: UNTUK UPDATE PROFIL UTAMA                          -->
        <!-- ========================================================== -->
        <form action="{{ route('student.profile.update') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden edit-container">
            @csrf
            @method('PUT')

            <div class="p-6 space-y-8">
                <!-- foto profil section -->
                <div class="pb-8 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">Foto Profil</h2>
                    <div class="flex items-center space-x-6" x-data="{ preview: '{{ $student->profile_photo_path ? asset('storage/' . $student->profile_photo_path) : '' }}' }">
                        <!-- preview foto -->
                        <div class="relative">
                            <template x-if="preview">
                                <img :src="preview" alt="Preview" class="w-32 h-32 rounded-full object-cover border-4 border-gray-200">
                            </template>
                            <template x-if="!preview">
                                <div class="w-32 h-32 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-3xl font-bold border-4 border-gray-200">
                                    {{ strtoupper(substr($student->first_name, 0, 1)) }}{{ strtoupper(substr($student->last_name, 0, 1)) }}
                                </div>
                            </template>
                        </div>
                        
                        <!-- upload section -->
                        <div class="flex-1">
                            <label for="profile_photo" class="block text-sm font-medium text-gray-700 mb-2">Upload Foto Baru</label>
                            <input type="file" 
                                   id="profile_photo" 
                                   name="profile_photo" 
                                   accept="image/jpeg,image/jpg,image/png"
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer"
                                   @change="preview = URL.createObjectURL($event.target.files[0])">
                            <p class="text-xs text-gray-500 mt-2">Format: JPG, JPEG, PNG. Maksimal 2MB.</p>
                            @error('profile_photo')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- informasi pribadi & akademik (digabung) -->
                <div class="pb-8 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">Informasi Personal & Akademik</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- nama depan -->
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">Nama Depan <span class="text-red-500">*</span></label>
                            <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $student->first_name) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('first_name') border-red-500 @enderror">
                            @error('first_name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                        
                        <!-- nama belakang -->
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">Nama Belakang <span class="text-red-500">*</span></label>
                            <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $student->last_name) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('last_name') border-red-500 @enderror">
                            @error('last_name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                        
                        <!-- nomor whatsapp -->
                        <div>
                            <label for="whatsapp_number" class="block text-sm font-medium text-gray-700 mb-2">Nomor WhatsApp <span class="text-red-500">*</span></label>
                            <input type="text" id="whatsapp_number" name="whatsapp_number" value="{{ old('whatsapp_number', $student->whatsapp_number) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('whatsapp_number') border-red-500 @enderror" placeholder="Contoh: 08123456789">
                            @error('whatsapp_number') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                        
                        <!-- email (readonly) -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" id="email" name="email" value="{{ $user->email }}" readonly class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed">
                            <p class="text-xs text-gray-500 mt-1">Email tidak dapat diubah</p>
                        </div>
                        
                        <!-- universitas -->
                        <div>
                            <label for="university_id" class="block text-sm font-medium text-gray-700 mb-2">Universitas <span class="text-red-500">*</span></label>
                            <select id="university_id" name="university_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('university_id') border-red-500 @enderror">
                                <option value="">Pilih Universitas</option>
                                @foreach($universities as $university)
                                    <option value="{{ $university->id }}" {{ old('university_id', $student->university_id) == $university->id ? 'selected' : '' }}>
                                        {{ $university->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('university_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                        
                        <!-- jurusan -->
                        <div>
                            <label for="major" class="block text-sm font-medium text-gray-700 mb-2">Jurusan <span class="text-red-500">*</span></label>
                            <input type="text" id="major" name="major" value="{{ old('major', $student->major) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('major') border-red-500 @enderror">
                            @error('major') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                        
                        <!-- nim -->
                        <div>
                            <label for="nim" class="block text-sm font-medium text-gray-700 mb-2">NIM <span class="text-red-500">*</span></label>
                            <input type="text" id="nim" name="nim" value="{{ old('nim', $student->nim) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nim') border-red-500 @enderror">
                            @error('nim') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                        
                        <!-- semester -->
                        <div>
                            <label for="semester" class="block text-sm font-medium text-gray-700 mb-2">Semester <span class="text-red-500">*</span></label>
                            <select id="semester" name="semester" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('semester') border-red-500 @enderror">
                                <option value="">Pilih Semester</option>
                                @for($i = 1; $i <= 14; $i++)
                                    <option value="{{ $i }}" {{ old('semester', $student->semester) == $i ? 'selected' : '' }}>Semester {{ $i }}</option>
                                @endfor
                            </select>
                            @error('semester') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- bio -->
                <div>
                    <h2 class="text-xl font-bold text-gray-900 mb-6">Tentang Saya</h2>
                    <label for="bio" class="block text-sm font-medium text-gray-700 mb-2">Bio</label>
                    <textarea id="bio" name="bio" rows="4" maxlength="500" placeholder="Ceritakan sedikit tentang diri Anda..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('bio') border-red-500 @enderror">{{ old('bio', $student->bio) }}</textarea>
                    @error('bio') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- action buttons for profile form -->
            <div class="flex items-center justify-end p-6 bg-gray-50 rounded-b-xl">
                <a href="{{ route('student.profile.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors font-medium">Batal</a>
                <button type="submit" class="ml-4 inline-flex items-center px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium cursor-pointer">
                    Simpan Perubahan
                </button>
            </div>
        </form>

        <!-- ========================================================== -->
        <!-- FORM 2: UNTUK UPDATE PASSWORD (Dengan Fitur Peek)          -->
        <!-- ========================================================== -->
        <div class="mt-8 edit-container">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                
                <!-- tambahkan x-data di sini -->
                <form action="{{ route('student.profile.password.update') }}" method="POST" class="p-6 space-y-6"
                      x-data="{ showCurrent: false, showNew: false, showConfirm: false }">
                    @csrf
                    @method('PUT')

                    <h2 class="text-xl font-bold text-gray-900">Ubah Password</h2>
                    
                    <!-- password saat ini -->
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Password Saat Ini <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input :type="showCurrent ? 'text' : 'password'" 
                                   id="current_password" 
                                   name="current_password" 
                                   required 
                                   class="w-full px-4 py-2 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('current_password') border-red-500 @enderror">
                            <button type="button" 
                                    @click="showCurrent = !showCurrent" 
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                <svg x-show="!showCurrent" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg x-show="showCurrent" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                </svg>
                            </button>
                        </div>
                        @error('current_password') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    
                    <!-- password baru -->
                    <div>
                        <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">Password Baru <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input :type="showNew ? 'text' : 'password'" 
                                   id="new_password" 
                                   name="new_password" 
                                   required 
                                   class="w-full px-4 py-2 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('new_password') border-red-500 @enderror">
                            <button type="button" 
                                    @click="showNew = !showNew" 
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                <svg x-show="!showNew" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg x-show="showNew" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                </svg>
                            </button>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Minimal 8 karakter</p>
                        @error('new_password') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    
                    <!-- konfirmasi password baru -->
                    <div>
                        <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password Baru <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input :type="showConfirm ? 'text' : 'password'" 
                                   id="new_password_confirmation" 
                                   name="new_password_confirmation" 
                                   required 
                                   class="w-full px-4 py-2 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <button type="button" 
                                    @click="showConfirm = !showConfirm" 
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                <svg x-show="!showConfirm" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg x-show="showConfirm" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <!-- action buttons for password form -->
                    <div class="flex items-center justify-end pt-4">
                        <button type="submit" class="inline-flex items-center px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium cursor-pointer">
                            Ubah Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection