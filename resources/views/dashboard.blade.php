<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-600 text-sm">Total Files</div>
                    <div class="text-3xl font-bold text-blue-600">{{ number_format($stats['total_files']) }}</div>
                    <div class="text-xs text-gray-500 mt-1">Uploaded files</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-600 text-sm">Total Contacts</div>
                    <div class="text-3xl font-bold text-green-600">{{ number_format($stats['total_contacts']) }}</div>
                    <div class="text-xs text-gray-500 mt-1">Across all files</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-600 text-sm">HighLevel</div>
                    @if($stats['highlevel_connected'])
                        <div class="text-2xl font-bold text-green-600">Connected</div>
                        <div class="text-xs text-green-600 mt-1">✓ Ready to import</div>
                    @else
                        <div class="text-2xl font-bold text-gray-400">Not Connected</div>
                        <div class="text-xs text-gray-500 mt-1">
                            <a href="{{ route('settings.index') }}" class="text-blue-600 hover:underline">Connect now</a>
                        </div>
                    @endif
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-600 text-sm">External API</div>
                    @if($stats['external_api_connected'])
                        <div class="text-2xl font-bold text-green-600">Connected</div>
                        <div class="text-xs text-green-600 mt-1">✓ Ready to fetch</div>
                    @else
                        <div class="text-2xl font-bold text-gray-400">Not Connected</div>
                        <div class="text-xs text-gray-500 mt-1">
                            <a href="{{ route('settings.index') }}" class="text-blue-600 hover:underline">Connect now</a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Quick Actions</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <a href="{{ route('files.create') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:border-blue-500 hover:bg-blue-50 transition">
                            <svg class="w-8 h-8 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                            <div>
                                <div class="font-semibold">Upload File</div>
                                <div class="text-xs text-gray-500">Excel, CSV files</div>
                            </div>
                        </a>

                        @if($stats['external_api_connected'])
                            <a href="{{ route('external-api.index') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:border-green-500 hover:bg-green-50 transition">
                                <svg class="w-8 h-8 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                </svg>
                                <div>
                                    <div class="font-semibold">Import from API</div>
                                    <div class="text-xs text-gray-500">Fetch client list</div>
                                </div>
                            </a>
                        @endif

                        <a href="{{ route('settings.index') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:border-purple-500 hover:bg-purple-50 transition">
                            <svg class="w-8 h-8 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <div>
                                <div class="font-semibold">Settings</div>
                                <div class="text-xs text-gray-500">Connect integrations</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Recent Files -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Recent Files</h3>
                        <a href="{{ route('files.index') }}" class="text-sm text-blue-600 hover:text-blue-800">View all →</a>
                    </div>

                    @if($recentFiles->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Filename</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rows</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Size</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Uploaded</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($recentFiles as $file)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $file->original_filename }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-700">{{ number_format($file->row_count) }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-700">{{ $file->formatted_file_size }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-500">{{ $file->created_at->diffForHumans() }}</td>
                                            <td class="px-4 py-3 text-sm">
                                                <a href="{{ route('files.preview', $file) }}" class="text-blue-600 hover:text-blue-900">
                                                    Preview
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No files</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by uploading your first file.</p>
                            <div class="mt-6">
                                <a href="{{ route('files.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Upload File
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
