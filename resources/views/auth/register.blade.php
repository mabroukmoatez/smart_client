<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div>
            <label for="name" class="block font-medium text-sm text-gray-700">Name</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
            @error('name')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div class="mt-4">
            <label for="email" class="block font-medium text-sm text-gray-700">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
            @error('email')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div class="mt-4">
            <label for="password" class="block font-medium text-sm text-gray-700">Password</label>
            <input id="password" type="password" name="password" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
            @error('password')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div class="mt-4">
            <label for="password_confirmation" class="block font-medium text-sm text-gray-700">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
        </div>
        <div class="flex items-center justify-end mt-4">
            <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-900">Already registered?</a>
            <button type="submit" class="ml-4 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Register</button>
        </div>
    </form>
</x-guest-layout>
