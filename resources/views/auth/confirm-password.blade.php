<x-guest-layout>
    @section('title', 'Confirm Password')

    <h4 class="mb-1">Confirm Password 🔒</h4>
    <p class="mb-6">
        This is a secure area of the application. Please confirm your password before continuing.
    </p>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div class="mb-6">
            <label for="password" class="form-label">Password</label>
            <div class="input-group input-group-merge">
                <input id="password" type="password" name="password" required autofocus class="form-control" placeholder="Enter your password" />
                <span class="input-group-text cursor-pointer" onclick="togglePassword('password')">
                    <i class="bx bx-hide" id="password-icon"></i>
                </span>
            </div>
            @error('password')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary w-100">
            <i class="bx bx-check me-1"></i> Confirm
        </button>
    </form>

    @push('scripts')
    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = document.getElementById(fieldId + '-icon');
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('bx-hide');
                icon.classList.add('bx-show');
            } else {
                field.type = 'password';
                icon.classList.remove('bx-show');
                icon.classList.add('bx-hide');
            }
        }
    </script>
    @endpush
</x-guest-layout>
