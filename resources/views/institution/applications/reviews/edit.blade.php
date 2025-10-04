@extends('layouts.app')

@section('title', 'Edit Review')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-green-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- breadcrumb --}}
        <nav class="mb-6">
            <ol class="flex items-center gap-2 text-sm">
                <li><a href="{{ route('institution.dashboard') }}" class="text-gray-500 hover:text-gray-700 transition-colors duration-200">Dashboard</a></li>
                <li class="text-gray-400">/</li>
                <li><a href="{{ route('institution.reviews.index') }}" class="text-gray-500 hover:text-gray-700 transition-colors duration-200">Review</a></li>
                <li class="text-gray-400">/</li>
                <li class="text-gray-900 font-medium">Edit Review</li>
            </ol>
        </nav>

        {{-- warning notice --}}
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6">
            <div class="flex gap-3">
                <svg class="w-6 h-6 text-amber-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <p class="font-semibold text-amber-800 mb-1">Perhatian!</p>
                    <p class="text-sm text-amber-700">Review hanya dapat diedit dalam 30 hari setelah dibuat. Waktu tersisa: <span class="font-semibold">{{ $review->created_at->addDays(30)->diffForHumans() }}</span></p>
                </div>
            </div>
        </div>

        {{-- header --}}
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6 border border-gray-100">
            <div class="flex items-start gap-4">
                <img src="{{ $review->student->user->profile_picture ? asset('storage/' . $review->student->user->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($review->student->user->name) }}" 
                     alt="{{ $review->student->user->name }}"
                     class="w-20 h-20 rounded-full object-cover border-2 border-gray-200">
                
                <div class="flex-1">
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">Edit Review untuk {{ $review->student->user->name }}</h1>
                    <div class="space-y-1 text-sm text-gray-600">
                        <p><span class="font-medium text-gray-700">Universitas:</span> {{ $review->student->university->name }}</p>
                        <p><span class="font-medium text-gray-700">Proyek:</span> {{ $review->project->problem->title }}</p>
                        <p><span class="font-medium text-gray-700">Direview:</span> {{ $review->created_at->format('d M Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- form edit review --}}
        <form action="{{ route('institution.reviews.update', $review->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- rating section --}}
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <label class="block text-lg font-semibold text-gray-900 mb-4">Rating Keseluruhan <span class="text-red-500">*</span></label>
                
                <div x-data="{ rating: {{ old('rating', $review->rating) }}, hover: 0 }" class="flex items-center gap-4">
                    <div class="flex gap-2">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button" 
                                    @click="rating = {{ $i }}"
                                    @mouseenter="hover = {{ $i }}"
                                    @mouseleave="hover = 0"
                                    class="transition-transform duration-200 transform hover:scale-110">
                                <svg class="w-12 h-12 transition-colors duration-200" 
                                     :class="(hover >= {{ $i }} || rating >= {{ $i }}) ? 'text-yellow-400 fill-current' : 'text-gray-300 fill-current'" 
                                     viewBox="0 0 20 20">
                                    <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                </svg>
                            </button>
                        @endfor
                    </div>
                    
                    <div x-show="rating > 0" x-transition class="text-2xl font-bold text-gray-900">
                        <span x-text="rating"></span>/5
                    </div>

                    <input type="hidden" name="rating" :value="rating" required>
                </div>

                @error('rating')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror

                <div class="mt-4 grid grid-cols-5 gap-2 text-xs text-gray-500">
                    <div class="text-center">Sangat Buruk</div>
                    <div class="text-center">Buruk</div>
                    <div class="text-center">Cukup</div>
                    <div class="text-center">Baik</div>
                    <div class="text-center">Sangat Baik</div>
                </div>
            </div>

            {{-- review text --}}
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <label class="block text-lg font-semibold text-gray-900 mb-2">Review <span class="text-red-500">*</span></label>
                <p class="text-sm text-gray-600 mb-4">Berikan penilaian Anda terhadap kinerja mahasiswa selama proyek berlangsung</p>
                
                <textarea name="review" 
                          rows="6" 
                          required
                          maxlength="1000"
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 resize-none"
                          placeholder="Tuliskan review Anda... (maksimal 1000 karakter)">{{ old('review', $review->review) }}</textarea>
                
                <div class="flex justify-between items-center mt-2">
                    @error('review')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @else
                        <p class="text-sm text-gray-500">Minimal 10 karakter</p>
                    @enderror
                    <p class="text-sm text-gray-400" x-data="{ count: {{ old('review', $review->review) ? strlen(old('review', $review->review)) : 0 }} }">
                        <span x-text="count"></span>/1000
                        <script>
                            document.querySelector('textarea[name="review"]').addEventListener('input', function(e) {
                                Alpine.store('count', e.target.value.length);
                            });
                        </script>
                    </p>
                </div>
            </div>

            {{-- strengths --}}
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <label class="block text-lg font-semibold text-gray-900 mb-2">Kelebihan Mahasiswa</label>
                <p class="text-sm text-gray-600 mb-4">Sebutkan hal-hal positif yang ditunjukkan mahasiswa (opsional)</p>
                
                <textarea name="strengths" 
                          rows="4" 
                          maxlength="500"
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 resize-none"
                          placeholder="Contoh: Komunikasi baik, proaktif dalam mengidentifikasi masalah, tepat waktu...">{{ old('strengths', $review->strengths) }}</textarea>
                
                @error('strengths')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- improvements --}}
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <label class="block text-lg font-semibold text-gray-900 mb-2">Saran Perbaikan</label>
                <p class="text-sm text-gray-600 mb-4">Berikan saran konstruktif untuk pengembangan mahasiswa (opsional)</p>
                
                <textarea name="improvements" 
                          rows="4" 
                          maxlength="500"
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 resize-none"
                          placeholder="Contoh: Bisa lebih aktif dalam diskusi tim, perlu meningkatkan dokumentasi...">{{ old('improvements', $review->improvements) }}</textarea>
                
                @error('improvements')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- would collaborate again --}}
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-start gap-3">
                    <input type="checkbox" 
                           id="would_collaborate_again" 
                           name="would_collaborate_again" 
                           value="1"
                           {{ old('would_collaborate_again', $review->would_collaborate_again) ? 'checked' : '' }}
                           class="mt-1 w-5 h-5 text-green-600 border-gray-300 rounded focus:ring-green-500 transition-colors duration-200">
                    <div class="flex-1">
                        <label for="would_collaborate_again" class="block text-lg font-semibold text-gray-900 mb-1 cursor-pointer">
                            Bersedia Berkolaborasi Lagi
                        </label>
                        <p class="text-sm text-gray-600">Centang jika Anda bersedia bekerja sama dengan mahasiswa ini di proyek mendatang</p>
                    </div>
                </div>
            </div>

            {{-- change history note --}}
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                <div class="flex gap-3">
                    <svg class="w-6 h-6 text-blue-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <p class="font-semibold text-blue-800 mb-1">Catatan Perubahan</p>
                        <p class="text-sm text-blue-700">Perubahan yang Anda buat akan langsung tersimpan dan dapat dilihat oleh mahasiswa. Pastikan review yang Anda berikan sudah sesuai dan profesional.</p>
                    </div>
                </div>
            </div>

            {{-- action buttons --}}
            <div class="flex items-center justify-between pt-4">
                <a href="{{ route('institution.reviews.show', $review->id) }}" 
                   class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all duration-200 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Batal
                </a>

                <div class="flex items-center gap-3">
                    <form action="{{ route('institution.reviews.destroy', $review->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus review ini?')" class="inline">
                        @csrf
                        @method('DELETE')
                        @if($review->created_at->addDays(7)->isFuture())
                            <button type="submit" class="px-6 py-3 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-all duration-200 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Hapus Review
                            </button>
                        @endif
                    </form>

                    <button type="submit" 
                            class="px-8 py-3 bg-gradient-to-r from-blue-600 to-green-600 text-white rounded-lg hover:from-blue-700 hover:to-green-700 transition-all duration-200 transform hover:scale-105 flex items-center gap-2 shadow-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>

    </div>
</div>

{{-- alpine.js --}}
@push('scripts')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush
@endsection