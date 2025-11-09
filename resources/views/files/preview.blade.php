<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Preview: {{ $file->original_filename }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- File Information -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">File Information</h3>
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm text-gray-600">Original Filename</dt>
                            <dd class="text-sm font-medium">{{ $file->original_filename }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-600">Total Rows</dt>
                            <dd class="text-sm font-medium">{{ number_format($file->row_count) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-600">File Size</dt>
                            <dd class="text-sm font-medium">{{ $file->formatted_file_size }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-600">Uploaded</dt>
                            <dd class="text-sm font-medium">{{ $file->created_at->format('M d, Y H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-600">Phone Column</dt>
                            <dd class="text-sm font-medium">{{ $file->phone_column }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-600">Name Column</dt>
                            <dd class="text-sm font-medium">{{ $file->name_column ?? 'Not set' }}</dd>
                        </div>
                    </dl>

                    @if($file->notes)
                        <div class="mt-4">
                            <dt class="text-sm text-gray-600">Notes</dt>
                            <dd class="text-sm mt-1">{{ $file->notes }}</dd>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Preview Data (First 10 Rows) -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Data Preview (First 10 Rows)</h3>

                    @if(count($preview) > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Original Phone</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Normalized Phone</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($preview as $index => $row)
                                        <tr class="{{ $row['is_valid'] ? 'bg-white' : 'bg-red-50' }}">
                                            <td class="px-4 py-3 text-sm text-gray-900">{{ $index + 1 }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-900">{{ $row['name'] ?? '-' }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-600">{{ $row['phone'] }}</td>
                                            <td class="px-4 py-3 text-sm font-medium {{ $row['is_valid'] ? 'text-green-600' : 'text-red-600' }}">
                                                {{ $row['normalized_phone'] ?? 'Invalid' }}
                                            </td>
                                            <td class="px-4 py-3">
                                                @if($row['is_valid'])
                                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Valid</span>
                                                @else
                                                    <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Invalid</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4 p-4 bg-blue-50 rounded-md">
                            <h4 class="text-sm font-medium text-blue-900 mb-2">Phone Normalization Info:</h4>
                            <ul class="text-sm text-blue-800 list-disc list-inside space-y-1">
                                <li>All valid phone numbers are normalized to UAE format (+971)</li>
                                <li>Numbers starting with "05" are converted to "+9715"</li>
                                <li>Invalid numbers will be excluded from campaigns</li>
                                <li>Preview shows only the first 10 rows</li>
                            </ul>
                        </div>
                    @else
                        <p class="text-gray-500">No data to preview.</p>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center gap-4">
                        <a href="{{ route('files.index') }}" class="text-gray-600 hover:text-gray-900">
                            ← Back to Files
                        </a>
                        <a href="{{ route('files.download', $file) }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                            Download CSV
                        </a>
                        <a href="{{ route('automation.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Create Campaign with This File
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
