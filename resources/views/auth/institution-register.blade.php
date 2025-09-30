@extends('layouts.auth')

@section('title', 'Registrasi Instansi')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-primary-50 py-12 px-4">
    <div class="max-w-4xl mx-auto">
        <!-- header -->
        <div class="text-center mb-8 page-transition">
            <h2 class="text-4xl font-bold text-gray-900">registrasi instansi</h2>
            <p class="text-gray-600 mt-2">daftarkan instansi anda untuk menerbitkan program KKN</p>
        </div>

        <!-- form card -->
        <div class="bg-white rounded-2xl shadow-xl p-8 page-transition" style="animation-delay: 0.1s;">
            <form method="POST" action="{{ route('register.institution') }}" enctype="multipart/form-data" id="institutionRegisterForm">
                @csrf

                <!-- step indicator -->
                <div class="mb-8">
                    <div class="flex items-center justify-center space-x-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center font-semibold">
                                1
                            </div>
                            <span class="ml-2 text-sm font-medium text-gray-900">data instansi</span>
                        </div>
                        <div class="w-16 h-0.5 bg-gray-300"></div>
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center font-semibold">
                                2
                            </div>
                            <span class="ml-2 text-sm font-medium text-gray-500">penanggung jawab</span>
                        </div>
                        <div class="w-16 h-0.5 bg-gray-300"></div>
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center font-semibold">
                                3
                            </div>
                            <span class="ml-2 text-sm font-medium text-gray-500">akun & verifikasi</span>
                        </div>
                    </div>
                </div>

                <!-- step 1: data instansi -->
                <div id="step1" class="step-content">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">data instansi</h3>
                    
                    <div class="space-y-6">
                        <!-- nama instansi -->
                        <div>
                            <label for="institution_name" class="block text-sm font-medium text-gray-700 mb-2">
                                nama instansi <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="institution_name" 
                                   name="institution_name" 
                                   value="{{ old('institution_name') }}"
                                   placeholder="contoh: pemerintah desa sukamaju"
                                   class="input-field @error('institution_name') border-red-500 @enderror"
                                   required>
                            @error('institution_name')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- jenis instansi -->
                        <div>
                            <label for="institution_type" class="block text-sm font-medium text-gray-700 mb-2">
                                jenis instansi <span class="text-red-500">*</span>
                            </label>
                            <select id="institution_type" 
                                    name="institution_type" 
                                    class="input-field @error('institution_type') border-red-500 @enderror"
                                    required>
                                <option value="">pilih jenis instansi</option>
                                @foreach($institutionTypes as $key => $label)
                                    <option value="{{ $key }}" {{ old('institution_type') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('institution_type')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- alamat lengkap -->
                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                                alamat lengkap <span class="text-red-500">*</span>
                            </label>
                            <textarea id="address" 
                                      name="address" 
                                      rows="3"
                                      placeholder="jalan, nomor, kelurahan, kecamatan"
                                      class="input-field @error('address') border-red-500 @enderror"
                                      required>{{ old('address') }}</textarea>
                            @error('address')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- provinsi -->
                            <div>
                                <label for="province_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    provinsi <span class="text-red-500">*</span>
                                </label>
                                <select id="province_id" 
                                        name="province_id" 
                                        class="input-field @error('province_id') border-red-500 @enderror"
                                        required
                                        onchange="loadRegencies(this.value)">
                                    <option value="">pilih provinsi</option>
                                    {{-- TODO: loop provinces from database --}}
                                    <option value="1">contoh provinsi</option>
                                </select>
                                @error('province_id')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- kabupaten/kota -->
                            <div>
                                <label for="regency_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    kabupaten/kota <span class="text-red-500">*</span>
                                </label>
                                <select id="regency_id" 
                                        name="regency_id" 
                                        class="input-field @error('regency_id') border-red-500 @enderror"
                                        required>
                                    <option value="">pilih kabupaten/kota</option>
                                </select>
                                @error('regency_id')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- email resmi -->
                        <div>
                            <label for="official_email" class="block text-sm font-medium text-gray-700 mb-2">
                                email resmi instansi <span class="text-red-500">*</span>
                            </label>
                            <input type="email" 
                                   id="official_email" 
                                   name="official_email" 
                                   value="{{ old('official_email') }}"
                                   placeholder="contoh@instansi.go.id"
                                   class="input-field @error('official_email') border-red-500 @enderror"
                                   required>
                            @error('official_email')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- website (optional) -->
                        <div>
                            <label for="website" class="block text-sm font-medium text-gray-700 mb-2">
                                website instansi (opsional)
                            </label>
                            <input type="url" 
                                   id="website" 
                                   name="website" 
                                   value="{{ old('website') }}"
                                   placeholder="https://www.instansi.go.id"
                                   class="input-field @error('website') border-red-500 @enderror">
                            @error('website')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- deskripsi (optional) -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                deskripsi singkat instansi (opsional)
                            </label>
                            <textarea id="description" 
                                      name="description" 
                                      rows="4"
                                      placeholder="ceritakan tentang instansi anda..."
                                      class="input-field @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">maksimal 1000 karakter</p>
                            @error('description')
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

                <!-- step 2: penanggung jawab -->
                <div id="step2" class="step-content hidden">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">penanggung jawab</h3>
                    
                    <div class="space-y-6">
                        <!-- nama pic -->
                        <div>
                            <label for="pic_name" class="block text-sm font-medium text-gray-700 mb-2">
                                nama penanggung jawab <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="pic_name" 
                                   name="pic_name" 
                                   value="{{ old('pic_name') }}"
                                   placeholder="nama lengkap"
                                   class="input-field @error('pic_name') border-red-500 @enderror"
                                   required>
                            @error('pic_name')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- jabatan pic -->
                        <div>
                            <label for="pic_position" class="block text-sm font-medium text-gray-700 mb-2">
                                jabatan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="pic_position" 
                                   name="pic_position" 
                                   value="{{ old('pic_position') }}"
                                   placeholder="contoh: kepala desa, kepala dinas"
                                   class="input-field @error('pic_position') border-red-500 @enderror"
                                   required>
                            @error('pic_position')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- nomor telepon -->
                        <div>
                            <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-2">
                                nomor telepon <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" 
                                   id="phone_number" 
                                   name="phone_number" 
                                   value="{{ old('phone_number') }}"
                                   placeholder="08123456789"
                                   class="input-field @error('phone_number') border-red-500 @enderror"
                                   required>
                            @error('phone_number')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
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

                <!-- step 3: akun & verifikasi -->
                <div id="step3" class="step-content hidden">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">akun & verifikasi</h3>
                    
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
                                   placeholder="username unik untuk login"
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

                        <!-- logo instansi -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                logo instansi (opsional)
                            </label>
                            <input type="file" 
                                   name="logo" 
                                   accept="image/jpeg,image/jpg,image/png"
                                   class="input-field"
                                   onchange="previewImage(event, 'logoPreview')">
                            <p class="text-xs text-gray-500 mt-1">format: JPG, JPEG, PNG | max: 2MB</p>
                            @error('logo')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            
                            <!-- preview -->
                            <div id="logoPreview" class="mt-3 hidden">
                                <img src="" alt="preview logo" class="w-32 h-32 object-cover rounded-lg border-2 border-gray-200">
                            </div>
                        </div>

                        <!-- dokumen verifikasi -->
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <label class="block text-sm font-medium text-gray-900 mb-2">
                                dokumen verifikasi <span class="text-red-500">*</span>
                            </label>
                            <p class="text-xs text-gray-700 mb-3">
                                upload surat keterangan resmi dari instansi (SK pengangkatan, surat pengantar, atau dokumen resmi lainnya)
                            </p>
                            <input type="file" 
                                   name="verification_document" 
                                   accept="application/pdf"
                                   class="input-field"
                                   required
                                   onchange="showFileName(event)">
                            <p class="text-xs text-gray-500 mt-1">format: PDF | max: 5MB</p>
                            @error('verification_document')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <div id="fileName" class="text-xs text-green-600 mt-2 hidden"></div>
                        </div>

                        <!-- remember me -->
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="remember" 
                                   name="remember" 
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <label for="remember" class="ml-2 text-sm text-gray-700">
                                ingat saya
                            </label>
                        </div>

                        <!-- terms -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <label class="flex items-start">
                                <input type="checkbox" 
                                       required
                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 mt-1">
                                <span class="ml-2 text-sm text-gray-700">
                                    saya menyatakan bahwa data yang saya berikan adalah benar dan saya setuju dengan <a href="#" class="text-blue-600 hover:text-blue-700">syarat dan ketentuan</a> serta <a href="#" class="text-blue-600 hover:text-blue-700">kebijakan privasi</a> KKN-GO
                                </span>
                            </label>
                        </div>

                        <!-- info box -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                                <div class="text-sm text-blue-900">
                                    <p class="font-medium mb-1">verifikasi akun instansi</p>
                                    <p>akun anda akan diverifikasi oleh admin KKN-GO dalam 1-3 hari kerja. anda akan menerima notifikasi setelah akun disetujui.</p>
                                </div>
                            </div>
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
                    <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-700 font-medium transition-colors">
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
    document.querySelectorAll('.step-content').forEach(el => el.classList.add('hidden'));
    document.getElementById('step' + step).classList.remove('hidden');
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function prevStep(step) {
    document.querySelectorAll('.step-content').forEach(el => el.classList.add('hidden'));
    document.getElementById('step' + step).classList.remove('hidden');
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// preview image
function previewImage(event, previewId) {
    const preview = document.getElementById(previewId);
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

// show file name
function showFileName(event) {
    const fileName = document.getElementById('fileName');
    const file = event.target.files[0];
    
    if (file) {
        fileName.textContent = '✓ file terpilih: ' + file.name;
        fileName.classList.remove('hidden');
    }
}

// load regencies based on province
function loadRegencies(provinceId) {
    // TODO: implementasi ajax untuk load regencies
    console.log('loading regencies for province:', provinceId);
}
</script>
@endpush