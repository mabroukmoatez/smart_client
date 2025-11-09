<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $campaign->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Campaign Info -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Campaign Details</h3>
                            <dl class="space-y-2">
                                <div>
                                    <dt class="text-sm text-gray-600">Status</dt>
                                    <dd>
                                        <span class="px-2 py-1 text-xs rounded-full
                                            @if($campaign->status === 'completed') bg-green-100 text-green-800
                                            @elseif($campaign->status === 'processing') bg-blue-100 text-blue-800
                                            @elseif($campaign->status === 'failed') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ ucfirst($campaign->status) }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm text-gray-600">Template</dt>
                                    <dd class="text-sm font-medium">{{ $campaign->template_name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm text-gray-600">Scheduled At</dt>
                                    <dd class="text-sm">{{ $campaign->scheduled_at?->format('M d, Y H:i') }}</dd>
                                </div>
                                @if($campaign->started_at)
                                <div>
                                    <dt class="text-sm text-gray-600">Started At</dt>
                                    <dd class="text-sm">{{ $campaign->started_at->format('M d, Y H:i') }}</dd>
                                </div>
                                @endif
                                @if($campaign->completed_at)
                                <div>
                                    <dt class="text-sm text-gray-600">Completed At</dt>
                                    <dd class="text-sm">{{ $campaign->completed_at->format('M d, Y H:i') }}</dd>
                                </div>
                                @endif
                            </dl>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Statistics</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-blue-50 rounded-lg p-4">
                                    <div class="text-sm text-blue-600">Total Recipients</div>
                                    <div class="text-2xl font-bold text-blue-700">{{ number_format($stats['total']) }}</div>
                                </div>
                                <div class="bg-green-50 rounded-lg p-4">
                                    <div class="text-sm text-green-600">Sent</div>
                                    <div class="text-2xl font-bold text-green-700">{{ number_format($stats['sent']) }}</div>
                                </div>
                                <div class="bg-red-50 rounded-lg p-4">
                                    <div class="text-sm text-red-600">Failed</div>
                                    <div class="text-2xl font-bold text-red-700">{{ number_format($stats['failed']) }}</div>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="text-sm text-gray-600">Pending</div>
                                    <div class="text-2xl font-bold text-gray-700">{{ number_format($stats['pending']) }}</div>
                                </div>
                            </div>
                            <div class="mt-4">
                                <div class="text-sm text-gray-600 mb-1">Progress: {{ $stats['completion_percentage'] }}%</div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $stats['completion_percentage'] }}%"></div>
                                </div>
                            </div>
                            <div class="mt-4">
                                <div class="text-sm text-gray-600 mb-1">Success Rate: {{ $stats['success_rate'] }}%</div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-600 h-2 rounded-full" style="width: {{ $stats['success_rate'] }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(in_array($campaign->status, ['draft', 'scheduled']))
                        <div class="mt-6">
                            <form action="{{ route('automation.cancel', $campaign) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this campaign?')">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                                    Cancel Campaign
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Message Logs -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Recent Messages</h3>
                    @if($recentLogs->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Phone</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Sent At</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Error</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($recentLogs as $log)
                                        <tr>
                                            <td class="px-4 py-2 text-sm">{{ $log->recipient_phone }}</td>
                                            <td class="px-4 py-2 text-sm">{{ $log->recipient_name ?: '-' }}</td>
                                            <td class="px-4 py-2">
                                                <span class="px-2 py-1 text-xs rounded-full
                                                    @if($log->status === 'sent') bg-green-100 text-green-800
                                                    @elseif($log->status === 'failed') bg-red-100 text-red-800
                                                    @else bg-gray-100 text-gray-800
                                                    @endif">
                                                    {{ ucfirst($log->status) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-2 text-sm">{{ $log->sent_at?->format('M d, H:i') ?: '-' }}</td>
                                            <td class="px-4 py-2 text-sm text-red-600">
                                                @if($log->error_message)
                                                    <span title="{{ $log->error_message }}">{{ Str::limit($log->error_message, 30) }}</span>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500">No messages yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
