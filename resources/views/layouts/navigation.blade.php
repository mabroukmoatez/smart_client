<nav class="bg-white/80 backdrop-blur-xl border-b border-purple-100 shadow-lg sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="text-xl font-bold bg-gradient-to-r from-purple-600 via-pink-600 to-blue-600 bg-clip-text text-transparent hover:scale-105 transition-transform duration-300">
                        Code Automation(Demo 1 day)
                    </a>
                </div>
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('dashboard') ? 'border-purple-600 text-purple-700' : 'border-transparent text-gray-500 hover:text-purple-600 hover:border-purple-300' }} text-sm font-semibold transition-all duration-300">
                        Dashboard
                    </a>
                    <a href="{{ route('files.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('files.*') ? 'border-purple-600 text-purple-700' : 'border-transparent text-gray-500 hover:text-purple-600 hover:border-purple-300' }} text-sm font-semibold transition-all duration-300">
                        Files
                    </a>
                    @if(auth()->user()->highlevel_connected)
                        <a href="{{ route('contact-import.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('contact-import.*') ? 'border-purple-600 text-purple-700' : 'border-transparent text-gray-500 hover:text-purple-600 hover:border-purple-300' }} text-sm font-semibold transition-all duration-300">
                            Contact Import
                        </a>
                    @endif
                    @if(auth()->user()->external_api_connected)
                        <a href="{{ route('external-api.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('external-api.*') ? 'border-purple-600 text-purple-700' : 'border-transparent text-gray-500 hover:text-purple-600 hover:border-purple-300' }} text-sm font-semibold transition-all duration-300">
                            API Import
                        </a>
                    @endif
                    <a href="{{ route('settings.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('settings.*') ? 'border-purple-600 text-purple-700' : 'border-transparent text-gray-500 hover:text-purple-600 hover:border-purple-300' }} text-sm font-semibold transition-all duration-300">
                        Settings
                    </a>
                </div>
            </div>
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center px-4 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-purple-50 transition-all duration-300">
                        <div class="font-semibold">{{ Auth::user()->name }}</div>
                        <svg class="ml-2 h-4 w-4 transition-transform duration-300" :class="{ 'rotate-180': open }" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div x-show="open"
                         @click.away="open = false"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-48 rounded-xl shadow-xl bg-white/90 backdrop-blur-lg ring-1 ring-purple-100 overflow-hidden"
                         style="display: none;">
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-purple-50 hover:to-pink-50 transition-all duration-300">
                            Profile
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-3 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-purple-50 hover:to-pink-50 transition-all duration-300 border-t border-purple-50">
                                Log Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
