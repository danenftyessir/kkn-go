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

.form-group {
    transition: all 0.3s ease;
}

.form-group:focus-within label {
    color: #3b82f6;
}

.photo-preview {
    transition: all 0.3s ease;
}

.photo-preview:hover {
    transform: scale(1.05);
}
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- breadcrumb -->
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('home') }}" class="text-gray-600 hover:text-blue-600 transition-colors">
                        beranda
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <a href="{{ route('student.profile.index') }}" class="ml-1 text-gray-600 hover:text-blue-600 transition-colors">
                            profil
                        </a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-gray-500">edit profil</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- header -->
        <div class="mb-8 edit-container">
            <h1 class="text-3xl font-bold text-gray-900">edit profil</h1>
            <p class="text-gray-600 mt-2">perbarui informasi profil dan foto anda</p>
        </div>

        <!-- form -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden edit-container">
            <form action="{{ route('student.profile.update') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-8">
                @csrf
                @method('PATCH')

                <!-- foto profil section -->
                <div class="pb-8 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">foto profil</h2>
                    
                    <div class="flex items-center space-x-6" x-data="{ preview: '{{ $student->profile_photo_path ? asset('storage/' . $student->profile_photo_path) : '' }}' }">
                        <!-- current photo -->
                        <div class="flex-shrink-0">
                            <template x-if="preview">
                                <img :src="preview" alt="preview" class="w-32 h-32 rounded-full object-cover border-4 border-gray-200 photo-preview">
                            </template>
                            <template x-if="!preview">
                                <div class="w-32 h-32 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-4xl font-bold border-4 border-gray-200">
                                    {{ strtoupper(substr($student->first_name, 0, 1)) }}{{ strtoupper(substr($student->last_name, 0, 1)) }}
                                </div>
                            </template>
                        </div>
                        
                        <!-- upload button -->
                        <div class="flex-1">
                            <label for="profile_photo" class="cursor-pointer inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                pilih foto baru
                            </label>
                            <input type="file" 
                                   id="profile_photo" 
                                   name="profile_photo" 
                                   accept="image/jpeg,image/jpg,image/png"
                                   class="hidden"
                                   @change="preview = URL.createObjectURL($event.target.files[0])">
                            <p class="text-xs text-gray-500 mt-2">JPG, JPEG atau PNG. maksimal 2MB.</p>
                            @error('profile_photo')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- informasi pribadi -->
                <div class="pb-8 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">informasi pribadi</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- nama depan -->
                        <div class="form-group">
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">
                                nama depan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="first_name" 
                                   name="first_name" 
                                   value="{{ old('first_name', $student->first_name) }}"
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('first_name') border-red-500 @enderror">
                            @error('first_name')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- nama belakang -->
                        <div class="form-group">
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">
                                nama belakang <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="last_name" 
                                   name="last_name" 
                                   value="{{ old('last_name', $student->last_name) }}"
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('last_name') border-red-500 @enderror">
                            @error('last_name')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- email (readonly) -->
                        <div class="form-group">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                email
                            </label>
                            <input type="email" 
                                   id="email" 
                                   value="{{ $user->email }}"
                                   readonly
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-500 cursor-not-allowed">
                            <p class="text-xs text-gray-500 mt-1">email tidak dapat diubah</p>
                        </div>

                        <!-- nomor whatsapp -->
                        <div class="form-group">
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                nomor whatsapp <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="phone" 
                                   name="phone" 
                                   value="{{ old('phone', $student->phone) }}"
                                   placeholder="08123456789"
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('phone') border-red-500 @enderror">
                            @error('phone')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- informasi akademik -->
                <div class="pb-8 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">informasi akademik</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- universitas -->
                        <div class="form-group">
                            <label for="university_id" class="block text-sm font-medium text-gray-700 mb-2">
                                universitas <span class="text-red-500">*</span>
                            </label>
                            <select id="university_id" 
                                    name="university_id" 
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('university_id') border-red-500 @enderror">
                                <option value="">pilih universitas</option>
                                @foreach($universities as $university)
                                <option value="{{ $university->id }}" {{ old('university_id', $student->university_id) == $university->id ? 'selected' : '' }}>
                                    {{ $university->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('university_id')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- jurusan -->
                        <div class="form-group">
                            <label for="major" class="block text-sm font-medium text-gray-700 mb-2">
                                jurusan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="major" 
                                   name="major" 
                                   value="{{ old('major', $student->major) }}"
                                   placeholder="contoh: teknik informatika"
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('major') border-red-500 @enderror">
                            @error('major')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- nim (readonly) -->
                        <div class="form-group">
                            <label for="nim" class="block text-sm font-medium text-gray-700 mb-2">
                                nim
                            </label>
                            <input type="text" 
                                   id="nim" 
                                   value="{{ $student->nim }}"
                                   readonly
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-500 cursor-not-allowed">
                            <p class="text-xs text-gray-500 mt-1">nim tidak dapat diubah</p>
                        </div>

                        <!-- semester -->
                        <div class="form-group">
                            <label for="semester" class="block text-sm font-medium text-gray-700 mb-2">
                                semester <span class="text-red-500">*</span>
                            </label>
                            <select id="semester" 
                                    name="semester" 
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('semester') border-red-500 @enderror">
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

                <!-- bio -->
                <div class="pb-8 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">tentang saya</h2>
                    
                    <div class="form-group">
                        <label for="bio" class="block text-sm font-medium text-gray-700 mb-2">
                            bio
                        </label>
                        <textarea id="bio" 
                                  name="bio" 
                                  rows="4"
                                  maxlength="500"
                                  placeholder="ceritakan sedikit tentang diri anda..."
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('bio') border-red-500 @enderror">{{ old('bio', $student->bio) }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">maksimal 500 karakter</p>
                        @error('bio')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- TODO: skills section -->
                
                <!-- action buttons -->
                <div class="flex items-center justify-between pt-6">
                    <a href="{{ route('student.profile.index') }}" 
                       class="inline-flex items-center px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors font-medium">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        batal
                    </a>
                    
                    <button type="submit" 
                            class="inline-flex items-center px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        simpan perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // form validation
    const form = document.querySelector('form');
    
    form.addEventListener('submit', function(e) {
        const phoneInput = document.getElementById('phone');
        const phoneValue = phoneInput.value;
        
        // validasi format nomor whatsapp
        const phoneRegex = /^(08|628|\+628)[0-9]{9,12}$/;
        
        if (!phoneRegex.test(phoneValue)) {
            e.preventDefault();
            alert('format nomor whatsapp tidak valid. gunakan format: 08xxxxxxxxx');
            phoneInput.focus();
            return false;
        }
    });
    
    // character counter untuk bio
    const bioTextarea = document.getElementById('bio');
    if (bioTextarea) {
        const maxLength = 500;
        const counterElement = document.createElement('p');
        counterElement.className = 'text-xs text-gray-500 mt-1';
        
        const updateCounter = () => {
            const remaining = maxLength - bioTextarea.value.length;
            counterElement.textContent = `${remaining} karakter tersisa`;
            
            if (remaining < 50) {
                counterElement.classList.remove('text-gray-500');
                counterElement.classList.add('text-yellow-600');
            } else {
                counterElement.classList.remove('text-yellow-600');
                counterElement.classList.add('text-gray-500');
            }
        };
        
        bioTextarea.parentElement.appendChild(counterElement);
        bioTextarea.addEventListener('input', updateCounter);
        updateCounter();
    }
});
</script>
@endpush