<x-app-layout>
    @section('title', 'Dashboard')

    <div class="row">
        <!-- Welcome Card -->
        <div class="col-xxl-8 mb-6 order-0">
            <div class="card">
                <div class="d-flex align-items-start row">
                    <div class="col-sm-7">
                        <div class="card-body">
                            <h5 class="card-title text-primary mb-3">Welcome {{ Auth::user()->name }}! 🎉</h5>
                            <p class="mb-6">
                                You have {{ number_format($stats['total_files']) }} files with {{ number_format($stats['total_contacts']) }} contacts.<br />Check your statistics below.
                            </p>
                            <a href="{{ route('files.create') }}" class="btn btn-sm btn-outline-primary">Upload File</a>
                        </div>
                    </div>
                    <div class="col-sm-5 text-center text-sm-left">
                        <div class="card-body pb-0 px-0 px-md-6">
                            <img src="{{ asset('assets/img/illustrations/man-with-laptop.png') }}" height="175" alt="View Badge User" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="col-xxl-4 col-lg-12 col-md-4 order-1">
            <div class="row">
                <!-- Total Files Card -->
                <div class="col-lg-6 col-md-12 col-6 mb-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between mb-4">
                                <div class="avatar flex-shrink-0">
                                    <img src="{{ asset('assets/img/icons/unicons/chart-success.png') }}" alt="chart success" class="rounded" />
                                </div>
                            </div>
                            <p class="mb-1">Total Files</p>
                            <h4 class="card-title mb-3">{{ number_format($stats['total_files']) }}</h4>
                            <small class="text-success fw-medium"><i class="icon-base bx bx-up-arrow-alt"></i> Files Uploaded</small>
                        </div>
                    </div>
                </div>

                <!-- Total Contacts Card -->
                <div class="col-lg-6 col-md-12 col-6 mb-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between mb-4">
                                <div class="avatar flex-shrink-0">
                                    <img src="{{ asset('assets/img/icons/unicons/wallet-info.png') }}" alt="wallet info" class="rounded" />
                                </div>
                            </div>
                            <p class="mb-1">Total Contacts</p>
                            <h4 class="card-title mb-3">{{ number_format($stats['total_contacts']) }}</h4>
                            <small class="text-success fw-medium"><i class="icon-base bx bx-up-arrow-alt"></i> Contacts Added</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row">
        <!-- Files & Contacts Chart -->
        <div class="col-12 col-lg-8 order-2 order-md-3 order-lg-2 mb-6">
            <div class="card">
                <div class="row row-bordered g-0">
                    <div class="col-md-12">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="card-title m-0">Files & Contacts Overview</h5>
                        </div>
                        <div id="filesContactsChart"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Connection Status Cards -->
        <div class="col-12 col-md-8 col-lg-4 order-3 order-md-2">
            <div class="row">
                <!-- HighLevel Connection Card -->
                <div class="col-6 mb-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between mb-4">
                                <div class="avatar flex-shrink-0">
                                    <img src="{{ asset('assets/img/icons/unicons/paypal.png') }}" alt="HighLevel" class="rounded" />
                                </div>
                            </div>
                            <p class="mb-1">HighLevel</p>
                            <h4 class="card-title mb-3">{{ $stats['highlevel_connected'] ? 'Connected' : 'Not Connected' }}</h4>
                            @if(!$stats['highlevel_connected'])
                                <small class="text-danger fw-medium"><a href="{{ route('settings.index') }}">Connect now</a></small>
                            @else
                                <small class="text-success fw-medium"><i class="icon-base bx bx-check-circle"></i> Ready</small>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- External API Connection Card -->
                <div class="col-6 mb-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between mb-4">
                                <div class="avatar flex-shrink-0">
                                    <img src="{{ asset('assets/img/icons/unicons/cc-primary.png') }}" alt="External API" class="rounded" />
                                </div>
                            </div>
                            <p class="mb-1">External API</p>
                            <h4 class="card-title mb-3">{{ $stats['external_api_connected'] ? 'Connected' : 'Not Connected' }}</h4>
                            @if(!$stats['external_api_connected'])
                                <small class="text-danger fw-medium"><a href="{{ route('settings.index') }}">Connect now</a></small>
                            @else
                                <small class="text-success fw-medium"><i class="icon-base bx bx-check-circle"></i> Ready</small>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Activity Chart -->
                <div class="col-12 mb-6">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="card-title m-0">Activity Distribution</h5>
                        </div>
                        <div class="card-body">
                            <div id="activityChart"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Files Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title m-0">Recent Files</h5>
                    <a href="{{ route('files.index') }}" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body">
                    @if($recentFiles->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Filename</th>
                                        <th>Rows</th>
                                        <th>Size</th>
                                        <th>Uploaded</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentFiles as $file)
                                        <tr>
                                            <td><strong>{{ $file->original_filename }}</strong></td>
                                            <td><span class="badge bg-label-primary">{{ number_format($file->row_count) }}</span></td>
                                            <td>{{ $file->formatted_file_size }}</td>
                                            <td>{{ $file->created_at->diffForHumans() }}</td>
                                            <td>
                                                <a href="{{ route('files.preview', $file) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="bx bx-show me-1"></i> Preview
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="bx bx-file bx-lg text-muted"></i>
                            </div>
                            <h5 class="mb-2">No files yet</h5>
                            <p class="text-muted mb-4">Get started by uploading your first file.</p>
                            <a href="{{ route('files.create') }}" class="btn btn-primary">
                                <i class="bx bx-plus me-1"></i> Upload File
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- ApexCharts Library -->
    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />
    @endpush

    @push('scripts')
        <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
        <script>
            // Files & Contacts Chart
            const filesContactsChartEl = document.querySelector('#filesContactsChart');
            if (filesContactsChartEl) {
                const filesContactsChart = new ApexCharts(filesContactsChartEl, {
                    series: [{
                        name: 'Files',
                        data: [12, 19, 8, 15, {{ $stats['total_files'] }}]
                    }, {
                        name: 'Contacts',
                        data: [450, 680, 320, 550, {{ $stats['total_contacts'] }}]
                    }],
                    chart: {
                        height: 300,
                        type: 'bar',
                        toolbar: {
                            show: false
                        }
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: '55%',
                            borderRadius: 8
                        },
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        show: true,
                        width: 2,
                        colors: ['transparent']
                    },
                    xaxis: {
                        categories: ['Week 1', 'Week 2', 'Week 3', 'Week 4', 'This Week'],
                    },
                    yaxis: {
                        title: {
                            text: 'Count'
                        }
                    },
                    fill: {
                        opacity: 1
                    },
                    tooltip: {
                        y: {
                            formatter: function (val) {
                                return val
                            }
                        }
                    },
                    colors: ['#696cff', '#71dd37']
                });
                filesContactsChart.render();
            }

            // Activity Distribution Chart
            const activityChartEl = document.querySelector('#activityChart');
            if (activityChartEl) {
                const activityChart = new ApexCharts(activityChartEl, {
                    series: [{{ $stats['total_files'] }}, {{ $stats['total_contacts'] }}, 25, 15],
                    chart: {
                        height: 240,
                        type: 'donut',
                    },
                    labels: ['Files Uploaded', 'Contacts Added', 'API Imports', 'Manual Entries'],
                    colors: ['#696cff', '#71dd37', '#03c3ec', '#ffab00'],
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '70%'
                            }
                        }
                    },
                    legend: {
                        show: true,
                        position: 'bottom'
                    }
                });
                activityChart.render();
            }
        </script>
    @endpush
</x-app-layout>
