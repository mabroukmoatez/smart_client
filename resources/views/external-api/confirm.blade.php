<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Confirm Import from External API') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-800 rounded-md p-4 mb-6">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Statistics Summary -->
            <div class="bg-white shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Import Summary</h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                            <div class="text-sm text-blue-700">Total Fetched</div>
                            <div class="text-2xl font-bold text-blue-900">{{ number_format($totalCount) }}</div>
                            <div class="text-xs text-blue-600">Clients from API</div>
                        </div>

                        <div class="bg-green-50 border border-green-200 rounded-md p-4">
                            <div class="text-sm text-green-700">Valid Contacts</div>
                            <div class="text-2xl font-bold text-green-900">{{ number_format($validCount) }}</div>
                            <div class="text-xs text-green-600">With valid phone numbers</div>
                        </div>

                        @if($skippedCount > 0)
                            <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                                <div class="text-sm text-yellow-700">Skipped</div>
                                <div class="text-2xl font-bold text-yellow-900">{{ number_format($skippedCount) }}</div>
                                <div class="text-xs text-yellow-600">Missing phone numbers</div>
                            </div>
                        @else
                            <div class="bg-gray-50 border border-gray-200 rounded-md p-4">
                                <div class="text-sm text-gray-700">Skipped</div>
                                <div class="text-2xl font-bold text-gray-900">0</div>
                                <div class="text-xs text-gray-600">All contacts valid</div>
                            </div>
                        @endif
                    </div>

                    @if($skippedCount > 0)
                        <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-md">
                            <p class="text-sm text-yellow-800">
                                <strong>Note:</strong> {{ $skippedCount }} contact(s) will be skipped due to missing phone numbers.
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Data Preview -->
            <div class="bg-white shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Data Preview (First 10 Contacts)</h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Phone</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($preview as $index => $contact)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 text-sm text-gray-500">{{ $index + 1 }}</td>
                                        <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $contact['phone'] }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-700">{{ $contact['name'] ?? '-' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-700">{{ $contact['email'] ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($validCount > 10)
                        <p class="mt-4 text-sm text-gray-600 text-center">
                            Showing 10 of {{ number_format($validCount) }} contacts
                        </p>
                    @endif
                </div>
            </div>

            <!-- Import Form -->
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Confirm Import</h3>

                    <form action="{{ route('external-api.import') }}" method="POST" class="space-y-6">
                        @csrf

                        <div>
                            <label for="filename" class="block text-sm font-medium text-gray-700 mb-2">
                                File Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="filename" name="filename" required
                                value="{{ old('filename', 'external_api_import_' . date('Y-m-d')) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                placeholder="e.g., api_clients_january_2025">
                            <p class="mt-1 text-xs text-gray-500">Choose a descriptive name for this import file</p>
                        </div>

                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                Notes (Optional)
                            </label>
                            <textarea id="notes" name="notes" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                placeholder="Add any notes about this import...">{{ old('notes') }}</textarea>
                        </div>

                        <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                            <p class="text-sm text-blue-900">
                                <strong>What happens next:</strong> This will create a new file with {{ number_format($validCount) }} contacts.
                                You can then use this file for contact imports or campaigns.
                            </p>
                        </div>

                        <div class="flex gap-4">
                            <button type="submit" class="px-6 py-3 bg-green-600 text-white rounded-md hover:bg-green-700 font-medium inline-flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Confirm & Create File
                            </button>

                            <a href="{{ route('external-api.index') }}" class="px-6 py-3 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 font-medium inline-flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
