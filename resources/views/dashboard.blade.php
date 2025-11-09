<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-600 text-sm">Total Files</div>
                    <div class="text-3xl font-bold text-blue-600">{{ $stats['total_files'] }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-600 text-sm">Total Campaigns</div>
                    <div class="text-3xl font-bold text-green-600">{{ $stats['total_campaigns'] }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-600 text-sm">Messages Sent</div>
                    <div class="text-3xl font-bold text-purple-600">{{ $stats['total_messages_sent'] }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-600 text-sm">Active Campaigns</div>
                    <div class="text-3xl font-bold text-orange-600">{{ $stats['active_campaigns'] }}</div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Quick Actions</h3>
                    <div class="flex gap-4">
                        <a href="{{ route('files.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Upload File
                        </a>
                        <a href="{{ route('automation.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                            Create Campaign
                        </a>
                    </div>
                </div>
            </div>

            <!-- Recent Files -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Recent Files</h3>
                    @if($recentFiles->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Filename</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Rows</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Uploaded</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($recentFiles as $file)
                                        <tr>
                                            <td class="px-4 py-2">{{ $file->original_filename }}</td>
                                            <td class="px-4 py-2">{{ number_format($file->row_count) }}</td>
                                            <td class="px-4 py-2">{{ $file->created_at->diffForHumans() }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('files.index') }}" class="text-blue-600 hover:text-blue-800">View all files →</a>
                        </div>
                    @else
                        <p class="text-gray-500">No files uploaded yet.</p>
                    @endif
                </div>
            </div>

            <!-- Recent Campaigns -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Recent Campaigns</h3>
                    @if($recentCampaigns->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Scheduled</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Progress</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($recentCampaigns as $campaign)
                                        <tr>
                                            <td class="px-4 py-2">
                                                <a href="{{ route('automation.show', $campaign) }}" class="text-blue-600 hover:text-blue-800">
                                                    {{ $campaign->name }}
                                                </a>
                                            </td>
                                            <td class="px-4 py-2">
                                                <span class="px-2 py-1 text-xs rounded-full
                                                    @if($campaign->status === 'completed') bg-green-100 text-green-800
                                                    @elseif($campaign->status === 'processing') bg-blue-100 text-blue-800
                                                    @elseif($campaign->status === 'failed') bg-red-100 text-red-800
                                                    @else bg-gray-100 text-gray-800
                                                    @endif">
                                                    {{ ucfirst($campaign->status) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-2">{{ $campaign->scheduled_at?->format('M d, Y H:i') }}</td>
                                            <td class="px-4 py-2">{{ $campaign->completion_percentage }}%</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('automation.index') }}" class="text-blue-600 hover:text-blue-800">View all campaigns →</a>
                        </div>
                    @else
                        <p class="text-gray-500">No campaigns created yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
