<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-gradient-to-br from-purple-500 to-purple-600 overflow-hidden shadow-2xl rounded-2xl p-6 transform hover:scale-105 transition-all duration-300 animate-fadeInUp">
                    <div class="text-purple-100 text-sm font-semibold">Total Files</div>
                    <div class="text-4xl font-extrabold text-white mt-2">{{ number_format($stats['total_files']) }}</div>
                    <div class="text-xs text-purple-100 mt-2 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        Uploaded files
                    </div>
                </div>
                <div class="bg-gradient-to-br from-green-500 to-emerald-600 overflow-hidden shadow-2xl rounded-2xl p-6 transform hover:scale-105 transition-all duration-300 animate-fadeInUp" style="animation-delay: 0.1s">
                    <div class="text-green-100 text-sm font-semibold">Total Contacts</div>
                    <div class="text-4xl font-extrabold text-white mt-2">{{ number_format($stats['total_contacts']) }}</div>
                    <div class="text-xs text-green-100 mt-2 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Across all files
                    </div>
                </div>
                <div class="bg-gradient-to-br {{ $stats['highlevel_connected'] ? 'from-blue-500 to-cyan-600' : 'from-gray-400 to-gray-500' }} overflow-hidden shadow-2xl rounded-2xl p-6 transform hover:scale-105 transition-all duration-300 animate-fadeInUp" style="animation-delay: 0.2s">
                    <div class="text-white text-sm font-semibold">HighLevel</div>
                    @if($stats['highlevel_connected'])
                        <div class="text-3xl font-extrabold text-white mt-2">Connected</div>
                        <div class="text-xs text-blue-100 mt-2 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Ready to import
                        </div>
                    @else
                        <div class="text-3xl font-extrabold text-white mt-2">Not Connected</div>
                        <div class="text-xs text-gray-100 mt-2">
                            <a href="{{ route('settings.index') }}" class="underline hover:text-white transition-colors">Connect now →</a>
                        </div>
                    @endif
                </div>
                <div class="bg-gradient-to-br {{ $stats['external_api_connected'] ? 'from-pink-500 to-rose-600' : 'from-gray-400 to-gray-500' }} overflow-hidden shadow-2xl rounded-2xl p-6 transform hover:scale-105 transition-all duration-300 animate-fadeInUp" style="animation-delay: 0.3s">
                    <div class="text-white text-sm font-semibold">External API</div>
                    @if($stats['external_api_connected'])
                        <div class="text-3xl font-extrabold text-white mt-2">Connected</div>
                        <div class="text-xs text-pink-100 mt-2 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Ready to fetch
                        </div>
                    @else
                        <div class="text-3xl font-extrabold text-white mt-2">Not Connected</div>
                        <div class="text-xs text-gray-100 mt-2">
                            <a href="{{ route('settings.index') }}" class="underline hover:text-white transition-colors">Connect now →</a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white/80 backdrop-blur-lg overflow-hidden shadow-xl rounded-2xl mb-8 border border-purple-100">
                <div class="p-8">
                    <h3 class="text-2xl font-bold mb-6 bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">Quick Actions</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <a href="{{ route('files.create') }}" class="group flex items-center p-6 bg-gradient-to-br from-blue-50 to-purple-50 border-2 border-purple-200 rounded-2xl hover:shadow-2xl hover:scale-105 transition-all duration-300 hover:border-purple-400">
                            <div class="bg-gradient-to-br from-blue-500 to-purple-600 p-3 rounded-xl mr-4 group-hover:scale-110 transition-transform duration-300">
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
                            <a href="{{ route('external-api.index') }}" class="group flex items-center p-6 bg-gradient-to-br from-green-50 to-emerald-50 border-2 border-green-200 rounded-2xl hover:shadow-2xl hover:scale-105 transition-all duration-300 hover:border-green-400">
                                <div class="bg-gradient-to-br from-green-500 to-emerald-600 p-3 rounded-xl mr-4 group-hover:scale-110 transition-transform duration-300">
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

                        <a href="{{ route('settings.index') }}" class="group flex items-center p-6 bg-gradient-to-br from-purple-50 to-pink-50 border-2 border-purple-200 rounded-2xl hover:shadow-2xl hover:scale-105 transition-all duration-300 hover:border-purple-400">
                            <div class="bg-gradient-to-br from-purple-500 to-pink-600 p-3 rounded-xl mr-4 group-hover:scale-110 transition-transform duration-300">
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
            <div class="bg-white/80 backdrop-blur-lg overflow-hidden shadow-xl rounded-2xl border border-purple-100">
                <div class="p-8">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-2xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">Recent Files</h3>
                        <a href="{{ route('files.index') }}" class="text-sm font-semibold text-purple-600 hover:text-purple-800 transition-colors flex items-center">
                            View all
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>

                    @if($recentFiles->count() > 0)
                        <div class="overflow-x-auto rounded-xl">
                            <table class="min-w-full divide-y divide-purple-100">
                                <thead class="bg-gradient-to-r from-purple-50 to-pink-50">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-purple-700 uppercase tracking-wider">Filename</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-purple-700 uppercase tracking-wider">Rows</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-purple-700 uppercase tracking-wider">Size</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-purple-700 uppercase tracking-wider">Uploaded</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-purple-700 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-purple-50">
                                    @foreach($recentFiles as $file)
                                        <tr class="hover:bg-gradient-to-r hover:from-purple-50 hover:to-pink-50 transition-all duration-200">
                                            <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ $file->original_filename }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-700">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                    {{ number_format($file->row_count) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-700">{{ $file->formatted_file_size }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-500">{{ $file->created_at->diffForHumans() }}</td>
                                            <td class="px-6 py-4 text-sm">
                                                <a href="{{ route('files.preview', $file) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-lg hover:shadow-lg transition-all duration-300 transform hover:scale-105 font-semibold">
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
                            <div class="bg-gradient-to-br from-purple-100 to-pink-100 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="h-12 w-12 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">No files yet</h3>
                            <p class="text-gray-600 mb-6">Get started by uploading your first file.</p>
                            <div class="mt-6">
                                <a href="{{ route('files.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-full hover:shadow-2xl transition-all duration-300 transform hover:scale-110 font-bold">
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
</x-app-layout>
