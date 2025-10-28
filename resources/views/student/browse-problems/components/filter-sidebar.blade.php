{{-- filter sidebar component untuk browse problems --}}
<div class="filter-sidebar bg-white rounded-xl shadow-sm p-6 border border-gray-100 sticky top-24">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-xl font-bold text-gray-900">Filter</h3>
        <button type="button" 
                onclick="window.location.href='{{ route('student.browse-problems.index') }}'"
                class="text-sm text-blue-600 hover:text-blue-800 font-medium transition-colors">
            Reset
        </button>
    </div>

    <form method="GET" action="{{ route('student.browse-problems.index') }}" class="space-y-6">
        
        {{-- search --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Cari Proyek</label>
            <input type="text" 
                   name="search" 
                   value="{{ request('search') }}" 
                   placeholder="Cari berdasarkan judul atau deskripsi..."
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
        </div>

        {{-- lokasi --}}
        <div x-data="{
            provinceId: '{{ request('province_id') }}',
            regencyId: '{{ request('regency_id') }}',
            regencies: {{ json_encode($regencies ?? []) }},
            async fetchRegencies() {
                if (!this.provinceId) {
                    this.regencies = [];
                    this.regencyId = '';
                    return;
                }
                
                try {
                    const response = await fetch(`/student/browse-problems/get-regencies?province_id=${this.provinceId}`);
                    const data = await response.json();
                    this.regencies = data;
                } catch (error) {
                    console.error('Error fetching regencies:', error);
                    this.regencies = [];
                }
            }
        }" x-init="if (provinceId) fetchRegencies()">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Provinsi</label>
                <select name="province_id" 
                        x-model="provinceId"
                        @change="fetchRegencies()"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                    <option value="">Semua Provinsi</option>
                    @foreach($provinces ?? [] as $province)
                        <option value="{{ $province->id }}" {{ request('province_id') == $province->id ? 'selected' : '' }}>
                            {{ $province->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div x-show="provinceId" x-cloak>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kabupaten/Kota</label>
                <select name="regency_id" 
                        x-model="regencyId"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                    <option value="">Semua Kabupaten/Kota</option>
                    <template x-for="regency in regencies" :key="regency.id">
                        <option :value="regency.id" x-text="regency.name"></option>
                    </template>
                </select>
            </div>
        </div>

        {{-- kategori SDG - gunakan helper sdg_label() --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Kategori SDG</label>
            <div class="space-y-2 max-h-64 overflow-y-auto pr-2">
                @php
                    $sdgCategories = [
                        1 => 'Tanpa Kemiskinan',
                        2 => 'Tanpa Kelaparan',
                        3 => 'Kehidupan Sehat Dan Sejahtera',
                        4 => 'Pendidikan Berkualitas',
                        5 => 'Kesetaraan Gender',
                        6 => 'Air Bersih Dan Sanitasi',
                        7 => 'Energi Bersih Dan Terjangkau',
                        8 => 'Pekerjaan Layak Dan Pertumbuhan Ekonomi',
                        9 => 'Industri, Inovasi Dan Infrastruktur',
                        10 => 'Berkurangnya Kesenjangan',
                        11 => 'Kota Dan Komunitas Berkelanjutan',
                        12 => 'Konsumsi Dan Produksi Bertanggung Jawab',
                        13 => 'Penanganan Perubahan Iklim',
                        14 => 'Ekosistem Laut',
                        15 => 'Ekosistem Daratan',
                        16 => 'Perdamaian, Keadilan Dan Kelembagaan Yang Kuat',
                        17 => 'Kemitraan Untuk Mencapai Tujuan'
                    ];
                @endphp
                @foreach($sdgCategories as $value => $label)
                <label class="flex items-start cursor-pointer hover:bg-gray-50 p-2 rounded transition-colors">
                    <input type="checkbox" 
                           name="sdg_categories[]" 
                           value="{{ $value }}"
                           {{ in_array($value, request('sdg_categories', [])) ? 'checked' : '' }}
                           class="w-4 h-4 text-blue-600 focus:ring-2 focus:ring-blue-500 rounded mt-0.5">
                    <span class="ml-2 text-sm text-gray-700">{{ $label }}</span>
                </label>
                @endforeach
            </div>
        </div>

        {{-- tingkat kesulitan --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Tingkat Kesulitan</label>
            <div class="space-y-2">
                <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 rounded transition-colors">
                    <input type="radio" 
                           name="difficulty" 
                           value="" 
                           {{ request('difficulty') == '' ? 'checked' : '' }}
                           class="w-4 h-4 text-blue-600 focus:ring-2 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700">Semua Level</span>
                </label>
                <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 rounded transition-colors">
                    <input type="radio" 
                           name="difficulty" 
                           value="beginner" 
                           {{ request('difficulty') == 'beginner' ? 'checked' : '' }}
                           class="w-4 h-4 text-blue-600 focus:ring-2 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700">Beginner</span>
                </label>
                <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 rounded transition-colors">
                    <input type="radio" 
                           name="difficulty" 
                           value="intermediate" 
                           {{ request('difficulty') == 'intermediate' ? 'checked' : '' }}
                           class="w-4 h-4 text-blue-600 focus:ring-2 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700">Intermediate</span>
                </label>
                <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 rounded transition-colors">
                    <input type="radio" 
                           name="difficulty" 
                           value="advanced" 
                           {{ request('difficulty') == 'advanced' ? 'checked' : '' }}
                           class="w-4 h-4 text-blue-600 focus:ring-2 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700">Advanced</span>
                </label>
            </div>
        </div>

        {{-- durasi proyek --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Durasi Proyek</label>
            <div class="space-y-2">
                <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 rounded transition-colors">
                    <input type="radio" 
                           name="duration" 
                           value="" 
                           {{ request('duration') == '' ? 'checked' : '' }}
                           class="w-4 h-4 text-blue-600 focus:ring-2 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700">Semua Durasi</span>
                </label>
                <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 rounded transition-colors">
                    <input type="radio" 
                           name="duration" 
                           value="1-2" 
                           {{ request('duration') == '1-2' ? 'checked' : '' }}
                           class="w-4 h-4 text-blue-600 focus:ring-2 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700">1-2 Bulan</span>
                </label>
                <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 rounded transition-colors">
                    <input type="radio" 
                           name="duration" 
                           value="3-4" 
                           {{ request('duration') == '3-4' ? 'checked' : '' }}
                           class="w-4 h-4 text-blue-600 focus:ring-2 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700">3-4 Bulan</span>
                </label>
                <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 rounded transition-colors">
                    <input type="radio" 
                           name="duration" 
                           value="5-6" 
                           {{ request('duration') == '5-6' ? 'checked' : '' }}
                           class="w-4 h-4 text-blue-600 focus:ring-2 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700">5-6 Bulan</span>
                </label>
            </div>
        </div>

        {{-- tombol filter --}}
        <div class="pt-4 border-t border-gray-200">
            <button type="submit" 
                    class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white py-3 rounded-lg font-semibold hover:from-blue-700 hover:to-blue-800 transition-all duration-300 shadow-sm hover:shadow-md">
                Terapkan Filter
            </button>
        </div>
    </form>
</div>