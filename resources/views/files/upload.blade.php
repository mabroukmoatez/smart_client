<x-app-layout>
    @section('title', 'Upload File')

    <div class="d-flex justify-content-between align-items-center mb-6">
        <h4 class="fw-bold mb-0">
            <span class="text-muted fw-light">Files /</span> Upload File
        </h4>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('files.upload') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-6">
                            <label class="form-label">Upload Spreadsheet</label>
                            <div class="border border-2 border-dashed rounded p-4 text-center" style="border-color: #d9dee3;">
                                <div class="mb-3">
                                    <i class="bx bx-upload bx-lg text-muted"></i>
                                </div>
                                <div class="mb-2">
                                    <label for="file" class="btn btn-sm btn-primary">
                                        <span id="fileLabel">Choose file</span>
                                        <input id="file" name="file" type="file" class="d-none" accept=".xlsx,.xls,.csv" required>
                                    </label>
                                    <span class="text-muted ms-2">or drag and drop</span>
                                </div>
                                <p class="text-muted small mb-0">
                                    XLSX, XLS, or CSV up to {{ config('app.max_upload_size', 10240) / 1024 }}MB
                                </p>
                            </div>
                            @error('file')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-info">
                            <h6 class="alert-heading mb-2">File Requirements:</h6>
                            <ul class="mb-0 ps-3">
                                <li>Supported formats: .xlsx, .xls, .csv</li>
                                <li>Maximum file size: {{ config('app.max_upload_size', 10240) / 1024 }}MB</li>
                                <li>Maximum rows: {{ number_format(config('app.max_csv_rows', 50000)) }}</li>
                                <li>File should contain phone numbers and optionally names</li>
                            </ul>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('files.index') }}" class="btn btn-secondary">
                                <i class="bx bx-arrow-back me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                Next: Map Columns <i class="bx bx-arrow-right ms-1"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.getElementById('file').addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name;
            if (fileName) {
                document.getElementById('fileLabel').textContent = fileName;
            }
        });
    </script>
    @endpush
</x-app-layout>
