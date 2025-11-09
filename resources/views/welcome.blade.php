<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>WhatsApp Automation Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="antialiased bg-gray-50">
    <div class="min-h-screen flex flex-col">
        <header class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex justify-between items-center">
                    <h1 class="text-2xl font-bold text-gray-900">WhatsApp Automation</h1>
                    <div class="flex gap-4">
                        @auth
                            <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-900">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900">Login</a>
                            <a href="{{ route('register') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Register</a>
                        @endauth
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-grow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-gray-900 mb-4">Automate WhatsApp Messages with HighLevel</h2>
                    <p class="text-xl text-gray-600 mb-8">Upload spreadsheets, normalize phone numbers, and send automated WhatsApp campaigns</p>
                    @guest
                        <a href="{{ route('register') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white text-lg rounded-md hover:bg-blue-700">Get Started Free</a>
                    @endguest
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <h3 class="text-lg font-semibold mb-2">Upload Files</h3>
                        <p class="text-gray-600">Upload Excel or CSV files with your contact lists.</p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <h3 class="text-lg font-semibold mb-2">Normalize Numbers</h3>
                        <p class="text-gray-600">Automatically convert phone numbers to UAE format (+971).</p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <h3 class="text-lg font-semibold mb-2">Send Campaigns</h3>
                        <p class="text-gray-600">Schedule WhatsApp campaigns using HighLevel templates.</p>
                    </div>
                </div>
            </div>
        </main>

        <footer class="bg-white border-t">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <p class="text-center text-gray-600">&copy; {{ date('Y') }} WhatsApp Automation Platform</p>
            </div>
        </footer>
    </div>
</body>
</html>
