@section('title', 'Settings')

<x-app-layout>
    <x-slot name="header">
        <h4 class="fw-bold mb-0"><span class="text-muted fw-light">Settings /</span> API Integration</h4>
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

        <!-- HighLevel API Integration -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title mb-4">HighLevel / LeadConnector Integration</h5>

                @if($isConnected)
                    <!-- Connected Status -->
                    <div class="alert alert-success d-flex align-items-center mb-4" role="alert">
                        <i class='bx bx-check-circle fs-4 me-2'></i>
                        <div>
                            <div class="fw-semibold">Connected to HighLevel</div>
                            <div class="small">
                                Connected on {{ $connectedAt?->format('M d, Y H:i') }}
                            </div>
                        </div>
                    </div>

                    <!-- Connection Details -->
                    <div class="mb-4">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label text-muted small">Location ID</label>
                                <div class="fw-medium">{{ $locationId }}</div>
                            </div>
                            <div class="col-12">
                                <label class="form-label text-muted small">API Token</label>
                                <div class="fw-medium">••••••••••••••••</div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="d-flex flex-wrap gap-2 mb-4">
                        <form action="{{ route('settings.test-connection') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary">
                                <i class='bx bx-test-tube me-1'></i>
                                Test Connection
                            </button>
                        </form>

                        <button onclick="document.getElementById('updateForm').classList.toggle('d-none')" class="btn btn-secondary">
                            <i class='bx bx-edit me-1'></i>
                            Update Credentials
                        </button>

                        <form action="{{ route('settings.disconnect') }}" method="POST" onsubmit="return confirm('Are you sure you want to disconnect?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class='bx bx-unlink me-1'></i>
                                Disconnect
                            </button>
                        </form>
                    </div>

                    <!-- Update Form (Hidden by default) -->
                    <div id="updateForm" class="d-none mt-4 pt-4 border-top">
                        <form action="{{ route('settings.store-credentials') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="api_token" class="form-label">
                                    Private Integration Token <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="api_token" name="api_token" required
                                    class="form-control"
                                    placeholder="Enter your HighLevel Private Integration token">
                            </div>

                            <div class="mb-3">
                                <label for="location_id" class="form-label">
                                    Location ID <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="location_id" name="location_id" required
                                    class="form-control"
                                    placeholder="Enter your HighLevel Location ID">
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-success">
                                    <i class='bx bx-save me-1'></i>
                                    Update & Test Connection
                                </button>
                                <button type="button" onclick="document.getElementById('updateForm').classList.add('d-none')" class="btn btn-label-secondary">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                @else
                    <!-- Not Connected -->
                    <div class="alert alert-warning d-flex align-items-center mb-4" role="alert">
                        <i class='bx bx-error fs-4 me-2'></i>
                        <div>
                            <div class="fw-semibold">Not Connected</div>
                            <div class="small">
                                @if($hasCredentials)
                                    Credentials are stored but not verified. Click "Test Connection" below to verify.
                                @else
                                    Connect your HighLevel account to start importing contacts
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($hasCredentials && $locationId)
                        <!-- Show existing credentials -->
                        <div class="mb-4">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label text-muted small">Location ID</label>
                                    <div class="fw-medium">{{ $locationId }}</div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label text-muted small">API Token</label>
                                    <div class="fw-medium">••••••••••••••••</div>
                                </div>
                            </div>
                        </div>

                        <!-- Test Connection Button -->
                        <div class="d-flex flex-wrap gap-2 mb-4">
                            <form action="{{ route('settings.test-connection') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary">
                                    <i class='bx bx-test-tube me-1'></i>
                                    Test Connection
                                </button>
                            </form>

                            <button onclick="document.getElementById('updateFormNotConnected').classList.toggle('d-none')" class="btn btn-secondary">
                                <i class='bx bx-edit me-1'></i>
                                Update Credentials
                            </button>
                        </div>

                        <!-- Update Form (Hidden by default) -->
                        <div id="updateFormNotConnected" class="d-none pt-4 border-top">
                            <form action="{{ route('settings.store-credentials') }}" method="POST">
                                @csrf

                                <div class="mb-3">
                                    <label for="api_token_update" class="form-label">
                                        Private Integration Token <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" id="api_token_update" name="api_token" required
                                        class="form-control"
                                        placeholder="Enter your HighLevel Private Integration token">
                                    <div class="form-text">Get this from HighLevel → Settings → Private Integrations</div>
                                </div>

                                <div class="mb-3">
                                    <label for="location_id_update" class="form-label">
                                        Location ID <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" id="location_id_update" name="location_id" required
                                        value="{{ old('location_id', $locationId) }}"
                                        class="form-control"
                                        placeholder="Enter your HighLevel Location ID">
                                    <div class="form-text">Find this in your HighLevel account settings</div>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class='bx bx-save me-1'></i>
                                        Update & Test Connection
                                    </button>
                                    <button type="button" onclick="document.getElementById('updateFormNotConnected').classList.add('d-none')" class="btn btn-label-secondary">
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                    @else
                        <!-- Connection Form -->
                        <form action="{{ route('settings.store-credentials') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="api_token" class="form-label">
                                    Private Integration Token <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="api_token" name="api_token" required
                                    class="form-control"
                                    placeholder="Enter your HighLevel Private Integration token">
                                <div class="form-text">Get this from HighLevel → Settings → Private Integrations</div>
                            </div>

                            <div class="mb-3">
                                <label for="location_id" class="form-label">
                                    Location ID <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="location_id" name="location_id" required
                                    class="form-control"
                                    placeholder="Enter your HighLevel Location ID">
                                <div class="form-text">Find this in your HighLevel account settings</div>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class='bx bx-plug me-1'></i>
                                Connect HighLevel Account
                            </button>
                        </form>
                    @endif
                @endif

                <!-- Setup Instructions -->
                <div class="mt-4 pt-4 border-top">
                    <h6 class="fw-semibold mb-3">How to Get Your API Credentials:</h6>
                    <ol class="small mb-0">
                        <li class="mb-2">Log in to your <a href="https://app.gohighlevel.com" target="_blank" class="link-primary">HighLevel account</a></li>
                        <li class="mb-2">Navigate to <strong>Settings</strong> → Scroll down to <strong>"Other Settings"</strong> → Click <strong>"Private Integrations"</strong></li>
                        <li class="mb-2">Click <strong>"Create new Integration"</strong></li>
                        <li class="mb-2">Give it a name (e.g., "Code Automation")</li>
                        <li class="mb-2">Select required scopes/permissions:
                            <ul class="mt-1">
                                <li>contacts.readonly</li>
                                <li>contacts.write</li>
                                <li>conversations.readonly</li>
                                <li>conversations.write</li>
                                <li>conversations/message.readonly</li>
                                <li>conversations/message.write</li>
                            </ul>
                        </li>
                        <li class="mb-2"><strong>Copy the token</strong> - you won't be able to see it again!</li>
                        <li class="mb-2">Get your <strong>Location ID</strong> from HighLevel settings</li>
                        <li class="mb-2">Paste both values above and click "Connect"</li>
                    </ol>

                    <div class="alert alert-primary mt-3 mb-0" role="alert">
                        <strong>Need help?</strong> Check out the
                        <a href="https://help.leadconnectorhq.com/support/solutions/articles/155000002774" target="_blank" class="alert-link">official HighLevel documentation</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- External API Integration -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title mb-2">External API Integration</h5>
                <p class="text-muted small mb-4">Connect your custom web API to import client lists directly into the platform.</p>

                @if($isExternalApiConnected)
                    <!-- Connected Status -->
                    <div class="alert alert-success d-flex align-items-center mb-4" role="alert">
                        <i class='bx bx-check-circle fs-4 me-2'></i>
                        <div>
                            <div class="fw-semibold">Connected to External API</div>
                            <div class="small">
                                Connected on {{ $externalApiConnectedAt?->format('M d, Y H:i') }}
                            </div>
                        </div>
                    </div>

                    <!-- Connection Details -->
                    <div class="mb-4">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label text-muted small">API URL</label>
                                <div class="fw-medium text-break">{{ $externalApiUrl }}</div>
                            </div>
                            <div class="col-12">
                                <label class="form-label text-muted small">API Token</label>
                                <div class="fw-medium">••••••••••••••••</div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="d-flex flex-wrap gap-2 mb-4">
                        <a href="{{ route('external-api.index') }}" class="btn btn-success">
                            <i class='bx bx-import me-1'></i>
                            Import Clients
                        </a>

                        <form action="{{ route('settings.test-external-api-connection') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-primary">
                                <i class='bx bx-test-tube me-1'></i>
                                Test Connection
                            </button>
                        </form>

                        <button onclick="document.getElementById('updateExternalForm').classList.toggle('d-none')" class="btn btn-secondary">
                            <i class='bx bx-edit me-1'></i>
                            Update Credentials
                        </button>

                        <form action="{{ route('settings.disconnect-external-api') }}" method="POST" onsubmit="return confirm('Are you sure you want to disconnect?')" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class='bx bx-unlink me-1'></i>
                                Disconnect
                            </button>
                        </form>
                    </div>

                    <!-- Update Form (Hidden by default) -->
                    <div id="updateExternalForm" class="d-none mt-4 pt-4 border-top">
                        <form action="{{ route('settings.store-external-api-credentials') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="external_api_url" class="form-label">
                                    API URL <span class="text-danger">*</span>
                                </label>
                                <input type="url" id="external_api_url" name="api_url" required
                                    value="{{ old('api_url') }}"
                                    class="form-control"
                                    placeholder="https://your-api.com/clients">
                            </div>

                            <div class="mb-3">
                                <label for="external_api_token" class="form-label">
                                    API Token <span class="text-danger">*</span>
                                </label>
                                <input type="password" id="external_api_token" name="api_token" required
                                    class="form-control"
                                    placeholder="Enter your API token">
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-success">
                                    <i class='bx bx-save me-1'></i>
                                    Update & Test Connection
                                </button>
                                <button type="button" onclick="document.getElementById('updateExternalForm').classList.add('d-none')" class="btn btn-label-secondary">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                @else
                    <!-- Not Connected -->
                    <div class="alert alert-warning d-flex align-items-center mb-4" role="alert">
                        <i class='bx bx-error fs-4 me-2'></i>
                        <div>
                            <div class="fw-semibold">Not Connected</div>
                            <div class="small">Connect your external API to import client lists</div>
                        </div>
                    </div>

                    <!-- Connection Form -->
                    <form action="{{ route('settings.store-external-api-credentials') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="api_url" class="form-label">
                                API URL <span class="text-danger">*</span>
                            </label>
                            <input type="url" id="api_url" name="api_url" required
                                value="{{ old('api_url') }}"
                                class="form-control"
                                placeholder="https://your-api.com/clients">
                            <div class="form-text">The full URL endpoint that returns your client list</div>
                        </div>

                        <div class="mb-3">
                            <label for="api_token" class="form-label">
                                API Token <span class="text-danger">*</span>
                            </label>
                            <input type="password" id="api_token" name="api_token" required
                                class="form-control"
                                placeholder="Enter your API token">
                            <div class="form-text">Bearer token for authentication (stored encrypted)</div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class='bx bx-plug me-1'></i>
                            Connect External API
                        </button>
                    </form>
                @endif

                <!-- API Requirements -->
                <div class="mt-4 pt-4 border-top">
                    <h6 class="fw-semibold mb-3">API Response Format Requirements:</h6>
                    <p class="small mb-3">Your API should return JSON in one of these formats:</p>

                    <div class="card bg-label-secondary mb-3">
                        <div class="card-body">
                            <p class="small fw-semibold mb-2">Option 1: With wrapper key</p>
                            <pre class="small mb-0"><code>{
  "clients": [
    {"phone": "+971501234567", "name": "John Doe", "email": "john@example.com"},
    {"phone": "+971509876543", "name": "Jane Smith"}
  ]
}</code></pre>
                        </div>
                    </div>

                    <div class="card bg-label-secondary mb-3">
                        <div class="card-body">
                            <p class="small fw-semibold mb-2">Option 2: Direct array</p>
                            <pre class="small mb-0"><code>[
  {"phone": "+971501234567", "name": "John Doe"},
  {"phone": "+971509876543", "name": "Jane Smith"}
]</code></pre>
                        </div>
                    </div>

                    <div class="alert alert-primary mb-0" role="alert">
                        <p class="small mb-2">
                            <strong>Supported field names:</strong> phone/mobile/telephone, name/full_name, email/email_address
                        </p>
                        <p class="small mb-0">
                            <strong>Note:</strong> Phone number is required. Contacts without valid phone numbers will be skipped.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
