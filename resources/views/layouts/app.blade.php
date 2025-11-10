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
<body class="font-sans antialiased bg-gray-50 min-h-screen">
    <div class="min-h-screen">
        @include('layouts.navigation')
        @if (isset($header))
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 animate-fadeInUp">
                    {{ $header }}
                </div>
            </header>
        @endif
        <main class="animate-fadeIn">
            {{ $slot }}
        </main>
    </div>
</body>
</html>
