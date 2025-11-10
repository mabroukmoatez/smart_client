<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Code Automation(Demo 1 day)') }}</title>

    <!-- Modern Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Custom Styles -->
    @vite(['resources/css/app.css'])

    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="font-sans antialiased bg-gradient-to-br from-purple-600 to-indigo-600 min-h-screen">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 px-4">
        <div class="animate-fadeInUp">
            <a href="/">
                <h1 class="text-3xl font-bold text-white drop-shadow-lg hover:scale-105 transition-transform duration-300">
                    Code Automation<br><span class="text-xl">(Demo 1 day)</span>
                </h1>
            </a>
        </div>
        <div class="w-full sm:max-w-md mt-6 px-8 py-8 bg-white shadow-2xl overflow-hidden rounded-2xl animate-fadeInUp">
            {{ $slot }}
        </div>
    </div>
</body>
</html>
