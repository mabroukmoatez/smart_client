<x-guest-layout>
    @section('title', 'Login')

    <h4 class="mb-1">Welcome! 👋</h4>
    <p class="mb-6">Please sign-in to your account and start the adventure</p>

    <!-- Session Status -->
    @if (session('status'))
        <div class="alert alert-info mb-4">
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

    <form id="formAuthentication" class="mb-6" action="{{ route('login') }}" method="POST">
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

        <div class="mb-6 form-password-toggle">
            <label class="form-label" for="password">Password</label>
            <div class="input-group input-group-merge">
                <input
                    type="password"
                    id="password"
                    class="form-control"
                    name="password"
                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                    aria-describedby="password"
                    required />
                <span class="input-group-text cursor-pointer"><i class="icon-base bx bx-hide"></i></span>
            </div>
        </div>

        <div class="mb-8">
            <div class="d-flex justify-content-between">
                <div class="form-check mb-0">
                    <input class="form-check-input" type="checkbox" id="remember_me" name="remember" />
                    <label class="form-check-label" for="remember_me"> Remember Me </label>
                </div>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}">
                        <span>Forgot Password?</span>
                    </a>
                @endif
            </div>
        </div>

        <div class="mb-6">
            <button class="btn btn-primary d-grid w-100" type="submit">Login</button>
        </div>
    </form>

    <p class="text-center">
        <span>New on our platform?</span>
        @if (Route::has('register'))
            <a href="{{ route('register') }}">
                <span>Create an account</span>
            </a>
        @endif
    </p>
</x-guest-layout>
