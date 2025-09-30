<footer class="bg-gray-900 text-gray-300">
    <div class="container-custom py-12">
        <div class="grid md:grid-cols-4 gap-8">
            <!-- about -->
            <div>
                <h3 class="text-white text-lg font-semibold mb-4">KKN-GO</h3>
                <p class="text-sm">
                    Platform digital yang menghubungkan mahasiswa dengan instansi untuk program Kuliah Kerja Nyata berkelanjutan.
                </p>
            </div>

            <!-- links -->
            <div>
                <h3 class="text-white text-lg font-semibold mb-4">Tautan</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('home') }}" class="hover:text-white transition-colors">Beranda</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Tentang Kami</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">FAQ</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Kontak</a></li>
                </ul>
            </div>

            <!-- support -->
            <div>
                <h3 class="text-white text-lg font-semibold mb-4">Dukungan</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="#" class="hover:text-white transition-colors">Pusat Bantuan</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Kebijakan Privasi</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Syarat & Ketentuan</a></li>
                </ul>
            </div>

            <!-- contact -->
            <div>
                <h3 class="text-white text-lg font-semibold mb-4">Hubungi Kami</h3>
                <ul class="space-y-2 text-sm">
                    <li>Email: {{ config('kkn-go.contact_email') }}</li>
                    <li>Support: {{ config('kkn-go.support_email') }}</li>
                </ul>
            </div>
        </div>

        <div class="border-t border-gray-800 mt-8 pt-8 text-center text-sm">
            <p>&copy; {{ date('Y') }} {{ config('kkn-go.company_name') }}. All rights reserved.</p>
        </div>
    </div>
</footer>