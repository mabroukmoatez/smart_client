<x-app-layout>
    @section('title', 'My Files')

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-6">
        <h4 class="fw-bold mb-0">
            <span class="text-muted fw-light">Files /</span> My Files
        </h4>
        <a href="{{ route('files.create') }}" class="btn btn-primary">
            <i class="bx bx-plus me-1"></i> Upload New File
        </a>
    </div>

    @if($files->count() > 0)
        <div class="card">
            <!-- Bulk Actions Bar -->
            <div id="bulkActionsBar" class="card-header bg-label-primary" style="display: none;">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="fw-medium">
                        <strong id="selectedCount">0</strong> file(s) selected
                    </span>
                    <div class="d-flex gap-2">
                        <button onclick="showMergeModal()" class="btn btn-sm btn-success">
                            <i class="bx bx-merge me-1"></i> Merge Files
                        </button>
                        <button onclick="bulkDelete()" class="btn btn-sm btn-danger">
                            <i class="bx bx-trash me-1"></i> Delete Selected
                        </button>
                        <button onclick="clearSelection()" class="btn btn-sm btn-secondary">
                            Clear Selection
                        </button>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th style="width: 50px;">
                                    <input type="checkbox" id="selectAll" onclick="toggleSelectAll(this)" class="form-check-input">
                                </th>
                                <th>Filename</th>
                                <th>Rows</th>
                                <th>Size</th>
                                <th>Uploaded</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($files as $file)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="file-checkbox form-check-input" value="{{ $file->id }}" onchange="updateSelection()">
                                    </td>
                                    <td>
                                        <div class="fw-medium">{{ $file->original_filename }}</div>
                                        @if($file->notes)
                                            <small class="text-muted">{{ Str::limit($file->notes, 50) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-label-primary">{{ number_format($file->row_count) }} rows</span>
                                    </td>
                                    <td>{{ $file->formatted_file_size }}</td>
                                    <td><small class="text-muted">{{ $file->created_at->diffForHumans() }}</small></td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('files.preview', $file) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bx bx-show"></i>
                                            </a>
                                            <a href="{{ route('files.download', $file) }}" class="btn btn-sm btn-outline-success">
                                                <i class="bx bx-download"></i>
                                            </a>
                                            <form action="{{ route('files.destroy', $file) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this file?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $files->links() }}
                </div>
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-body text-center py-5">
                <div class="mb-4">
                    <i class="bx bx-file bx-lg text-muted"></i>
                </div>
                <h5 class="mb-2">No files uploaded</h5>
                <p class="text-muted mb-4">Get started by uploading a spreadsheet file.</p>
                <a href="{{ route('files.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus me-1"></i> Upload Your First File
                </a>
            </div>
        </div>
    @endif

    <!-- Merge Files Modal -->
    <div class="modal fade" id="mergeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Merge Files</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="mergeForm" action="{{ route('files.merge') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="file_ids" id="mergeFileIds">

                        <div class="mb-4">
                            <label for="merged_filename" class="form-label">
                                Merged File Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="merged_filename" id="merged_filename" required class="form-control" placeholder="e.g., combined_contacts">
                            <div class="form-text">Enter a name for the merged file (without extension)</div>
                        </div>

                        <div class="alert alert-info">
                            <strong>Note:</strong> Duplicate phone numbers will be automatically removed.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Merge Files</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
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
                bulkBar.style.display = 'block';
            } else {
                bulkBar.style.display = 'none';
                selectAll.checked = false;
            }

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
            const modal = new bootstrap.Modal(document.getElementById('mergeModal'));
            modal.show();
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
    </script>
    @endpush
</x-app-layout>
