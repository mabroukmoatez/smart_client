@extends('layouts.app')

@section('title', 'Import Job Details')

@section('content')
@if($importJob->status === 'processing' || $importJob->status === 'pending')
    <script>
        // Auto-refresh every 3 seconds if import is still processing
        setTimeout(function() {
            window.location.reload();
        }, 3000);
    </script>
@endif

<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Breadcrumb Header -->
    <h4 class="fw-bold mb-4">
        <span class="text-muted fw-light">Contacts / Import Jobs /</span> Details
    </h4>

    <!-- Success Alert -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Error Alert -->
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Job Header -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start flex-wrap">
                <div>
                    <h3 class="mb-2">{{ $importJob->name }}</h3>
                    @if($importJob->description)
                        <p class="text-muted mb-2">{{ $importJob->description }}</p>
                    @endif
                    <div class="d-flex align-items-center gap-3 flex-wrap">
                        @if($importJob->status === 'pending')
                            <span class="badge bg-warning">Pending</span>
                        @elseif($importJob->status === 'processing')
                            <span class="badge bg-primary">
                                <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                                Processing
                            </span>
                        @elseif($importJob->status === 'completed')
                            <span class="badge bg-success">Completed</span>
                        @elseif($importJob->status === 'failed')
                            <span class="badge bg-danger">Failed</span>
                        @elseif($importJob->status === 'cancelled')
                            <span class="badge bg-secondary">Cancelled</span>
                        @endif

                        <small class="text-muted">Created {{ $importJob->created_at->diffForHumans() }}</small>
                    </div>
                </div>
                <div class="d-flex gap-2 mt-3 mt-md-0">
                    <a href="{{ route('contact-import.list') }}" class="btn btn-secondary">
                        <i class='bx bx-arrow-back me-1'></i>
                        Back to List
                    </a>
                    @if($importJob->status === 'processing' || $importJob->status === 'pending')
                        <form action="{{ route('contact-import.cancel', $importJob) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this import?')">
                            @csrf
                            <button type="submit" class="btn btn-danger">
                                <i class='bx bx-x me-1'></i>
                                Cancel Import
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="text-muted small mb-1">Total Contacts</div>
                    <h3 class="text-primary mb-1">{{ number_format($importJob->total_contacts) }}</h3>
                    <small class="text-muted">To be imported</small>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="text-muted small mb-1">Imported</div>
                    <h3 class="text-success mb-1">{{ number_format($importJob->total_imported) }}</h3>
                    <small class="text-muted">Successfully imported</small>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="text-muted small mb-1">Failed</div>
                    <h3 class="text-danger mb-1">{{ number_format($importJob->total_failed) }}</h3>
                    <small class="text-muted">Import failures</small>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="text-muted small mb-1">Pending</div>
                    <h3 class="text-warning mb-1">{{ number_format($importJob->total_pending) }}</h3>
                    <small class="text-muted">In queue</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Progress Bar -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title mb-4">Overall Progress</h5>
            <div class="d-flex align-items-center">
                <div class="progress flex-grow-1 me-3" style="height: 16px;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated"
                        role="progressbar"
                        style="width: {{ $importJob->completion_percentage }}%"
                        aria-valuenow="{{ $importJob->completion_percentage }}"
                        aria-valuemin="0"
                        aria-valuemax="100"></div>
                </div>
                <span class="h5 mb-0">{{ number_format($importJob->completion_percentage, 1) }}%</span>
            </div>
            @if($importJob->success_rate > 0)
                <p class="mt-3 mb-0">
                    Success Rate: <span class="fw-bold text-success">{{ number_format($importJob->success_rate, 1) }}%</span>
                </p>
            @endif
        </div>
    </div>

    <!-- Import Details -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title mb-4">Import Details</h5>
            <div class="row g-4">
                <div class="col-md-6">
                    <h6 class="text-muted mb-2">Selected Files</h6>
                    @if(count($importJob->uploadedFiles()) > 0)
                        <ul class="list-unstyled">
                            @foreach($importJob->uploadedFiles() as $file)
                                <li class="mb-1">
                                    <i class='bx bx-file me-1'></i>
                                    {{ $file->original_filename }} ({{ number_format($file->row_count) }} contacts)
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted mb-0">No files</p>
                    @endif
                </div>

                <div class="col-md-6">
                    <h6 class="text-muted mb-2">Tags Applied</h6>
                    @if(count($importJob->all_tags) > 0)
                        <div class="d-flex flex-wrap gap-1">
                            @foreach($importJob->all_tags as $tag)
                                <span class="badge bg-label-primary">{{ $tag }}</span>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted mb-0">No tags</p>
                    @endif
                </div>

                <div class="col-md-6">
                    <h6 class="text-muted mb-2">Started At</h6>
                    <p class="mb-0">{{ $importJob->started_at ? $importJob->started_at->format('M d, Y H:i:s') : 'Not started yet' }}</p>
                </div>

                <div class="col-md-6">
                    <h6 class="text-muted mb-2">Completed At</h6>
                    <p class="mb-0">{{ $importJob->completed_at ? $importJob->completed_at->format('M d, Y H:i:s') : 'In progress' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Logs -->
    <div class="card">
        <div class="card-body">
            <h5 class="card-title mb-4">Contact Import Logs</h5>

            @if($importJob->contactLogs->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Status</th>
                                <th>Action</th>
                                <th>Phone</th>
                                <th>Name</th>
                                <th>HighLevel ID</th>
                                <th>Tags</th>
                                <th>Time</th>
                                <th>Error</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($importJob->contactLogs->sortByDesc('created_at')->take(100) as $log)
                                @php
                                    $action = $log->contact_data['action'] ?? 'unknown';
                                    $rowClass = '';
                                    if ($log->status === 'failed') {
                                        $rowClass = 'table-danger';
                                    } elseif ($action === 'created') {
                                        $rowClass = 'table-primary';
                                    } elseif ($action === 'updated') {
                                        $rowClass = 'table-warning';
                                    }
                                @endphp
                                <tr class="{{ $rowClass }}">
                                    <td>
                                        @if($log->status === 'sent')
                                            <span class="badge bg-success">Success</span>
                                        @else
                                            <span class="badge bg-danger">Failed</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($log->status === 'sent')
                                            @if($action === 'created')
                                                <span class="badge bg-label-primary">CREATED</span>
                                            @elseif($action === 'updated')
                                                <span class="badge bg-label-warning">UPDATED</span>
                                            @else
                                                <span class="badge bg-label-secondary">-</span>
                                            @endif
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>{{ $log->contact_phone }}</td>
                                    <td>{{ $log->contact_name ?? '-' }}</td>
                                    <td><code>{{ $log->highlevel_contact_id ?? '-' }}</code></td>
                                    <td>
                                        @if($log->assigned_tags && count($log->assigned_tags) > 0)
                                            <div class="d-flex flex-wrap gap-1">
                                                @foreach($log->assigned_tags as $tag)
                                                    <span class="badge bg-label-info">{{ $tag }}</span>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-muted">None</span>
                                        @endif
                                    </td>
                                    <td><small>{{ $log->imported_at ? $log->imported_at->format('H:i:s') : '-' }}</small></td>
                                    <td>
                                        @if($log->error_message)
                                            <small class="text-danger">{{ Str::limit($log->error_message, 50) }}</small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($importJob->contactLogs->count() > 100)
                    <p class="text-center text-muted mt-3 mb-0">
                        Showing first 100 logs of {{ number_format($importJob->contactLogs->count()) }}
                    </p>
                @endif
            @else
                <div class="text-center py-5">
                    <i class='bx bx-file display-1 text-muted'></i>
                    <h5 class="mt-3">No logs yet</h5>
                    <p class="text-muted mb-0">Import processing hasn't started or no contacts have been processed.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
