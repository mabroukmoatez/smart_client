<nav class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="text-xl font-bold text-gray-900">WhatsApp Automation</a>
                </div>
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('dashboard') ? 'border-blue-600 text-gray-900' : 'border-transparent text-gray-500' }} text-sm font-medium">Dashboard</a>
                    <a href="{{ route('files.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('files.*') ? 'border-blue-600 text-gray-900' : 'border-transparent text-gray-500' }} text-sm font-medium">Files</a>
                    <a href="{{ route('automation.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('automation.*') ? 'border-blue-600 text-gray-900' : 'border-transparent text-gray-500' }} text-sm font-medium">Campaigns</a>
                    <a href="{{ route('settings.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('settings.*') ? 'border-blue-600 text-gray-900' : 'border-transparent text-gray-500' }} text-sm font-medium">Settings</a>
                </div>
            </div>
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700">
                        <div>{{ Auth::user()->name }}</div>
                        <svg class="ml-2 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5" style="display: none;">
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Log Out</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
