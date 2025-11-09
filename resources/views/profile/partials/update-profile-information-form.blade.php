<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">Profile Information</h2>
        <p class="mt-1 text-sm text-gray-600">Update your account's profile information and email address.</p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <label for="name" class="block font-medium text-sm text-gray-700">Name</label>
            <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autofocus class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
            @error('name')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="email" class="block font-medium text-sm text-gray-700">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
            @error('email')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Save</button>
            @if (session('status') === 'profile-updated')
                <p class="text-sm text-gray-600">Saved.</p>
            @endif
        </div>
    </form>
</section>
