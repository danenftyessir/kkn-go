@extends('layouts.app')

@section('title', 'Cari Teman - KKN-Go')

@section('content')
{{-- hero section dengan background --}}
<div class="relative bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600 overflow-hidden">
    <div class="absolute inset-0">
        <img src="{{ asset('placeholder-search-hero.jpg') }}" 
             alt="Search Background" 
             class="w-full h-full object-cover opacity-20">
        <div class="absolute inset-0 bg-gradient-to-br from-indigo-900/80 to-purple-900/80"></div>
    </div>

    <div class="relative container mx-auto px-6 py-12">
        <div class="max-w-4xl mx-auto text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
                Temukan Koneksi Baru
            </h1>
            <p class="text-xl text-indigo-100 mb-8">
                Bangun jaringan profesional dengan mahasiswa KKN di seluruh Indonesia
            </p>

            {{-- search bar --}}
            <form method="GET" action="{{ route('student.friends.search') }}" class="mb-4">
                <div class="relative max-w-2xl mx-auto">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Cari berdasarkan nama, universitas, atau jurusan..." 
                           class="w-full px-6 py-4 pr-12 text-lg border-0 rounded-lg shadow-lg focus:ring-2 focus:ring-white">
                    <button type="submit" 
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 p-2 text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>
                </div>
            </form>

            {{-- quick filter tags --}}
            <div class="flex flex-wrap justify-center gap-2">
                <a href="{{ route('student.friends.search', ['university_id' => $student->university_id]) }}" 
                   class="px-4 py-2 bg-white/20 backdrop-blur-sm text-white text-sm font-medium rounded-full hover:bg-white/30 transition-colors border border-white/30">
                    Dari Universitas Saya
                </a>
                <a href="{{ route('student.friends.search', ['major' => $student->major]) }}" 
                   class="px-4 py-2 bg-white/20 backdrop-blur-sm text-white text-sm font-medium rounded-full hover:bg-white/30 transition-colors border border-white/30">
                    Jurusan Yang Sama
                </a>
                <button onclick="toggleFilters()" 
                        class="px-4 py-2 bg-white/20 backdrop-blur-sm text-white text-sm font-medium rounded-full hover:bg-white/30 transition-colors border border-white/30">
                    <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                    </svg>
                    Filter Lanjutan
                </button>
            </div>
        </div>
    </div>
</div>

{{-- advanced filters (collapsible) --}}
<div id="advanced-filters" class="hidden bg-white border-b border-gray-200">
    <div class="container mx-auto px-6 py-6">
        <form method="GET" action="{{ route('student.friends.search') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                {{-- filter universitas --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Universitas</label>
                    <select name="university_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Universitas</option>
                        @foreach(\App\Models\University::orderBy('name')->get() as $university)
                        <option value="{{ $university->id }}" {{ request('university_id') == $university->id ? 'selected' : '' }}>
                            {{ $university->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- filter jurusan --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jurusan</label>
                    <input type="text" 
                           name="major" 
                           value="{{ request('major') }}"
                           placeholder="Contoh: Teknik Informatika" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                {{-- filter skills --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Skills</label>
                    <input type="text" 
                           name="skills" 
                           value="{{ request('skills') }}"
                           placeholder="Contoh: Web Development" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="flex gap-3">
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                    Terapkan Filter
                </button>
                <a href="{{ route('student.friends.search') }}" 
                   class="px-6 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                    Reset Filter
                </a>
            </div>
        </form>
    </div>
</div>

{{-- results section --}}
<div class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-6 py-8">
        
        {{-- results header --}}
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900">
                Hasil Pencarian
                @if($results->total() > 0)
                <span class="text-gray-600 font-normal">({{ $results->total() }} mahasiswa ditemukan)</span>
                @endif
            </h2>
        </div>

        {{-- results grid --}}
        @if($results->count() > 0)
        <div class="space-y-4 mb-8">
            @foreach($results as $result)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
                <div class="p-6">
                    <div class="flex items-start gap-6">
                        {{-- profile photo --}}
                        <div class="flex-shrink-0">
                            @if($result->user->profile_photo)
                                <img src="{{ Storage::url($result->user->profile_photo) }}"
                                     alt="{{ $result->user->first_name }}"
                                     class="w-24 h-24 rounded-lg object-cover shadow-sm">
                            @else
                                <div class="w-24 h-24 rounded-lg bg-gradient-to-br from-blue-500 to-green-500 flex items-center justify-center shadow-sm">
                                    <span class="text-white text-2xl font-bold">{{ strtoupper(substr($result->user->first_name, 0, 1)) }}{{ strtoupper(substr($result->user->last_name, 0, 1)) }}</span>
                                </div>
                            @endif
                        </div>

                        {{-- profile info --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between mb-3">
                                <div>
                                    <h3 class="text-2xl font-bold text-gray-900 mb-1">
                                        {{ $result->user->first_name }} {{ $result->user->last_name }}
                                    </h3>
                                    <p class="text-gray-700 font-medium mb-1">
                                        {{ $result->major }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        {{ $result->university->name }}
                                    </p>
                                </div>

                                {{-- connection status badge --}}
                                <div>
                                    @if($result->friendship_status === 'friends')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Terhubung
                                    </span>
                                    @elseif($result->friendship_status === 'pending_sent')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                        </svg>
                                        Menunggu
                                    </span>
                                    @elseif($result->friendship_status === 'pending_received')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/>
                                        </svg>
                                        Permintaan Masuk
                                    </span>
                                    @endif
                                </div>
                            </div>

                            {{-- bio --}}
                            @if($result->bio)
                            <p class="text-gray-700 mb-4 line-clamp-2">
                                {{ $result->bio }}
                            </p>
                            @endif

                            {{-- skills tags --}}
                            @if($result->skills && is_array($result->skills) && count($result->skills) > 0)
                            <div class="flex flex-wrap gap-2 mb-4">
                                @foreach(array_slice($result->skills, 0, 5) as $skill)
                                <span class="px-3 py-1 bg-gray-100 text-gray-700 text-xs font-medium rounded-full">
                                    {{ $skill }}
                                </span>
                                @endforeach
                                @if(count($result->skills) > 5)
                                <span class="px-3 py-1 bg-gray-100 text-gray-500 text-xs font-medium rounded-full">
                                    +{{ count($result->skills) - 5 }} lainnya
                                </span>
                                @endif
                            </div>
                            @endif

                            {{-- action buttons --}}
                            <div class="flex gap-3">
                                <a href="{{ route('student.friends.profile', $result->id) }}" 
                                   class="px-5 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                                    Lihat Profil
                                </a>

                                @if($result->friendship_status === 'none')
                                <form method="POST" action="{{ route('student.friends.send-request', $result->id) }}">
                                    @csrf
                                    <button type="submit" 
                                            class="px-5 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                        <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                        </svg>
                                        Hubungkan
                                    </button>
                                </form>
                                @elseif($result->friendship_status === 'friends')
                                <button disabled 
                                        class="px-5 py-2 bg-green-50 text-green-700 font-medium rounded-lg cursor-not-allowed">
                                    <svg class="w-4 h-4 inline-block mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Terhubung
                                </button>
                                @elseif($result->friendship_status === 'pending_sent')
                                <button disabled 
                                        class="px-5 py-2 bg-yellow-50 text-yellow-700 font-medium rounded-lg cursor-not-allowed">
                                    <svg class="w-4 h-4 inline-block mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                    </svg>
                                    Menunggu Respons
                                </button>
                                @elseif($result->friendship_status === 'pending_received')
                                <a href="{{ route('student.friends.index') }}" 
                                   class="px-5 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                    <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                    </svg>
                                    Lihat Permintaan
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- pagination --}}
        <div class="mt-6">
            {{ $results->links() }}
        </div>

        @else
        {{-- empty state --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-4">
                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">
                Tidak Ada Hasil Ditemukan
            </h3>
            <p class="text-gray-600 mb-6">
                Coba gunakan kata kunci yang berbeda atau sesuaikan filter pencarian Anda
            </p>
            <a href="{{ route('student.friends.search') }}" 
               class="inline-block px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                Reset Pencarian
            </a>
        </div>
        @endif

    </div>
</div>

@push('scripts')
<script>
function toggleFilters() {
    const filters = document.getElementById('advanced-filters');
    filters.classList.toggle('hidden');
}

// smooth scroll untuk smooth experience
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
});
</script>
@endpush
@endsection