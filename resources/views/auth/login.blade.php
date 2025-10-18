<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - KKN-GO</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        .hero-bg {
            background-image: linear-gradient(135deg, rgba(59, 89, 152, 0.65) 0%, rgba(44, 95, 127, 0.62) 50%, rgba(30, 139, 115, 0.58) 100%),
                              url('{{ asset('login-register-view.jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
    </style>
</head>
<body class="min-h-screen bg-gray-50">
    <!-- back to home -->
    <div class="absolute top-6 left-6 z-10">
        <a href="{{ route('home') }}" class="inline-flex items-center text-white hover:text-gray-200 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <span class="font-medium">Back to Home</span>
        </a>
    </div>

    <div class="absolute top-6 right-6 z-10 flex items-center space-x-6">
        <a href="{{ route('contact') }}" class="text-white hover:text-gray-200 font-medium transition-colors">Contact</a>
        <a href="{{ route('about') }}" class="text-white hover:text-gray-200 font-medium transition-colors">About</a>
    </div>

    <div class="flex min-h-screen">
        <!-- left side - promotional content with hero image -->
        <div class="hidden lg:flex lg:w-1/2 hero-bg text-white p-12 flex-col justify-center relative">
            <!-- overlay ringan untuk text contrast -->
            <div class="absolute inset-0 bg-gradient-to-br from-blue-900/10 via-transparent to-green-900/5"></div>
            
            <div class="max-w-xl mx-auto relative z-10">
                <!-- tagline -->
                <h1 class="text-5xl font-bold mb-4 leading-tight" style="text-shadow: 0 3px 12px rgba(0,0,0,0.5), 0 1px 4px rgba(0,0,0,0.3);">
                    Connect. Collaborate.<br>
                    <span class="text-lime-300">Create Impact.</span>
                </h1>
                
                <p class="text-xl text-white mb-16 leading-relaxed" style="text-shadow: 0 2px 10px rgba(0,0,0,0.5), 0 1px 4px rgba(0,0,0,0.3);">
                    Join the largest platform connecting students and institutions for meaningful community service programs across Indonesia's villages and communities.
                </p>

                <!-- key features -->
                <div class="space-y-6">
                    <div class="flex items-start space-x-4 group">
                        <div class="flex-shrink-0 w-12 h-12 bg-white/10 backdrop-blur-sm rounded-lg flex items-center justify-center group-hover:bg-white/20 transition-colors">
                            <svg class="w-6 h-6 text-lime-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold mb-1" style="text-shadow: 0 2px 8px rgba(0,0,0,0.5);">Build Meaningful Connections</h3>
                            <p class="text-white/90 text-sm" style="text-shadow: 0 2px 6px rgba(0,0,0,0.4);">Connect with organizations and students who share your passion for community development</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-4 group">
                        <div class="flex-shrink-0 w-12 h-12 bg-white/10 backdrop-blur-sm rounded-lg flex items-center justify-center group-hover:bg-white/20 transition-colors">
                            <svg class="w-6 h-6 text-lime-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold mb-1" style="text-shadow: 0 2px 8px rgba(0,0,0,0.5);">Track Your Impact</h3>
                            <p class="text-white/90 text-sm" style="text-shadow: 0 2px 6px rgba(0,0,0,0.4);">Monitor project progress and showcase your contributions with comprehensive reporting tools</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-4 group">
                        <div class="flex-shrink-0 w-12 h-12 bg-white/10 backdrop-blur-sm rounded-lg flex items-center justify-center group-hover:bg-white/20 transition-colors">
                            <svg class="w-6 h-6 text-lime-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold mb-1" style="text-shadow: 0 2px 8px rgba(0,0,0,0.5);">Access Knowledge Repository</h3>
                            <p class="text-white/90 text-sm" style="text-shadow: 0 2px 6px rgba(0,0,0,0.4);">Learn from past projects and best practices shared by the community</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- right side - login form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 lg:p-12">
            <div class="w-full max-w-md">
                <!-- logo untuk mobile -->
                <div class="lg:hidden flex justify-center mb-8">
                    <img src="{{ asset('kkn-go-logo.png') }}" alt="KKN-GO Logo" class="h-20">
                </div>

                <!-- header -->
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">Welcome Back!</h2>
                    <p class="text-gray-600">Please sign in to your account</p>
                </div>

                <!-- error messages -->
                @if($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-red-600 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                            <div class="flex-1">
                                <p class="font-semibold text-red-800 mb-1">Login Failed</p>
                                <ul class="text-sm text-red-700 list-disc list-inside space-y-1">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- form -->
                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- email atau username -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email atau Username <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}"
                               placeholder="Email atau username"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('email') border-red-500 @enderror"
                               required
                               autofocus>
                        @error('email')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Password <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" 
                                   id="password" 
                                   name="password"
                                   placeholder="Masukkan password Anda"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all pr-12 @error('password') border-red-500 @enderror"
                                   required>
                            <button type="button" 
                                    onclick="togglePassword()"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                <svg id="eyeIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- remember me & forgot password -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="remember" 
                                   {{ old('remember') ? 'checked' : '' }}
                                   class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-600">Remember me</span>
                        </label>
                        <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:text-blue-700">
                            Forgot password?
                        </a>
                    </div>

                    <!-- submit button -->
                    <button type="submit" 
                            class="w-full bg-blue-600 text-white py-3.5 rounded-lg font-semibold hover:bg-blue-700 transition-all flex items-center justify-center space-x-2 shadow-sm hover:shadow-md">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        <span>Sign In</span>
                    </button>
                </form>

                <!-- register link -->
                <div class="mt-8 text-center">
                    <p class="text-gray-600">
                        Don't have an account? 
                        <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-700 font-semibold">
                            Sign up here
                        </a>
                    </p>
                </div>

                <!-- security notice -->
                <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-4 flex items-start space-x-3">
                    <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    <p class="text-sm text-blue-900">
                        Your login is secured with industry-standard encryption. We never store your password in plain text.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // toggle password visibility
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                `;
            } else {
                passwordInput.type = 'password';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                `;
            }
        }
    </script>
</body>
</html>