@section('title', 'Import Contacts to HighLevel')

<x-app-layout>
    <x-slot name="header">
        <h4 class="fw-bold mb-0"><span class="text-muted fw-light">Contacts /</span> Import to HighLevel</h4>
    </x-slot>

    <div class="container-xxl flex-grow-1 container-p-y">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible" role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible" role="alert">
                {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Quick Actions -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                    <a href="{{ route('contact-import.list') }}" class="text-primary small">
                        View All Imports <i class='bx bx-right-arrow-alt'></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Import Form -->
        <form action="{{ route('contact-import.store') }}" method="POST">
            @csrf

            <!-- Import Details -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-4">Import Details</h5>

                    <div class="mb-3">
                        <label for="name" class="form-label">
                            Import Name <span class="text-danger">*</span>
                        </label>
                        <input type="text" id="name" name="name" required
                            value="{{ old('name', 'Contact Import ' . date('Y-m-d')) }}"
                            class="form-control"
                            placeholder="e.g., January 2025 Contacts">
                        <div class="form-text">Give this import a descriptive name for tracking</div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">
                            Description (Optional)
                        </label>
                        <textarea id="description" name="description" rows="3"
                            class="form-control"
                            placeholder="Add notes about this import...">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Select Files -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-4">Select Files <span class="text-danger">*</span></h5>

                    @if($files->count() > 0)
                        <div class="mb-3">
                            @foreach($files as $file)
                                <label class="card mb-3 cursor-pointer">
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-start">
                                            <div class="form-check mt-1">
                                                <input type="checkbox" name="file_ids[]" value="{{ $file->id }}"
                                                    class="form-check-input"
                                                    {{ in_array($file->id, old('file_ids', [])) ? 'checked' : '' }}>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <div class="fw-medium">{{ $file->original_filename }}</div>
                                                <div class="text-muted small mt-1">
                                                    <span class="d-inline-flex align-items-center">
                                                        <i class='bx bx-group me-1'></i>
                                                        {{ number_format($file->row_count) }} contacts
                                                    </span>
                                                    <span class="ms-3">{{ $file->formatted_file_size }}</span>
                                                    <span class="ms-3">{{ $file->created_at->diffForHumans() }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>

                        <div class="alert alert-info mb-0" role="alert">
                            <strong>Tip:</strong> You can select multiple files. All contacts will be imported to HighLevel.
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class='bx bx-file fs-1 text-muted mb-3'></i>
                            <h5 class="mb-2">No files available</h5>
                            <p class="text-muted mb-4">Upload some contact files first.</p>
                            <a href="{{ route('files.create') }}" class="btn btn-primary">
                                <i class='bx bx-plus me-1'></i>
                                Upload File
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Select Tags -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-4">Tags (Optional)</h5>

                    @if(count($tags) > 0)
                        <div class="mb-4">
                            <label class="form-label">
                                Select Existing Tags
                            </label>
                            <div class="row g-3">
                                @foreach($tags as $tag)
                                    @php
                                        $tagName = is_array($tag) ? ($tag['name'] ?? $tag['id']) : $tag;
                                    @endphp
                                    <div class="col-md-4 col-6">
                                        <div class="form-check">
                                            <input type="checkbox" name="selected_tags[]" value="{{ $tagName }}"
                                                class="form-check-input"
                                                id="tag_{{ $loop->index }}"
                                                {{ in_array($tagName, old('selected_tags', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="tag_{{ $loop->index }}">
                                                {{ $tagName }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label for="new_tags" class="form-label">
                            Add New Tags
                        </label>
                        <input type="text" id="new_tags" name="new_tags"
                            value="{{ old('new_tags') }}"
                            class="form-control"
                            placeholder="e.g., New Lead, January 2025, VIP">
                        <div class="form-text">Separate multiple tags with commas. These will be created if they don't exist.</div>
                    </div>

                    <div class="alert alert-primary mb-0" role="alert">
                        <strong>Note:</strong> All selected and new tags will be applied to every imported contact.
                    </div>
                </div>
            </div>

            <!-- Submit -->
            @if($files->count() > 0)
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex gap-2 mb-3">
                            <button type="submit" class="btn btn-success">
                                <i class='bx bx-upload me-1'></i>
                                Start Import
                            </button>

                            <a href="{{ route('dashboard') }}" class="btn btn-label-secondary">
                                Cancel
                            </a>
                        </div>

                        <div class="alert alert-info mb-0" role="alert">
                            <strong>What happens next:</strong> Your contacts will be queued for import and processed in the background. You can monitor the progress on the import details page.
                        </div>
                    </div>
                </div>
            @endif
        </form>
    </div>
</x-app-layout>
