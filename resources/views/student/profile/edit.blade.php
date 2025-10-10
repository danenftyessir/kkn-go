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

        <!-- pesan sukses/error -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <p class="text-green-800 font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-red-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <p class="text-red-800 font-medium">{{ session('error') }}</p>
                </div>
            </div>
        @endif

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
                    <div class="flex items-center space-x-6" x-data="{ preview: '{{ $student->profile_photo_url ?? '' }}' }">
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
                            <p class="text-xs text-gray-500 mt-2">Format: JPG, JPEG, PNG. Maksimal 2MB</p>
                            @error('profile_photo')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- informasi personal -->
                <div class="pb-8 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">Informasi Personal</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- nama depan -->
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">Nama Depan <span class="text-red-500">*</span></label>
                            <input type="text" 
                                   id="first_name" 
                                   name="first_name" 
                                   value="{{ old('first_name', $student->first_name) }}" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                   required>
                            @error('first_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- nama belakang -->
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">Nama Belakang <span class="text-red-500">*</span></label>
                            <input type="text" 
                                   id="last_name" 
                                   name="last_name" 
                                   value="{{ old('last_name', $student->last_name) }}" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                   required>
                            @error('last_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- email -->
                        <div class="md:col-span-2">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email', $user->email) }}" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all bg-gray-50"
                                   readonly>
                            <p class="text-xs text-gray-500 mt-1">Email tidak dapat diubah</p>
                        </div>
                    </div>
                </div>

                <!-- informasi akademik -->
                <div class="pb-8 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">Informasi Akademik</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- universitas -->
                        <div>
                            <label for="university_id" class="block text-sm font-medium text-gray-700 mb-2">Universitas <span class="text-red-500">*</span></label>
                            <select id="university_id" 
                                    name="university_id" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                    required>
                                <option value="">Pilih Universitas</option>
                                @foreach($universities as $university)
                                    <option value="{{ $university->id }}" {{ old('university_id', $student->university_id) == $university->id ? 'selected' : '' }}>
                                        {{ $university->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('university_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- jurusan -->
                        <div>
                            <label for="major" class="block text-sm font-medium text-gray-700 mb-2">Jurusan <span class="text-red-500">*</span></label>
                            <input type="text" 
                                   id="major" 
                                   name="major" 
                                   value="{{ old('major', $student->major) }}" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                   required>
                            @error('major')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- nim -->
                        <div>
                            <label for="nim" class="block text-sm font-medium text-gray-700 mb-2">NIM <span class="text-red-500">*</span></label>
                            <input type="text" 
                                   id="nim" 
                                   name="nim" 
                                   value="{{ old('nim', $student->nim) }}" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all bg-gray-50"
                                   readonly>
                            <p class="text-xs text-gray-500 mt-1">NIM tidak dapat diubah</p>
                        </div>

                        <!-- semester -->
                        <div>
                            <label for="semester" class="block text-sm font-medium text-gray-700 mb-2">Semester <span class="text-red-500">*</span></label>
                            <select id="semester" 
                                    name="semester" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                    required>
                                @for($i = 1; $i <= 14; $i++)
                                    <option value="{{ $i }}" {{ old('semester', $student->semester) == $i ? 'selected' : '' }}>
                                        Semester {{ $i }}
                                    </option>
                                @endfor
                            </select>
                            @error('semester')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- kontak -->
                <div class="pb-8 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">Kontak</h2>
                    <div>
                        <label for="whatsapp_number" class="block text-sm font-medium text-gray-700 mb-2">Nomor WhatsApp <span class="text-red-500">*</span></label>
                        <input type="tel" 
                               id="whatsapp_number" 
                               name="whatsapp_number" 
                               value="{{ old('whatsapp_number', $student->whatsapp_number ?? $student->phone) }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                               placeholder="contoh: 081234567890"
                               required>
                        @error('whatsapp_number')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- bio -->
                <div>
                    <h2 class="text-xl font-bold text-gray-900 mb-6">Bio & Tentang Saya</h2>
                    <div>
                        <label for="bio" class="block text-sm font-medium text-gray-700 mb-2">Bio</label>
                        <textarea id="bio" 
                                  name="bio" 
                                  rows="4" 
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all resize-none"
                                  placeholder="Ceritakan tentang diri Anda, minat, dan tujuan Anda...">{{ old('bio', $student->bio) }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">Maksimal 500 karakter</p>
                        @error('bio')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- tombol aksi -->
            <div class="bg-gray-50 px-6 py-4 flex items-center justify-between border-t border-gray-200">
                <a href="{{ route('student.profile.index') }}" class="text-gray-600 hover:text-gray-800 font-medium transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                    Simpan Perubahan
                </button>
            </div>
        </form>

        <!-- ========================================================== -->
        <!-- FORM 2: UNTUK UPDATE PASSWORD (TERPISAH)                   -->
        <!-- ========================================================== -->
        <form action="{{ route('student.profile.password.update') }}" method="POST" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden edit-container mt-6">
            @csrf
            @method('PUT')

            <div class="p-6 space-y-6">
                <h2 class="text-xl font-bold text-gray-900">Ubah Password</h2>

                <!-- password lama -->
                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Password Lama <span class="text-red-500">*</span></label>
                    <input type="password" 
                           id="current_password" 
                           name="current_password" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                           required>
                    @error('current_password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- password baru -->
                <div>
                    <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">Password Baru <span class="text-red-500">*</span></label>
                    <input type="password" 
                           id="new_password" 
                           name="new_password" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                           required>
                    @error('new_password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- konfirmasi password baru -->
                <div>
                    <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password Baru <span class="text-red-500">*</span></label>
                    <input type="password" 
                           id="new_password_confirmation" 
                           name="new_password_confirmation" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                           required>
                </div>
            </div>

            <!-- tombol aksi -->
            <div class="bg-gray-50 px-6 py-4 flex items-center justify-end border-t border-gray-200">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                    Ubah Password
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
// smooth scroll behavior sudah dihandle di CSS
document.addEventListener('DOMContentLoaded', function() {
    // auto hide success/error messages after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.bg-green-50, .bg-red-50');
        alerts.forEach(function(alert) {
            alert.style.transition = 'opacity 0.5s ease-out';
            alert.style.opacity = '0';
            setTimeout(function() {
                alert.remove();
            }, 500);
        });
    }, 5000);
});
</script>
@endpush
@endsection