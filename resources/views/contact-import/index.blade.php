<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Import Contacts to HighLevel') }}
        </h2>
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

            @if(session('warning'))
                <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 rounded-md p-4 mb-6">
                    {{ session('warning') }}
                </div>
            @endif

            <!-- Quick Actions -->
            <div class="bg-white shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold">Quick Actions</h3>
                        <a href="{{ route('contact-import.list') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                            View All Imports →
                        </a>
                    </div>
                </div>
            </div>

            <!-- Import Form -->
            <form action="{{ route('contact-import.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Import Details -->
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Import Details</h3>

                        <div class="space-y-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Import Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="name" name="name" required
                                    value="{{ old('name', 'Contact Import ' . date('Y-m-d')) }}"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    placeholder="e.g., January 2025 Contacts">
                                <p class="mt-1 text-xs text-gray-500">Give this import a descriptive name for tracking</p>
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                    Description (Optional)
                                </label>
                                <textarea id="description" name="description" rows="3"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    placeholder="Add notes about this import...">{{ old('description') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Select Files -->
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Select Files <span class="text-red-500">*</span></h3>

                        @if($files->count() > 0)
                            <div class="space-y-3">
                                @foreach($files as $file)
                                    <label class="flex items-start p-4 border border-gray-200 rounded-md hover:border-blue-500 hover:bg-blue-50 cursor-pointer transition">
                                        <input type="checkbox" name="file_ids[]" value="{{ $file->id }}"
                                            class="mt-1 mr-3 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                            {{ in_array($file->id, old('file_ids', [])) ? 'checked' : '' }}>
                                        <div class="flex-1">
                                            <div class="font-medium text-gray-900">{{ $file->original_filename }}</div>
                                            <div class="text-sm text-gray-600 mt-1">
                                                <span class="inline-flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                                    </svg>
                                                    {{ number_format($file->row_count) }} contacts
                                                </span>
                                                <span class="ml-3">{{ $file->formatted_file_size }}</span>
                                                <span class="ml-3 text-gray-500">{{ $file->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>

                            <p class="mt-3 text-sm text-gray-600">
                                <strong>Tip:</strong> You can select multiple files. All contacts will be imported to HighLevel.
                            </p>
                        @else
                            <div class="text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No files available</h3>
                                <p class="mt-1 text-sm text-gray-500">Upload some contact files first.</p>
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

                <!-- Select Tags -->
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Tags (Optional)</h3>

                        @if(count($tags) > 0)
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-3">
                                    Select Existing Tags
                                </label>
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                    @foreach($tags as $tag)
                                        <label class="flex items-center p-3 border border-gray-200 rounded-md hover:border-blue-500 hover:bg-blue-50 cursor-pointer transition">
                                            <input type="checkbox" name="selected_tags[]" value="{{ $tag }}"
                                                class="mr-2 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                                {{ in_array($tag, old('selected_tags', [])) ? 'checked' : '' }}>
                                            <span class="text-sm text-gray-700">{{ $tag }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div>
                            <label for="new_tags" class="block text-sm font-medium text-gray-700 mb-2">
                                Add New Tags
                            </label>
                            <input type="text" id="new_tags" name="new_tags"
                                value="{{ old('new_tags') }}"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                placeholder="e.g., New Lead, January 2025, VIP">
                            <p class="mt-1 text-xs text-gray-500">Separate multiple tags with commas. These will be created if they don't exist.</p>
                        </div>

                        <div class="mt-4 p-4 bg-blue-50 rounded-md">
                            <p class="text-sm text-blue-900">
                                <strong>Note:</strong> All selected and new tags will be applied to every imported contact.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Submit -->
                @if($files->count() > 0)
                    <div class="bg-white shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex gap-4">
                                <button type="submit" class="px-6 py-3 bg-green-600 text-white rounded-md hover:bg-green-700 font-medium inline-flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                    </svg>
                                    Start Import
                                </button>

                                <a href="{{ route('dashboard') }}" class="px-6 py-3 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 font-medium inline-flex items-center">
                                    Cancel
                                </a>
                            </div>

                            <p class="mt-4 text-sm text-gray-600">
                                <strong>What happens next:</strong> Your contacts will be queued for import and processed in the background. You can monitor the progress on the import details page.
                            </p>
                        </div>
                    </div>
                @endif
            </form>
        </div>
    </div>
</x-app-layout>
