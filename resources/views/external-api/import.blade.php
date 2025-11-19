@extends('layouts.app')

@section('title', 'Import from External API')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Breadcrumb Header -->
    <h4 class="fw-bold mb-4">
        <span class="text-muted fw-light">External API /</span> Import
    </h4>

    <!-- Top Action Bar -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">Import from External API</h5>
                </div>
                <a href="{{ route('settings.index') }}" class="btn btn-secondary">
                    <i class='bx bx-cog me-1'></i>
                    Manage API Connection
                </a>
            </div>
        </div>
    </div>

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

    <!-- Import Section -->
    <div class="card">
        <div class="card-body">
            <h5 class="card-title mb-3">Fetch Client List from Your API</h5>
            <p class="card-text text-muted mb-4">
                Click the button below to fetch your client list from the connected external API.
                The data will be previewed before creating a file.
            </p>

            <!-- API Information -->
            <div class="alert alert-info mb-4">
                <h6 class="alert-heading mb-2">Connected API:</h6>
                <div class="mb-2">
                    <strong>Endpoint:</strong>
                    <span class="ms-2" style="word-break: break-all;">{{ auth()->user()->external_api_url }}</span>
                </div>
                <div class="mb-0">
                    <strong>Last Connected:</strong>
                    <span class="ms-2">{{ auth()->user()->external_api_connected_at?->format('M d, Y H:i') }}</span>
                </div>
            </div>

            <!-- Fetch Button -->
            <form action="{{ route('external-api.preview') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-success btn-lg">
                    <i class='bx bx-download me-2'></i>
                    Fetch Clients from API
                </button>
            </form>

            <!-- Instructions -->
            <hr class="my-4">

            <h6 class="mb-3">What Happens Next:</h6>
            <ol class="mb-4">
                <li class="mb-2">We'll fetch the client list from your API endpoint</li>
                <li class="mb-2">Phone numbers will be validated and normalized to UAE format (+971)</li>
                <li class="mb-2">Invalid contacts (missing phone numbers) will be identified</li>
                <li class="mb-2">You'll preview the data before confirming the import</li>
                <li class="mb-2">A new file will be created with the imported clients</li>
                <li class="mb-2">You can then use this file for contact imports or campaigns</li>
            </ol>

            <div class="alert alert-secondary">
                <p class="mb-0">
                    <strong>Note:</strong> This process may take a few moments depending on the size of your client list.
                    The import will automatically skip any contacts without valid phone numbers.
                </p>
            </div>

            <!-- Troubleshooting -->
            <hr class="my-4">

            <h6 class="mb-3">Troubleshooting:</h6>
            <div class="mb-2">
                <strong>Connection failed?</strong> Test your API connection in <a href="{{ route('settings.index') }}" class="link-primary">Settings</a>
            </div>
            <div class="mb-2">
                <strong>No clients found?</strong> Ensure your API returns data in the correct JSON format
            </div>
            <div>
                <strong>Unexpected structure?</strong> Check that your API response matches the required format in Settings
            </div>
        </div>
    </div>
</div>
@endsection
