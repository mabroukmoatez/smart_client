<x-guest-layout>
    @section('title', 'Verify Email')

    <h4 class="mb-1">Verify Your Email Address 📧</h4>
    <p class="mb-6">
        Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.
    </p>

    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success alert-dismissible mb-6" role="alert">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            A new verification link has been sent to the email address you provided during registration.
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn btn-primary">
                <i class="bx bx-envelope me-1"></i> Resend Verification Email
            </button>
        </form>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-secondary">
                <i class="bx bx-log-out me-1"></i> Log Out
            </button>
        </form>
    </div>
</x-guest-layout>
