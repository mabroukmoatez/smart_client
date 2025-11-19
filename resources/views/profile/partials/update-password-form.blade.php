<section>
    <header>
        <h5 class="fw-medium">Update Password</h5>
        <p class="text-muted small mt-1">Ensure your account is using a long, random password to stay secure.</p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-4">
        @csrf
        @method('put')

        <div class="mb-3">
            <label for="current_password" class="form-label">Current Password</label>
            <input id="current_password" name="current_password" type="password" required class="form-control" />
            @error('current_password')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">New Password</label>
            <input id="password" name="password" type="password" required class="form-control" />
            @error('password')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input id="password_confirmation" name="password_confirmation" type="password" required class="form-control" />
        </div>

        <div class="d-flex align-items-center gap-3">
            <button type="submit" class="btn btn-primary">
                <i class='bx bx-save me-1'></i>
                Save
            </button>
            @if (session('status') === 'password-updated')
                <span class="text-success small">Saved.</span>
            @endif
        </div>
    </form>
</section>
