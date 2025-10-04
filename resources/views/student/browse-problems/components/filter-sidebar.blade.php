{{-- resources/views/student/browse-problems/components/filter-sidebar.blade.php --}}
{{-- filter sidebar component --}}

<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 filter-sidebar" 
     x-data="filterSidebar()" 
     @province-changed.window="loadRegencies($event.detail)">
    
    <!-- mobile filter toggle -->
    <div class="lg:hidden mb-4">
        <button @click="showMobileFilter = !showMobileFilter" 
                class="w-full flex items-center justify-between px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
            <span class="font-medium">Filter</span>
            <svg class="w-5 h-5" :class="{'rotate-180': showMobileFilter}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>
    </div>

    <div :class="{'hidden': !showMobileFilter}" class="lg:block space-y-6">
        <form method="GET" action="{{ route('student.browse-problems.index') }}" id="filter-form">
            
            <!-- preserve existing filters -->
            @if(request('search'))
            <input type="hidden" name="search" value="{{ request('search') }}">
            @endif
            @if(request('sort'))
            <input type="hidden" name="sort" value="{{ request('sort') }}">
            @endif

            <!-- lokasi filter -->
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Lokasi
                </h3>
                
                <!-- provinsi -->
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Provinsi</label>
                    <select name="province_id" 
                            x-model="provinceId"
                            @change="onProvinceChange($event.target.value)"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm transition-all">
                        <option value="">Semua Provinsi</option>
                        @foreach($provinces as $province)
                        <option value="{{ $province->id }}" {{ request('province_id') == $province->id ? 'selected' : '' }}>
                            {{ $province->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- kabupaten/kota -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kabupaten/Kota</label>
                    <select name="regency_id" 
                            x-model="regencyId"
                            :disabled="!provinceId"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm transition-all disabled:bg-gray-100 disabled:cursor-not-allowed">
                        <option value="">Semua Kabupaten/Kota</option>
                        <template x-for="regency in regencies" :key="regency.id">
                            <option :value="regency.id" x-text="regency.name"></option>
                        </template>
                    </select>
                </div>
            </div>

            <!-- kategori SDG -->
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                    Kategori SDG
                </h3>
                <div class="space-y-2">
                    @php
                    $sdgs = [
                        'no_poverty' => 'Tanpa Kemiskinan',
                        'zero_hunger' => 'Tanpa Kelaparan',
                        'good_health' => 'Kehidupan Sehat',
                        'quality_education' => 'Pendidikan Berkualitas',
                        'gender_equality' => 'Kesetaraan Gender',
                        'clean_water' => 'Air Bersih',
                        'affordable_energy' => 'Energi Bersih',
                        'decent_work' => 'Pekerjaan Layak',
                        'industry_innovation' => 'Industri & Inovasi',
                        'reduced_inequalities' => 'Berkurangnya Kesenjangan',
                        'sustainable_cities' => 'Kota Berkelanjutan',
                        'responsible_consumption' => 'Konsumsi Bertanggung Jawab',
                        'climate_action' => 'Aksi Iklim',
                        'life_below_water' => 'Kehidupan Bawah Air',
                        'life_on_land' => 'Kehidupan di Darat',
                        'peace_justice' => 'Perdamaian & Keadilan',
                        'partnerships' => 'Kemitraan untuk Tujuan',
                    ];
                    $selectedSdgs = request('sdg_categories', []);
                    @endphp
                    
                    @foreach($sdgs as $key => $label)
                    <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 rounded-lg transition-colors">
                        <input type="checkbox" 
                               name="sdg_categories[]" 
                               value="{{ $key }}"
                               {{ in_array($key, $selectedSdgs) ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">{{ $label }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <!-- durasi proyek -->
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Durasi Proyek
                </h3>
                <div class="space-y-2">
                    @php
                    $durations = [
                        '1-2' => '1-2 Bulan',
                        '3-4' => '3-4 Bulan',
                        '5-6' => '5-6 Bulan',
                        '6+' => 'Lebih dari 6 Bulan',
                    ];
                    @endphp
                    
                    @foreach($durations as $key => $label)
                    <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 rounded-lg transition-colors">
                        <input type="radio" 
                               name="duration" 
                               value="{{ $key }}"
                               {{ request('duration') == $key ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">{{ $label }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <!-- status proyek -->
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Status
                </h3>
                <div class="space-y-2">
                    @php
                    $statuses = [
                        'open' => 'Terbuka',
                        'in_progress' => 'Sedang Berjalan',
                        'completed' => 'Selesai',
                    ];
                    @endphp
                    
                    @foreach($statuses as $key => $label)
                    <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 rounded-lg transition-colors">
                        <input type="radio" 
                               name="status" 
                               value="{{ $key }}"
                               {{ request('status') == $key ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">{{ $label }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <!-- tingkat kesulitan -->
            <div class="pb-6">
                <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    Tingkat Kesulitan
                </h3>
                <div class="space-y-2">
                    @php
                    $difficulties = [
                        'beginner' => 'Pemula',
                        'intermediate' => 'Menengah',
                        'advanced' => 'Lanjutan',
                    ];
                    @endphp
                    
                    @foreach($difficulties as $key => $label)
                    <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 rounded-lg transition-colors">
                        <input type="radio" 
                               name="difficulty" 
                               value="{{ $key }}"
                               {{ request('difficulty') == $key ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">{{ $label }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <!-- action buttons -->
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

<script>
function filterSidebar() {
    return {
        showMobileFilter: false,
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
                const response = await fetch(`/institution/problems/regencies/${provinceId}`);
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