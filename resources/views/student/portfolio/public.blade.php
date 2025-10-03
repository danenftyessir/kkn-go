{{-- resources/views/student/portfolio/public.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio - {{ $student->first_name }} {{ $student->last_name }} | KKN-GO</title>
    <meta name="description" content="Portfolio KKN {{ $student->first_name }} {{ $student->last_name }} dari {{ $student->university->name }}">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">

    {{-- simple navbar --}}
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="{{ route('home') }}" class="flex items-center">
                    <img src="{{ asset('kkn-go-logo.png') }}" alt="KKN-GO" class="h-8">
                </a>
                <div class="flex items-center gap-4">
                    @guest
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 transition-colors">Login</a>
                        <a href="{{ route('register') }}" class="px-4 py-2 bg-gradient-to-r from-blue-600 to-green-600 text-white rounded-lg hover:shadow-lg transition-all">
                            Daftar
                        </a>
                    @else
                        <a href="{{ route('student.dashboard') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            Dashboard
                        </a>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    <div class="min-h-screen py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- profile header --}}
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden mb-8 fade-in-up">
                <div class="h-40 bg-gradient-to-r from-blue-600 to-green-600"></div>
                <div class="px-8 pb-8">
                    <div class="flex flex-col md:flex-row items-start md:items-end gap-6 -mt-20 mb-8">
                        <div class="flex-shrink-0">
                            @if($student->profile_photo_path)
                                <img src="{{ asset('storage/' . $student->profile_photo_path) }}" 
                                     alt="{{ $student->user->name }}"
                                     class="w-40 h-40 rounded-2xl border-4 border-white shadow-xl object-cover">
                            @else
                                <div class="w-40 h-40 rounded-2xl border-4 border-white shadow-xl bg-gradient-to-br from-blue-500 to-green-500 flex items-center justify-center">
                                    <span class="text-white text-5xl font-bold">{{ strtoupper(substr($student->first_name, 0, 1)) }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <h1 class="text-4xl font-bold text-gray-900 mb-2">{{ $student->first_name }} {{ $student->last_name }}</h1>
                            <p class="text-xl text-gray-600 mb-1">{{ $student->university->name }}</p>
                            <p class="text-gray-500">{{ $student->major }} • Semester {{ $student->semester }}</p>
                        </div>
                        <button onclick="sharePortfolio()" 
                                class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2 font-semibold">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path>
                            </svg>
                            Share
                        </button>
                    </div>

                    @if($student->bio)
                        <p class="text-gray-600 text-lg leading-relaxed mb-8">{{ $student->bio }}</p>
                    @endif

                    {{-- statistics --}}
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                        <div class="text-center p-6 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl">
                            <p class="text-4xl font-bold text-blue-600 mb-2">{{ $stats['total_projects'] }}</p>
                            <p class="text-sm text-gray-700 font-medium">Proyek Selesai</p>
                        </div>
                        <div class="text-center p-6 bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl">
                            <div class="flex items-center justify-center mb-2">
                                <p class="text-4xl font-bold text-yellow-600">{{ number_format($stats['average_rating'], 1) }}</p>
                                <svg class="w-8 h-8 ml-2 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            </div>
                            <p class="text-sm text-gray-700 font-medium">Rating Rata-rata</p>
                        </div>
                        <div class="text-center p-6 bg-gradient-to-br from-green-50 to-green-100 rounded-xl">
                            <p class="text-4xl font-bold text-green-600 mb-2">{{ $stats['total_beneficiaries'] }}</p>
                            <p class="text-sm text-gray-700 font-medium">Penerima Manfaat</p>
                        </div>
                        <div class="text-center p-6 bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl">
                            <p class="text-4xl font-bold text-purple-600 mb-2">{{ count($sdg_addressed) }}</p>
                            <p class="text-sm text-gray-700 font-medium">SDG Categories</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- skills --}}
            @if(!empty($skills))
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8 mb-8 fade-in-up" style="animation-delay: 0.1s;">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Skills & Kompetensi</h2>
                    <div class="flex flex-wrap gap-3">
                        @foreach($skills as $skill)
                            <span class="px-5 py-2 bg-gradient-to-r from-blue-100 to-green-100 text-gray-800 rounded-full font-medium">
                                {{ $skill }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- achievements --}}
            @if(!empty($achievements))
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8 mb-8 fade-in-up" style="animation-delay: 0.15s;">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Pencapaian</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach($achievements as $achievement)
                            <div class="p-6 border-2 border-{{ $achievement['color'] }}-200 bg-{{ $achievement['color'] }}-50 rounded-xl text-center hover:shadow-lg transition-all">
                                <div class="w-16 h-16 bg-{{ $achievement['color'] }}-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-{{ $achievement['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                    </svg>
                                </div>
                                <h3 class="font-bold text-gray-900 mb-2 text-lg">{{ $achievement['title'] }}</h3>
                                <p class="text-sm text-gray-600">{{ $achievement['description'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- projects showcase --}}
            <div class="fade-in-up" style="animation-delay: 0.2s;">
                <h2 class="text-3xl font-bold text-gray-900 mb-8">Proyek Portfolio</h2>

                @if($projects->isEmpty())
                    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-16 text-center">
                        <svg class="w-20 h-20 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Proyek</h3>
                        <p class="text-gray-600">Portfolio proyek akan ditampilkan di sini</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach($projects as $index => $project)
                            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden hover:shadow-2xl transition-all group">
                                {{-- project image --}}
                                <div class="h-56 bg-gradient-to-br from-blue-500 to-green-500 relative overflow-hidden">
                                    @if($project->problem->images->isNotEmpty())
                                        <img src="{{ asset('storage/' . $project->problem->images->first()->image_path) }}" 
                                             alt="{{ $project->title }}"
                                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                    @else
                                        <div class="absolute inset-0 flex items-center justify-center">
                                            <svg class="w-20 h-20 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                <div class="p-6">
                                    <h3 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2 group-hover:text-blue-600 transition-colors">
                                        {{ $project->title }}
                                    </h3>
                                    
                                    {{-- institution --}}
                                    <div class="flex items-center text-gray-600 mb-4">
                                        @if($project->institution->logo_path)
                                            <img src="{{ asset('storage/' . $project->institution->logo_path) }}" 
                                                 alt="{{ $project->institution->name }}"
                                                 class="w-8 h-8 rounded mr-2 object-cover">
                                        @endif
                                        <span class="text-sm">{{ $project->institution->name }}</span>
                                    </div>

                                    {{-- duration --}}
                                    <div class="flex items-center text-sm text-gray-500 mb-4 pb-4 border-b">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        {{ $project->start_date->format('M Y') }} - {{ $project->end_date->format('M Y') }}
                                    </div>

                                    {{-- rating --}}
                                    @if($project->reviews->isNotEmpty())
                                        @php
                                            $review = $project->reviews->first();
                                        @endphp
                                        <div class="mb-4">
                                            <div class="flex items-center mb-2">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <svg class="w-5 h-5 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" 
                                                         fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                    </svg>
                                                @endfor
                                                <span class="ml-2 font-semibold text-gray-900">{{ number_format($review->rating, 1) }}</span>
                                            </div>
                                            <p class="text-sm text-gray-600 italic line-clamp-3">"{{ $review->review_text }}"</p>
                                        </div>
                                    @endif

                                    {{-- impact metrics --}}
                                    @if($project->impact_metrics)
                                        <div class="grid grid-cols-2 gap-3">
                                            <div class="text-center p-3 bg-blue-50 rounded-lg">
                                                <p class="text-2xl font-bold text-blue-600">{{ $project->impact_metrics['beneficiaries'] ?? 0 }}</p>
                                                <p class="text-xs text-gray-600 mt-1">Penerima Manfaat</p>
                                            </div>
                                            <div class="text-center p-3 bg-green-50 rounded-lg">
                                                <p class="text-2xl font-bold text-green-600">{{ $project->impact_metrics['activities'] ?? 0 }}</p>
                                                <p class="text-xs text-gray-600 mt-1">Kegiatan</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- footer --}}
            <div class="mt-16 pt-8 border-t text-center">
                <p class="text-gray-600 mb-4">Portfolio dibuat dengan KKN-GO Platform</p>
                <a href="{{ route('home') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700 font-semibold">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Kembali ke Homepage
                </a>
            </div>

        </div>
    </div>

    <script>
    function sharePortfolio() {
        const url = window.location.href;
        
        if (navigator.share) {
            navigator.share({
                title: 'Portfolio {{ $student->first_name }} {{ $student->last_name }}',
                text: 'Lihat portfolio KKN saya!',
                url: url
            }).catch(err => console.log('Error sharing:', err));
        } else {
            // fallback: copy to clipboard
            navigator.clipboard.writeText(url).then(() => {
                alert('Link portfolio berhasil disalin ke clipboard!');
            });
        }
    }
    </script>

    <style>
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .fade-in-up {
        animation: fadeInUp 0.8s ease-out forwards;
        opacity: 0;
    }
    </style>

</body>
</html>