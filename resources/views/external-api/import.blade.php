<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Import from External API') }}
            </h2>
            <a href="{{ route('settings.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                Manage API Connection
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-800 rounded-md p-4 mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-800 rounded-md p-4 mb-6">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Import Section -->
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Fetch Client List from Your API</h3>
                    <p class="text-sm text-gray-600 mb-6">
                        Click the button below to fetch your client list from the connected external API.
                        The data will be previewed before creating a file.
                    </p>

                    <!-- API Information -->
                    <div class="bg-blue-50 border border-blue-200 rounded-md p-4 mb-6">
                        <h4 class="font-semibold text-blue-900 mb-2">Connected API:</h4>
                        <div class="space-y-2 text-sm">
                            <div>
                                <span class="text-blue-700">Endpoint:</span>
                                <span class="font-medium text-blue-900 break-all">{{ auth()->user()->external_api_url }}</span>
                            </div>
                            <div>
                                <span class="text-blue-700">Last Connected:</span>
                                <span class="font-medium text-blue-900">{{ auth()->user()->external_api_connected_at?->format('M d, Y H:i') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Fetch Button -->
                    <form action="{{ route('external-api.preview') }}" method="POST">
                        @csrf
                        <button type="submit" class="px-6 py-3 bg-green-600 text-white rounded-md hover:bg-green-700 font-medium inline-flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Fetch Clients from API
                        </button>
                    </form>

                    <!-- Instructions -->
                    <div class="mt-8 pt-8 border-t">
                        <h4 class="font-semibold mb-4">What Happens Next:</h4>
                        <ol class="list-decimal list-inside space-y-2 text-sm text-gray-700">
                            <li>We'll fetch the client list from your API endpoint</li>
                            <li>Phone numbers will be validated and normalized to UAE format (+971)</li>
                            <li>Invalid contacts (missing phone numbers) will be identified</li>
                            <li>You'll preview the data before confirming the import</li>
                            <li>A new file will be created with the imported clients</li>
                            <li>You can then use this file for contact imports or campaigns</li>
                        </ol>

                        <div class="mt-4 p-4 bg-gray-50 rounded-md">
                            <p class="text-sm text-gray-800">
                                <strong>Note:</strong> This process may take a few moments depending on the size of your client list.
                                The import will automatically skip any contacts without valid phone numbers.
                            </p>
                        </div>
                    </div>

                    <!-- Troubleshooting -->
                    <div class="mt-6 pt-6 border-t">
                        <h4 class="font-semibold mb-2">Troubleshooting:</h4>
                        <div class="space-y-2 text-sm text-gray-700">
                            <p><strong>Connection failed?</strong> Test your API connection in <a href="{{ route('settings.index') }}" class="text-blue-600 hover:underline">Settings</a></p>
                            <p><strong>No clients found?</strong> Ensure your API returns data in the correct JSON format</p>
                            <p><strong>Unexpected structure?</strong> Check that your API response matches the required format in Settings</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
