@extends('layouts.app')

@section('title', $student->first_name . ' ' . $student->last_name . ' - Portfolio')

@section('content')
<div class="min-h-screen bg-gray-50 page-transition">
    <!-- header / hero section -->
    <div class="bg-gradient-to-r from-primary-600 to-blue-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="flex flex-col md:flex-row items-center md:items-start space-y-6 md:space-y-0 md:space-x-8">
                <!-- foto profil -->
                <div class="flex-shrink-0">
                    @if($student->profile_photo_url)
                        <img src="{{ asset('storage/' . $student->profile_photo_url) }}" 
                             alt="{{ $student->first_name }}"
                             class="w-32 h-32 md:w-40 md:h-40 rounded-full object-cover border-4 border-white shadow-xl">
                    @else
                        <div class="w-32 h-32 md:w-40 md:h-40 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center border-4 border-white shadow-xl">
                            <span class="text-5xl font-bold text-white">
                                {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                            </span>
                        </div>
                    @endif
                </div>

                <!-- info profil -->
                <div class="flex-1 text-center md:text-left">
                    <h1 class="text-4xl font-bold mb-2">
                        {{ $student->first_name }} {{ $student->last_name }}
                    </h1>
                    <p class="text-xl text-white/90 mb-4">{{ $student->major }}</p>
                    
                    <div class="flex flex-wrap gap-4 justify-center md:justify-start text-sm mb-6">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"></path>
                            </svg>
                            {{-- TODO: nama universitas dari relasi --}}
                            universitas
                        </div>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                            </svg>
                            semester {{ $student->semester }}
                        </div>
                    </div>

                    <!-- contact buttons -->
                    <div class="flex flex-wrap gap-3 justify-center md:justify-start">
                        @if($student->show_email ?? false)
                            <a href="mailto:{{ $user->email }}" 
                               class="inline-flex items-center px-4 py-2 bg-white text-primary-600 rounded-lg font-medium hover:bg-white/90 transition-all">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                </svg>
                                email
                            </a>
                        @endif
                        
                        @if($student->show_phone ?? false)
                            <a href="https://wa.me/{{ str_replace('+', '', $student->whatsapp_number) }}" 
                               target="_blank"
                               class="inline-flex items-center px-4 py-2 bg-green-500 text-white rounded-lg font-medium hover:bg-green-600 transition-all">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                </svg>
                                whatsapp
                            </a>
                        @endif
                        
                        <button onclick="shareProfile()" 
                                class="inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur-sm text-white rounded-lg font-medium hover:bg-white/30 transition-all">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path>
                            </svg>
                            bagikan profil
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
                                <span class="text-sm text-gray-600">proyek selesai</span>
                                <span class="text-2xl font-bold text-primary-600">
                                    {{ count($projects) }}
                                </span>
                            </div>
                        </div>
                        
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-sm text-gray-600">SDG tersentuh</span>
                                <span class="text-2xl font-bold text-green-600">0</span>
                            </div>
                        </div>
                        
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-sm text-gray-600">review positif</span>
                                <span class="text-2xl font-bold text-yellow-600">0</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- keahlian -->
                @if(count($skills) > 0)
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">keahlian</h3>
                    
                    <div class="flex flex-wrap gap-2">
                        @foreach($skills as $skill)
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                                {{ $skill }}
                            </span>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- achievements -->
                @if(count($achievements) > 0)
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">pencapaian</h3>
                    
                    <div class="space-y-3">
                        @foreach($achievements as $achievement)
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-yellow-500 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                <span class="text-sm text-gray-700">{{ $achievement }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- main content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- bio -->
                @if($student->bio)
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">tentang</h2>
                    <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $student->bio }}</p>
                </div>
                @endif

                <!-- proyek -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">portfolio proyek</h2>
                    
                    @if(count($projects) > 0)
                        <div class="space-y-6">
                            @foreach($projects as $project)
                                <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                                    <div class="flex items-start justify-between mb-3">
                                        <div class="flex-1">
                                            <h3 class="text-lg font-semibold text-gray-900 mb-1">
                                                {{ $project->title ?? 'judul proyek' }}
                                            </h3>
                                            <p class="text-sm text-gray-600">
                                                {{ $project->institution ?? 'nama instansi' }} • {{ $project->duration ?? 'durasi' }}
                                            </p>
                                        </div>
                                        <div class="flex items-center text-yellow-500">
                                            <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                            <span class="text-sm font-semibold">5.0</span>
                                        </div>
                                    </div>
                                    
                                    <p class="text-gray-700 text-sm mb-4">
                                        {{ $project->description ?? 'deskripsi proyek' }}
                                    </p>
                                    
                                    <div class="flex items-center justify-between">
                                        <div class="flex flex-wrap gap-2">
                                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">SDG 1</span>
                                            <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">SDG 4</span>
                                        </div>
                                        <a href="#" class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                                            lihat detail →
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="text-gray-500">belum ada proyek yang diselesaikan</p>
                        </div>
                    @endif
                </div>
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
            title: '{{ $student->first_name }} {{ $student->last_name }} - Portfolio',
            text: 'lihat portfolio KKN dari {{ $student->first_name }}',
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