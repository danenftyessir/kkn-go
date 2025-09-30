@extends('layouts.app')

@section('title', $institution->institution_name . ' - Profil Instansi')

@section('content')
<div class="min-h-screen bg-gray-50 page-transition">
    <!-- header / hero section -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="flex flex-col md:flex-row items-center md:items-start space-y-6 md:space-y-0 md:space-x-8">
                <!-- logo instansi -->
                <div class="flex-shrink-0">
                    @if($institution->logo_url)
                        <img src="{{ asset('storage/' . $institution->logo_url) }}" 
                             alt="{{ $institution->institution_name }}"
                             class="w-32 h-32 md:w-40 md:h-40 rounded-lg object-cover border-4 border-white shadow-xl bg-white">
                    @else
                        <div class="w-32 h-32 md:w-40 md:h-40 rounded-lg bg-white/20 backdrop-blur-sm flex items-center justify-center border-4 border-white shadow-xl">
                            <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                    @endif
                </div>

                <!-- info instansi -->
                <div class="flex-1 text-center md:text-left">
                    <div class="flex items-center justify-center md:justify-start mb-2">
                        <h1 class="text-4xl font-bold">
                            {{ $institution->institution_name }}
                        </h1>
                        @if($institution->is_verified)
                            <svg class="w-8 h-8 text-blue-400 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        @endif
                    </div>
                    
                    <p class="text-xl text-white/90 mb-4">
                        {{ ucwords(str_replace('_', ' ', $institution->institution_type)) }}
                    </p>
                    
                    <div class="flex flex-wrap gap-4 justify-center md:justify-start text-sm mb-6">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                            </svg>
                            {{-- TODO: nama regency dari relasi --}}
                            lokasi instansi
                        </div>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z"></path>
                                <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z"></path>
                            </svg>
                            {{ count($activeProjects) + count($completedProjects) }} program
                        </div>
                    </div>

                    <!-- contact buttons -->
                    <div class="flex flex-wrap gap-3 justify-center md:justify-start">
                        <a href="mailto:{{ $user->email }}" 
                           class="inline-flex items-center px-4 py-2 bg-white text-blue-600 rounded-lg font-medium hover:bg-white/90 transition-all">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                            </svg>
                            email
                        </a>
                        
                        <a href="tel:{{ $institution->phone_number }}" 
                           class="inline-flex items-center px-4 py-2 bg-white text-blue-600 rounded-lg font-medium hover:bg-white/90 transition-all">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                            </svg>
                            telepon
                        </a>
                        
                        @if($institution->website)
                            <a href="{{ $institution->website }}" 
                               target="_blank"
                               class="inline-flex items-center px-4 py-2 bg-white text-blue-600 rounded-lg font-medium hover:bg-white/90 transition-all">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.083 9h1.946c.089-1.546.383-2.97.837-4.118A6.004 6.004 0 004.083 9zM10 2a8 8 0 100 16 8 8 0 000-16zm0 2c-.076 0-.232.032-.465.262-.238.234-.497.623-.737 1.182-.389.907-.673 2.142-.766 3.556h3.936c-.093-1.414-.377-2.649-.766-3.556-.24-.56-.5-.948-.737-1.182C10.232 4.032 10.076 4 10 4zm3.971 5c-.089-1.546-.383-2.97-.837-4.118A6.004 6.004 0 0115.917 9h-1.946zm-2.003 2H8.032c.093 1.414.377 2.649.766 3.556.24.56.5.948.737 1.182.233.23.389.262.465.262.076 0 .232-.032.465-.262.238-.234.498-.623.737-1.182.389-.907.673-2.142.766-3.556zm1.166 4.118c.454-1.147.748-2.572.837-4.118h1.946a6.004 6.004 0 01-2.783 4.118zm-6.268 0C6.412 13.97 6.118 12.546 6.03 11H4.083a6.004 6.004 0 002.783 4.118z" clip-rule="evenodd"></path>
                                </svg>
                                website
                            </a>
                        @endif
                        
                        <button onclick="shareProfile()" 
                                class="inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur-sm text-white rounded-lg font-medium hover:bg-white/30 transition-all">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path>
                            </svg>
                            bagikan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- statistik -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">statistik</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-sm text-gray-600">program aktif</span>
                                <span class="text-2xl font-bold text-blue-600">
                                    {{ count($activeProjects) }}
                                </span>
                            </div>
                        </div>
                        
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-sm text-gray-600">program selesai</span>
                                <span class="text-2xl font-bold text-green-600">
                                    {{ count($completedProjects) }}
                                </span>
                            </div>
                        </div>
                        
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-sm text-gray-600">total mahasiswa</span>
                                <span class="text-2xl font-bold text-primary-600">0</span>
                            </div>
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-sm text-gray-600">rating</span>
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                    <span class="text-xl font-bold text-gray-900">5.0</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- penanggung jawab -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">penanggung jawab</h3>
                    
                    <div class="space-y-2">
                        <div>
                            <p class="text-sm text-gray-600">nama</p>
                            <p class="font-medium text-gray-900">{{ $institution->pic_name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">jabatan</p>
                            <p class="font-medium text-gray-900">{{ $institution->pic_position }}</p>
                        </div>
                    </div>
                </div>

                <!-- alamat -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">alamat</h3>
                    
                    <div class="space-y-3">
                        <p class="text-sm text-gray-700">{{ $institution->address }}</p>
                        <div class="pt-3 border-t space-y-1">
                            <p class="text-xs text-gray-600">
                                {{-- TODO: nama regency & province dari relasi --}}
                                kabupaten/kota, provinsi
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- main content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- tentang instansi -->
                @if($institution->description)
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">tentang instansi</h2>
                    <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $institution->description }}</p>
                </div>
                @endif

                <!-- program terbaru -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">program terbaru</h2>
                    
                    @if(count($problems) > 0)
                        <div class="space-y-4">
                            @foreach($problems as $problem)
                                <div class="border border-gray-200 rounded-lg p-5 hover:shadow-md transition-shadow">
                                    <div class="flex items-start justify-between mb-3">
                                        <div class="flex-1">
                                            <h3 class="text-lg font-semibold text-gray-900 mb-1">
                                                {{ $problem->title ?? 'judul program' }}
                                            </h3>
                                            <div class="flex items-center text-sm text-gray-600 space-x-3">
                                                <span class="flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    lokasi
                                                </span>
                                                <span class="flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    {{ $problem->needed_students ?? '0' }} mahasiswa
                                                </span>
                                            </div>
                                        </div>
                                        <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">
                                            open
                                        </span>
                                    </div>
                                    
                                    <p class="text-gray-700 text-sm mb-4 line-clamp-2">
                                        {{ $problem->description ?? 'deskripsi program' }}
                                    </p>
                                    
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center text-sm text-gray-600">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                            </svg>
                                            deadline: {{ $problem->deadline ?? 'tba' }}
                                        </div>
                                        <a href="#" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                                            lihat detail →
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        @if(count($problems) > 3)
                            <div class="text-center mt-6">
                                <a href="#" class="btn-primary">
                                    lihat semua program
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="text-gray-500">belum ada program yang dipublikasikan</p>
                        </div>
                    @endif
                </div>

                <!-- testimoni -->
                @if(count($reviews) > 0)
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">testimoni mahasiswa</h2>
                    
                    <div class="space-y-6">
                        @foreach($reviews as $review)
                            <div class="border-b border-gray-200 pb-6 last:border-0 last:pb-0">
                                <div class="flex items-start space-x-4">
                                    <img src="{{ $review->student_photo ?? 'https://ui-avatars.com/api/?name=Student' }}" 
                                         alt="foto mahasiswa"
                                         class="w-12 h-12 rounded-full object-cover">
                                    
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between mb-2">
                                            <div>
                                                <p class="font-semibold text-gray-900">{{ $review->student_name ?? 'nama mahasiswa' }}</p>
                                                <p class="text-xs text-gray-500">{{ $review->university ?? 'universitas' }}</p>
                                            </div>
                                            <div class="flex items-center">
                                                @for($i = 0; $i < 5; $i++)
                                                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                    </svg>
                                                @endfor
                                            </div>
                                        </div>
                                        <p class="text-gray-700 text-sm">
                                            {{ $review->comment ?? 'testimoni mahasiswa' }}
                                        </p>
                                        <p class="text-xs text-gray-500 mt-2">{{ $review->project ?? 'nama proyek' }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// share profile
function shareProfile() {
    if (navigator.share) {
        navigator.share({
            title: '{{ $institution->institution_name }} - KKN-GO',
            text: 'lihat program KKN dari {{ $institution->institution_name }}',
            url: window.location.href
        }).catch(console.error);
    } else {
        // fallback: copy to clipboard
        navigator.clipboard.writeText(window.location.href).then(() => {
            alert('link profil berhasil disalin!');
        });
    }
}
</script>
@endpush