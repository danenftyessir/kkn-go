{{-- resources/views/components/footer.blade.php --}}
<footer class="bg-white border-t border-gray-200">
    <div class="container-custom py-12">
        {{-- logo dan tagline --}}
        <div class="flex flex-col items-center mb-8 space-y-4">
            <div class="flex items-center gap-3">
                <img src="{{ asset('kkn-go-logo.png') }}" alt="KKN-Go Logo" class="h-12 w-auto">
                <span class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-green-600 bg-clip-text text-transparent">KKN-Go</span>
            </div>
            <p class="text-gray-600 text-center max-w-xl text-sm">
                Platform Digital Yang Menghubungkan Mahasiswa Dengan Instansi Untuk Program Kuliah Kerja Nyata Berkelanjutan
            </p>
        </div>

        {{-- navigasi footer --}}
        <div class="flex flex-wrap justify-center gap-x-8 gap-y-4 mb-8 text-sm">
            <a href="{{ route('home') }}" class="text-gray-600 hover:text-blue-600 transition-colors duration-200">
                Beranda
            </a>
            <a href="{{ route('about') }}" class="text-gray-600 hover:text-blue-600 transition-colors duration-200">
                Tentang Kami
            </a>
            <a href="#" class="text-gray-600 hover:text-blue-600 transition-colors duration-200">
                Kebijakan Privasi
            </a>
            <a href="#" class="text-gray-600 hover:text-blue-600 transition-colors duration-200">
                Kontak
            </a>
        </div>

        {{-- copyright --}}
        <div class="border-t border-gray-200 pt-8">
            <p class="text-center text-xs text-gray-500 leading-relaxed max-w-3xl mx-auto">
                Hak cipta © {{ date('Y') }} KKN-Go. Seluruh hak cipta dilindungi undang-undang dan terdaftar pada Direktorat Jendral Kekayaan Intelektual Republik Indonesia.
            </p>
        </div>
    </div>
</footer>