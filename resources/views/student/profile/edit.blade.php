@extends('layouts.student')

@section('title', 'Edit Profil')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 page-transition">
    <!-- header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">edit profil</h1>
                <p class="text-gray-600 mt-1">perbarui informasi profil anda</p>
            </div>
            <a href="{{ route('student.profile.index') }}" class="btn-secondary">
                <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                kembali
            </a>
        </div>
    </div>

    <form method="POST" action="{{ route('student.profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- foto profil -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6">foto profil</h2>
            
            <div class="flex items-center space-x-6">
                <!-- preview foto -->
                <div class="flex-shrink-0">
                    @if($student->profile_photo_url)
                        <img id="photoPreview" 
                             src="{{ asset('storage/' . $student->profile_photo_url) }}" 
                             alt="foto profil"
                             class="w-24 h-24 rounded-full object-cover border-4 border-gray-100">
                    @else
                        <div id="photoPreview" class="w-24 h-24 rounded-full bg-primary-100 flex items-center justify-center">
                            <span class="text-3xl font-bold text-primary-600">
                                {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                            </span>
                        </div>
                    @endif
                </div>

                <!-- upload controls -->
                <div class="flex-1">
                    <input type="file" 
                           name="profile_photo" 
                           id="profile_photo"
                           accept="image/jpeg,image/jpg,image/png"
                           class="hidden"
                           onchange="previewPhoto(event)">
                    
                    <div class="flex items-center space-x-3">
                        <button type="button" 
                                onclick="document.getElementById('profile_photo').click()"
                                class="btn-primary">
                            upload foto baru
                        </button>
                        
                        @if($student->profile_photo_url)
                            <button type="button" 
                                    onclick="deletePhoto()"
                                    class="btn-secondary text-red-600 hover:bg-red-50">
                                hapus foto
                            </button>
                        @endif
                    </div>
                    
                    <p class="text-xs text-gray-500 mt-2">
                        JPG, JPEG, atau PNG. maksimal 2MB
                    </p>
                    @error('profile_photo')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- data pribadi -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6">data pribadi</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- nama depan -->
                <div>
                    <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">
                        nama depan <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="first_name" 
                           name="first_name" 
                           value="{{ old('first_name', $student->first_name) }}"
                           class="input-field @error('first_name') border-red-500 @enderror"
                           required>
                    @error('first_name')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- nama belakang -->
                <div>
                    <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">
                        nama belakang <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="last_name" 
                           name="last_name" 
                           value="{{ old('last_name', $student->last_name) }}"
                           class="input-field @error('last_name') border-red-500 @enderror"
                           required>
                    @error('last_name')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- nomor whatsapp -->
                <div class="md:col-span-2">
                    <label for="whatsapp_number" class="block text-sm font-medium text-gray-700 mb-2">
                        nomor whatsapp <span class="text-red-500">*</span>
                    </label>
                    <input type="tel" 
                           id="whatsapp_number" 
                           name="whatsapp_number" 
                           value="{{ old('whatsapp_number', $student->whatsapp_number) }}"
                           class="input-field @error('whatsapp_number') border-red-500 @enderror"
                           required>
                    @error('whatsapp_number')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- data akademik -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6">data akademik</h2>
            
            <div class="space-y-6">
                <!-- universitas -->
                <div>
                    <label for="university_id" class="block text-sm font-medium text-gray-700 mb-2">
                        universitas <span class="text-red-500">*</span>
                    </label>
                    <select id="university_id" 
                            name="university_id" 
                            class="input-field @error('university_id') border-red-500 @enderror"
                            required>
                        <option value="">pilih universitas</option>
                        {{-- TODO: loop universities from database --}}
                        <option value="{{ $student->university_id }}" selected>universitas saat ini</option>
                    </select>
                    @error('university_id')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- jurusan -->
                <div>
                    <label for="major" class="block text-sm font-medium text-gray-700 mb-2">
                        jurusan / program studi <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="major" 
                           name="major" 
                           value="{{ old('major', $student->major) }}"
                           class="input-field @error('major') border-red-500 @enderror"
                           required>
                    @error('major')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- semester -->
                <div>
                    <label for="semester" class="block text-sm font-medium text-gray-700 mb-2">
                        semester <span class="text-red-500">*</span>
                    </label>
                    <select id="semester" 
                            name="semester" 
                            class="input-field @error('semester') border-red-500 @enderror"
                            required>
                        @for($i = 1; $i <= 14; $i++)
                            <option value="{{ $i }}" {{ old('semester', $student->semester) == $i ? 'selected' : '' }}>
                                semester {{ $i }}
                            </option>
                        @endfor
                    </select>
                    @error('semester')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- tentang saya -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6">tentang saya</h2>
            
            <div>
                <label for="bio" class="block text-sm font-medium text-gray-700 mb-2">
                    bio / deskripsi singkat
                </label>
                <textarea id="bio" 
                          name="bio" 
                          rows="5"
                          placeholder="ceritakan tentang diri anda, minat, pengalaman, atau hal lain yang ingin anda bagikan..."
                          class="input-field @error('bio') border-red-500 @enderror">{{ old('bio', $student->bio) }}</textarea>
                <p class="text-xs text-gray-500 mt-1">maksimal 500 karakter</p>
                @error('bio')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- keahlian -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6">keahlian</h2>
            
            <div>
                <label for="skills" class="block text-sm font-medium text-gray-700 mb-2">
                    daftar keahlian
                </label>
                <input type="text" 
                       id="skills" 
                       name="skills" 
                       value="{{ old('skills') }}"
                       placeholder="contoh: web development, data analysis, public speaking"
                       class="input-field @error('skills') border-red-500 @enderror">
                <p class="text-xs text-gray-500 mt-1">pisahkan dengan koma (,)</p>
                @error('skills')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            {{-- TODO: tampilkan existing skills sebagai tags yang bisa dihapus --}}
            <div class="mt-4 flex flex-wrap gap-2">
                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                    contoh skill 1 ×
                </span>
                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                    contoh skill 2 ×
                </span>
            </div>
        </div>

        <!-- action buttons -->
        <div class="flex justify-between items-center">
            <a href="{{ route('student.profile.index') }}" class="btn-secondary">
                batal
            </a>
            <button type="submit" class="btn-primary">
                simpan perubahan
            </button>
        </div>
    </form>
</div>

<!-- delete photo form (hidden) -->
<form id="deletePhotoForm" 
      method="POST" 
      action="{{ route('student.profile.photo.delete') }}"
      class="hidden">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
// preview photo
function previewPhoto(event) {
    const preview = document.getElementById('photoPreview');
    const file = event.target.files[0];
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            // ganti konten dengan img baru
            preview.innerHTML = `<img src="${e.target.result}" alt="preview" class="w-24 h-24 rounded-full object-cover border-4 border-gray-100">`;
        }
        reader.readAsDataURL(file);
    }
}

// delete photo
function deletePhoto() {
    if (confirm('apakah anda yakin ingin menghapus foto profil?')) {
        document.getElementById('deletePhotoForm').submit();
    }
}
</script>
@endpush