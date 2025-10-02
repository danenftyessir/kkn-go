{{-- resources/views/student/browse-problems/components/problem-card.blade.php --}}
{{-- component untuk menampilkan card masalah dengan wishlist button --}}

@php
    $daysLeft = now()->diffInDays($problem->application_deadline, false);
    $isUrgent = $daysLeft <= 7 && $daysLeft >= 0;
    
    // cek apakah user sudah wishlist problem ini
    // SAFETY CHECK: cek apakah table wishlists sudah ada
    $isSaved = false;
    if (auth()->check() && auth()->user()->student) {
        try {
            $isSaved = auth()->user()->student->hasWishlisted($problem->id);
        } catch (\Illuminate\Database\QueryException $e) {
            // table wishlists belum ada, skip check
            $isSaved = false;
        }
    }
@endphp

<div class="problem-card bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-all duration-300 fade-in-up" 
     style="animation-delay: {{ $index * 0.1 }}s;">
    <a href="{{ route('student.problems.show', $problem->id) }}" class="block">
        <!-- image -->
        <div class="relative h-48 overflow-hidden bg-gray-100">
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
            
            <!-- badges overlay -->
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

            <!-- wishlist button - hanya tampil jika table wishlists sudah ada -->
            @auth
                @if(auth()->user()->user_type === 'student' && Schema::hasTable('wishlists'))
                <div class="absolute top-3 right-3">
                    <button @click.prevent="toggle()"
                            x-data="wishlistButton({{ $problem->id }}, {{ $isSaved ? 'true' : 'false' }})"
                            :class="getButtonClass()"
                            :disabled="loading"
                            class="wishlist-btn p-2 rounded-full shadow-lg transition-all duration-300 hover:scale-110"
                            title="Simpan ke wishlist">
                        <svg class="wishlist-icon w-5 h-5" 
                             :class="getIconClass()"
                             stroke="currentColor" 
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                        <span x-show="loading" class="absolute inset-0 flex items-center justify-center">
                            <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </span>
                    </button>
                </div>
                @endif
            @endauth
        </div>
    </a>

    <!-- content -->
    <div class="p-5">
        <!-- institution -->
        <div class="flex items-center mb-3">
            @if($problem->institution->logo_path)
            <img src="{{ asset('storage/' . $problem->institution->logo_path) }}" 
                 alt="{{ $problem->institution->name }}"
                 class="w-8 h-8 rounded-full object-cover mr-2">
            @else
            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-green-500 flex items-center justify-center mr-2">
                <span class="text-white text-xs font-bold">
                    {{ strtoupper(substr($problem->institution->name, 0, 1)) }}
                </span>
            </div>
            @endif
            <span class="text-sm text-gray-600 truncate">{{ $problem->institution->name }}</span>
        </div>

        <!-- title -->
        <a href="{{ route('student.problems.show', $problem->id) }}" 
           class="block group">
            <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2 group-hover:text-blue-600 transition-colors">
                {{ $problem->title }}
            </h3>
        </a>

        <!-- description -->
        <p class="text-sm text-gray-600 mb-4 line-clamp-2">
            {{ Str::limit($problem->description, 100) }}
        </p>

        <!-- details -->
        <div class="space-y-2 mb-4">
            <div class="flex items-center text-sm text-gray-600">
                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                </svg>
                <span class="truncate">{{ $problem->regency->name }}, {{ $problem->province->name }}</span>
            </div>
            <div class="flex items-center text-sm text-gray-600">
                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                {{ $problem->required_students }} mahasiswa dibutuhkan
            </div>
            <div class="flex items-center text-sm text-gray-600">
                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ $problem->duration_months }} bulan
            </div>
        </div>

        <!-- tags/skills -->
        @php
            // handle both string (JSON) and array format
            $skills = $problem->required_skills;
            if (is_string($skills)) {
                $skills = json_decode($skills, true) ?? [];
            }
            $skills = is_array($skills) ? $skills : [];
        @endphp
        @if(count($skills) > 0)
        <div class="flex flex-wrap gap-1 mb-4">
            @foreach(array_slice($skills, 0, 3) as $skill)
            <span class="px-2 py-1 bg-blue-50 text-blue-700 text-xs rounded-md">
                {{ $skill }}
            </span>
            @endforeach
            @if(count($skills) > 3)
            <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded-md">
                +{{ count($skills) - 3 }}
            </span>
            @endif
        </div>
        @endif

        <!-- footer -->
        <div class="pt-4 border-t border-gray-200 flex items-center justify-between">
            <div class="flex items-center text-xs text-gray-500">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                {{ $problem->views_count }} views
            </div>
            
            @if($isUrgent)
            <span class="text-xs font-semibold {{ $daysLeft <= 3 ? 'text-red-600' : 'text-orange-600' }}">
                {{ abs($daysLeft) }} hari lagi
            </span>
            @else
            <span class="text-xs text-gray-500">
                {{ $problem->application_deadline->format('d M Y') }}
            </span>
            @endif
        </div>

        <!-- difficulty badge -->
        <div class="mt-3">
            <span class="inline-flex items-center px-2 py-1 {{ $problem->getDifficultyBadgeColor() }} text-xs font-medium rounded-md">
                {{ $problem->getDifficultyLabel() }}
            </span>
        </div>
    </div>
</div>

@once
@push('scripts')
<script>
// import wishlist functionality
function wishlistButton(problemId, initialSaved = false) {
    return {
        problemId: problemId,
        saved: initialSaved,
        loading: false,
        
        async toggle() {
            if (this.loading) return;
            
            this.loading = true;
            
            try {
                const response = await fetch(`/student/wishlist/${this.problemId}/toggle`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) throw new Error('gagal toggle wishlist');

                const data = await response.json();
                
                if (data.success) {
                    this.saved = data.saved;
                    this.showNotification(data.message);
                    this.animateButton();
                }
            } catch (error) {
                console.error('error toggle wishlist:', error);
                this.showNotification('terjadi kesalahan, silakan coba lagi', 'error');
            } finally {
                this.loading = false;
            }
        },
        
        animateButton() {
            if (this.saved) {
                this.$el.classList.add('animate-heart-beat');
                setTimeout(() => {
                    this.$el.classList.remove('animate-heart-beat');
                }, 1000);
            }
        },
        
        showNotification(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `fixed bottom-4 right-4 px-6 py-3 rounded-lg shadow-lg text-white z-50 transform transition-all duration-300 ${
                type === 'success' ? 'bg-green-500' : 'bg-red-500'
            }`;
            toast.textContent = message;
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(20px)';
            
            document.body.appendChild(toast);
            
            requestAnimationFrame(() => {
                toast.style.opacity = '1';
                toast.style.transform = 'translateY(0)';
            });
            
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    document.body.removeChild(toast);
                }, 300);
            }, 3000);
        },
        
        getButtonClass() {
            if (this.saved) {
                return 'bg-red-500 text-white hover:bg-red-600';
            }
            return 'bg-white text-gray-700 hover:bg-gray-50 border border-gray-300';
        },
        
        getIconClass() {
            return this.saved ? 'fill-current' : 'fill-none';
        }
    };
}

// tambahkan animation styles
if (!document.getElementById('wishlist-animation-styles')) {
    const style = document.createElement('style');
    style.id = 'wishlist-animation-styles';
    style.textContent = `
        @keyframes heartBeat {
            0%, 100% { transform: scale(1); }
            10%, 30% { transform: scale(0.9); }
            20%, 40%, 60%, 80% { transform: scale(1.1); }
            50%, 70% { transform: scale(1.05); }
        }
        
        .animate-heart-beat {
            animation: heartBeat 1s ease-in-out;
        }
    `;
    document.head.appendChild(style);
}
</script>
@endpush
@endonce