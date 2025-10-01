<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dev Login - KKN GO</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center p-4">
    
    <div class="max-w-4xl w-full">
        <!-- warning banner -->
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 rounded-lg shadow-sm">
            <div class="flex items-start">
                <svg class="w-6 h-6 text-yellow-400 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <h3 class="text-sm font-medium text-yellow-800">Mode Development</h3>
                    <p class="text-sm text-yellow-700 mt-1">Ini adalah bypass login untuk testing. <strong>HAPUS routes ini di production!</strong></p>
                </div>
            </div>
        </div>

        <!-- main card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- header -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-8 py-6">
                <h1 class="text-3xl font-bold text-white text-center">🚀 Dev Login</h1>
                <p class="text-blue-100 text-center mt-2">pilih role untuk bypass authentication</p>
            </div>

            <!-- success message -->
            @if(session('success'))
            <div class="mx-8 mt-6 bg-green-50 border-l-4 border-green-400 p-4 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
            @endif

            <!-- role cards -->
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    
                    <!-- mahasiswa card -->
                    <a href="{{ route('dev.login.student') }}" 
                       class="group relative bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 border-2 border-blue-200 hover:border-blue-400 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                        <div class="text-center">
                            <div class="w-20 h-20 bg-blue-500 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 mb-2">Mahasiswa</h3>
                            <p class="text-sm text-gray-600 mb-4">Login sebagai Budi Santoso<br>UGM - Teknik Informatika</p>
                            <div class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-lg text-sm font-medium group-hover:bg-blue-600 transition-colors">
                                Login
                                <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                </svg>
                            </div>
                        </div>
                    </a>

                    <!-- instansi card -->
                    <a href="{{ route('dev.login.institution') }}" 
                       class="group relative bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-6 border-2 border-green-200 hover:border-green-400 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                        <div class="text-center">
                            <div class="w-20 h-20 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 mb-2">Instansi</h3>
                            <p class="text-sm text-gray-600 mb-4">Login sebagai Pemerintah Desa Makmur<br>Kepala Desa - Verified ✓</p>
                            <div class="inline-flex items-center px-4 py-2 bg-green-500 text-white rounded-lg text-sm font-medium group-hover:bg-green-600 transition-colors">
                                Login
                                <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                </svg>
                            </div>
                        </div>
                    </a>

                    <!-- admin card -->
                    <a href="{{ route('dev.login.admin') }}" 
                       class="group relative bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-6 border-2 border-purple-200 hover:border-purple-400 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                        <div class="text-center">
                            <div class="w-20 h-20 bg-purple-500 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 mb-2">Admin</h3>
                            <p class="text-sm text-gray-600 mb-4">Login sebagai Admin KKN-GO<br>Full Access</p>
                            <div class="inline-flex items-center px-4 py-2 bg-purple-500 text-white rounded-lg text-sm font-medium group-hover:bg-purple-600 transition-colors">
                                Login
                                <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                </svg>
                            </div>
                        </div>
                    </a>

                </div>

                <!-- info credentials -->
                <div class="mt-8 bg-gray-50 rounded-xl p-6 border border-gray-200">
                    <h4 class="text-sm font-bold text-gray-700 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        informasi akun testing
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
                        <div>
                            <p class="font-semibold text-blue-600">Mahasiswa:</p>
                            <p class="text-gray-600 mt-1">username: budisantoso<br>email: budi.santoso@mail.ugm.ac.id</p>
                        </div>
                        <div>
                            <p class="font-semibold text-green-600">Instansi:</p>
                            <p class="text-gray-600 mt-1">username: desamakmur<br>email: admin@desamakmur.go.id</p>
                        </div>
                        <div>
                            <p class="font-semibold text-purple-600">Admin:</p>
                            <p class="text-gray-600 mt-1">username: admin<br>email: admin@kkngo.id</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- footer -->
            <div class="bg-gray-50 px-8 py-4 border-t border-gray-200">
                <p class="text-center text-sm text-gray-600">
                    <strong>Catatan:</strong> Bypass ini hanya menyimpan data di session, tidak terhubung dengan database.
                </p>
            </div>
        </div>
    </div>

</body>
</html>