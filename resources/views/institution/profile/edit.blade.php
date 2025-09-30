@extends('layouts.institution')

@section('title', 'Edit Profil Instansi')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 page-transition">
    <!-- header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">edit profil instansi</h1>
                <p class="text-gray-600 mt-1">perbarui informasi instansi anda</p>
            </div>
            <a href="{{ route('institution.profile.index') }}" class="btn-secondary">
                <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                kembali
            </a>
        </div>
    </div>

    <form method="POST" action="{{ route('institution.profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- logo instansi -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6">logo instansi</h2>
            
            <div class="flex items-center space-x-6">
                <!-- preview logo -->
                <div class="flex-shrink-0">
                    @if($institution->logo_url)
                        <img id="logoPreview" 
                             src="{{ asset('storage/' . $institution->logo_url) }}" 
                             alt="logo instansi"
                             class="w-24 h-24 rounded-lg object-cover border-4 border-gray-100">
                    @else
                        <div id="logoPreview" class="w-24 h-24 rounded-lg bg-blue-100 flex items-center justify-center">
                            <svg class="w-12 h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                    @endif
                </div>

                <!-- upload controls -->
                <div class="flex-1">
                    <input type="file" 
                           name="logo" 
                           id="logo"
                           accept="image/jpeg,image/jpg,image/png"
                           class="hidden"
                           onchange="previewLogo(event)">
                    
                    <div class="flex items-center space-x-3">
                        <button type="button" 
                                onclick="document.getElementById('logo').click()"
                                class="btn-primary">
                            upload logo baru
                        </button>
                        
                        @if($institution->logo_url)
                            <button type="button" 
                                    onclick="deleteLogo()"
                                    class="btn-secondary text-red-600 hover:bg-red-50">
                                hapus logo
                            </button>
                        @endif
                    </div>
                    
                    <p class="text-xs text-gray-500 mt-2">
                        JPG, JPEG, atau PNG. maksimal 2MB
                    </p>
                    @error('logo')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- data instansi -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6">data instansi</h2>
            
            <div class="space-y-6">
                <!-- nama instansi -->
                <div>
                    <label for="institution_name" class="block text-sm font-medium text-gray-700 mb-2">
                        nama instansi <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="institution_name" 
                           name="institution_name" 
                           value="{{ old('institution_name', $institution->institution_name) }}"
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
                        <option value="pemerintah_desa" {{ old('institution_type', $institution->institution_type) == 'pemerintah_desa' ? 'selected' : '' }}>pemerintah desa</option>
                        <option value="dinas" {{ old('institution_type', $institution->institution_type) == 'dinas' ? 'selected' : '' }}>dinas</option>
                        <option value="ngo" {{ old('institution_type', $institution->institution_type) == 'ngo' ? 'selected' : '' }}>NGO / lembaga non-profit</option>
                        <option value="puskesmas" {{ old('institution_type', $institution->institution_type) == 'puskesmas' ? 'selected' : '' }}>puskesmas</option>
                        <option value="sekolah" {{ old('institution_type', $institution->institution_type) == 'sekolah' ? 'selected' : '' }}>sekolah</option>
                        <option value="perguruan_tinggi" {{ old('institution_type', $institution->institution_type) == 'perguruan_tinggi' ? 'selected' : '' }}>perguruan tinggi</option>
                        <option value="lainnya" {{ old('institution_type', $institution->institution_type) == 'lainnya' ? 'selected' : '' }}>lainnya</option>
                    </select>
                    @error('institution_type')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- website -->
                <div>
                    <label for="website" class="block text-sm font-medium text-gray-700 mb-2">
                        website instansi
                    </label>
                    <input type="url" 
                           id="website" 
                           name="website" 
                           value="{{ old('website', $institution->website) }}"
                           placeholder="https://www.instansi.go.id"
                           class="input-field @error('website') border-red-500 @enderror">
                    @error('website')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- deskripsi -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        deskripsi instansi
                    </label>
                    <textarea id="description" 
                              name="description" 
                              rows="5"
                              placeholder="ceritakan tentang instansi anda, visi, misi, atau layanan yang ditawarkan..."
                              class="input-field @error('description') border-red-500 @enderror">{{ old('description', $institution->description) }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">maksimal 1000 karakter</p>
                    @error('description')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- alamat -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6">alamat</h2>
            
            <div class="space-y-6">
                <!-- alamat lengkap -->
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                        alamat lengkap <span class="text-red-500">*</span>
                    </label>
                    <textarea id="address" 
                              name="address" 
                              rows="3"
                              class="input-field @error('address') border-red-500 @enderror"
                              required>{{ old('address', $institution->address) }}</textarea>
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
                            <option value="{{ $institution->province_id }}" selected>provinsi saat ini</option>
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
                            {{-- TODO: loop regencies from database --}}
                            <option value="{{ $institution->regency_id }}" selected>kabupaten/kota saat ini</option>
                        </select>
                        @error('regency_id')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- penanggung jawab -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6">penanggung jawab</h2>
            
            <div class="space-y-6">
                <!-- nama pic -->
                <div>
                    <label for="pic_name" class="block text-sm font-medium text-gray-700 mb-2">
                        nama penanggung jawab <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="pic_name" 
                           name="pic_name" 
                           value="{{ old('pic_name', $institution->pic_name) }}"
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
                           value="{{ old('pic_position', $institution->pic_position) }}"
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
                           value="{{ old('phone_number', $institution->phone_number) }}"
                           class="input-field @error('phone_number') border-red-500 @enderror"
                           required>
                    @error('phone_number')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- action buttons -->
        <div class="flex justify-between items-center">
            <a href="{{ route('institution.profile.index') }}" class="btn-secondary">
                batal
            </a>
            <button type="submit" class="btn-primary">
                simpan perubahan
            </button>
        </div>
    </form>
</div>

<!-- delete logo form (hidden) -->
<form id="deleteLogoForm" 
      method="POST" 
      action="{{ route('institution.profile.logo.delete') }}"
      class="hidden">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
// preview logo
function previewLogo(event) {
    const preview = document.getElementById('logoPreview');
    const file = event.target.files[0];
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" alt="preview" class="w-24 h-24 rounded-lg object-cover border-4 border-gray-100">`;
        }
        reader.readAsDataURL(file);
    }
}

// delete logo
function deleteLogo() {
    if (confirm('apakah anda yakin ingin menghapus logo instansi?')) {
        document.getElementById('deleteLogoForm').submit();
    }
}

// load regencies based on province
function loadRegencies(provinceId) {
    // TODO: implementasi ajax untuk load regencies
    console.log('loading regencies for province:', provinceId);
}
</script>
@endpush