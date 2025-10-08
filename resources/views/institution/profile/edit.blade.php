@extends('layouts.app')

@section('title', 'Edit Profil Instansi')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- header --}}
        <div class="mb-8">
            <a href="{{ route('institution.profile.index') }}" class="text-blue-600 hover:text-blue-700 flex items-center gap-2 mb-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Edit Profil Instansi</h1>
            <p class="text-gray-600 mt-1">Perbarui informasi profil instansi Anda</p>
        </div>

        {{-- form edit profile --}}
        <form method="POST" action="{{ route('institution.profile.update') }}">
            @csrf
            @method('PUT')

            {{-- logo --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Logo Instansi</h2>
                
                <div class="flex items-start gap-6">
                    {{-- preview logo --}}
                    <div class="flex-shrink-0">
                        @if($institution->logo_path)
                        <img id="logo-preview" 
                             src="{{ Storage::url($institution->logo_path) }}" 
                             alt="Logo" 
                             class="w-32 h-32 rounded-lg object-cover border-2 border-gray-200">
                        @else
                        <div id="logo-preview" class="w-32 h-32 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                            <span class="text-white text-4xl font-bold">{{ substr($institution->name, 0, 1) }}</span>
                        </div>
                        @endif
                    </div>

                    <div class="flex-1">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Upload Logo Baru</label>
                        <input type="file" 
                               name="logo" 
                               id="logo-input"
                               accept="image/jpeg,image/jpg,image/png"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <p class="text-xs text-gray-500 mt-2">Format: JPG, PNG. Maksimal 2MB</p>
                        @error('logo')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- informasi dasar --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Informasi Dasar</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- nama instansi --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Instansi *</label>
                        <input type="text" 
                               name="name" 
                               value="{{ old('name', $institution->name) }}"
                               required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('name')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- jenis instansi --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis Instansi *</label>
                        <select name="type" 
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Pilih Jenis</option>
                            <option value="Pemerintah Desa" {{ old('type', $institution->type) == 'Pemerintah Desa' ? 'selected' : '' }}>Pemerintah Desa</option>
                            <option value="Dinas" {{ old('type', $institution->type) == 'Dinas' ? 'selected' : '' }}>Dinas</option>
                            <option value="NGO" {{ old('type', $institution->type) == 'NGO' ? 'selected' : '' }}>NGO</option>
                            <option value="Puskesmas" {{ old('type', $institution->type) == 'Puskesmas' ? 'selected' : '' }}>Puskesmas</option>
                            <option value="Sekolah" {{ old('type', $institution->type) == 'Sekolah' ? 'selected' : '' }}>Sekolah</option>
                            <option value="Lainnya" {{ old('type', $institution->type) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('type')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- telepon --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Telepon *</label>
                        <input type="tel" 
                               name="phone" 
                               value="{{ old('phone', $institution->phone) }}"
                               required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('phone')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- website --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Website</label>
                        <input type="url" 
                               name="website" 
                               value="{{ old('website', $institution->website) }}"
                               placeholder="https://example.com"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('website')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- deskripsi --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi</label>
                        <textarea name="description" 
                                  rows="4"
                                  maxlength="1000"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('description', $institution->description) }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">Maksimal 1000 karakter</p>
                        @error('description')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- lokasi --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Lokasi</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- provinsi --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Provinsi *</label>
                        <select name="province_id" 
                                id="province_id"
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Pilih Provinsi</option>
                            @foreach($provinces as $province)
                            <option value="{{ $province->id }}" {{ old('province_id', $institution->province_id) == $province->id ? 'selected' : '' }}>
                                {{ $province->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('province_id')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- kabupaten/kota --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Kabupaten/Kota *</label>
                        <select name="regency_id" 
                                id="regency_id"
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Pilih Kabupaten/Kota</option>
                            @foreach($regencies as $regency)
                            <option value="{{ $regency->id }}" {{ old('regency_id', $institution->regency_id) == $regency->id ? 'selected' : '' }}>
                                {{ $regency->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('regency_id')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- alamat lengkap --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat Lengkap *</label>
                        <textarea name="address" 
                                  rows="3"
                                  required
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('address', $institution->address) }}</textarea>
                        @error('address')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- penanggung jawab --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Penanggung Jawab</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- nama PIC --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama PIC *</label>
                        <input type="text" 
                               name="pic_name" 
                               value="{{ old('pic_name', $institution->pic_name) }}"
                               required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('pic_name')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- jabatan PIC --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Jabatan *</label>
                        <input type="text" 
                               name="pic_position" 
                               value="{{ old('pic_position', $institution->pic_position) }}"
                               required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('pic_position')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- telepon PIC --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Telepon PIC *</label>
                        <input type="tel" 
                               name="pic_phone" 
                               value="{{ old('pic_phone', $institution->pic_phone) }}"
                               required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('pic_phone')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- action buttons --}}
            <div class="flex gap-4">
                <button type="submit" 
                        class="flex-1 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                    Simpan Perubahan
                </button>
                <a href="{{ route('institution.profile.index') }}" 
                   class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-semibold">
                    Batal
                </a>
            </div>
        </form>

        {{-- form ubah password --}}
        <form method="POST" action="{{ route('institution.profile.update') }}">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Ubah Password</h2>

                <div class="space-y-4 max-w-md">
                    {{-- password saat ini --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Password Saat Ini *</label>
                        <input type="password" 
                               name="current_password" 
                               required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('current_password')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- password baru --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Password Baru *</label>
                        <input type="password" 
                               name="new_password" 
                               required
                               minlength="8"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <p class="text-xs text-gray-500 mt-1">Minimal 8 karakter</p>
                        @error('new_password')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- konfirmasi password --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Konfirmasi Password Baru *</label>
                        <input type="password" 
                               name="new_password_confirmation" 
                               required
                               minlength="8"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <button type="submit" 
                            class="w-full px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold">
                        Ubah Password
                    </button>
                </div>
            </div>
        </form>

    </div>
</div>

<script>
// preview logo saat upload
document.getElementById('logo-input').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(event) {
            const preview = document.getElementById('logo-preview');
            preview.innerHTML = `<img src="${event.target.result}" alt="Logo Preview" class="w-32 h-32 rounded-lg object-cover border-2 border-gray-200">`;
        };
        reader.readAsDataURL(file);
    }
});

// load regencies saat province berubah
document.getElementById('province_id').addEventListener('change', function() {
    const provinceId = this.value;
    const regencySelect = document.getElementById('regency_id');
    
    if (provinceId) {
        fetch(`/institution/profile/regencies/${provinceId}`)
            .then(response => response.json())
            .then(data => {
                regencySelect.innerHTML = '<option value="">Pilih Kabupaten/Kota</option>';
                data.forEach(regency => {
                    regencySelect.innerHTML += `<option value="${regency.id}">${regency.name}</option>`;
                });
            });
    } else {
        regencySelect.innerHTML = '<option value="">Pilih Kabupaten/Kota</option>';
    }
});
</script>
@endsection