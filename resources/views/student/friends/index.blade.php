@extends('layouts.app')

@section('title', 'Jaringan - KKN-Go')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/friends.css') }}">
<style>
    /* Hero with background image - LinkedIn style */
    .network-hero {
        position: relative;
        background: linear-gradient(135deg, #0a66c2 0%, #004182 100%);
        min-height: 200px;
        overflow: hidden;
    }

    .network-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-image: url('/dashboard-student.jpg');
        background-size: cover;
        background-position: center;
        opacity: 0.15;
    }

    /* Clean list-based design - NO CARDS */
    .connection-item {
        background: white;
        border-bottom: 1px solid #e5e7eb;
        transition: background-color 0.2s ease;
    }

    .connection-item:hover {
        background-color: #f9fafb;
    }

    .connection-item:last-child {
        border-bottom: none;
    }

    /* Profile image styling */
    .profile-image {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #e5e7eb;
    }

    /* Subtle section backgrounds */
    .section-with-bg {
        position: relative;
        background: white;
    }

    .section-with-bg::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-image: url('/dashboard-student2.jpeg');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        opacity: 0.03;
        pointer-events: none;
    }

    /* Clean button styles */
    .btn-primary-linkedin {
        background: #0a66c2;
        color: white;
        padding: 8px 16px;
        border-radius: 24px;
        font-weight: 600;
        font-size: 14px;
        border: 2px solid #0a66c2;
        transition: all 0.2s ease;
    }

    .btn-primary-linkedin:hover {
        background: #004182;
        border-color: #004182;
    }

    .btn-secondary-linkedin {
        background: white;
        color: #0a66c2;
        padding: 8px 16px;
        border-radius: 24px;
        font-weight: 600;
        font-size: 14px;
        border: 2px solid #0a66c2;
        transition: all 0.2s ease;
    }

    .btn-secondary-linkedin:hover {
        background: #f3f6f8;
    }

    /* Stats clean design */
    .stat-item {
        text-align: center;
        padding: 16px;
    }

    .stat-number {
        font-size: 32px;
        font-weight: 700;
        color: #0a66c2;
        display: block;
    }

    .stat-label {
        font-size: 14px;
        color: #666;
        margin-top: 4px;
    }
</style>
@endpush

@section('content')
{{-- Clean LinkedIn-style hero --}}
<div class="network-hero">
    <div class="relative z-10 max-w-6xl mx-auto px-6 py-12">
        <h1 class="text-3xl font-bold text-white mb-2">Jaringan Anda</h1>
        <p class="text-lg text-white/90">Kelola koneksi profesional Anda</p>
    </div>
</div>

{{-- Stats bar - clean and minimal --}}
<div class="bg-white border-b border-gray-200">
    <div class="max-w-6xl mx-auto px-6">
        <div class="grid grid-cols-3 divide-x divide-gray-200">
            <div class="stat-item">
                <span class="stat-number">{{ $stats['total_friends'] }}</span>
                <span class="stat-label">Koneksi</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">{{ $stats['pending_requests'] }}</span>
                <span class="stat-label">Permintaan</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">{{ $stats['sent_requests'] }}</span>
                <span class="stat-label">Terkirim</span>
            </div>
        </div>
    </div>
</div>

<div class="bg-gray-50 min-h-screen py-6">
    <div class="max-w-6xl mx-auto px-6">

        {{-- Pending Requests Section --}}
        @if($pendingRequests->count() > 0)
        <div class="mb-6 section-with-bg">
            <div class="relative bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900">
                        Permintaan Pertemanan ({{ $pendingRequests->count() }})
                    </h2>
                </div>

                <div>
                    @foreach($pendingRequests as $request)
                    <div class="connection-item px-6 py-4">
                        <div class="flex items-start gap-4">
                            <a href="{{ route('profile.public', $request->requester->user->username) }}">
                                <img src="{{ $request->requester->profile_photo_url }}"
                                     alt="{{ $request->requester->user->name }}"
                                     class="avatar-circle avatar-md">
                            </a>

                            <div class="flex-1 min-w-0">
                                <a href="{{ route('profile.public', $request->requester->user->username) }}"
                                   class="text-lg font-semibold text-gray-900 hover:text-blue-600 hover:underline">
                                    {{ $request->requester->user->name }}
                                </a>
                                <p class="text-sm text-gray-600 mt-1">
                                    {{ $request->requester->major }} • {{ $request->requester->university->name }}
                                </p>
                                @if($request->message)
                                <p class="text-sm text-gray-700 mt-2 italic">
                                    "{{ $request->message }}"
                                </p>
                                @endif
                                <p class="text-xs text-gray-500 mt-2">
                                    {{ $request->created_at->diffForHumans() }}
                                </p>
                            </div>

                            <div class="flex gap-2">
                                <form method="POST" action="{{ route('student.friends.accept', $request->id) }}">
                                    @csrf
                                    <button type="submit" class="btn-primary-linkedin">
                                        Terima
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('student.friends.reject', $request->id) }}">
                                    @csrf
                                    <button type="submit" class="btn-secondary-linkedin">
                                        Tolak
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        {{-- Suggestions Section --}}
        @if($suggestions->count() > 0)
        <div class="mb-6 section-with-bg">
            <div class="relative bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h2 class="text-xl font-bold text-gray-900">
                        Rekomendasi untuk Anda
                    </h2>
                    <a href="{{ route('student.friends.search') }}"
                       class="text-sm font-semibold text-blue-600 hover:underline">
                        Lihat Semua
                    </a>
                </div>

                <div>
                    @foreach($suggestions as $suggestion)
                    <div class="connection-item px-6 py-4">
                        <div class="flex items-center gap-4">
                            <a href="{{ route('profile.public', $suggestion->user->username) }}">
                                <img src="{{ $suggestion->profile_photo_url }}"
                                     alt="{{ $suggestion->user->name }}"
                                     class="avatar-circle avatar-md">
                            </a>

                            <div class="flex-1 min-w-0">
                                <a href="{{ route('profile.public', $suggestion->user->username) }}"
                                   class="text-lg font-semibold text-gray-900 hover:text-blue-600 hover:underline">
                                    {{ $suggestion->user->name }}
                                </a>
                                <p class="text-sm text-gray-600 mt-1">
                                    {{ $suggestion->major }} • {{ $suggestion->university->name }}
                                </p>
                            </div>

                            <form method="POST" action="{{ route('student.friends.send-request', $suggestion->id) }}">
                                @csrf
                                <button type="submit" class="btn-secondary-linkedin">
                                    Hubungkan
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        {{-- Friends List Section --}}
        <div class="section-with-bg">
            <div class="relative bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h2 class="text-xl font-bold text-gray-900">
                        Koneksi Anda ({{ $friends->count() }})
                    </h2>
                    <div class="flex-1 max-w-xs ml-6">
                        <input type="text"
                               id="friend-search"
                               placeholder="Cari koneksi..."
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>

                @if($friends->count() > 0)
                <div id="friends-list">
                    @foreach($friends as $friend)
                    <div class="connection-item friend-item px-6 py-4"
                         data-name="{{ strtolower($friend->user->name) }}"
                         data-university="{{ strtolower($friend->university->name ?? '') }}">
                        <div class="flex items-center gap-4">
                            <a href="{{ route('profile.public', $friend->user->username) }}">
                                <img src="{{ $friend->profile_photo_url }}"
                                     alt="{{ $friend->user->name }}"
                                     class="avatar-circle avatar-md">
                            </a>

                            <div class="flex-1 min-w-0">
                                <a href="{{ route('profile.public', $friend->user->username) }}"
                                   class="text-lg font-semibold text-gray-900 hover:text-blue-600 hover:underline">
                                    {{ $friend->user->name }}
                                </a>
                                <p class="text-sm text-gray-600 mt-1">
                                    {{ $friend->major }} • {{ $friend->university->name }}
                                </p>
                            </div>

                            <a href="{{ route('profile.public', $friend->user->username) }}"
                               class="btn-secondary-linkedin">
                                Lihat Profil
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="px-6 py-12 text-center">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-4">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">
                        Belum Ada Koneksi
                    </h3>
                    <p class="text-gray-600 mb-6 max-w-md mx-auto">
                        Mulai bangun jaringan profesional Anda dengan terhubung ke mahasiswa KKN lainnya
                    </p>
                    <a href="{{ route('student.friends.search') }}"
                       class="inline-block btn-primary-linkedin">
                        Cari Teman
                    </a>
                </div>
                @endif
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
// Real-time search
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

// Auto-hide alerts
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
