{{-- resources/views/student/browse-problems/components/problem-card-list.blade.php --}}
{{-- component untuk menampilkan problem dalam list view --}}

@php
    $daysLeft = now()->diffInDays($problem->application_deadline, false);
    $isUrgent = $daysLeft <= 7 && $daysLeft >= 0;
    
    // cek apakah user sudah wishlist problem ini
    $isSaved = false;
    if (auth()->check() && auth()->user()->student) {
        try {
            $isSaved = auth()->user()->student->hasWishlisted($problem->id);
        } catch (\Illuminate\Database\QueryException $e) {
            $isSaved = false;
        }
    }
@endphp

<div class="problem-card-list bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-all duration-300 fade-in-up" 
     style="animation-delay: {{ $index * 0.05 }}s;">
    <div class="flex flex-col md:flex-row">
        {{-- image section --}}
        <a href="{{ route('student.problems.show', $problem->id) }}" class="block md:w-64 flex-shrink-0">
            <div class="relative h-48 md:h-full overflow-hidden bg-gray-100">
                @if($problem->images->isNotEmpty())
                    <img src="{{ asset('storage/' . $problem->images->first()->image_path) }}" 
                         alt="{{ $problem->title }}"
                         class="w-full h-full object-cover transform hover:scale-110 transition-transform duration-500"
                         loading="lazy">
                @else
                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-500 to-green-500">
                        <svg class="w-16 h-16 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                @endif
                
                {{-- badges overlay --}}
                <div class="absolute top-3 left-3 flex flex-wrap gap-2">
                    @if($problem->is_featured)
                    <span class="badge px-3 py-1 bg-yellow-500 text-white text-xs font-semibold rounded-full shadow-lg">
                        Unggulan
                    </span>
                    @endif
                    
                    @if($problem->is_urgent)
                    <span class="badge px-3 py-1 bg-red-500 text-white text-xs font-semibold rounded-full shadow-lg animate-pulse">
                        Mendesak
                    </span>
                    @endif
                </div>
            </div>
        </a>

        {{-- content section --}}
        <div class="flex-1 p-6">
            <div class="flex justify-between items-start mb-3">
                <div class="flex-1">
                    {{-- instansi --}}
                    <div class="flex items-center space-x-2 mb-2">
                        @if($problem->institution->logo_path)
                        <img src="{{ asset('storage/' . $problem->institution->logo_path) }}" 
                             alt="{{ $problem->institution->name }}"
                             class="w-8 h-8 rounded-full object-cover">
                        @else
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-green-500 flex items-center justify-center">
                            <span class="text-white text-xs font-bold">
                                {{ strtoupper(substr($problem->institution->name, 0, 1)) }}
                            </span>
                        </div>
                        @endif
                        <span class="text-sm text-gray-600">{{ $problem->institution->name }}</span>
                    </div>

                    {{-- title --}}
                    <a href="{{ route('student.problems.show', $problem->id) }}" 
                       class="block group">
                        <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-blue-600 transition-colors line-clamp-2">
                            {{ $problem->title }}
                        </h3>
                    </a>

                    {{-- description --}}
                    <p class="text-sm text-gray-600 mb-3 line-clamp-2">
                        {{ Str::limit($problem->description, 150) }}
                    </p>
                </div>

                {{-- wishlist button --}}
                @auth
                    @if(auth()->user()->user_type === 'student')
                    <div class="ml-4" x-data="wishlistButton({{ $problem->id }}, {{ $isSaved ? 'true' : 'false' }})">
                        <button @click="toggle()" 
                                :class="isSaved ? 'bg-red-50 border-red-300' : 'bg-white border-gray-300'"
                                class="p-2 rounded-lg border hover:bg-red-50 transition-all duration-200">
                            <svg :class="isSaved ? 'text-red-600' : 'text-gray-600'" 
                                 class="w-5 h-5" 
                                 :fill="isSaved ? 'currentColor' : 'none'" 
                                 stroke="currentColor" 
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </button>
                    </div>
                    @endif
                @endauth
            </div>

            {{-- info grid --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
                <div class="flex items-center text-sm text-gray-600">
                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    </svg>
                    {{ $problem->regency->name }}
                </div>
                <div class="flex items-center text-sm text-gray-600">
                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    {{ $problem->required_students }} mahasiswa
                </div>
                <div class="flex items-center text-sm text-gray-600">
                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    {{ $problem->duration_months }} bulan
                </div>
                <div class="flex items-center text-sm {{ $isUrgent ? 'text-red-600 font-semibold' : 'text-gray-600' }}">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ max(0, $daysLeft) }} hari lagi
                </div>
            </div>

            {{-- tags --}}
            <div class="flex flex-wrap gap-2 mb-4">
                @php
                    $sdgCategories = is_array($problem->sdg_categories) 
                        ? $problem->sdg_categories 
                        : json_decode($problem->sdg_categories, true) ?? [];
                @endphp
                @foreach(array_slice($sdgCategories, 0, 3) as $sdg)
                <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded">
                    SDG {{ $sdg }}
                </span>
                @endforeach
                @if(count($sdgCategories) > 3)
                <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs font-semibold rounded">
                    +{{ count($sdgCategories) - 3 }} lainnya
                </span>
                @endif
                
                <span class="px-2 py-1 bg-purple-100 text-purple-700 text-xs font-semibold rounded">
                    {{ ucfirst($problem->difficulty_level) }}
                </span>
            </div>

            {{-- action button --}}
            <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                <div class="flex items-center space-x-4 text-xs text-gray-500">
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        {{ $problem->views_count ?? 0 }} views
                    </span>
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        {{ $problem->applications_count ?? 0 }} aplikasi
                    </span>
                </div>
                <a href="{{ route('student.problems.show', $problem->id) }}" 
                   class="px-6 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition-all duration-200 hover:shadow-lg transform hover:-translate-y-0.5">
                    Lihat Detail
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// alpine component untuk wishlist button (jika belum ada)
if (typeof wishlistButton === 'undefined') {
    function wishlistButton(problemId, initialSaved) {
        return {
            isSaved: initialSaved,
            
            async toggle() {
                try {
                    const response = await fetch(`/student/wishlist/${problemId}/toggle`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        this.isSaved = data.saved;
                        
                        // tampilkan notifikasi sederhana
                        const message = data.saved ? 'Ditambahkan ke wishlist' : 'Dihapus dari wishlist';
                        this.showNotification(message);
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan. Silakan coba lagi.');
                }
            },
            
            showNotification(message) {
                const notification = document.createElement('div');
                notification.className = 'fixed top-4 right-4 bg-gray-900 text-white px-4 py-3 rounded-lg shadow-lg z-50 transition-all duration-300';
                notification.textContent = message;
                document.body.appendChild(notification);
                
                setTimeout(() => {
                    notification.style.opacity = '0';
                    setTimeout(() => notification.remove(), 300);
                }, 2000);
            }
        };
    }
}
</script>
@endpush