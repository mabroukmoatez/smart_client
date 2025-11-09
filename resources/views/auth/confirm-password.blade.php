<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        This is a secure area of the application. Please confirm your password before continuing.
    </div>
    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf
        <div>
            <label for="password" class="block font-medium text-sm text-gray-700">Password</label>
            <input id="password" type="password" name="password" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
            @error('password')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div class="flex justify-end mt-4">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Confirm</button>
        </div>
    </form>
</x-guest-layout>
