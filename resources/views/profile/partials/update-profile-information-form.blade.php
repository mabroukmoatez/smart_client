<section>
    <header>
        <h5 class="fw-medium">Profile Information</h5>
        <p class="text-muted small mt-1">Update your account's profile information and email address.</p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="mt-4">
        @csrf
        @method('patch')

        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autofocus class="form-control" />
            @error('name')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required class="form-control" />
            @error('email')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
        </div>

        <div class="d-flex align-items-center gap-3">
            <button type="submit" class="btn btn-primary">
                <i class='bx bx-save me-1'></i>
                Save
            </button>
            @if (session('status') === 'profile-updated')
                <span class="text-success small">Saved.</span>
            @endif
        </div>
    </form>
</section>
