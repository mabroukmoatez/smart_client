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

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-800 rounded-md p-4 mb-6">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if($files->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <!-- Bulk Actions Bar -->
                    <div id="bulkActionsBar" class="bg-blue-50 border-b border-blue-200 p-4 hidden">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-blue-900">
                                <strong id="selectedCount">0</strong> file(s) selected
                            </span>
                            <div class="flex gap-3">
                                <button onclick="showMergeModal()" class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm rounded-md hover:bg-green-700">
                                    Merge Files
                                </button>
                                <button onclick="bulkDelete()" class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm rounded-md hover:bg-red-700">
                                    Delete Selected
                                </button>
                                <button onclick="clearSelection()" class="inline-flex items-center px-3 py-2 bg-gray-200 text-gray-700 text-sm rounded-md hover:bg-gray-300">
                                    Clear Selection
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-3 text-left">
                                            <input type="checkbox" id="selectAll" onclick="toggleSelectAll(this)"
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </th>
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
                                                <input type="checkbox" class="file-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                                       value="{{ $file->id }}" onchange="updateSelection()">
                                            </td>
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
                                                <span class="font-semibold">{{ number_format($file->row_count) }}</span> rows
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

    <!-- Merge Files Modal -->
    <div id="mergeModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Merge Files</h3>
                <form id="mergeForm" action="{{ route('files.merge') }}" method="POST">
                    @csrf
                    <input type="hidden" name="file_ids" id="mergeFileIds">

                    <div class="mb-4">
                        <label for="merged_filename" class="block text-sm font-medium text-gray-700 mb-2">
                            Merged File Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="merged_filename" id="merged_filename" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               placeholder="e.g., combined_contacts">
                        <p class="mt-1 text-xs text-gray-500">Enter a name for the merged file (without extension)</p>
                    </div>

                    <div class="bg-blue-50 rounded-md p-3 mb-4">
                        <p class="text-sm text-blue-800">
                            <strong>Note:</strong> Duplicate phone numbers will be automatically removed.
                        </p>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit" class="flex-1 px-4 py-2 bg-green-600 text-white text-sm rounded-md hover:bg-green-700">
                            Merge Files
                        </button>
                        <button type="button" onclick="closeMergeModal()" class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 text-sm rounded-md hover:bg-gray-300">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleSelectAll(checkbox) {
            const checkboxes = document.querySelectorAll('.file-checkbox');
            checkboxes.forEach(cb => cb.checked = checkbox.checked);
            updateSelection();
        }

        function updateSelection() {
            const selected = document.querySelectorAll('.file-checkbox:checked');
            const count = selected.length;
            const bulkBar = document.getElementById('bulkActionsBar');
            const selectAll = document.getElementById('selectAll');

            document.getElementById('selectedCount').textContent = count;

            if (count > 0) {
                bulkBar.classList.remove('hidden');
            } else {
                bulkBar.classList.add('hidden');
                selectAll.checked = false;
            }

            // Update selectAll checkbox state
            const totalCheckboxes = document.querySelectorAll('.file-checkbox').length;
            selectAll.checked = count === totalCheckboxes && count > 0;
        }

        function clearSelection() {
            document.querySelectorAll('.file-checkbox').forEach(cb => cb.checked = false);
            document.getElementById('selectAll').checked = false;
            updateSelection();
        }

        function showMergeModal() {
            const selected = Array.from(document.querySelectorAll('.file-checkbox:checked')).map(cb => cb.value);

            if (selected.length < 2) {
                alert('Please select at least 2 files to merge.');
                return;
            }

            document.getElementById('mergeFileIds').value = JSON.stringify(selected);
            document.getElementById('mergeModal').classList.remove('hidden');
        }

        function closeMergeModal() {
            document.getElementById('mergeModal').classList.add('hidden');
            document.getElementById('merged_filename').value = '';
        }

        function bulkDelete() {
            const selected = Array.from(document.querySelectorAll('.file-checkbox:checked')).map(cb => cb.value);

            if (selected.length === 0) {
                alert('Please select at least one file to delete.');
                return;
            }

            if (!confirm(`Are you sure you want to delete ${selected.length} file(s)? This action cannot be undone.`)) {
                return;
            }

            // Create form and submit
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route('files.bulk-delete') }}';

            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';
            form.appendChild(csrf);

            const fileIds = document.createElement('input');
            fileIds.type = 'hidden';
            fileIds.name = 'file_ids';
            fileIds.value = JSON.stringify(selected);
            form.appendChild(fileIds);

            document.body.appendChild(form);
            form.submit();
        }

        // Close modal when clicking outside
        document.getElementById('mergeModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeMergeModal();
            }
        });
    </script>
</x-app-layout>
