{{-- resources/views/student/browse-problems/components/problem-card.blade.php --}}
{{-- component untuk menampilkan card masalah dengan wishlist button --}}

@php
    $daysLeft = now()->diffInDays($problem->application_deadline, false);
    $isUrgent = $daysLeft <= 7 && $daysLeft >= 0;
    
    // cek apakah user sudah wishlist problem ini
    $isSaved = false;
    if (auth()->check() && auth()->user()->student) {
        try {
            $isSaved = auth()->user()->student->hasWishlisted($problem->id);
        } catch (\Exception $e) {
            // jika terjadi error, default false
            $isSaved = false;
        }
    }
    
    // parse sdg_categories dengan aman
    $sdgCategories = [];
    if ($problem->sdg_categories) {
        if (is_array($problem->sdg_categories)) {
            $sdgCategories = $problem->sdg_categories;
        } elseif (is_string($problem->sdg_categories)) {
            $sdgCategories = json_decode($problem->sdg_categories, true) ?? [];
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

            <!-- wishlist button - hanya tampil untuk student yang sudah login -->
            @auth
                @if(auth()->user()->user_type === 'student')
                <div class="absolute top-3 right-3" x-data="wishlistButton({{ $problem->id }}, {{ $isSaved ? 'true' : 'false' }})">
                    <button @click.prevent="toggle()"
                            :disabled="loading"
                            :class="saved ? 'bg-red-50 border-red-300' : 'bg-white border-gray-300'"
                            class="p-2 rounded-lg border hover:shadow-lg transition-all duration-200 backdrop-blur-sm">
                        <svg :class="saved ? 'text-red-600' : 'text-gray-600'" 
                             class="w-5 h-5 transition-colors" 
                             :fill="saved ? 'currentColor' : 'none'" 
                             stroke="currentColor" 
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </button>
                </div>
                @endif
            @endauth
        </div>
    </a>

    <!-- content -->
    <div class="p-5">
        <!-- instansi -->
        <div class="flex items-center space-x-2 mb-3">
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

        <!-- title -->
        <a href="{{ route('student.problems.show', $problem->id) }}" class="block group">
            <h3 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-blue-600 transition-colors line-clamp-2">
                {{ $problem->title }}
            </h3>
        </a>

        <!-- location -->
        <div class="flex items-center text-sm text-gray-600 mb-3">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            <span>{{ $problem->regency->name ?? '' }}, {{ $problem->province->name ?? '' }}</span>
        </div>

        <!-- sdg badges -->
        @if(!empty($sdgCategories))
        <div class="flex flex-wrap gap-2 mb-3">
            @foreach(array_slice($sdgCategories, 0, 3) as $sdg)
            <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-medium rounded-full">
                SDG {{ $sdg }}
            </span>
            @endforeach
            @if(count($sdgCategories) > 3)
            <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs font-medium rounded-full">
                +{{ count($sdgCategories) - 3 }}
            </span>
            @endif
        </div>
        @endif

        <!-- stats -->
        <div class="flex items-center justify-between pt-3 border-t border-gray-100">
            <div class="flex items-center text-sm text-gray-600">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <span>{{ $problem->required_students }} mahasiswa</span>
            </div>
            
            @if($daysLeft >= 0)
            <span class="text-xs {{ $isUrgent ? 'text-red-600' : 'text-orange-600' }}">
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
// alpine.js component untuk wishlist button
window.wishlistButton = function(problemId, initialSaved = false) {
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

                if (!response.ok) {
                    throw new Error('gagal toggle wishlist');
                }

                const data = await response.json();
                
                if (data.success) {
                    this.saved = data.saved;
                    this.showNotification(data.message);
                    
                    // trigger animation
                    if (this.saved) {
                        this.$el.querySelector('button').classList.add('animate-bounce');
                        setTimeout(() => {
                            this.$el.querySelector('button').classList.remove('animate-bounce');
                        }, 500);
                    }
                }
            } catch (error) {
                console.error('error toggle wishlist:', error);
                this.showNotification('terjadi kesalahan, silakan coba lagi', 'error');
            } finally {
                this.loading = false;
            }
        },
        
        showNotification(message, type = 'success') {
            // hapus notification sebelumnya jika ada
            const existing = document.querySelector('.wishlist-toast');
            if (existing) {
                existing.remove();
            }
            
            // buat toast notification
            const toast = document.createElement('div');
            toast.className = `wishlist-toast fixed bottom-4 right-4 px-6 py-3 rounded-lg shadow-lg text-white z-50 transform transition-all duration-300 ${
                type === 'success' ? 'bg-gray-900' : 'bg-red-600'
            }`;
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(20px)';
            toast.textContent = message;
            
            document.body.appendChild(toast);
            
            // animate in
            setTimeout(() => {
                toast.style.opacity = '1';
                toast.style.transform = 'translateY(0)';
            }, 10);
            
            // animate out dan remove
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(20px)';
                setTimeout(() => toast.remove(), 300);
            }, 2500);
        }
    };
};
</script>
@endpush
@endonce