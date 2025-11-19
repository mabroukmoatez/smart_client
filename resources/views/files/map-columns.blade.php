<x-app-layout>
    @section('title', 'Map Columns')

    <div class="d-flex justify-content-between align-items-center mb-6">
        <h4 class="fw-bold mb-0">
            <span class="text-muted fw-light">Files /</span> Map Columns
        </h4>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="mb-6">
                        <h5 class="card-title">File: {{ $filename }}</h5>
                        <p class="text-muted">Rows: {{ number_format($rowCount) }}</p>
                    </div>

                    <form action="{{ route('files.store') }}" method="POST">
                        @csrf

                        <div class="mb-6">
                            <label for="phone_column" class="form-label">
                                Phone Number Column <span class="text-danger">*</span>
                            </label>
                            <select id="phone_column" name="phone_column" required class="form-select">
                                <option value="">Select column...</option>
                                @foreach($headers as $header)
                                    <option value="{{ $header }}">{{ $header }}</option>
                                @endforeach
                            </select>
                            <div class="form-text">Select the column containing phone numbers</div>
                            @error('phone_column')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="name_column" class="form-label">
                                Name Column (Optional)
                            </label>
                            <select id="name_column" name="name_column" class="form-select">
                                <option value="">None</option>
                                @foreach($headers as $header)
                                    <option value="{{ $header }}">{{ $header }}</option>
                                @endforeach
                            </select>
                            <div class="form-text">Select the column containing contact names</div>
                            @error('name_column')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="notes" class="form-label">
                                Notes (Optional)
                            </label>
                            <textarea id="notes" name="notes" rows="3" class="form-control" placeholder="Add any notes about this file..."></textarea>
                            @error('notes')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-warning">
                            <h6 class="alert-heading mb-2">Phone Number Normalization:</h6>
                            <p class="mb-0">
                                All phone numbers will be automatically normalized to UAE format (+971).
                                Numbers starting with "05" will be converted to "+9715".
                            </p>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('files.create') }}" class="btn btn-secondary">
                                <i class="bx bx-arrow-back me-1"></i> Back
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-save me-1"></i> Save File
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
