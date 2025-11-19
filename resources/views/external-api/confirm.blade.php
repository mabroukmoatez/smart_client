@extends('layouts.app')

@section('title', 'Confirm Import from External API')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Breadcrumb Header -->
    <h4 class="fw-bold mb-4">
        <span class="text-muted fw-light">External API /</span> Confirm Import
    </h4>

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

    <!-- Statistics Summary -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title mb-4">Import Summary</h5>

            <div class="row g-3">
                <div class="col-md-4">
                    <div class="card bg-label-primary border">
                        <div class="card-body">
                            <div class="text-primary small mb-1">Total Fetched</div>
                            <h3 class="mb-1">{{ number_format($totalCount) }}</h3>
                            <small class="text-muted">Clients from API</small>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card bg-label-success border">
                        <div class="card-body">
                            <div class="text-success small mb-1">Valid Contacts</div>
                            <h3 class="mb-1">{{ number_format($validCount) }}</h3>
                            <small class="text-muted">With valid phone numbers</small>
                        </div>
                    </div>
                </div>

                @if($skippedCount > 0)
                    <div class="col-md-4">
                        <div class="card bg-label-warning border">
                            <div class="card-body">
                                <div class="text-warning small mb-1">Skipped</div>
                                <h3 class="mb-1">{{ number_format($skippedCount) }}</h3>
                                <small class="text-muted">Missing phone numbers</small>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="col-md-4">
                        <div class="card bg-label-secondary border">
                            <div class="card-body">
                                <div class="text-secondary small mb-1">Skipped</div>
                                <h3 class="mb-1">0</h3>
                                <small class="text-muted">All contacts valid</small>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            @if($skippedCount > 0)
                <div class="alert alert-warning mt-3 mb-0">
                    <strong>Note:</strong> {{ $skippedCount }} contact(s) will be skipped due to missing phone numbers.
                </div>
            @endif
        </div>
    </div>

    <!-- Data Preview -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title mb-4">Data Preview (First 10 Contacts)</h5>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Phone</th>
                            <th>Name</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($preview as $index => $contact)
                            <tr>
                                <td><span class="text-muted">{{ $index + 1 }}</span></td>
                                <td><strong>{{ $contact['phone'] }}</strong></td>
                                <td>{{ $contact['name'] ?? '-' }}</td>
                                <td>{{ $contact['email'] ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($validCount > 10)
                <p class="text-center text-muted mb-0 mt-3">
                    Showing 10 of {{ number_format($validCount) }} contacts
                </p>
            @endif
        </div>
    </div>

    <!-- Import Form -->
    <div class="card">
        <div class="card-body">
            <h5 class="card-title mb-4">Confirm Import</h5>

            <form action="{{ route('external-api.import') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="filename" class="form-label">
                        File Name <span class="text-danger">*</span>
                    </label>
                    <input type="text" id="filename" name="filename" required
                        value="{{ old('filename', 'external_api_import_' . date('Y-m-d')) }}"
                        class="form-control"
                        placeholder="e.g., api_clients_january_2025">
                    <div class="form-text">Choose a descriptive name for this import file</div>
                </div>

                <div class="mb-4">
                    <label for="notes" class="form-label">
                        Notes (Optional)
                    </label>
                    <textarea id="notes" name="notes" rows="3"
                        class="form-control"
                        placeholder="Add any notes about this import...">{{ old('notes') }}</textarea>
                </div>

                <div class="alert alert-info mb-4">
                    <strong>What happens next:</strong> This will create a new file with {{ number_format($validCount) }} contacts.
                    You can then use this file for contact imports or campaigns.
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success">
                        <i class='bx bx-check me-1'></i>
                        Confirm & Create File
                    </button>

                    <a href="{{ route('external-api.index') }}" class="btn btn-secondary">
                        <i class='bx bx-x me-1'></i>
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
