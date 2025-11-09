<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Campaigns') }}
            </h2>
            <a href="{{ route('automation.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                Create New Campaign
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-800 rounded-md p-4 mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if($campaigns->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Campaign Name
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Template
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Scheduled
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Progress
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($campaigns as $campaign)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $campaign->name }}
                                                </div>
                                                @if($campaign->description)
                                                    <div class="text-xs text-gray-500">
                                                        {{ Str::limit($campaign->description, 50) }}
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-4 py-4">
                                                <span class="px-2 py-1 text-xs rounded-full
                                                    @if($campaign->status === 'completed') bg-green-100 text-green-800
                                                    @elseif($campaign->status === 'processing') bg-blue-100 text-blue-800
                                                    @elseif($campaign->status === 'scheduled') bg-purple-100 text-purple-800
                                                    @elseif($campaign->status === 'failed') bg-red-100 text-red-800
                                                    @elseif($campaign->status === 'cancelled') bg-gray-100 text-gray-800
                                                    @else bg-yellow-100 text-yellow-800
                                                    @endif">
                                                    {{ ucfirst($campaign->status) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-4 text-sm text-gray-900">
                                                {{ $campaign->template_name }}
                                            </td>
                                            <td class="px-4 py-4 text-sm text-gray-500">
                                                {{ $campaign->scheduled_at?->format('M d, Y H:i') }}
                                            </td>
                                            <td class="px-4 py-4">
                                                <div class="text-xs text-gray-600 mb-1">
                                                    {{ $campaign->total_sent }}/{{ $campaign->total_recipients }}
                                                </div>
                                                <div class="w-24 bg-gray-200 rounded-full h-2">
                                                    <div class="bg-blue-600 h-2 rounded-full"
                                                         style="width: {{ $campaign->completion_percentage }}%"></div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-4 text-sm font-medium">
                                                <a href="{{ route('automation.show', $campaign) }}"
                                                   class="text-blue-600 hover:text-blue-900">
                                                    View Details
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6">
                            {{ $campaigns->links() }}
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No campaigns yet</h3>
                        <p class="mt-1 text-sm text-gray-500">Get started by creating your first WhatsApp campaign.</p>
                        <div class="mt-6">
                            <a href="{{ route('automation.create') }}"
                               class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                                Create Your First Campaign
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
