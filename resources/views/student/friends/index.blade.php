@extends('layouts.app')

@section('title', 'Jaringan Saya - KKN-Go')

@section('content')
{{-- hero section dengan background image --}}
<div class="relative bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 overflow-hidden">
    {{-- background image dengan overlay --}}
    <div class="absolute inset-0">
        <img src="{{ asset('placeholder-network-hero.jpg') }}" 
             alt="Network Background" 
             class="w-full h-full object-cover opacity-20">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-900/80 to-indigo-900/80"></div>
    </div>

    <div class="relative container mx-auto px-6 py-16">
        <div class="max-w-4xl">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
                Jaringan Profesional Anda
            </h1>
            <p class="text-xl text-blue-100 mb-8">
                Bangun koneksi bermakna dengan sesama mahasiswa KKN di seluruh Indonesia
            </p>

            {{-- statistics bar --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 border border-white/20">
                    <div class="text-3xl font-bold text-white">{{ $stats['total_friends'] }}</div>
                    <div class="text-sm text-blue-100">Koneksi</div>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 border border-white/20">
                    <div class="text-3xl font-bold text-white">{{ $stats['pending_requests'] }}</div>
                    <div class="text-sm text-blue-100">Permintaan Masuk</div>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 border border-white/20">
                    <div class="text-3xl font-bold text-white">{{ $stats['sent_requests'] }}</div>
                    <div class="text-sm text-blue-100">Permintaan Terkirim</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- main content --}}
<div class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-6 py-8">
        <div class="flex flex-col lg:flex-row gap-6">
            
            {{-- sidebar kiri --}}
            <div class="lg:w-1/4">
                {{-- profile card --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-6">
                    <div class="relative h-20 bg-gradient-to-r from-blue-500 to-indigo-600">
                        <img src="{{ asset('placeholder-profile-cover.jpg') }}" 
                             alt="Cover" 
                             class="w-full h-full object-cover opacity-50">
                    </div>
                    <div class="relative px-4 pb-4">
                        <div class="flex justify-center -mt-12 mb-3">
                            <img src="{{ $student->user->profile_photo 
                                        ? Storage::url($student->user->profile_photo) 
                                        : asset('default-avatar.png') }}" 
                                 alt="{{ $student->user->first_name }}" 
                                 class="w-24 h-24 rounded-full border-4 border-white shadow-lg object-cover">
                        </div>
                        <h3 class="text-center font-bold text-gray-900 text-lg">
                            {{ $student->user->first_name }} {{ $student->user->last_name }}
                        </h3>
                        <p class="text-center text-sm text-gray-600 mb-4">
                            {{ $student->major }} - {{ $student->university->name }}
                        </p>
                        <div class="border-t border-gray-200 pt-3">
                            <a href="{{ route('student.profile.index') }}"
                               class="flex items-center justify-center text-sm text-blue-600 hover:text-blue-700 font-medium">
                                Lihat Profil Lengkap
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- quick links --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                    <h3 class="font-semibold text-gray-900 mb-3">Navigasi Cepat</h3>
                    <nav class="space-y-1">
                        <a href="{{ route('student.friends.index') }}" 
                           class="flex items-center px-3 py-2 text-sm font-medium text-blue-600 bg-blue-50 rounded-lg">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            Koneksi Saya
                        </a>
                        <a href="{{ route('student.friends.search') }}" 
                           class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 rounded-lg">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Cari Teman
                        </a>
                        <a href="{{ route('student.dashboard') }}" 
                           class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 rounded-lg">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            Dashboard
                        </a>
                    </nav>
                </div>
            </div>

            {{-- main content area --}}
            <div class="lg:w-3/4 space-y-6">
                
                {{-- pending requests section --}}
                @if($pendingRequests->count() > 0)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="border-b border-gray-200 px-6 py-4">
                        <h2 class="text-xl font-bold text-gray-900">
                            Permintaan Pertemanan ({{ $pendingRequests->count() }})
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach($pendingRequests as $request)
                            <div class="flex items-start gap-4 p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                <img src="{{ $request->requester->user->profile_photo 
                                            ? Storage::url($request->requester->user->profile_photo) 
                                            : asset('default-avatar.png') }}" 
                                     alt="{{ $request->requester->user->first_name }}" 
                                     class="w-16 h-16 rounded-full object-cover">
                                
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900 text-lg">
                                        {{ $request->requester->user->first_name }} {{ $request->requester->user->last_name }}
                                    </h3>
                                    <p class="text-sm text-gray-600 mb-1">
                                        {{ $request->requester->major }}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        {{ $request->requester->university->name }}
                                    </p>
                                    @if($request->message)
                                    <p class="text-sm text-gray-700 mt-2 italic">
                                        "{{ $request->message }}"
                                    </p>
                                    @endif
                                    <div class="flex gap-2 mt-3">
                                        <form method="POST" action="{{ route('student.friends.accept', $request->id) }}">
                                            @csrf
                                            <button type="submit" 
                                                    class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                                Terima
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('student.friends.reject', $request->id) }}">
                                            @csrf
                                            <button type="submit" 
                                                    class="px-4 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-300 transition-colors">
                                                Tolak
                                            </button>
                                        </form>
                                        <a href="{{ route('student.friends.profile', $request->requester->id) }}" 
                                           class="px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                                            Lihat Profil
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                {{-- suggestions section --}}
                @if($suggestions->count() > 0)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="border-b border-gray-200 px-6 py-4 flex justify-between items-center">
                        <h2 class="text-xl font-bold text-gray-900">
                            Rekomendasi Koneksi
                        </h2>
                        <a href="{{ route('student.friends.search') }}" 
                           class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                            Lihat Semua
                        </a>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($suggestions as $suggestion)
                            <div class="flex items-start gap-4 p-4 border border-gray-200 rounded-lg hover:shadow-md transition-shadow">
                                <img src="{{ $suggestion->user->profile_photo 
                                            ? Storage::url($suggestion->user->profile_photo) 
                                            : asset('default-avatar.png') }}" 
                                     alt="{{ $suggestion->user->first_name }}" 
                                     class="w-12 h-12 rounded-full object-cover">
                                
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-semibold text-gray-900 truncate">
                                        {{ $suggestion->user->first_name }} {{ $suggestion->user->last_name }}
                                    </h3>
                                    <p class="text-xs text-gray-600 truncate">
                                        {{ $suggestion->major }}
                                    </p>
                                    <p class="text-xs text-gray-500 truncate">
                                        {{ $suggestion->university->name }}
                                    </p>
                                    <div class="flex gap-2 mt-2">
                                        <form method="POST" action="{{ route('student.friends.send-request', $suggestion->id) }}" class="flex-1">
                                            @csrf
                                            <button type="submit" 
                                                    class="w-full px-3 py-1.5 bg-blue-600 text-white text-xs font-medium rounded hover:bg-blue-700 transition-colors">
                                                Hubungkan
                                            </button>
                                        </form>
                                        <a href="{{ route('student.friends.profile', $suggestion->id) }}" 
                                           class="px-3 py-1.5 border border-gray-300 text-gray-700 text-xs font-medium rounded hover:bg-gray-50 transition-colors">
                                            Profil
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                {{-- friends list section --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="border-b border-gray-200 px-6 py-4 flex justify-between items-center">
                        <h2 class="text-xl font-bold text-gray-900">
                            Koneksi Saya ({{ $friends->count() }})
                        </h2>
                        <div class="relative">
                            <input type="text" 
                                   id="friend-search" 
                                   placeholder="Cari koneksi..." 
                                   class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>
                    
                    @if($friends->count() > 0)
                    <div class="p-6">
                        <div id="friends-list" class="space-y-3">
                            @foreach($friends as $friend)
                            <div class="friend-item flex items-center gap-4 p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors"
                                 data-name="{{ strtolower($friend->user->first_name . ' ' . $friend->user->last_name) }}"
                                 data-university="{{ strtolower($friend->university->name ?? '') }}">
                                <img src="{{ $friend->user->profile_photo 
                                            ? Storage::url($friend->user->profile_photo) 
                                            : asset('default-avatar.png') }}" 
                                     alt="{{ $friend->user->first_name }}" 
                                     class="w-14 h-14 rounded-full object-cover">
                                
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900 text-lg">
                                        {{ $friend->user->first_name }} {{ $friend->user->last_name }}
                                    </h3>
                                    <p class="text-sm text-gray-600">
                                        {{ $friend->major }}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        {{ $friend->university->name }}
                                    </p>
                                </div>
                                
                                <div class="flex gap-2">
                                    <a href="{{ route('student.friends.profile', $friend->id) }}" 
                                       class="px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                                        Lihat Profil
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @else
                    <div class="p-12 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">
                            Belum Ada Koneksi
                        </h3>
                        <p class="text-gray-600 mb-6">
                            Mulai bangun jaringan Anda dengan mencari dan terhubung dengan mahasiswa lain
                        </p>
                        <a href="{{ route('student.friends.search') }}" 
                           class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            Cari Teman
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </a>
                    </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// real-time search untuk friends list
document.getElementById('friend-search')?.addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const friendItems = document.querySelectorAll('.friend-item');
    
    friendItems.forEach(item => {
        const name = item.dataset.name;
        const university = item.dataset.university;
        
        if (name.includes(searchTerm) || university.includes(searchTerm)) {
            item.style.display = 'flex';
        } else {
            item.style.display = 'none';
        }
    });
});

// auto-hide alerts
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });
});
</script>
@endpush
@endsection