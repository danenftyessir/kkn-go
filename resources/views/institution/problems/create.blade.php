@extends('layouts.app')

@section('title', 'Buat Masalah Baru')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-green-50 py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Buat Masalah Baru</h1>
            <p class="mt-2 text-gray-600">Publikasikan Masalah Untuk Mendapatkan Bantuan Mahasiswa KKN</p>
        </div>

        @if($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
            <div class="font-semibold mb-2">Terdapat kesalahan pada form:</div>
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('institution.problems.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6" id="problemForm">
            @csrf

            {{-- informasi dasar --}}
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Informasi Dasar</h2>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Judul Masalah <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" value="{{ old('title') }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Contoh: Peningkatan Literasi Digital di Desa Sukamaju">
                        @error('title') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Deskripsi <span class="text-red-500">*</span>
                        </label>
                        <textarea name="description" rows="4" required
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Jelaskan masalah yang dihadapi...">{{ old('description') }}</textarea>
                        @error('description') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Latar Belakang</label>
                        <textarea name="background" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Jelaskan latar belakang masalah...">{{ old('background') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tujuan</label>
                        <textarea name="objectives" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Sebutkan tujuan dari proyek ini...">{{ old('objectives') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ruang Lingkup</label>
                        <textarea name="scope" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Jelaskan ruang lingkup pekerjaan...">{{ old('scope') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Expected Outcomes</label>
                        <textarea name="expected_outcomes" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Hasil yang diharapkan dari proyek ini...">{{ old('expected_outcomes') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- lokasi --}}
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Lokasi</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Provinsi <span class="text-red-500">*</span>
                        </label>
                        <select name="province_id" id="province_id" required
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
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Kabupaten/Kota <span class="text-red-500">*</span>
                        </label>
                        <select name="regency_id" id="regency_id" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Pilih Kabupaten/Kota</option>
                        </select>
                        @error('regency_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Desa</label>
                        <input type="text" name="village" value="{{ old('village') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Nama desa">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Detail Lokasi</label>
                        <input type="text" name="detailed_location" value="{{ old('detailed_location') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Alamat lengkap">
                    </div>
                </div>
            </div>

            {{-- kategori SDG --}}
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h2 class="text-xl font-bold text-gray-900 mb-6">
                    Kategori SDG <span class="text-red-500">*</span>
                </h2>
                <p class="text-sm text-gray-600 mb-4">Pilih minimal 1 kategori Sustainable Development Goals yang relevan</p>
                
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    @php
                    $sdgs = [
                        1 => 'No Poverty',
                        2 => 'Zero Hunger',
                        3 => 'Good Health',
                        4 => 'Quality Education',
                        5 => 'Gender Equality',
                        6 => 'Clean Water',
                        7 => 'Clean Energy',
                        8 => 'Economic Growth',
                        9 => 'Innovation',
                        10 => 'Reduced Inequalities',
                        11 => 'Sustainable Cities',
                        12 => 'Responsible Consumption',
                        13 => 'Climate Action',
                        14 => 'Life Below Water',
                        15 => 'Life On Land',
                        16 => 'Peace And Justice',
                        17 => 'Partnerships'
                    ];
                    @endphp
                    
                    @foreach($sdgs as $key => $sdg)
                        <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition-all">
                            <input type="checkbox" name="sdg_categories[]" value="{{ $key }}"
                                   {{ in_array($key, old('sdg_categories', [])) ? 'checked' : '' }}
                                   class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">{{ $key }}. {{ $sdg }}</span>
                        </label>
                    @endforeach
                </div>
                @error('sdg_categories') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- requirements --}}
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Kebutuhan Mahasiswa</h2>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Jumlah Mahasiswa Dibutuhkan <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="required_students" value="{{ old('required_students', 1) }}" required min="1"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('required_students') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Skills Yang Dibutuhkan <span class="text-red-500">*</span>
                        </label>
                        <p class="text-xs text-gray-500 mb-2">Pisahkan dengan koma. Contoh: Komunikasi, Penelitian, Desain Grafis</p>
                        <input type="text" name="required_skills" value="{{ old('required_skills') }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Contoh: Komunikasi, Penelitian, Desain Grafis">
                        @error('required_skills') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jurusan Yang Dibutuhkan (Opsional)</label>
                        <p class="text-xs text-gray-500 mb-2">Pisahkan dengan koma. Contoh: Teknik Informatika, Kesehatan Masyarakat</p>
                        <input type="text" name="required_majors" value="{{ old('required_majors') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Kosongkan jika tidak ada persyaratan khusus">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tingkat Kesulitan <span class="text-red-500">*</span>
                        </label>
                        <select name="difficulty_level" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="beginner" {{ old('difficulty_level') == 'beginner' ? 'selected' : '' }}>Beginner</option>
                            <option value="intermediate" {{ old('difficulty_level', 'intermediate') == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                            <option value="advanced" {{ old('difficulty_level') == 'advanced' ? 'selected' : '' }}>Advanced</option>
                        </select>
                        @error('difficulty_level') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- timeline --}}
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Timeline Proyek</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Mulai <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="start_date" value="{{ old('start_date') }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('start_date') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Selesai <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="end_date" value="{{ old('end_date') }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('end_date') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Deadline Aplikasi <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="application_deadline" value="{{ old('application_deadline') }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('application_deadline') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Durasi (Bulan) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="duration_months" value="{{ old('duration_months', 2) }}" required min="1"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('duration_months') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- deliverables & fasilitas --}}
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Deliverables & Fasilitas</h2>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Deliverables</label>
                        <div id="deliverables-container" class="space-y-2">
                            <input type="text" name="deliverables[]" value="{{ old('deliverables.0', 'Laporan Akhir') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="Contoh: Laporan Akhir">
                        </div>
                        <button type="button" onclick="addDeliverableField()" class="mt-2 text-blue-600 hover:text-blue-700 text-sm font-semibold">
                            + Tambah Deliverable
                        </button>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Fasilitas Yang Disediakan</label>
                        <div id="facilities-container" class="space-y-2">
                            <input type="text" name="facilities_provided[]" value="{{ old('facilities_provided.0', 'Akomodasi') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="Contoh: Akomodasi">
                        </div>
                        <button type="button" onclick="addFacilityField()" class="mt-2 text-blue-600 hover:text-blue-700 text-sm font-semibold">
                            + Tambah Fasilitas
                        </button>
                    </div>
                </div>
            </div>

            {{-- gambar --}}
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Gambar Pendukung</h2>
                
                <input type="file" name="images[]" multiple accept="image/*" id="images-input"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <p class="text-sm text-gray-500 mt-2">Upload maksimal 5 gambar (JPG, PNG). Maksimal 5MB per file.</p>
                <div id="images-preview" class="mt-4 grid grid-cols-2 md:grid-cols-5 gap-2"></div>
            </div>

            {{-- status --}}
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Status Publikasi</h2>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select name="status" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>Draft (Tidak Dipublikasikan)</option>
                        <option value="open" {{ old('status') == 'open' ? 'selected' : '' }}>Open (Terima Aplikasi)</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Pilih "Draft" jika ingin menyimpan terlebih dahulu, atau "Open" untuk langsung dipublikasikan</p>
                    @error('status') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- action buttons --}}
            <div class="flex gap-4">
                <button type="submit" 
                        class="flex-1 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                    Simpan Problem
                </button>
                <a href="{{ route('institution.problems.index') }}" 
                   class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-semibold">
                    Batal
                </a>
            </div>

        </form>

    </div>
</div>

<script>
// dynamic fields functions
function addDeliverableField() {
    const container = document.getElementById('deliverables-container');
    const input = document.createElement('input');
    input.type = 'text';
    input.name = 'deliverables[]';
    input.placeholder = 'Deliverable lainnya';
    input.className = 'w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent';
    container.appendChild(input);
}

function addFacilityField() {
    const container = document.getElementById('facilities-container');
    const input = document.createElement('input');
    input.type = 'text';
    input.name = 'facilities_provided[]';
    input.placeholder = 'Fasilitas lainnya';
    input.className = 'w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent';
    container.appendChild(input);
}

// load regencies berdasarkan provinsi
document.getElementById('province_id').addEventListener('change', function() {
    const provinceId = this.value;
    const regencySelect = document.getElementById('regency_id');
    
    if (provinceId) {
        fetch(`/api/regencies/${provinceId}`)
            .then(response => response.json())
            .then(data => {
                regencySelect.innerHTML = '<option value="">Pilih Kabupaten/Kota</option>';
                data.forEach(regency => {
                    regencySelect.innerHTML += `<option value="${regency.id}">${regency.name}</option>`;
                });
            })
            .catch(error => {
                console.error('Error loading regencies:', error);
                alert('Gagal memuat data kabupaten/kota');
            });
    } else {
        regencySelect.innerHTML = '<option value="">Pilih Kabupaten/Kota</option>';
    }
});

// preview gambar yang diupload
document.getElementById('images-input').addEventListener('change', function(e) {
    const preview = document.getElementById('images-preview');
    preview.innerHTML = '';
    
    const files = Array.from(e.target.files).slice(0, 5); // max 5 images
    
    files.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const div = document.createElement('div');
            div.className = 'relative aspect-square';
            div.innerHTML = `
                <img src="${e.target.result}" class="w-full h-full object-cover rounded-lg border border-gray-300">
                <div class="absolute top-1 left-1 bg-blue-600 text-white text-xs px-2 py-1 rounded">
                    ${index === 0 ? 'Cover' : index + 1}
                </div>
            `;
            preview.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
});

// form validation sebelum submit
document.getElementById('problemForm').addEventListener('submit', function(e) {
    const sdgChecked = document.querySelectorAll('input[name="sdg_categories[]"]:checked');
    if (sdgChecked.length === 0) {
        e.preventDefault();
        alert('Pilih minimal 1 kategori SDG!');
        return false;
    }
});
</script>
@endsection