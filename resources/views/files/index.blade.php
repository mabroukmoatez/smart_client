<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('My Files') }}
            </h2>
            <a href="{{ route('files.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                Upload New File
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-800 rounded-md p-4 mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if($files->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Filename
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Rows
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Size
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Uploaded
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($files as $file)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $file->original_filename }}
                                                </div>
                                                @if($file->notes)
                                                    <div class="text-xs text-gray-500">
                                                        {{ Str::limit($file->notes, 50) }}
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-4 py-4 text-sm text-gray-900">
                                                {{ number_format($file->row_count) }}
                                            </td>
                                            <td class="px-4 py-4 text-sm text-gray-900">
                                                {{ $file->formatted_file_size }}
                                            </td>
                                            <td class="px-4 py-4 text-sm text-gray-500">
                                                {{ $file->created_at->diffForHumans() }}
                                            </td>
                                            <td class="px-4 py-4 text-sm font-medium">
                                                <div class="flex items-center gap-2">
                                                    <a href="{{ route('files.preview', $file) }}"
                                                       class="text-blue-600 hover:text-blue-900">
                                                        Preview
                                                    </a>
                                                    <a href="{{ route('files.download', $file) }}"
                                                       class="text-green-600 hover:text-green-900">
                                                        Download
                                                    </a>
                                                    <form action="{{ route('files.destroy', $file) }}"
                                                          method="POST"
                                                          onsubmit="return confirm('Are you sure you want to delete this file?')"
                                                          class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                                            Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6">
                            {{ $files->links() }}
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No files uploaded</h3>
                        <p class="mt-1 text-sm text-gray-500">Get started by uploading a spreadsheet file.</p>
                        <div class="mt-6">
                            <a href="{{ route('files.create') }}"
                               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                Upload Your First File
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
