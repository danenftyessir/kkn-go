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
            @method('PATCH')

            <div class="p-6 space-y-8">
                <!-- foto profil section -->
                <div class="pb-8 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">Foto Profil</h2>
                    <div class="flex items-center space-x-6" x-data="{ preview: '{{ $student->profile_photo_path ? asset('storage/' . $student->profile_photo_path) : '' }}' }">
                        <!-- current photo -->
                        <div class="flex-shrink-0">
                            <template x-if="preview">
                                <img :src="preview" alt="preview" class="w-32 h-32 rounded-full object-cover border-4 border-gray-200">
                            </template>
                            <template x-if="!preview">
                                <div class="w-32 h-32 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-4xl font-bold border-4 border-gray-200">
                                    {{ strtoupper(substr($student->first_name, 0, 1)) }}{{ strtoupper(substr($student->last_name, 0, 1)) }}
                                </div>
                            </template>
                        </div>
                        
                        <!-- upload button -->
                        <div>
                            <label for="profile_photo" class="cursor-pointer inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                Pilih Foto Baru
                            </label>
                            <input type="file" id="profile_photo" name="profile_photo" accept="image/jpeg,image/jpg,image/png" class="hidden" @change="preview = URL.createObjectURL($event.target.files[0])">
                            <p class="text-xs text-gray-500 mt-2">JPG, JPEG atau PNG. Maksimal 2MB.</p>
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
                        <!-- fields... -->
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">Nama Depan <span class="text-red-500">*</span></label>
                            <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $student->first_name) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('first_name') border-red-500 @enderror">
                            @error('first_name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">Nama Belakang <span class="text-red-500">*</span></label>
                            <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $student->last_name) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('last_name') border-red-500 @enderror">
                            @error('last_name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Nomor WhatsApp <span class="text-red-500">*</span></label>
                            <input type="text" id="phone" name="phone" value="{{ old('phone', $student->phone) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('phone') border-red-500 @enderror">
                            @error('phone') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="university_id" class="block text-sm font-medium text-gray-700 mb-2">Universitas <span class="text-red-500">*</span></label>
                            <select id="university_id" name="university_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('university_id') border-red-500 @enderror">
                                <option value="">Pilih Universitas</option>
                                @foreach($universities as $university)
                                <option value="{{ $university->id }}" {{ old('university_id', $student->university_id) == $university->id ? 'selected' : '' }}>{{ $university->name }}</option>
                                @endforeach
                            </select>
                            @error('university_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="major" class="block text-sm font-medium text-gray-700 mb-2">Jurusan <span class="text-red-500">*</span></label>
                            <input type="text" id="major" name="major" value="{{ old('major', $student->major) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('major') border-red-500 @enderror">
                            @error('major') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="semester" class="block text-sm font-medium text-gray-700 mb-2">Semester <span class="text-red-500">*</span></label>
                            <select id="semester" name="semester" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('semester') border-red-500 @enderror">
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
        
        <!-- Tambahkan x-data di sini -->
        <form action="{{ route('student.profile.password.update') }}" method="POST" class="p-6 space-y-6"
              x-data="{ showCurrent: false, showNew: false }">
            @csrf
            @method('PATCH')

            <h2 class="text-xl font-bold text-gray-900">Ubah Password</h2>
            
            <!-- password saat ini -->
            <div>
                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Password Saat Ini <span class="text-red-500">*</span></label>
                <div class="relative">
                    <input :type="showCurrent ? 'text' : 'password'" 
                           id="current_password" 
                           name="current_password" 
                           required 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all pr-10 @error('current_password') border-red-500 @enderror">
                    <!-- Tombol Peek -->
                    <button type="button" @click="showCurrent = !showCurrent" class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 hover:text-blue-600">
                        <svg x-show="!showCurrent" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        <svg x-show="showCurrent" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                    </button>
                </div>
                @error('current_password')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            
            <!-- password baru -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password Baru <span class="text-red-500">*</span></label>
                <div class="relative">
                    <input :type="showNew ? 'text' : 'password'"
                           id="password" 
                           name="password" 
                           required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all pr-10 @error('password') border-red-500 @enderror">
                    <!-- Tombol Peek -->
                    <button type="button" @click="showNew = !showNew" class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 hover:text-blue-600">
                        <svg x-show="!showNew" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        <svg x-show="showNew" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                    </button>
                </div>
                <p class="text-xs text-gray-500 mt-1">Minimal 8 karakter, kombinasi huruf besar, kecil, angka, dan simbol.</p>
                @error('password')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            
            <!-- konfirmasi password baru -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password Baru <span class="text-red-500">*</span></label>
                <div class="relative">
                    <input :type="showNew ? 'text' : 'password'" 
                           id="password_confirmation" 
                           name="password_confirmation" 
                           required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all pr-10">
                </div>
            </div>
            
            <div class="flex justify-end pt-4 border-t border-gray-200">
                <button type="submit" class="inline-flex items-center px-6 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900 transition-colors font-medium cursor-pointer">
                    Update Password
                </button>
            </div>
        </form>
    </div>
</div>
@endsection