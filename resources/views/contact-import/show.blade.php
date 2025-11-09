<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Import Job Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-800 rounded-md p-4 mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-800 rounded-md p-4 mb-6">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Job Header -->
            <div class="bg-white shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">{{ $importJob->name }}</h3>
                            @if($importJob->description)
                                <p class="mt-1 text-sm text-gray-600">{{ $importJob->description }}</p>
                            @endif
                            <div class="mt-2 flex items-center gap-4">
                                @if($importJob->status === 'pending')
                                    <span class="px-3 py-1 text-sm rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                @elseif($importJob->status === 'processing')
                                    <span class="px-3 py-1 text-sm rounded-full bg-blue-100 text-blue-800 animate-pulse">Processing</span>
                                @elseif($importJob->status === 'completed')
                                    <span class="px-3 py-1 text-sm rounded-full bg-green-100 text-green-800">Completed</span>
                                @elseif($importJob->status === 'failed')
                                    <span class="px-3 py-1 text-sm rounded-full bg-red-100 text-red-800">Failed</span>
                                @elseif($importJob->status === 'cancelled')
                                    <span class="px-3 py-1 text-sm rounded-full bg-gray-100 text-gray-800">Cancelled</span>
                                @endif

                                <span class="text-sm text-gray-500">Created {{ $importJob->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <a href="{{ route('contact-import.list') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                                Back to List
                            </a>
                            @if($importJob->status === 'processing' || $importJob->status === 'pending')
                                <form action="{{ route('contact-import.cancel', $importJob) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this import?')">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                                        Cancel Import
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-600 text-sm">Total Contacts</div>
                    <div class="text-3xl font-bold text-blue-600">{{ number_format($importJob->total_contacts) }}</div>
                    <div class="text-xs text-gray-500 mt-1">To be imported</div>
                </div>

                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-600 text-sm">Imported</div>
                    <div class="text-3xl font-bold text-green-600">{{ number_format($importJob->total_imported) }}</div>
                    <div class="text-xs text-gray-500 mt-1">Successfully imported</div>
                </div>

                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-600 text-sm">Failed</div>
                    <div class="text-3xl font-bold text-red-600">{{ number_format($importJob->total_failed) }}</div>
                    <div class="text-xs text-gray-500 mt-1">Import failures</div>
                </div>

                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-600 text-sm">Pending</div>
                    <div class="text-3xl font-bold text-yellow-600">{{ number_format($importJob->total_pending) }}</div>
                    <div class="text-xs text-gray-500 mt-1">In queue</div>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="bg-white shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Overall Progress</h3>
                    <div class="flex items-center">
                        <div class="flex-1 bg-gray-200 rounded-full h-4 mr-4">
                            <div class="bg-blue-600 h-4 rounded-full transition-all duration-500" style="width: {{ $importJob->completion_percentage }}%"></div>
                        </div>
                        <span class="text-lg font-semibold text-gray-700">{{ number_format($importJob->completion_percentage, 1) }}%</span>
                    </div>
                    @if($importJob->success_rate > 0)
                        <p class="mt-3 text-sm text-gray-600">
                            Success Rate: <span class="font-semibold text-green-600">{{ number_format($importJob->success_rate, 1) }}%</span>
                        </p>
                    @endif
                </div>
            </div>

            <!-- Import Details -->
            <div class="bg-white shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Import Details</h3>
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm text-gray-600">Selected Files</dt>
                            <dd class="mt-1 text-sm font-medium">
                                @if(count($importJob->uploadedFiles()) > 0)
                                    <ul class="list-disc list-inside">
                                        @foreach($importJob->uploadedFiles() as $file)
                                            <li>{{ $file->original_filename }} ({{ number_format($file->row_count) }} contacts)</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <span class="text-gray-500">No files</span>
                                @endif
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm text-gray-600">Tags Applied</dt>
                            <dd class="mt-1 text-sm font-medium">
                                @if(count($importJob->all_tags) > 0)
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($importJob->all_tags as $tag)
                                            <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">{{ $tag }}</span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-gray-500">No tags</span>
                                @endif
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm text-gray-600">Started At</dt>
                            <dd class="mt-1 text-sm font-medium">
                                {{ $importJob->started_at ? $importJob->started_at->format('M d, Y H:i:s') : 'Not started yet' }}
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm text-gray-600">Completed At</dt>
                            <dd class="mt-1 text-sm font-medium">
                                {{ $importJob->completed_at ? $importJob->completed_at->format('M d, Y H:i:s') : 'In progress' }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Contact Logs -->
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Contact Import Logs</h3>

                    @if($importJob->contactLogs->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Phone</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">HighLevel ID</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tags</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Time</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Error</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($importJob->contactLogs->take(100) as $log)
                                        <tr class="hover:bg-gray-50 {{ $log->status === 'failed' ? 'bg-red-50' : '' }}">
                                            <td class="px-4 py-3">
                                                @if($log->status === 'sent')
                                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Success</span>
                                                @else
                                                    <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Failed</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-900">{{ $log->contact_phone }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-700">{{ $log->contact_name ?? '-' }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-600 font-mono">{{ $log->highlevel_contact_id ?? '-' }}</td>
                                            <td class="px-4 py-3">
                                                @if($log->assigned_tags && count($log->assigned_tags) > 0)
                                                    <div class="flex flex-wrap gap-1">
                                                        @foreach($log->assigned_tags as $tag)
                                                            <span class="px-1 py-0.5 text-xs rounded bg-blue-100 text-blue-800">{{ $tag }}</span>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <span class="text-gray-400 text-xs">None</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-500">
                                                {{ $log->imported_at ? $log->imported_at->format('H:i:s') : '-' }}
                                            </td>
                                            <td class="px-4 py-3 text-sm text-red-600">
                                                {{ $log->error_message ? Str::limit($log->error_message, 50) : '-' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if($importJob->contactLogs->count() > 100)
                            <p class="mt-4 text-sm text-gray-600 text-center">
                                Showing first 100 logs of {{ number_format($importJob->contactLogs->count()) }}
                            </p>
                        @endif
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No logs yet</h3>
                            <p class="mt-1 text-sm text-gray-500">Import processing hasn't started or no contacts have been processed.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
