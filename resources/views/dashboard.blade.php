<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <!-- Total Files Card -->
                <div class="bg-white overflow-hidden shadow-lg rounded-xl p-6 transform hover:scale-105 transition-all duration-300 animate-fadeInUp border-l-4 border-purple-500 hover:shadow-2xl">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-500 text-sm font-medium uppercase tracking-wider">Total Files</div>
                            <div class="text-4xl font-bold text-gray-800 mt-2">{{ number_format($stats['total_files']) }}</div>
                            <div class="text-xs text-gray-600 mt-2 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                                Uploaded files
                            </div>
                        </div>
                        <div class="bg-purple-100 p-4 rounded-lg">
                            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Contacts Card -->
                <div class="bg-white overflow-hidden shadow-lg rounded-xl p-6 transform hover:scale-105 transition-all duration-300 animate-fadeInUp border-l-4 border-emerald-500 hover:shadow-2xl" style="animation-delay: 0.1s">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-500 text-sm font-medium uppercase tracking-wider">Total Contacts</div>
                            <div class="text-4xl font-bold text-gray-800 mt-2">{{ number_format($stats['total_contacts']) }}</div>
                            <div class="text-xs text-gray-600 mt-2 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                Across all files
                            </div>
                        </div>
                        <div class="bg-emerald-100 p-4 rounded-lg">
                            <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- HighLevel Connection Card -->
                <div class="bg-white overflow-hidden shadow-lg rounded-xl p-6 transform hover:scale-105 transition-all duration-300 animate-fadeInUp border-l-4 {{ $stats['highlevel_connected'] ? 'border-blue-500' : 'border-gray-400' }} hover:shadow-2xl" style="animation-delay: 0.2s">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-500 text-sm font-medium uppercase tracking-wider">HighLevel</div>
                            @if($stats['highlevel_connected'])
                                <div class="text-3xl font-bold text-gray-800 mt-2">Connected</div>
                                <div class="text-xs text-gray-600 mt-2 flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Ready to import
                                </div>
                            @else
                                <div class="text-3xl font-bold text-gray-800 mt-2">Not Connected</div>
                                <div class="text-xs text-gray-600 mt-2">
                                    <a href="{{ route('settings.index') }}" class="text-blue-600 hover:text-blue-800 underline">Connect now →</a>
                                </div>
                            @endif
                        </div>
                        <div class="{{ $stats['highlevel_connected'] ? 'bg-blue-100' : 'bg-gray-100' }} p-4 rounded-lg">
                            <svg class="w-8 h-8 {{ $stats['highlevel_connected'] ? 'text-blue-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- External API Connection Card -->
                <div class="bg-white overflow-hidden shadow-lg rounded-xl p-6 transform hover:scale-105 transition-all duration-300 animate-fadeInUp border-l-4 {{ $stats['external_api_connected'] ? 'border-indigo-500' : 'border-gray-400' }} hover:shadow-2xl" style="animation-delay: 0.3s">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-500 text-sm font-medium uppercase tracking-wider">External API</div>
                            @if($stats['external_api_connected'])
                                <div class="text-3xl font-bold text-gray-800 mt-2">Connected</div>
                                <div class="text-xs text-gray-600 mt-2 flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Ready to fetch
                                </div>
                            @else
                                <div class="text-3xl font-bold text-gray-800 mt-2">Not Connected</div>
                                <div class="text-xs text-gray-600 mt-2">
                                    <a href="{{ route('settings.index') }}" class="text-indigo-600 hover:text-indigo-800 underline">Connect now →</a>
                                </div>
                            @endif
                        </div>
                        <div class="{{ $stats['external_api_connected'] ? 'bg-indigo-100' : 'bg-gray-100' }} p-4 rounded-lg">
                            <svg class="w-8 h-8 {{ $stats['external_api_connected'] ? 'text-indigo-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Files & Contacts Chart -->
                <div class="bg-white shadow-lg rounded-xl p-6 border border-gray-200 hover:shadow-xl transition-shadow duration-300">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Files & Contacts Overview
                    </h3>
                    <div class="relative" style="height: 280px;">
                        <canvas id="filesContactsChart"></canvas>
                    </div>
                </div>

                <!-- Activity Distribution Chart -->
                <div class="bg-white shadow-lg rounded-xl p-6 border border-gray-200 hover:shadow-xl transition-shadow duration-300">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                        </svg>
                        Activity Distribution
                    </h3>
                    <div class="relative" style="height: 280px;">
                        <canvas id="activityChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white overflow-hidden shadow-lg rounded-xl mb-8 border border-gray-200">
                <div class="p-8">
                    <h3 class="text-xl font-bold mb-6 text-gray-800">Quick Actions</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <a href="{{ route('files.create') }}" class="group flex items-center p-6 bg-gray-50 border-2 border-gray-200 rounded-xl hover:shadow-xl hover:scale-105 transition-all duration-300 hover:border-purple-400 hover:bg-purple-50">
                            <div class="bg-purple-500 p-3 rounded-lg mr-4 group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                            </div>
                            <div>
                                <div class="font-bold text-gray-800 text-lg">Upload File</div>
                                <div class="text-sm text-gray-600">Excel, CSV files</div>
                            </div>
                        </a>

                        @if($stats['external_api_connected'])
                            <a href="{{ route('external-api.index') }}" class="group flex items-center p-6 bg-gray-50 border-2 border-gray-200 rounded-xl hover:shadow-xl hover:scale-105 transition-all duration-300 hover:border-emerald-400 hover:bg-emerald-50">
                                <div class="bg-emerald-500 p-3 rounded-lg mr-4 group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-bold text-gray-800 text-lg">Import from API</div>
                                    <div class="text-sm text-gray-600">Fetch client list</div>
                                </div>
                            </a>
                        @endif

                        <a href="{{ route('settings.index') }}" class="group flex items-center p-6 bg-gray-50 border-2 border-gray-200 rounded-xl hover:shadow-xl hover:scale-105 transition-all duration-300 hover:border-blue-400 hover:bg-blue-50">
                            <div class="bg-blue-500 p-3 rounded-lg mr-4 group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div>
                                <div class="font-bold text-gray-800 text-lg">Settings</div>
                                <div class="text-sm text-gray-600">Connect integrations</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Recent Files -->
            <div class="bg-white overflow-hidden shadow-lg rounded-xl border border-gray-200">
                <div class="p-8">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold text-gray-800">Recent Files</h3>
                        <a href="{{ route('files.index') }}" class="text-sm font-semibold text-purple-600 hover:text-purple-800 transition-colors flex items-center">
                            View all
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>

                    @if($recentFiles->count() > 0)
                        <div class="overflow-x-auto rounded-xl">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Filename</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Rows</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Size</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Uploaded</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($recentFiles as $file)
                                        <tr class="hover:bg-gray-50 transition-all duration-200">
                                            <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ $file->original_filename }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-700">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                    {{ number_format($file->row_count) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-700">{{ $file->formatted_file_size }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-500">{{ $file->created_at->diffForHumans() }}</td>
                                            <td class="px-6 py-4 text-sm">
                                                <a href="{{ route('files.preview', $file) }}" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 hover:shadow-lg transition-all duration-300 transform hover:scale-105 font-semibold">
                                                    Preview
                                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-16">
                            <div class="bg-gray-100 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">No files yet</h3>
                            <p class="text-gray-600 mb-6">Get started by uploading your first file.</p>
                            <div class="mt-6">
                                <a href="{{ route('files.create') }}" class="inline-flex items-center px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 hover:shadow-xl transition-all duration-300 transform hover:scale-110 font-bold">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Upload File
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js Library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <!-- Chart Scripts -->
    <script>
        // Files & Contacts Bar Chart
        const filesContactsCtx = document.getElementById('filesContactsChart').getContext('2d');
        new Chart(filesContactsCtx, {
            type: 'bar',
            data: {
                labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4', 'This Week'],
                datasets: [
                    {
                        label: 'Files Uploaded',
                        data: [12, 19, 8, 15, {{ $stats['total_files'] }}],
                        backgroundColor: 'rgba(147, 51, 234, 0.8)',
                        borderColor: 'rgba(147, 51, 234, 1)',
                        borderWidth: 2,
                        borderRadius: 8,
                        hoverBackgroundColor: 'rgba(147, 51, 234, 1)',
                    },
                    {
                        label: 'Contacts Imported',
                        data: [450, 680, 320, 550, {{ $stats['total_contacts'] }}],
                        backgroundColor: 'rgba(16, 185, 129, 0.8)',
                        borderColor: 'rgba(16, 185, 129, 1)',
                        borderWidth: 2,
                        borderRadius: 8,
                        hoverBackgroundColor: 'rgba(16, 185, 129, 1)',
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            font: {
                                size: 12,
                                weight: '600'
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        borderColor: 'rgba(255, 255, 255, 0.2)',
                        borderWidth: 1,
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 13
                        },
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.parsed.y.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            font: {
                                size: 11
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 11
                            }
                        }
                    }
                },
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
            }
        });

        // Activity Distribution Pie Chart
        const activityCtx = document.getElementById('activityChart').getContext('2d');
        new Chart(activityCtx, {
            type: 'doughnut',
            data: {
                labels: ['Files Uploaded', 'Contacts Added', 'API Imports', 'Manual Entries'],
                datasets: [{
                    label: 'Activity',
                    data: [{{ $stats['total_files'] }}, {{ $stats['total_contacts'] }}, 25, 15],
                    backgroundColor: [
                        'rgba(147, 51, 234, 0.8)',
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(99, 102, 241, 0.8)'
                    ],
                    borderColor: [
                        'rgba(147, 51, 234, 1)',
                        'rgba(16, 185, 129, 1)',
                        'rgba(59, 130, 246, 1)',
                        'rgba(99, 102, 241, 1)'
                    ],
                    borderWidth: 2,
                    hoverOffset: 15,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            font: {
                                size: 12,
                                weight: '600'
                            },
                            generateLabels: function(chart) {
                                const data = chart.data;
                                if (data.labels.length && data.datasets.length) {
                                    return data.labels.map((label, i) => {
                                        const value = data.datasets[0].data[i];
                                        return {
                                            text: `${label} (${value})`,
                                            fillStyle: data.datasets[0].backgroundColor[i],
                                            strokeStyle: data.datasets[0].borderColor[i],
                                            lineWidth: 2,
                                            hidden: false,
                                            index: i
                                        };
                                    });
                                }
                                return [];
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        borderColor: 'rgba(255, 255, 255, 0.2)',
                        borderWidth: 1,
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 13
                        },
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((value / total) * 100).toFixed(1);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                },
                cutout: '60%',
            }
        });
    </script>
</x-app-layout>
