<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register - KKN-GO</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-green-50">
    <div class="absolute top-6 left-6 right-6 flex items-center justify-between z-10">
        <a href="{{ route('home') }}" class="inline-flex items-center text-gray-700 hover:text-gray-900 transition-colors group">
            <svg class="w-5 h-5 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <span class="font-medium">Back to Home</span>
        </a>
        <div class="flex items-center space-x-6">
            <a href="{{ route('contact') }}" class="text-gray-600 hover:text-gray-900 font-medium transition-colors">Contact</a>
            <a href="{{ route('about') }}" class="text-gray-600 hover:text-gray-900 font-medium transition-colors">About</a>
        </div>
    </div>

    <div class="flex items-center justify-center min-h-screen p-8">
        <div class="w-full max-w-2xl">
            <!-- logo -->
            <div class="flex justify-center mb-8 mt-12">
                <img src="{{ asset('kkn-go-logo.png') }}" 
                     alt="KKN-GO Logo" 
                     class="h-24 w-auto transform hover:scale-105 transition-transform">
            </div>

            <!-- main card -->
            <div class="bg-white rounded-2xl shadow-xl p-10 border border-gray-100">
                <!-- header -->
                <div class="text-center mb-10">
                    <h1 class="text-4xl font-bold text-gray-900 mb-3">Join KKN-GO</h1>
                    <p class="text-gray-600 text-lg">Choose your account type to get started</p>
                </div>

                <!-- pilihan registrasi -->
                <div class="grid md:grid-cols-2 gap-6">
                    <!-- mahasiswa card -->
                    <a href="{{ route('register.student') }}" 
                       class="group relative bg-gradient-to-br from-blue-50 to-blue-100 hover:from-blue-100 hover:to-blue-200 rounded-xl p-8 transition-all duration-300 hover:shadow-2xl hover:-translate-y-1 border-2 border-blue-200 hover:border-blue-400">
                        <!-- icon -->
                        <div class="bg-blue-600 text-white w-16 h-16 rounded-lg flex items-center justify-center mb-6 group-hover:scale-110 transition-transform shadow-lg">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        
                        <!-- content -->
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">I'm a Student</h3>
                        <p class="text-gray-700 mb-6 leading-relaxed">
                            Join as a student to find meaningful community service opportunities and make a real impact
                        </p>
                        
                        <!-- benefits -->
                        <ul class="space-y-2 mb-6">
                            <li class="flex items-center text-sm text-gray-700">
                                <svg class="w-5 h-5 text-blue-600 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Browse and apply for projects
                            </li>
                            <li class="flex items-center text-sm text-gray-700">
                                <svg class="w-5 h-5 text-blue-600 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Build your portfolio
                            </li>
                            <li class="flex items-center text-sm text-gray-700">
                                <svg class="w-5 h-5 text-blue-600 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Track your impact
                            </li>
                        </ul>
                        
                        <!-- button -->
                        <div class="flex items-center text-blue-600 font-semibold group-hover:translate-x-2 transition-transform">
                            <span>Register as Student</span>
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </div>
                    </a>

                    <!-- instansi card -->
                    <a href="{{ route('register.institution') }}" 
                       class="group relative bg-gradient-to-br from-green-50 to-emerald-100 hover:from-green-100 hover:to-emerald-200 rounded-xl p-8 transition-all duration-300 hover:shadow-2xl hover:-translate-y-1 border-2 border-green-200 hover:border-green-400">
                        <!-- icon -->
                        <div class="bg-green-600 text-white w-16 h-16 rounded-lg flex items-center justify-center mb-6 group-hover:scale-110 transition-transform shadow-lg">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        
                        <!-- content -->
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">I'm an Institution</h3>
                        <p class="text-gray-700 mb-6 leading-relaxed">
                            Join as an institution to post problems and find passionate students to help your community
                        </p>
                        
                        <!-- benefits -->
                        <ul class="space-y-2 mb-6">
                            <li class="flex items-center text-sm text-gray-700">
                                <svg class="w-5 h-5 text-green-600 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Post community projects
                            </li>
                            <li class="flex items-center text-sm text-gray-700">
                                <svg class="w-5 h-5 text-green-600 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Find talented students
                            </li>
                            <li class="flex items-center text-sm text-gray-700">
                                <svg class="w-5 h-5 text-green-600 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Manage applications
                            </li>
                        </ul>
                        
                        <!-- button -->
                        <div class="flex items-center text-green-600 font-semibold group-hover:translate-x-2 transition-transform">
                            <span>Register as Institution</span>
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </div>
                    </a>
                </div>

                <!-- divider -->
                <div class="relative my-10">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-4 bg-white text-gray-500">Already have an account?</span>
                    </div>
                </div>

                <!-- login link -->
                <div class="text-center">
                    <a href="{{ route('login') }}" 
                       class="inline-flex items-center justify-center w-full md:w-auto px-8 py-3 border-2 border-gray-300 rounded-lg text-gray-700 font-semibold hover:border-gray-400 hover:bg-gray-50 transition-all group">
                        <span>Sign in to your account</span>
                        <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- trust indicators -->
            <div class="mt-8 text-center">
                <div class="inline-flex items-center space-x-2 text-sm text-gray-600">
                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span>Secure & encrypted registration process</span>
                </div>
            </div>
        </div>
    </div>
</body>
</html>