@extends('layouts.app')

@section('title', 'Buat Masalah Baru')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-green-50 py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Buat Masalah Baru</h1>
            <p class="mt-2 text-gray-600">Publikasikan masalah untuk mendapatkan bantuan mahasiswa KKN</p>
        </div>

        <form action="{{ route('institution.problems.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            {{-- informasi dasar --}}
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Informasi Dasar</h2>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Judul Masalah <span class="text-red-500">*</span></label>
                        <input type="text" name="title" value="{{ old('title') }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Contoh: Peningkatan Literasi Digital di Desa Sukamaju">
                        @error('title') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi <span class="text-red-500">*</span></label>
                        <textarea name="description" rows="4" required
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Jelaskan masalah yang dihadapi...">{{ old('description') }}</textarea>
                        @error('description') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- lokasi --}}
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Lokasi</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Provinsi <span class="text-red-500">*</span></label>
                        <select name="province_id" required id="province-select"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Pilih Provinsi</option>
                            @foreach($provinces as $province)
                                <option value="{{ $province->id }}" {{ old('province_id') == $province->id ? 'selected' : '' }}>
                                    {{ $province->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('province_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kabupaten/Kota <span class="text-red-500">*</span></label>
                        <select name="regency_id" required id="regency-select"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Pilih Kabupaten/Kota</option>
                        </select>
                        @error('regency_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Desa/Kelurahan</label>
                        <input type="text" name="village" value="{{ old('village') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Detail Lokasi</label>
                        <input type="text" name="detailed_location" value="{{ old('detailed_location') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Alamat lengkap">
                    </div>
                </div>
            </div>

            {{-- requirements --}}
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Kebutuhan Mahasiswa</h2>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Mahasiswa Dibutuhkan <span class="text-red-500">*</span></label>
                        <input type="number" name="required_students" value="{{ old('required_students', 1) }}" required min="1"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('required_students') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Skills yang Dibutuhkan (pisahkan dengan koma)</label>
                        <input type="text" name="required_skills" value="{{ old('required_skills') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Contoh: Komunikasi, Penelitian, Desain Grafis">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tingkat Kesulitan <span class="text-red-500">*</span></label>
                        <select name="difficulty_level" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="beginner" {{ old('difficulty_level') == 'beginner' ? 'selected' : '' }}>Beginner</option>
                            <option value="intermediate" {{ old('difficulty_level', 'intermediate') == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                            <option value="advanced" {{ old('difficulty_level') == 'advanced' ? 'selected' : '' }}>Advanced</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- timeline --}}
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Timeline</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai <span class="text-red-500">*</span></label>
                        <input type="date" name="start_date" value="{{ old('start_date') }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('start_date') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai <span class="text-red-500">*</span></label>
                        <input type="date" name="end_date" value="{{ old('end_date') }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('end_date') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Deadline Aplikasi <span class="text-red-500">*</span></label>
                        <input type="date" name="application_deadline" value="{{ old('application_deadline') }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('application_deadline') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- kategori SDG --}}
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Kategori SDG <span class="text-red-500">*</span></h2>
                
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    @php
                    $sdgs = ['No Poverty', 'Zero Hunger', 'Good Health', 'Quality Education', 'Gender Equality', 
                             'Clean Water', 'Clean Energy', 'Economic Growth', 'Innovation', 'Reduced Inequalities',
                             'Sustainable Cities', 'Responsible Consumption', 'Climate Action', 'Life Below Water',
                             'Life on Land', 'Peace and Justice', 'Partnerships'];
                    @endphp
                    
                    @foreach($sdgs as $sdg)
                        <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                            <input type="checkbox" name="sdg_categories[]" value="{{ $sdg }}"
                                   {{ in_array($sdg, old('sdg_categories', [])) ? 'checked' : '' }}
                                   class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">{{ $sdg }}</span>
                        </label>
                    @endforeach
                </div>
                @error('sdg_categories') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- gambar --}}
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Gambar Pendukung</h2>
                
                <input type="file" name="images[]" multiple accept="image/*"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <p class="text-sm text-gray-500 mt-2">Upload maksimal 5 gambar (JPG, PNG). Ukuran maksimal 2MB per gambar.</p>
            </div>

            {{-- action buttons --}}
            <div class="flex items-center justify-between">
                <a href="{{ route('institution.problems.index') }}" 
                   class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all duration-200">
                    Batal
                </a>

                <button type="submit" 
                        class="px-8 py-3 bg-gradient-to-r from-blue-600 to-green-600 text-white rounded-lg hover:from-blue-700 hover:to-green-700 transition-all duration-200 transform hover:scale-105 shadow-lg">
                    Publikasikan Masalah
                </button>
            </div>
        </form>

    </div>
</div>

@push('scripts')
<script>
// load regencies berdasarkan province
document.getElementById('province-select').addEventListener('change', function() {
    const provinceId = this.value;
    const regencySelect = document.getElementById('regency-select');
    
    regencySelect.innerHTML = '<option value="">Loading...</option>';
    
    if (provinceId) {
        fetch(`/api/regencies/${provinceId}`)
            .then(response => response.json())
            .then(data => {
                regencySelect.innerHTML = '<option value="">Pilih Kabupaten/Kota</option>';
                data.forEach(regency => {
                    regencySelect.innerHTML += `<option value="${regency.id}">${regency.name}</option>`;
                });
            })
            .catch(() => {
                regencySelect.innerHTML = '<option value="">Error loading data</option>';
            });
    } else {
        regencySelect.innerHTML = '<option value="">Pilih Kabupaten/Kota</option>';
    }
});
</script>
@endpush
@endsection