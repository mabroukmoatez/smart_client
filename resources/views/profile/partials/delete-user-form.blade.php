<section>
    <header>
        <h5 class="fw-medium">Delete Account</h5>
        <p class="text-muted small mt-1">
            Once your account is deleted, all of its resources and data will be permanently deleted.
        </p>
    </header>

    <form method="post" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Are you sure you want to delete your account?')" class="mt-4">
        @csrf
        @method('delete')

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input id="password" name="password" type="password" placeholder="Confirm your password" required class="form-control" />
            @error('password')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
        </div>

        <div>
            <button type="submit" class="btn btn-danger">
                <i class='bx bx-trash me-1'></i>
                Delete Account
            </button>
        </div>
    </form>
</section>
