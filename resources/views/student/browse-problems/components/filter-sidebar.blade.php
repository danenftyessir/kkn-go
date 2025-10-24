{{-- resources/views/student/browse-problems/components/filter-sidebar.blade.php --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6" 
     x-data="{
         provinceId: '{{ request('province_id') }}',
         regencyId: '{{ request('regency_id') }}',
         regencies: []
     }"
     x-init="
         if (provinceId) {
             fetch(`/student/browse-problems/regencies?province_id=${provinceId}`)
                 .then(res => res.json())
                 .then(data => {
                     regencies = data;
                     if (!data.find(r => r.id == regencyId)) {
                         regencyId = '';
                     }
                 });
         }
     "
     @change="
         if ($event.target.name === 'province_id') {
             regencyId = '';
             if (provinceId) {
                 fetch(`/student/browse-problems/regencies?province_id=${provinceId}`)
                     .then(res => res.json())
                     .then(data => regencies = data);
             } else {
                 regencies = [];
             }
         }
     ">
    
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-bold text-gray-900">Filter Pencarian</h3>
        <button type="button" 
                onclick="window.location.href='{{ route('student.browse-problems.index') }}'"
                class="text-sm text-blue-600 hover:text-blue-700 font-medium">
            Reset Filter
        </button>
    </div>

    <form method="GET" action="{{ route('student.browse-problems.index') }}" class="space-y-6">
        {{-- search bar --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Cari Proyek</label>
            <input type="text" 
                   name="search" 
                   value="{{ request('search') }}"
                   placeholder="Cari berdasarkan judul atau deskripsi..."
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
        </div>

        {{-- lokasi --}}
        <div class="space-y-4">
            <h4 class="font-semibold text-gray-900">Lokasi</h4>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Provinsi</label>
                <select name="province_id" 
                        x-model="provinceId"
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
        </div>

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
                    <span class="ml-2 text-sm text-gray-700">{{ $value }}. {{ $label }}</span>
                </label>
                @endforeach
            </div>
        </div>

        {{-- durasi proyek --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Durasi Proyek</label>
            <select name="duration" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                <option value="">Semua Durasi</option>
                <option value="1-2" {{ request('duration') === '1-2' ? 'selected' : '' }}>1-2 Bulan</option>
                <option value="3-4" {{ request('duration') === '3-4' ? 'selected' : '' }}>3-4 Bulan</option>
                <option value="5-6" {{ request('duration') === '5-6' ? 'selected' : '' }}>5-6 Bulan</option>
                <option value="7+" {{ request('duration') === '7+' ? 'selected' : '' }}>7+ Bulan</option>
            </select>
        </div>

        {{-- tingkat kesulitan --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Tingkat Kesulitan</label>
            <div class="space-y-2">
                @php
                    $difficulties = [
                        'beginner' => 'Pemula',
                        'intermediate' => 'Menengah',
                        'advanced' => 'Lanjutan'
                    ];
                @endphp
                @foreach($difficulties as $value => $label)
                <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 rounded transition-colors">
                    <input type="radio" 
                           name="difficulty" 
                           value="{{ $value }}"
                           {{ request('difficulty') === $value ? 'checked' : '' }}
                           class="w-4 h-4 text-blue-600 focus:ring-2 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700">{{ $label }}</span>
                </label>
                @endforeach
                @if(request('difficulty'))
                <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 rounded transition-colors">
                    <input type="radio" 
                           name="difficulty" 
                           value=""
                           class="w-4 h-4 text-blue-600 focus:ring-2 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-500">Semua Tingkat</span>
                </label>
                @endif
            </div>
        </div>

        {{-- status proyek --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Status Proyek</label>
            <div class="space-y-2">
                @php
                    $statuses = [
                        'open' => 'Terbuka',
                        'in_progress' => 'Sedang Berjalan',
                        'completed' => 'Selesai'
                    ];
                @endphp
                @foreach($statuses as $value => $label)
                <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 rounded transition-colors">
                    <input type="checkbox" 
                           name="status[]" 
                           value="{{ $value }}"
                           {{ in_array($value, request('status', [])) ? 'checked' : '' }}
                           class="w-4 h-4 text-blue-600 focus:ring-2 focus:ring-blue-500 rounded">
                    <span class="ml-2 text-sm text-gray-700">{{ $label }}</span>
                </label>
                @endforeach
            </div>
        </div>

        {{-- sorting --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Urutkan Berdasarkan</label>
            <select name="sort" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                <option value="latest" {{ request('sort', 'latest') === 'latest' ? 'selected' : '' }}>Terbaru</option>
                <option value="deadline" {{ request('sort') === 'deadline' ? 'selected' : '' }}>Deadline Terdekat</option>
            </select>
        </div>

        {{-- submit button --}}
        <button type="submit" 
                class="w-full px-4 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors shadow-sm hover:shadow-md">
            Terapkan Filter
        </button>
    </form>
</div>