<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">Update Password</h2>
        <p class="mt-1 text-sm text-gray-600">Ensure your account is using a long, random password to stay secure.</p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <label for="current_password" class="block font-medium text-sm text-gray-700">Current Password</label>
            <input id="current_password" name="current_password" type="password" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
            @error('current_password')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="password" class="block font-medium text-sm text-gray-700">New Password</label>
            <input id="password" name="password" type="password" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
            @error('password')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="password_confirmation" class="block font-medium text-sm text-gray-700">Confirm Password</label>
            <input id="password_confirmation" name="password_confirmation" type="password" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Save</button>
            @if (session('status') === 'password-updated')
                <p class="text-sm text-gray-600">Saved.</p>
            @endif
        </div>
    </form>
</section>
