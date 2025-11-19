<x-app-layout>
    @section('title', 'Preview File')

    <div class="d-flex justify-content-between align-items-center mb-6">
        <h4 class="fw-bold mb-0">
            <span class="text-muted fw-light">Files /</span> Preview: {{ $file->original_filename }}
        </h4>
    </div>

    <!-- File Information -->
    <div class="card mb-6">
        <div class="card-body">
            <h5 class="card-title mb-4">File Information</h5>
            <div class="row">
                <div class="col-md-6 mb-4">
                    <small class="text-muted d-block">Original Filename</small>
                    <strong>{{ $file->original_filename }}</strong>
                </div>
                <div class="col-md-6 mb-4">
                    <small class="text-muted d-block">Total Rows</small>
                    <strong>{{ number_format($file->row_count) }}</strong>
                </div>
                <div class="col-md-6 mb-4">
                    <small class="text-muted d-block">File Size</small>
                    <strong>{{ $file->formatted_file_size }}</strong>
                </div>
                <div class="col-md-6 mb-4">
                    <small class="text-muted d-block">Uploaded</small>
                    <strong>{{ $file->created_at->format('M d, Y H:i') }}</strong>
                </div>
                <div class="col-md-6 mb-4">
                    <small class="text-muted d-block">Phone Column</small>
                    <strong>{{ $file->phone_column }}</strong>
                </div>
                <div class="col-md-6 mb-4">
                    <small class="text-muted d-block">Name Column</small>
                    <strong>{{ $file->name_column ?? 'Not set' }}</strong>
                </div>
            </div>

            @if($file->notes)
                <div class="mt-3">
                    <small class="text-muted d-block">Notes</small>
                    <p class="mb-0">{{ $file->notes }}</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Preview Data (First 10 Rows) -->
    <div class="card mb-6">
        <div class="card-body">
            <h5 class="card-title mb-4">Data Preview (First 10 Rows)</h5>

            @if(count($preview) > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Original Phone</th>
                                <th>Normalized Phone</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($preview as $index => $row)
                                <tr class="{{ $row['is_valid'] ? '' : 'table-danger' }}">
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $row['name'] ?? '-' }}</td>
                                    <td class="text-muted">{{ $row['phone'] }}</td>
                                    <td class="{{ $row['is_valid'] ? 'text-success' : 'text-danger' }} fw-medium">
                                        {{ $row['normalized_phone'] ?? 'Invalid' }}
                                    </td>
                                    <td>
                                        @if($row['is_valid'])
                                            <span class="badge bg-label-success">Valid</span>
                                        @else
                                            <span class="badge bg-label-danger">Invalid</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="alert alert-info mt-4">
                    <h6 class="alert-heading mb-2">Phone Normalization Info:</h6>
                    <ul class="mb-0 ps-3">
                        <li>All valid phone numbers are normalized to UAE format (+971)</li>
                        <li>Numbers starting with "05" are converted to "+9715"</li>
                        <li>Invalid numbers will be excluded from campaigns</li>
                        <li>Preview shows only the first 10 rows</li>
                    </ul>
                </div>
            @else
                <p class="text-muted">No data to preview.</p>
            @endif
        </div>
    </div>

    <!-- Actions -->
    <div class="card">
        <div class="card-body">
            <div class="d-flex gap-3">
                <a href="{{ route('files.index') }}" class="btn btn-secondary">
                    <i class="bx bx-arrow-back me-1"></i> Back to Files
                </a>
                <a href="{{ route('files.download', $file) }}" class="btn btn-success">
                    <i class="bx bx-download me-1"></i> Download CSV
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
