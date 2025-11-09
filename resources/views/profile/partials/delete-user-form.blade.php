<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">Delete Account</h2>
        <p class="mt-1 text-sm text-gray-600">
            Once your account is deleted, all of its resources and data will be permanently deleted.
        </p>
    </header>

    <form method="post" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Are you sure you want to delete your account?')">
        @csrf
        @method('delete')

        <div>
            <label for="password" class="block font-medium text-sm text-gray-700">Password</label>
            <input id="password" name="password" type="password" placeholder="Confirm your password" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
            @error('password')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div class="mt-6">
            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">Delete Account</button>
        </div>
    </form>
</section>
