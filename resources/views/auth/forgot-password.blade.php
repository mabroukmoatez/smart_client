<x-guest-layout>
    @section('title', 'Forgot Password')

    <h4 class="mb-1">Forgot Password? 🔒</h4>
    <p class="mb-6">Enter your email and we'll send you instructions to reset your password</p>

    <!-- Session Status -->
    @if (session('status'))
        <div class="alert alert-success mb-4">
            {{ session('status') }}
        </div>
    @endif

    <!-- Validation Errors -->
    @if ($errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="formAuthentication" class="mb-6" action="{{ route('password.email') }}" method="POST">
        @csrf

        <div class="mb-6">
            <label for="email" class="form-label">Email</label>
            <input
                type="email"
                class="form-control"
                id="email"
                name="email"
                value="{{ old('email') }}"
                placeholder="Enter your email"
                autofocus
                required />
        </div>

        <button class="btn btn-primary d-grid w-100 mb-6">Send Reset Link</button>
    </form>

    <div class="text-center">
        <a href="{{ route('login') }}" class="d-flex align-items-center justify-content-center">
            <i class="icon-base bx bx-chevron-left scaleX-n1-rtl me-1"></i>
            Back to login
        </a>
    </div>
</x-guest-layout>
