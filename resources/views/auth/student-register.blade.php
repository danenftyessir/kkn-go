@extends('layouts.auth')

@section('title', 'Registrasi Mahasiswa')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-primary-50 via-white to-blue-50 py-12 px-4">
    <div class="max-w-4xl mx-auto">
        <!-- header -->
        <div class="text-center mb-8 page-transition">
            <h2 class="text-4xl font-bold text-gray-900">registrasi mahasiswa</h2>
            <p class="text-gray-600 mt-2">lengkapi data diri anda untuk bergabung</p>
        </div>

        <!-- form card -->
        <div class="bg-white rounded-2xl shadow-xl p-8 page-transition" style="animation-delay: 0.1s;">
            <form method="POST" action="{{ route('register.student') }}" enctype="multipart/form-data" id="studentRegisterForm">
                @csrf

                <!-- step indicator -->
                <div class="mb-8">
                    <div class="flex items-center justify-center space-x-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-primary-600 text-white rounded-full flex items-center justify-center font-semibold">
                                1
                            </div>
                            <span class="ml-2 text-sm font-medium text-gray-900">data pribadi</span>
                        </div>
                        <div class="w-16 h-0.5 bg-gray-300"></div>
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center font-semibold">
                                2
                            </div>
                            <span class="ml-2 text-sm font-medium text-gray-500">data akademik</span>
                        </div>
                        <div class="w-16 h-0.5 bg-gray-300"></div>
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center font-semibold">
                                3
                            </div>
                            <span class="ml-2 text-sm font-medium text-gray-500">akun</span>
                        </div>
                    </div>
                </div>

                <!-- step 1: data pribadi -->
                <div id="step1" class="step-content">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">data pribadi</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- nama depan -->
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">
                                nama depan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="first_name" 
                                   name="first_name" 
                                   value="{{ old('first_name') }}"
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
                                   value="{{ old('last_name') }}"
                                   class="input-field @error('last_name') border-red-500 @enderror"
                                   required>
                            @error('last_name')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- email -->
                        <div class="md:col-span-2">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                email universitas <span class="text-red-500">*</span>
                            </label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}"
                                   placeholder="contoh@student.university.ac.id"
                                   class="input-field @error('email') border-red-500 @enderror"
                                   required>
                            <p class="text-xs text-gray-500 mt-1">gunakan email dengan domain .ac.id atau .edu</p>
                            @error('email')
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
                                   value="{{ old('whatsapp_number') }}"
                                   placeholder="08123456789"
                                   class="input-field @error('whatsapp_number') border-red-500 @enderror"
                                   required>
                            @error('whatsapp_number')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="button" onclick="nextStep(2)" class="btn-primary">
                            selanjutnya
                        </button>
                    </div>
                </div>

                <!-- step 2: data akademik -->
                <div id="step2" class="step-content hidden">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">data akademik</h3>
                    
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
                                <option value="1">contoh universitas</option>
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
                                   value="{{ old('major') }}"
                                   placeholder="contoh: teknik informatika"
                                   class="input-field @error('major') border-red-500 @enderror"
                                   required>
                            @error('major')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- nim -->
                            <div>
                                <label for="nim" class="block text-sm font-medium text-gray-700 mb-2">
                                    nim <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="nim" 
                                       name="nim" 
                                       value="{{ old('nim') }}"
                                       placeholder="123456789"
                                       class="input-field @error('nim') border-red-500 @enderror"
                                       required>
                                @error('nim')
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
                                    <option value="">pilih semester</option>
                                    @for($i = 1; $i <= 14; $i++)
                                        <option value="{{ $i }}" {{ old('semester') == $i ? 'selected' : '' }}>
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

                    <div class="mt-6 flex justify-between">
                        <button type="button" onclick="prevStep(1)" class="btn-secondary">
                            kembali
                        </button>
                        <button type="button" onclick="nextStep(3)" class="btn-primary">
                            selanjutnya
                        </button>
                    </div>
                </div>

                <!-- step 3: akun -->
                <div id="step3" class="step-content hidden">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">buat akun</h3>
                    
                    <div class="space-y-6">
                        <!-- username -->
                        <div>
                            <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                                username <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="username" 
                                   name="username" 
                                   value="{{ old('username') }}"
                                   placeholder="username unik anda"
                                   class="input-field @error('username') border-red-500 @enderror"
                                   required>
                            <p class="text-xs text-gray-500 mt-1">minimal 4 karakter, hanya huruf, angka, titik, underscore, dan strip</p>
                            @error('username')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                password <span class="text-red-500">*</span>
                            </label>
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   class="input-field @error('password') border-red-500 @enderror"
                                   required>
                            <p class="text-xs text-gray-500 mt-1">minimal 8 karakter dengan huruf besar, kecil, angka, dan simbol</p>
                            @error('password')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- confirm password -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                konfirmasi password <span class="text-red-500">*</span>
                            </label>
                            <input type="password" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   class="input-field"
                                   required>
                        </div>

                        <!-- foto profil -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                foto profil (opsional)
                            </label>
                            <input type="file" 
                                   name="profile_photo" 
                                   accept="image/jpeg,image/jpg,image/png"
                                   class="input-field"
                                   onchange="previewImage(event)">
                            <p class="text-xs text-gray-500 mt-1">format: JPG, JPEG, PNG | max: 2MB</p>
                            @error('profile_photo')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            
                            <!-- preview -->
                            <div id="imagePreview" class="mt-3 hidden">
                                <img src="" alt="preview" class="w-32 h-32 object-cover rounded-lg">
                            </div>
                        </div>

                        <!-- remember me -->
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="remember" 
                                   name="remember" 
                                   class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                            <label for="remember" class="ml-2 text-sm text-gray-700">
                                ingat saya
                            </label>
                        </div>

                        <!-- terms -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <label class="flex items-start">
                                <input type="checkbox" 
                                       required
                                       class="rounded border-gray-300 text-primary-600 focus:ring-primary-500 mt-1">
                                <span class="ml-2 text-sm text-gray-700">
                                    saya setuju dengan <a href="#" class="text-primary-600 hover:text-primary-700">syarat dan ketentuan</a> serta <a href="#" class="text-primary-600 hover:text-primary-700">kebijakan privasi</a> KKN-GO
                                </span>
                            </label>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-between">
                        <button type="button" onclick="prevStep(2)" class="btn-secondary">
                            kembali
                        </button>
                        <button type="submit" class="btn-primary">
                            daftar sekarang
                        </button>
                    </div>
                </div>

                <!-- login link -->
                <div class="mt-6 text-center text-sm text-gray-600">
                    sudah punya akun?
                    <a href="{{ route('login') }}" class="text-primary-600 hover:text-primary-700 font-medium transition-colors">
                        login di sini
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// multi-step form navigation
function nextStep(step) {
    // hide current step
    document.querySelectorAll('.step-content').forEach(el => el.classList.add('hidden'));
    
    // show next step
    document.getElementById('step' + step).classList.remove('hidden');
    
    // update indicators (TODO)
    
    // smooth scroll to top
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function prevStep(step) {
    // hide current step
    document.querySelectorAll('.step-content').forEach(el => el.classList.add('hidden'));
    
    // show previous step
    document.getElementById('step' + step).classList.remove('hidden');
    
    // update indicators (TODO)
    
    // smooth scroll to top
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// preview image
function previewImage(event) {
    const preview = document.getElementById('imagePreview');
    const file = event.target.files[0];
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.querySelector('img').src = e.target.result;
            preview.classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    }
}
</script>
@endpush