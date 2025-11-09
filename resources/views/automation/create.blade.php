<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Campaign') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if(session('warning'))
                        <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4 mb-6">
                            <p class="text-sm text-yellow-800">{{ session('warning') }}</p>
                        </div>
                    @endif

                    <form action="{{ route('automation.store') }}" method="POST" id="campaignForm">
                        @csrf

                        <div class="mb-6">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Campaign Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="name" name="name" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                placeholder="e.g., Summer Promotion">
                            @error('name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Description
                            </label>
                            <textarea id="description" name="description" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                placeholder="Campaign description..."></textarea>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Select Files <span class="text-red-500">*</span>
                            </label>
                            @if($files->count() > 0)
                                <div class="space-y-2 max-h-60 overflow-y-auto border rounded-md p-4">
                                    @foreach($files as $file)
                                        <label class="flex items-center p-2 hover:bg-gray-50 rounded">
                                            <input type="checkbox" name="file_ids[]" value="{{ $file->id }}"
                                                class="file-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                            <span class="ml-3 text-sm">
                                                {{ $file->original_filename }}
                                                <span class="text-gray-500">({{ number_format($file->row_count) }} rows)</span>
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            @else
                                <div class="bg-gray-50 rounded-md p-4">
                                    <p class="text-sm text-gray-600">No files uploaded yet.</p>
                                    <a href="{{ route('files.create') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                                        Upload a file first →
                                    </a>
                                </div>
                            @endif
                            @error('file_ids')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="template_id" class="block text-sm font-medium text-gray-700 mb-2">
                                WhatsApp Template <span class="text-red-500">*</span>
                            </label>
                            <select id="template_id" name="template_id" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Select template...</option>
                                @foreach($templates as $template)
                                    <option value="{{ $template['id'] }}" data-name="{{ $template['name'] }}">
                                        {{ $template['name'] }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="hidden" id="template_name" name="template_name">
                            @error('template_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="scheduled_at" class="block text-sm font-medium text-gray-700 mb-2">
                                Schedule Date & Time <span class="text-red-500">*</span>
                            </label>
                            <input type="datetime-local" id="scheduled_at" name="scheduled_at" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                min="{{ now()->format('Y-m-d\TH:i') }}">
                            @error('scheduled_at')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div id="statistics" class="bg-gray-50 rounded-md p-4 mb-6 hidden">
                            <h4 class="text-sm font-medium text-gray-900 mb-3">Campaign Statistics:</h4>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div>
                                    <div class="text-xs text-gray-600">Total Recipients</div>
                                    <div class="text-lg font-semibold" id="stat-total">0</div>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-600">Valid Phones</div>
                                    <div class="text-lg font-semibold text-green-600" id="stat-valid">0</div>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-600">Invalid Phones</div>
                                    <div class="text-lg font-semibold text-red-600" id="stat-invalid">0</div>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-600">Files Selected</div>
                                    <div class="text-lg font-semibold" id="stat-files">0</div>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <a href="{{ route('automation.index') }}" class="text-gray-600 hover:text-gray-800">
                                Cancel
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                                Create Campaign
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Update template name when template is selected
        document.getElementById('template_id').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            document.getElementById('template_name').value = selectedOption.getAttribute('data-name') || '';
        });

        // Calculate statistics when files are selected
        document.querySelectorAll('.file-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', calculateStats);
        });

        function calculateStats() {
            const selectedFiles = Array.from(document.querySelectorAll('.file-checkbox:checked'))
                .map(cb => cb.value);

            if (selectedFiles.length === 0) {
                document.getElementById('statistics').classList.add('hidden');
                return;
            }

            fetch('{{ route('automation.calculate-stats') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ file_ids: selectedFiles })
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('stat-total').textContent = data.total_recipients.toLocaleString();
                document.getElementById('stat-valid').textContent = data.valid_phones.toLocaleString();
                document.getElementById('stat-invalid').textContent = data.invalid_phones.toLocaleString();
                document.getElementById('stat-files').textContent = data.files_count;
                document.getElementById('statistics').classList.remove('hidden');
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
</x-app-layout>
