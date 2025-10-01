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
        <form method="GET" action="{{ route('student.browse-problems') }}" id="filter-form">
            
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
                <div x-show="provinceId" x-transition style="display: none;">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kabupaten/Kota</label>
                    <select name="regency_id" 
                            x-model="regencyId"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm transition-all">
                        <option value="">Semua Kabupaten/Kota</option>
                        <template x-for="regency in regencies" :key="regency.id">
                            <option :value="regency.id" x-text="regency.name"></option>
                        </template>
                    </select>
                </div>
            </div>

            <!-- SDG category filter -->
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Kategori SDG
                </h3>
                
                <div class="grid grid-cols-3 gap-2">
                    @for($i = 1; $i <= 17; $i++)
                    <label class="flex items-center justify-center p-2 border border-gray-300 rounded-lg cursor-pointer hover:bg-blue-50 transition-colors {{ request('sdg') == $i ? 'bg-blue-100 border-blue-500' : '' }}">
                        <input type="radio" 
                               name="sdg" 
                               value="{{ $i }}" 
                               {{ request('sdg') == $i ? 'checked' : '' }}
                               class="hidden">
                        <span class="text-xs font-semibold {{ request('sdg') == $i ? 'text-blue-700' : 'text-gray-700' }}">
                            {{ $i }}
                        </span>
                    </label>
                    @endfor
                </div>
                
                @if(request('sdg'))
                <button type="button" 
                        onclick="document.querySelector('input[name=sdg]:checked').checked = false; document.getElementById('filter-form').submit();"
                        class="mt-3 text-sm text-blue-600 hover:text-blue-800 transition-colors">
                    Hapus Filter SDG
                </button>
                @endif
            </div>

            <!-- tingkat kesulitan -->
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    Tingkat Kesulitan
                </h3>
                
                <div class="space-y-2">
                    @php
                    $difficulties = [
                        'beginner' => ['label' => 'Pemula', 'color' => 'green'],
                        'intermediate' => ['label' => 'Menengah', 'color' => 'yellow'],
                        'advanced' => ['label' => 'Lanjutan', 'color' => 'red'],
                    ];
                    @endphp
                    
                    @foreach($difficulties as $value => $data)
                    <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors {{ request('difficulty') === $value ? 'bg-'.$data['color'].'-50 border-'.$data['color'].'-500' : '' }}">
                        <input type="radio" 
                               name="difficulty" 
                               value="{{ $value }}" 
                               {{ request('difficulty') === $value ? 'checked' : '' }}
                               class="text-{{ $data['color'] }}-600 focus:ring-{{ $data['color'] }}-500">
                        <span class="ml-3 text-sm {{ request('difficulty') === $value ? 'font-semibold text-'.$data['color'].'-700' : 'text-gray-700' }}">
                            {{ $data['label'] }}
                        </span>
                    </label>
                    @endforeach
                </div>
                
                @if(request('difficulty'))
                <button type="button" 
                        onclick="document.querySelector('input[name=difficulty]:checked').checked = false; document.getElementById('filter-form').submit();"
                        class="mt-3 text-sm text-blue-600 hover:text-blue-800 transition-colors">
                    Hapus Filter Tingkat
                </button>
                @endif
            </div>

            <!-- durasi proyek -->
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Durasi Proyek
                </h3>
                
                <div class="space-y-2">
                    @php
                    $durations = [
                        '1-2' => '1-2 bulan',
                        '3-4' => '3-4 bulan',
                        '5-6' => '5-6 bulan',
                    ];
                    @endphp
                    
                    @foreach($durations as $value => $label)
                    <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors {{ request('duration') === $value ? 'bg-blue-50 border-blue-500' : '' }}">
                        <input type="radio" 
                               name="duration" 
                               value="{{ $value }}" 
                               {{ request('duration') === $value ? 'checked' : '' }}
                               class="text-blue-600 focus:ring-blue-500">
                        <span class="ml-3 text-sm {{ request('duration') === $value ? 'font-semibold text-blue-700' : 'text-gray-700' }}">
                            {{ $label }}
                        </span>
                    </label>
                    @endforeach
                </div>
                
                @if(request('duration'))
                <button type="button" 
                        onclick="document.querySelector('input[name=duration]:checked').checked = false; document.getElementById('filter-form').submit();"
                        class="mt-3 text-sm text-blue-600 hover:text-blue-800 transition-colors">
                    Hapus Filter Durasi
                </button>
                @endif
            </div>

            <!-- universitas mitra -->
            <div class="pb-6">
                <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    Universitas Mitra
                </h3>
                
                <select name="university_id" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm transition-all">
                    <option value="">Semua Universitas</option>
                    @foreach($universities as $university)
                    <option value="{{ $university->id }}" {{ request('university_id') == $university->id ? 'selected' : '' }}>
                        {{ $university->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- action buttons -->
            <div class="flex gap-3">
                <button type="submit" 
                        class="flex-1 px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors shadow-sm hover:shadow-md">
                    Terapkan Filter
                </button>
                <a href="{{ route('student.browse-problems') }}" 
                   class="px-4 py-2 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition-colors">
                    Reset
                </a>
            </div>
        </form>
    </div>
</div>

<script>
// alpine.js component untuk filter sidebar
function filterSidebar() {
    return {
        showMobileFilter: false,
        provinceId: '{{ request("province_id", "") }}',
        regencyId: '{{ request("regency_id", "") }}',
        regencies: {!! $regencies->toJson() !!},
        
        init() {
            // jika ada province yang terpilih, load regencies
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
        
        loadRegencies(provinceId) {
            if (!provinceId) return;
            
            // fetch regencies dari API
            fetch(`/api/regencies/${provinceId}`)
                .then(response => response.json())
                .then(data => {
                    this.regencies = data;
                })
                .catch(error => {
                    console.error('Error loading regencies:', error);
                });
        }
    }
}
</script>