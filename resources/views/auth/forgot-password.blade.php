<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        Forgot your password? No problem. Just let us know your email address and we will email you a password reset link.
    </div>
    @if (session('status'))
        <div class="mb-4 font-medium text-sm text-green-600">{{ session('status') }}</div>
    @endif
    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div>
            <label for="email" class="block font-medium text-sm text-gray-700">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
            @error('email')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div class="flex items-center justify-end mt-4">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Email Password Reset Link</button>
        </div>
    </form>
</x-guest-layout>
