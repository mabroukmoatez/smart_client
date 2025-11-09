<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Contact Import Jobs') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-800 rounded-md p-4 mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Quick Actions -->
            <div class="bg-white shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold">All Import Jobs</h3>
                        <a href="{{ route('contact-import.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            New Import
                        </a>
                    </div>
                </div>
            </div>

            <!-- Import Jobs List -->
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($importJobs->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progress</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Contacts</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Imported</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Failed</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($importJobs as $job)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3">
                                                <div class="text-sm font-medium text-gray-900">{{ $job->name }}</div>
                                                @if($job->description)
                                                    <div class="text-xs text-gray-500">{{ Str::limit($job->description, 40) }}</div>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3">
                                                @if($job->status === 'pending')
                                                    <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                                @elseif($job->status === 'processing')
                                                    <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800 animate-pulse">Processing</span>
                                                @elseif($job->status === 'completed')
                                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Completed</span>
                                                @elseif($job->status === 'failed')
                                                    <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Failed</span>
                                                @elseif($job->status === 'cancelled')
                                                    <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">Cancelled</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="flex items-center">
                                                    <div class="w-32 bg-gray-200 rounded-full h-2 mr-2">
                                                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $job->completion_percentage }}%"></div>
                                                    </div>
                                                    <span class="text-xs text-gray-600">{{ number_format($job->completion_percentage, 1) }}%</span>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-700">{{ number_format($job->total_contacts) }}</td>
                                            <td class="px-4 py-3">
                                                <span class="text-sm font-medium text-green-600">{{ number_format($job->total_imported) }}</span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="text-sm font-medium text-red-600">{{ number_format($job->total_failed) }}</span>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-500">
                                                {{ $job->created_at->format('M d, Y H:i') }}
                                            </td>
                                            <td class="px-4 py-3">
                                                <a href="{{ route('contact-import.show', $job) }}" class="text-blue-600 hover:text-blue-900 text-sm">
                                                    View Details
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $importJobs->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No import jobs</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by creating your first import.</p>
                            <div class="mt-6">
                                <a href="{{ route('contact-import.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Create Import
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
