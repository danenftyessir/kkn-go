@extends('layouts.app')

@section('title', 'Buat Review Mahasiswa')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-green-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- breadcrumb --}}
        <nav class="mb-6">
            <ol class="flex items-center gap-2 text-sm">
                <li><a href="{{ route('institution.dashboard') }}" class="text-gray-500 hover:text-gray-700 transition-colors duration-200">Dashboard</a></li>
                <li class="text-gray-400">/</li>
                <li><a href="{{ route('institution.projects.show', $project->id) }}" class="text-gray-500 hover:text-gray-700 transition-colors duration-200">Proyek</a></li>
                <li class="text-gray-400">/</li>
                <li class="text-gray-900 font-medium">Buat Review</li>
            </ol>
        </nav>

        {{-- header --}}
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6 border border-gray-100">
            <div class="flex items-start gap-4">
                {{-- student avatar - FIXED: gunakan profile_photo_url dari user --}}
                <img src="{{ $project->student->user->profile_photo_url }}" 
                     alt="{{ $project->student->user->name }}"
                     class="w-20 h-20 rounded-full object-cover border-2 border-gray-200">
                
                <div class="flex-1">
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">Buat Review untuk {{ $project->student->user->name }}</h1>
                    <div class="space-y-1 text-sm text-gray-600">
                        <p><span class="font-medium text-gray-700">Universitas:</span> {{ $project->student->university->name }}</p>
                        <p><span class="font-medium text-gray-700">Proyek:</span> {{ $project->problem->title }}</p>
                        <p><span class="font-medium text-gray-700">Periode:</span> {{ $project->start_date->format('d M Y') }} - {{ $project->end_date->format('d M Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- form review --}}
        <form action="{{ route('institution.reviews.store', $project->id) }}" method="POST" class="space-y-6">
            @csrf

            {{-- rating section --}}
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <label class="block text-lg font-semibold text-gray-900 mb-4">Rating Keseluruhan <span class="text-red-500">*</span></label>
                
                <div x-data="{ rating: {{ old('rating', 0) }}, hover: 0 }" class="flex items-center gap-4">
                    <div class="flex gap-2">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button" 
                                    @click="rating = {{ $i }}"
                                    @mouseenter="hover = {{ $i }}"
                                    @mouseleave="hover = 0"
                                    class="transition-transform duration-200 transform hover:scale-110">
                                <svg class="w-12 h-12 transition-colors duration-200" 
                                     :class="(hover >= {{ $i }} || rating >= {{ $i }}) ? 'text-yellow-400 fill-yellow-400' : 'text-gray-300'"
                                     fill="currentColor" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                </svg>
                            </button>
                        @endfor
                    </div>
                    <input type="hidden" name="rating" x-model="rating" required>
                    <span class="text-2xl font-bold text-gray-900" x-show="rating > 0" x-text="rating + '/5'"></span>
                </div>

                @error('rating')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror

                <div class="mt-4 p-4 bg-blue-50 rounded-lg">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <p class="text-sm text-blue-700">Rating yang Anda berikan akan mempengaruhi reputasi mahasiswa di platform ini.</p>
                    </div>
                </div>
            </div>

            {{-- comment section --}}
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <label for="comment" class="block text-lg font-semibold text-gray-900 mb-4">Ulasan <span class="text-red-500">*</span></label>
                
                <textarea id="comment" 
                          name="comment" 
                          rows="8" 
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('comment') border-red-500 @enderror"
                          placeholder="Tuliskan ulasan Anda tentang kinerja mahasiswa, dedikasi, kreativitas, dan kontribusinya terhadap proyek..."
                          required>{{ old('comment') }}</textarea>

                @error('comment')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror

                <div class="mt-3 flex items-center justify-between text-sm text-gray-500">
                    <p>Minimal 50 karakter</p>
                    <p id="charCount" class="font-medium">0 karakter</p>
                </div>
            </div>

            {{-- visibility section --}}
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <label class="block text-lg font-semibold text-gray-900 mb-4">Visibilitas Review</label>
                
                <div class="space-y-3">
                    <label class="flex items-start gap-3 p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 transition-colors duration-200">
                        <input type="radio" name="is_public" value="1" class="mt-1" {{ old('is_public', '1') == '1' ? 'checked' : '' }}>
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <span class="font-semibold text-gray-900">Publik</span>
                            </div>
                            <p class="text-sm text-gray-600">Review akan ditampilkan di portofolio mahasiswa dan dapat dilihat oleh siapa saja</p>
                        </div>
                    </label>

                    <label class="flex items-start gap-3 p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 transition-colors duration-200">
                        <input type="radio" name="is_public" value="0" class="mt-1" {{ old('is_public') == '0' ? 'checked' : '' }}>
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                </svg>
                                <span class="font-semibold text-gray-900">Privat</span>
                            </div>
                            <p class="text-sm text-gray-600">Review hanya dapat dilihat oleh Anda dan mahasiswa yang bersangkutan</p>
                        </div>
                    </label>
                </div>

                @error('is_public')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- action buttons --}}
            <div class="flex justify-between gap-4">
                <a href="{{ route('institution.projects.show', $project->id) }}" 
                   class="inline-flex items-center px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Batal
                </a>

                <button type="submit" 
                        class="inline-flex items-center px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 font-semibold">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Review
                </button>
            </div>
        </form>

    </div>
</div>

@push('scripts')
<script>
// character counter untuk textarea
const commentTextarea = document.getElementById('comment');
const charCount = document.getElementById('charCount');

if (commentTextarea && charCount) {
    commentTextarea.addEventListener('input', function() {
        const length = this.value.length;
        charCount.textContent = length + ' karakter';
        
        if (length < 50) {
            charCount.classList.remove('text-green-600');
            charCount.classList.add('text-red-600');
        } else {
            charCount.classList.remove('text-red-600');
            charCount.classList.add('text-green-600');
        }
    });
}
</script>
@endpush

@endsection