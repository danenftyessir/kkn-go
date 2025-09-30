@extends('layouts.auth')

@section('title', 'Verifikasi Email')

@section('content')
<div class="bg-white rounded-2xl shadow-xl p-8 page-transition">
    <!-- icon -->
    <div class="flex justify-center mb-6">
        <div class="w-20 h-20 bg-yellow-100 rounded-full flex items-center justify-center">
            <svg class="w-10 h-10 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
            </svg>
        </div>
    </div>

    <!-- header -->
    <div class="text-center mb-8">
        <h2 class="text-3xl font-bold text-gray-900">verifikasi email anda</h2>
        <p class="text-gray-600 mt-2">
            kami telah mengirim link verifikasi ke email anda
        </p>
    </div>

    <!-- content -->
    <div class="space-y-6">
        <!-- email info -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                </svg>
                <div class="flex-1">
                    <p class="text-sm text-blue-900 font-medium">email terkirim ke:</p>
                    <p class="text-sm text-blue-800 mt-1">{{ auth()->user()->email }}</p>
                </div>
            </div>
        </div>

        <!-- instruksi -->
        <div class="space-y-3">
            <p class="text-sm text-gray-700">
                <strong>langkah selanjutnya:</strong>
            </p>
            <ol class="text-sm text-gray-600 space-y-2 ml-4">
                <li class="flex items-start">
                    <span class="font-semibold mr-2">1.</span>
                    <span>buka email anda dan cari email dari KKN-GO</span>
                </li>
                <li class="flex items-start">
                    <span class="font-semibold mr-2">2.</span>
                    <span>klik link verifikasi yang ada di email</span>
                </li>
                <li class="flex items-start">
                    <span class="font-semibold mr-2">3.</span>
                    <span>anda akan diarahkan kembali ke website dan akun anda akan aktif</span>
                </li>
            </ol>
        </div>

        <!-- resend form -->
        <div class="border-t pt-6">
            <form method="POST" action="{{ route('verification.resend') }}">
                @csrf
                
                <p class="text-sm text-gray-600 mb-4">
                    tidak menerima email?
                    <button type="submit" class="text-primary-600 hover:text-primary-700 font-medium transition-colors">
                        kirim ulang
                    </button>
                </p>

                @error('email')
                    <div class="text-red-600 text-sm mb-3">{{ $message }}</div>
                @enderror
            </form>
        </div>

        <!-- tips -->
        <div class="bg-gray-50 rounded-lg p-4 text-sm text-gray-600">
            <p class="font-medium text-gray-900 mb-2">tips:</p>
            <ul class="space-y-1 ml-4 list-disc">
                <li>periksa folder spam atau junk email</li>
                <li>pastikan email yang anda daftarkan benar</li>
                <li>tunggu beberapa menit untuk email masuk</li>
            </ul>
        </div>

        <!-- logout -->
        <div class="text-center pt-4">
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="text-sm text-gray-500 hover:text-gray-700 transition-colors">
                    keluar
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// auto refresh setiap 30 detik untuk cek verifikasi
let checkInterval = setInterval(async () => {
    try {
        const response = await fetch('/api/check-verification', {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        const data = await response.json();
        
        if (data.verified) {
            clearInterval(checkInterval);
            window.location.href = '/';
        }
    } catch (error) {
        console.error('error checking verification:', error);
    }
}, 30000);
</script>
@endpush