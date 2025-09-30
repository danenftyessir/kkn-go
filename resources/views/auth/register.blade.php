@extends('layouts.auth')

@section('title', 'Pilih Jenis Akun')

@section('content')
<div class="bg-white rounded-2xl shadow-xl p-8">
    <!-- header -->
    <div class="text-center mb-8">
        <h2 class="text-3xl font-bold text-gray-900">Bergabung dengan KKN-GO</h2>
        <p class="text-gray-600 mt-2">Pilih jenis akun yang sesuai dengan anda</p>
    </div>

    <!-- pilihan user type -->
    <div class="space-y-4">
        <!-- mahasiswa -->
        <a href="{{ route('register.student') }}" class="card-hover block p-6 group">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center group-hover:bg-primary-200 transition-colors">
                        <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-6 flex-1">
                    <h3 class="text-xl font-semibold text-gray-900 group-hover:text-primary-600 transition-colors">
                        Mahasiswa
                    </h3>
                    <p class="text-gray-600 mt-1">
                        Daftar sebagai mahasiswa untuk mencari dan mengikuti program KKN
                    </p>
                </div>
                <div class="ml-4">
                    <svg class="w-6 h-6 text-gray-400 group-hover:text-primary-600 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </div>
        </a>

        <!-- instansi -->
        <a href="{{ route('register.institution') }}" class="card-hover block p-6 group">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-6 flex-1">
                    <h3 class="text-xl font-semibold text-gray-900 group-hover:text-blue-600 transition-colors">
                        Instansi
                    </h3>
                    <p class="text-gray-600 mt-1">
                        Daftar sebagai instansi untuk mempublikasikan program KKN
                    </p>
                </div>
                <div class="ml-4">
                    <svg class="w-6 h-6 text-gray-400 group-hover:text-blue-600 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </div>
        </a>
    </div>

    <!-- login link -->
    <p class="mt-6 text-center text-sm text-gray-600">
        Sudah punya akun? 
        <a href="{{ route('login') }}" class="text-primary-600 hover:text-primary-800 font-medium">
            Masuk di sini
        </a>
    </p>
</div>
@endsection