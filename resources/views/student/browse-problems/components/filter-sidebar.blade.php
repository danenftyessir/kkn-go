{{-- resources/views/student/browse-problems/components/filter-sidebar.blade.php --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden sticky top-24" x-data="filterSidebar()">
    <div class="p-6">
        <h3 class="font-bold text-gray-900 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
            </svg>
            Filter Pencarian
        </h3>

        <form action="{{ route('student.browse-problems.index') }}" method="GET" class="space-y-6">
            
            {{-- preserve search query --}}
            @if(request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif

            {{-- lokasi --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Provinsi</label>
                <select name="province_id" 
                        x-model="provinceId"
                        @change="onProvinceChange($event.target.value)"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                    <option value="">Semua Provinsi</option>
                    @foreach($provinces as $province)
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

            {{-- kategori SDG --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kategori SDG</label>
                <div class="space-y-2 max-h-64 overflow-y-auto pr-2">
                    @php
                        $sdgCategories = [
                            'no_poverty' => 'Tanpa Kemiskinan',
                            'zero_hunger' => 'Tanpa Kelaparan',
                            'good_health' => 'Kehidupan Sehat dan Sejahtera',
                            'quality_education' => 'Pendidikan Berkualitas',
                            'gender_equality' => 'Kesetaraan Gender',
                            'clean_water' => 'Air Bersih dan Sanitasi',
                            'affordable_energy' => 'Energi Bersih dan Terjangkau',
                            'decent_work' => 'Pekerjaan Layak dan Pertumbuhan Ekonomi',
                            'industry_innovation' => 'Industri, Inovasi dan Infrastruktur',
                            'reduced_inequality' => 'Berkurangnya Kesenjangan',
                            'sustainable_cities' => 'Kota dan Komunitas Berkelanjutan',
                            'responsible_consumption' => 'Konsumsi dan Produksi Bertanggung Jawab',
                            'climate_action' => 'Penanganan Perubahan Iklim',
                            'life_below_water' => 'Ekosistem Laut',
                            'life_on_land' => 'Ekosistem Daratan',
                            'peace_justice' => 'Perdamaian, Keadilan dan Kelembagaan yang Kuat',
                            'partnerships' => 'Kemitraan untuk Mencapai Tujuan'
                        ];
                    @endphp
                    @foreach($sdgCategories as $value => $label)
                    <label class="flex items-start cursor-pointer hover:bg-gray-50 p-2 rounded transition-colors">
                        <input type="checkbox" 
                               name="sdg_categories[]" 
                               value="{{ $value }}"
                               {{ in_array($value, request('sdg_categories', [])) ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 mt-0.5 flex-shrink-0">
                        <span class="ml-2 text-sm text-gray-700">{{ $label }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            {{-- tingkat kesulitan --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tingkat Kesulitan</label>
                <div class="space-y-2">
                    @foreach(['beginner' => 'Pemula', 'intermediate' => 'Menengah', 'advanced' => 'Lanjutan'] as $value => $label)
                    <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 rounded transition-colors">
                        <input type="radio" 
                               name="difficulty" 
                               value="{{ $value }}"
                               {{ request('difficulty') === $value ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">{{ $label }}</span>
                    </label>
                    @endforeach
                    @if(request('difficulty'))
                    <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 rounded transition-colors">
                        <input type="radio" 
                               name="difficulty" 
                               value=""
                               class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-500 italic">Semua Tingkat</span>
                    </label>
                    @endif
                </div>
            </div>

            {{-- durasi proyek --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Durasi Proyek</label>
                <div class="space-y-2">
                    @foreach(['1-2' => '1-2 Bulan', '3-4' => '3-4 Bulan', '5-6' => '5-6 Bulan'] as $value => $label)
                    <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 rounded transition-colors">
                        <input type="radio" 
                               name="duration" 
                               value="{{ $value }}"
                               {{ request('duration') === $value ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">{{ $label }}</span>
                    </label>
                    @endforeach
                    @if(request('duration'))
                    <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 rounded transition-colors">
                        <input type="radio" 
                               name="duration" 
                               value=""
                               class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-500 italic">Semua Durasi</span>
                    </label>
                    @endif
                </div>
            </div>

            {{-- status proyek --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status Proyek</label>
                <div class="space-y-2">
                    @foreach(['open' => 'Terbuka', 'in_progress' => 'Sedang Berjalan', 'completed' => 'Selesai'] as $value => $label)
                    <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 rounded transition-colors">
                        <input type="checkbox" 
                               name="status[]" 
                               value="{{ $value }}"
                               {{ in_array($value, request('status', [])) ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">{{ $label }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            {{-- action buttons --}}
            <div class="flex space-x-3 pt-4 border-t border-gray-200">
                <button type="submit" 
                        class="flex-1 px-4 py-2 bg-gradient-to-r from-blue-600 to-green-600 text-white rounded-lg hover:shadow-lg transition-all duration-200 font-medium text-sm">
                    Terapkan Filter
                </button>
                <a href="{{ route('student.browse-problems.index') }}" 
                   class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium text-sm text-center">
                    Reset
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
// alpine component untuk filter sidebar
function filterSidebar() {
    return {
        provinceId: '{{ request("province_id") }}',
        regencyId: '{{ request("regency_id") }}',
        regencies: [],

        init() {
            // load regencies jika provinsi sudah dipilih
            if (this.provinceId) {
                this.loadRegencies(this.provinceId);
            }
        },

        onProvinceChange(provinceId) {
            this.provinceId = provinceId;
            this.regencyId = '';
            this.regencies = [];
            
            if (provinceId) {
                this.loadRegencies(provinceId);
            }
        },

        async loadRegencies(provinceId) {
            if (!provinceId) return;
            
            try {
                const response = await fetch(`/api/regencies/${provinceId}`);
                const data = await response.json();
                this.regencies = data;
                
                // set selected regency jika ada
                if (this.regencyId) {
                    this.$nextTick(() => {
                        const option = this.regencies.find(r => r.id == this.regencyId);
                        if (!option) {
                            this.regencyId = '';
                        }
                    });
                }
            } catch (error) {
                console.error('Error loading regencies:', error);
            }
        }
    }
}
</script>
@endpush