<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-800 rounded-md p-4">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-800 rounded-md p-4">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- HighLevel API Integration -->
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">HighLevel / LeadConnector Integration</h3>

                    @if($isConnected)
                        <!-- Connected Status -->
                        <div class="bg-green-50 border border-green-200 rounded-md p-4 mb-6">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div>
                                    <div class="font-semibold text-green-900">Connected to HighLevel</div>
                                    <div class="text-sm text-green-700">
                                        Connected on {{ $connectedAt?->format('M d, Y H:i') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Connection Details -->
                        <div class="mb-6">
                            <dl class="grid grid-cols-1 gap-4">
                                <div>
                                    <dt class="text-sm text-gray-600">Location ID</dt>
                                    <dd class="mt-1 text-sm font-medium">{{ $locationId }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm text-gray-600">API Token</dt>
                                    <dd class="mt-1 text-sm font-medium text-gray-900">••••••••••••••••</dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Actions -->
                        <div class="flex gap-4">
                            <form action="{{ route('settings.test-connection') }}" method="POST">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                    Test Connection
                                </button>
                            </form>

                            <button onclick="document.getElementById('updateForm').classList.toggle('hidden')" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                                Update Credentials
                            </button>

                            <form action="{{ route('settings.disconnect') }}" method="POST" onsubmit="return confirm('Are you sure you want to disconnect?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                                    Disconnect
                                </button>
                            </form>
                        </div>

                        <!-- Update Form (Hidden by default) -->
                        <div id="updateForm" class="hidden mt-6 pt-6 border-t">
                            <form action="{{ route('settings.store-credentials') }}" method="POST">
                                @csrf
                                <div class="space-y-4">
                                    <div>
                                        <label for="api_token" class="block text-sm font-medium text-gray-700 mb-2">
                                            Private Integration Token <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" id="api_token" name="api_token" required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                            placeholder="Enter your HighLevel Private Integration token">
                                    </div>

                                    <div>
                                        <label for="location_id" class="block text-sm font-medium text-gray-700 mb-2">
                                            Location ID <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" id="location_id" name="location_id" required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                            placeholder="Enter your HighLevel Location ID">
                                    </div>

                                    <div class="flex gap-4">
                                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                                            Update & Test Connection
                                        </button>
                                        <button type="button" onclick="document.getElementById('updateForm').classList.add('hidden')" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                                            Cancel
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @else
                        <!-- Not Connected -->
                        <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4 mb-6">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-yellow-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <div>
                                    <div class="font-semibold text-yellow-900">Not Connected</div>
                                    <div class="text-sm text-yellow-700">
                                        @if($hasCredentials)
                                            Credentials are stored but not verified. Click "Test Connection" below to verify.
                                        @else
                                            Connect your HighLevel account to start importing contacts
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($hasCredentials && $locationId)
                            <!-- Show existing credentials -->
                            <div class="mb-6">
                                <dl class="grid grid-cols-1 gap-4">
                                    <div>
                                        <dt class="text-sm text-gray-600">Location ID</dt>
                                        <dd class="mt-1 text-sm font-medium">{{ $locationId }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm text-gray-600">API Token</dt>
                                        <dd class="mt-1 text-sm font-medium text-gray-900">••••••••••••••••</dd>
                                    </div>
                                </dl>
                            </div>

                            <!-- Test Connection Button -->
                            <div class="flex gap-4 mb-6">
                                <form action="{{ route('settings.test-connection') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 font-medium">
                                        Test Connection
                                    </button>
                                </form>

                                <button onclick="document.getElementById('updateFormNotConnected').classList.toggle('hidden')" class="px-6 py-3 bg-gray-600 text-white rounded-md hover:bg-gray-700 font-medium">
                                    Update Credentials
                                </button>
                            </div>

                            <!-- Update Form (Hidden by default) -->
                            <div id="updateFormNotConnected" class="hidden pt-6 border-t">
                                <form action="{{ route('settings.store-credentials') }}" method="POST" class="space-y-6">
                                    @csrf

                                    <div>
                                        <label for="api_token_update" class="block text-sm font-medium text-gray-700 mb-2">
                                            Private Integration Token <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" id="api_token_update" name="api_token" required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                            placeholder="Enter your HighLevel Private Integration token">
                                        <p class="mt-1 text-xs text-gray-500">Get this from HighLevel → Settings → Private Integrations</p>
                                    </div>

                                    <div>
                                        <label for="location_id_update" class="block text-sm font-medium text-gray-700 mb-2">
                                            Location ID <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" id="location_id_update" name="location_id" required
                                            value="{{ old('location_id', $locationId) }}"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                            placeholder="Enter your HighLevel Location ID">
                                        <p class="mt-1 text-xs text-gray-500">Find this in your HighLevel account settings</p>
                                    </div>

                                    <div class="flex gap-4">
                                        <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 font-medium">
                                            Update & Test Connection
                                        </button>
                                        <button type="button" onclick="document.getElementById('updateFormNotConnected').classList.add('hidden')" class="px-6 py-3 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 font-medium">
                                            Cancel
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @else
                            <!-- Connection Form -->
                            <form action="{{ route('settings.store-credentials') }}" method="POST" class="space-y-6">
                                @csrf

                                <div>
                                    <label for="api_token" class="block text-sm font-medium text-gray-700 mb-2">
                                        Private Integration Token <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="api_token" name="api_token" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        placeholder="Enter your HighLevel Private Integration token">
                                    <p class="mt-1 text-xs text-gray-500">Get this from HighLevel → Settings → Private Integrations</p>
                                </div>

                                <div>
                                    <label for="location_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        Location ID <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="location_id" name="location_id" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        placeholder="Enter your HighLevel Location ID">
                                    <p class="mt-1 text-xs text-gray-500">Find this in your HighLevel account settings</p>
                                </div>

                                <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 font-medium">
                                    Connect HighLevel Account
                                </button>
                            </form>
                        @endif
                    @endif

                    <!-- Setup Instructions -->
                    <div class="mt-8 pt-8 border-t">
                        <h4 class="font-semibold mb-4">How to Get Your API Credentials:</h4>
                        <ol class="list-decimal list-inside space-y-2 text-sm text-gray-700">
                            <li>Log in to your <a href="https://app.gohighlevel.com" target="_blank" class="text-blue-600 hover:text-blue-800">HighLevel account</a></li>
                            <li>Navigate to <strong>Settings</strong> → Scroll down to <strong>"Other Settings"</strong> → Click <strong>"Private Integrations"</strong></li>
                            <li>Click <strong>"Create new Integration"</strong></li>
                            <li>Give it a name (e.g., "WhatsApp Automation")</li>
                            <li>Select required scopes/permissions:
                                <ul class="list-disc list-inside ml-6 mt-1">
                                    <li>contacts.readonly</li>
                                    <li>contacts.write</li>
                                    <li>conversations.readonly</li>
                                    <li>conversations.write</li>
                                    <li>conversations/message.readonly</li>
                                    <li>conversations/message.write</li>
                                </ul>
                            </li>
                            <li><strong>Copy the token</strong> - you won't be able to see it again!</li>
                            <li>Get your <strong>Location ID</strong> from HighLevel settings</li>
                            <li>Paste both values above and click "Connect"</li>
                        </ol>

                        <div class="mt-4 p-4 bg-blue-50 rounded-md">
                            <p class="text-sm text-blue-900">
                                <strong>Need help?</strong> Check out the
                                <a href="https://help.leadconnectorhq.com/support/solutions/articles/155000002774" target="_blank" class="underline">official HighLevel documentation</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- External API Integration -->
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">External API Integration</h3>
                    <p class="text-sm text-gray-600 mb-6">Connect your custom web API to import client lists directly into the platform.</p>

                    @if($isExternalApiConnected)
                        <!-- Connected Status -->
                        <div class="bg-green-50 border border-green-200 rounded-md p-4 mb-6">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div>
                                    <div class="font-semibold text-green-900">Connected to External API</div>
                                    <div class="text-sm text-green-700">
                                        Connected on {{ $externalApiConnectedAt?->format('M d, Y H:i') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Connection Details -->
                        <div class="mb-6">
                            <dl class="grid grid-cols-1 gap-4">
                                <div>
                                    <dt class="text-sm text-gray-600">API URL</dt>
                                    <dd class="mt-1 text-sm font-medium break-all">{{ $externalApiUrl }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm text-gray-600">API Token</dt>
                                    <dd class="mt-1 text-sm font-medium text-gray-900">••••••••••••••••</dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Actions -->
                        <div class="flex gap-4 flex-wrap">
                            <a href="{{ route('external-api.index') }}" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                                Import Clients
                            </a>

                            <form action="{{ route('settings.test-external-api-connection') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                    Test Connection
                                </button>
                            </form>

                            <button onclick="document.getElementById('updateExternalForm').classList.toggle('hidden')" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                                Update Credentials
                            </button>

                            <form action="{{ route('settings.disconnect-external-api') }}" method="POST" onsubmit="return confirm('Are you sure you want to disconnect?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                                    Disconnect
                                </button>
                            </form>
                        </div>

                        <!-- Update Form (Hidden by default) -->
                        <div id="updateExternalForm" class="hidden mt-6 pt-6 border-t">
                            <form action="{{ route('settings.store-external-api-credentials') }}" method="POST">
                                @csrf
                                <div class="space-y-4">
                                    <div>
                                        <label for="external_api_url" class="block text-sm font-medium text-gray-700 mb-2">
                                            API URL <span class="text-red-500">*</span>
                                        </label>
                                        <input type="url" id="external_api_url" name="api_url" required
                                            value="{{ old('api_url') }}"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                            placeholder="https://your-api.com/clients">
                                    </div>

                                    <div>
                                        <label for="external_api_token" class="block text-sm font-medium text-gray-700 mb-2">
                                            API Token <span class="text-red-500">*</span>
                                        </label>
                                        <input type="password" id="external_api_token" name="api_token" required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                            placeholder="Enter your API token">
                                    </div>

                                    <div class="flex gap-4">
                                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                                            Update & Test Connection
                                        </button>
                                        <button type="button" onclick="document.getElementById('updateExternalForm').classList.add('hidden')" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                                            Cancel
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @else
                        <!-- Not Connected -->
                        <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4 mb-6">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-yellow-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <div>
                                    <div class="font-semibold text-yellow-900">Not Connected</div>
                                    <div class="text-sm text-yellow-700">Connect your external API to import client lists</div>
                                </div>
                            </div>
                        </div>

                        <!-- Connection Form -->
                        <form action="{{ route('settings.store-external-api-credentials') }}" method="POST" class="space-y-6">
                            @csrf

                            <div>
                                <label for="api_url" class="block text-sm font-medium text-gray-700 mb-2">
                                    API URL <span class="text-red-500">*</span>
                                </label>
                                <input type="url" id="api_url" name="api_url" required
                                    value="{{ old('api_url') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    placeholder="https://your-api.com/clients">
                                <p class="mt-1 text-xs text-gray-500">The full URL endpoint that returns your client list</p>
                            </div>

                            <div>
                                <label for="api_token" class="block text-sm font-medium text-gray-700 mb-2">
                                    API Token <span class="text-red-500">*</span>
                                </label>
                                <input type="password" id="api_token" name="api_token" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    placeholder="Enter your API token">
                                <p class="mt-1 text-xs text-gray-500">Bearer token for authentication (stored encrypted)</p>
                            </div>

                            <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 font-medium">
                                Connect External API
                            </button>
                        </form>
                    @endif

                    <!-- API Requirements -->
                    <div class="mt-8 pt-8 border-t">
                        <h4 class="font-semibold mb-4">API Response Format Requirements:</h4>
                        <p class="text-sm text-gray-700 mb-3">Your API should return JSON in one of these formats:</p>

                        <div class="bg-gray-50 rounded-md p-4 mb-4">
                            <p class="text-xs font-semibold mb-2">Option 1: With wrapper key</p>
                            <pre class="text-xs overflow-x-auto"><code>{
  "clients": [
    {"phone": "+971501234567", "name": "John Doe", "email": "john@example.com"},
    {"phone": "+971509876543", "name": "Jane Smith"}
  ]
}</code></pre>
                        </div>

                        <div class="bg-gray-50 rounded-md p-4">
                            <p class="text-xs font-semibold mb-2">Option 2: Direct array</p>
                            <pre class="text-xs overflow-x-auto"><code>[
  {"phone": "+971501234567", "name": "John Doe"},
  {"phone": "+971509876543", "name": "Jane Smith"}
]</code></pre>
                        </div>

                        <div class="mt-4 p-4 bg-blue-50 rounded-md">
                            <p class="text-sm text-blue-900">
                                <strong>Supported field names:</strong> phone/mobile/telephone, name/full_name, email/email_address
                            </p>
                            <p class="text-sm text-blue-900 mt-2">
                                <strong>Note:</strong> Phone number is required. Contacts without valid phone numbers will be skipped.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
