<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Code Automation(Demo 1 day)</title>

    <!-- Modern Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Custom Styles -->
    @vite(['resources/css/app.css'])

    <style>
        body { font-family: 'Inter', sans-serif; }
        .blob {
            border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
            animation: blob 7s ease-in-out infinite;
        }
        @keyframes blob {
            0%, 100% { border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%; }
            25% { border-radius: 58% 42% 75% 25% / 76% 46% 54% 24%; }
            50% { border-radius: 50% 50% 33% 67% / 55% 27% 73% 45%; }
            75% { border-radius: 33% 67% 58% 42% / 63% 68% 32% 37%; }
        }
    </style>
</head>
<body class="antialiased bg-gradient-to-br from-purple-500 via-pink-500 to-blue-500 min-h-screen">
    <!-- Animated background blobs -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="blob absolute top-20 -left-20 w-96 h-96 bg-purple-300 opacity-30 blur-3xl"></div>
        <div class="blob absolute top-40 -right-20 w-96 h-96 bg-pink-300 opacity-30 blur-3xl" style="animation-delay: 2s"></div>
        <div class="blob absolute -bottom-20 left-1/2 w-96 h-96 bg-blue-300 opacity-30 blur-3xl" style="animation-delay: 4s"></div>
    </div>

    <div class="min-h-screen flex flex-col relative z-10">
        <!-- Header -->
        <header class="bg-white/10 backdrop-blur-xl border-b border-white/20 shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex justify-between items-center">
                    <h1 class="text-2xl font-bold text-white drop-shadow-lg">Code Automation(Demo 1 day)</h1>
                    <div class="flex gap-4">
                        @auth
                            <a href="{{ route('dashboard') }}" class="text-white hover:text-gray-200 font-semibold transition-all duration-300 hover:scale-105">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-white hover:text-gray-200 font-semibold transition-all duration-300 hover:scale-105">Login</a>
                            <a href="{{ route('register') }}" class="px-6 py-2 bg-white text-purple-600 rounded-full font-bold hover:bg-gray-100 hover:shadow-2xl transition-all duration-300 hover:scale-105">
                                Register
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-grow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24">
                <!-- Hero Section -->
                <div class="text-center mb-16 animate-fadeInUp">
                    <h2 class="text-5xl sm:text-6xl lg:text-7xl font-extrabold text-white mb-6 drop-shadow-2xl leading-tight">
                        Automate Your Messages<br>
                        <span class="bg-gradient-to-r from-yellow-200 via-pink-200 to-blue-200 bg-clip-text text-transparent">
                            with HighLevel
                        </span>
                    </h2>
                    <p class="text-xl sm:text-2xl text-white/90 mb-8 max-w-3xl mx-auto font-light drop-shadow-lg">
                        Upload spreadsheets, normalize phone numbers, and send automated WhatsApp campaigns with ease
                    </p>
                    @guest
                        <a href="{{ route('register') }}" class="inline-flex items-center px-8 py-4 bg-white text-purple-600 text-lg font-bold rounded-full hover:bg-yellow-300 hover:text-purple-700 shadow-2xl hover:shadow-3xl transition-all duration-300 transform hover:scale-110 hover:-translate-y-1">
                            Get Started Free
                            <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                        </a>
                    @endguest
                </div>

                <!-- Features Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
                    <div class="bg-white/10 backdrop-blur-xl p-8 rounded-3xl border border-white/20 shadow-2xl hover:shadow-3xl transition-all duration-500 transform hover:-translate-y-2 hover:bg-white/20 animate-fadeInUp">
                        <div class="bg-gradient-to-br from-purple-400 to-purple-600 w-16 h-16 rounded-2xl flex items-center justify-center mb-6 shadow-xl">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold mb-3 text-white">Upload Files</h3>
                        <p class="text-white/80 text-lg">Upload Excel or CSV files with your contact lists effortlessly.</p>
                    </div>

                    <div class="bg-white/10 backdrop-blur-xl p-8 rounded-3xl border border-white/20 shadow-2xl hover:shadow-3xl transition-all duration-500 transform hover:-translate-y-2 hover:bg-white/20 animate-fadeInUp" style="animation-delay: 0.1s">
                        <div class="bg-gradient-to-br from-pink-400 to-pink-600 w-16 h-16 rounded-2xl flex items-center justify-center mb-6 shadow-xl">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold mb-3 text-white">Normalize Numbers</h3>
                        <p class="text-white/80 text-lg">Automatically convert phone numbers to UAE format (+971).</p>
                    </div>

                    <div class="bg-white/10 backdrop-blur-xl p-8 rounded-3xl border border-white/20 shadow-2xl hover:shadow-3xl transition-all duration-500 transform hover:-translate-y-2 hover:bg-white/20 animate-fadeInUp" style="animation-delay: 0.2s">
                        <div class="bg-gradient-to-br from-blue-400 to-blue-600 w-16 h-16 rounded-2xl flex items-center justify-center mb-6 shadow-xl">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold mb-3 text-white">Send Campaigns</h3>
                        <p class="text-white/80 text-lg">Schedule WhatsApp campaigns using HighLevel templates.</p>
                    </div>
                </div>

                <!-- Additional Features -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-5xl mx-auto">
                    <div class="bg-gradient-to-br from-white/15 to-white/5 backdrop-blur-xl p-6 rounded-2xl border border-white/20 shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:scale-105">
                        <h4 class="text-lg font-bold text-white mb-2">🚀 Lightning Fast</h4>
                        <p class="text-white/70">Process thousands of contacts in seconds</p>
                    </div>
                    <div class="bg-gradient-to-br from-white/15 to-white/5 backdrop-blur-xl p-6 rounded-2xl border border-white/20 shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:scale-105">
                        <h4 class="text-lg font-bold text-white mb-2">🔒 Secure & Private</h4>
                        <p class="text-white/70">Your data is encrypted and protected</p>
                    </div>
                    <div class="bg-gradient-to-br from-white/15 to-white/5 backdrop-blur-xl p-6 rounded-2xl border border-white/20 shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:scale-105">
                        <h4 class="text-lg font-bold text-white mb-2">📊 Advanced Analytics</h4>
                        <p class="text-white/70">Track campaign performance in real-time</p>
                    </div>
                    <div class="bg-gradient-to-br from-white/15 to-white/5 backdrop-blur-xl p-6 rounded-2xl border border-white/20 shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:scale-105">
                        <h4 class="text-lg font-bold text-white mb-2">🎯 Smart Targeting</h4>
                        <p class="text-white/70">Reach the right audience every time</p>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white/10 backdrop-blur-xl border-t border-white/20 mt-auto">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <p class="text-center text-white/80 font-medium">&copy; {{ date('Y') }} Code Automation(Demo 1 day) - All rights reserved</p>
            </div>
        </footer>
    </div>
</body>
</html>
