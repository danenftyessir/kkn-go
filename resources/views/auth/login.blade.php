@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<div class="bg-white rounded-2xl shadow-xl p-8">
    <!-- logo -->
    <div class="text-center mb-8">
        <h2 class="text-3xl font-bold text-gray-900">Selamat Datang Kembali!</h2>
        <p class="text-gray-600 mt-2">Masuk ke akun KKN-GO anda</p>
    </div>

    <!-- form login -->
    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- email or username -->
        <div>
            <label for="email" class="form-label">Email atau Username</label>
            <input 
                id="email" 
                type="text" 
                name="email" 
                value="{{ old('email') }}"
                class="input @error('email') input-error @enderror" 
                placeholder="Masukkan email atau username"
                required 
                autofocus
            >
            @error('email')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <!-- password -->
        <div>
            <label for="password" class="form-label">Password</label>
            <input 
                id="password" 
                type="password" 
                name="password" 
                class="input @error('password') input-error @enderror"
                placeholder="Masukkan password"
                required
            >
            @error('password')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <!-- remember me & forgot password -->
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <input 
                    id="remember" 
                    type="checkbox" 
                    name="remember" 
                    class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
                >
                <label for="remember" class="ml-2 block text-sm text-gray-700">
                    Ingat saya
                </label>
            </div>

            <div class="text-sm">
                <a href="{{ route('password.request') }}" class="text-primary-600 hover:text-primary-800">
                    Lupa password?
                </a>
            </div>
        </div>

        <!-- submit button -->
        <button type="submit" class="btn-primary w-full">
            Masuk
        </button>
    </form>

    <!-- register link -->
    <p class="mt-6 text-center text-sm text-gray-600">
        Belum punya akun? 
        <a href="{{ route('register') }}" class="text-primary-600 hover:text-primary-800 font-medium">
            Daftar sekarang
        </a>
    </p>
</div>
@endsection