<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Map Columns') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900">File: {{ $filename }}</h3>
                        <p class="text-sm text-gray-600">Rows: {{ number_format($rowCount) }}</p>
                    </div>

                    <form action="{{ route('files.store') }}" method="POST">
                        @csrf

                        <div class="mb-6">
                            <label for="phone_column" class="block text-sm font-medium text-gray-700 mb-2">
                                Phone Number Column <span class="text-red-500">*</span>
                            </label>
                            <select id="phone_column" name="phone_column" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Select column...</option>
                                @foreach($headers as $header)
                                    <option value="{{ $header }}">{{ $header }}</option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-sm text-gray-500">Select the column containing phone numbers</p>
                            @error('phone_column')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="name_column" class="block text-sm font-medium text-gray-700 mb-2">
                                Name Column (Optional)
                            </label>
                            <select id="name_column" name="name_column" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">None</option>
                                @foreach($headers as $header)
                                    <option value="{{ $header }}">{{ $header }}</option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-sm text-gray-500">Select the column containing contact names</p>
                            @error('name_column')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                Notes (Optional)
                            </label>
                            <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Add any notes about this file..."></textarea>
                            @error('notes')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4 mb-6">
                            <h4 class="text-sm font-medium text-yellow-900 mb-2">Phone Number Normalization:</h4>
                            <p class="text-sm text-yellow-800">
                                All phone numbers will be automatically normalized to UAE format (+971).
                                Numbers starting with "05" will be converted to "+9715".
                            </p>
                        </div>

                        <div class="flex items-center justify-between">
                            <a href="{{ route('files.create') }}" class="text-gray-600 hover:text-gray-800">
                                Back
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                Save File
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
