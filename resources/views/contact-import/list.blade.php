@extends('layouts.app')

@section('title', 'Contact Import Jobs')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Breadcrumb Header -->
    <h4 class="fw-bold mb-4">
        <span class="text-muted fw-light">Contacts /</span> Import Jobs
    </h4>

    <!-- Success Alert -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Quick Actions -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">All Import Jobs</h5>
                <a href="{{ route('contact-import.index') }}" class="btn btn-primary">
                    <i class='bx bx-plus me-1'></i>
                    New Import
                </a>
            </div>
        </div>
    </div>

    <!-- Import Jobs List -->
    <div class="card">
        <div class="card-body">
            @if($importJobs->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Status</th>
                                <th>Progress</th>
                                <th>Total Contacts</th>
                                <th>Imported</th>
                                <th>Failed</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($importJobs as $job)
                                <tr>
                                    <td>
                                        <div class="fw-medium">{{ $job->name }}</div>
                                        @if($job->description)
                                            <small class="text-muted">{{ Str::limit($job->description, 40) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($job->status === 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif($job->status === 'processing')
                                            <span class="badge bg-primary">
                                                <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                                                Processing
                                            </span>
                                        @elseif($job->status === 'completed')
                                            <span class="badge bg-success">Completed</span>
                                        @elseif($job->status === 'failed')
                                            <span class="badge bg-danger">Failed</span>
                                        @elseif($job->status === 'cancelled')
                                            <span class="badge bg-secondary">Cancelled</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="progress flex-grow-1" style="width: 120px; height: 6px;">
                                                <div class="progress-bar" role="progressbar"
                                                    style="width: {{ $job->completion_percentage }}%"
                                                    aria-valuenow="{{ $job->completion_percentage }}"
                                                    aria-valuemin="0"
                                                    aria-valuemax="100"></div>
                                            </div>
                                            <small class="text-muted ms-2">{{ number_format($job->completion_percentage, 1) }}%</small>
                                        </div>
                                    </td>
                                    <td>{{ number_format($job->total_contacts) }}</td>
                                    <td><span class="text-success fw-medium">{{ number_format($job->total_imported) }}</span></td>
                                    <td><span class="text-danger fw-medium">{{ number_format($job->total_failed) }}</span></td>
                                    <td><small class="text-muted">{{ $job->created_at->format('M d, Y H:i') }}</small></td>
                                    <td>
                                        <a href="{{ route('contact-import.show', $job) }}" class="btn btn-sm btn-primary">
                                            <i class='bx bx-show me-1'></i>
                                            View Details
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $importJobs->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class='bx bx-clipboard display-1 text-muted'></i>
                    <h5 class="mt-3">No import jobs</h5>
                    <p class="text-muted">Get started by creating your first import.</p>
                    <div class="mt-4">
                        <a href="{{ route('contact-import.index') }}" class="btn btn-primary">
                            <i class='bx bx-plus me-1'></i>
                            Create Import
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
