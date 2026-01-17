@extends('layouts.app')

@section('title', 'Grafik Kehadiran Anak')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-dashboard">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-bar-chart me-2"></i>
                    Grafik Kehadiran Anak
                </h5>
                <div>
                    <a href="{{ route('ortu.attendance.index') }}" class="btn btn-primary">
                        <i class="bi bi-calendar-check me-1"></i> Lihat Absensi
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="alert alert-info mb-4">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-info-circle me-2" style="font-size: 1.2rem;"></i>
                        <div>
                            <strong>Informasi:</strong> Anda sedang melihat grafik kehadiran untuk 
                            <strong>{{ $student->nama }}</strong> (Kelas: {{ $student->kelas }}, NIS: {{ $student->nis }})
                        </div>
                    </div>
                </div>

                <!-- Chart Type Selector -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card card-dashboard">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="bi bi-filter me-2"></i>
                                    Pilih Jenis Grafik
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                    <button type="button" class="btn btn-outline-primary active" data-chart-type="monthly">
                                        <i class="bi bi-calendar-month me-1"></i> Bulanan
                                    </button>
                                    <button type="button" class="btn btn-outline-primary" data-chart-type="weekly">
                                        <i class="bi bi-calendar-week me-1"></i> Mingguan
                                    </button>
                                    <button type="button" class="btn btn-outline-primary" data-chart-type="distribution">
                                        <i class="bi bi-pie-chart me-1"></i> Distribusi
                                    </button>
                                    <button type="button" class="btn btn-outline-primary" data-chart-type="trend">
                                        <i class="bi bi-graph-up me-1"></i> Tren
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Monthly Chart -->
                <div class="row mb-4 chart-container" id="monthly-chart">
                    <div class="col-12">
                        <div class="card card-dashboard">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="bi bi-calendar-month me-2"></i>
                                    Kehadiran Bulan Ini ({{ \Carbon\Carbon::now()->locale('id')->monthName }})
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="chart-wrapper">
                                    <canvas id="monthlyAttendanceChart" height="100"></canvas>
                                </div>
                                <div class="mt-3">
                                    <div class="row">
                                        <div class="col-md-3 col-sm-6">
                                            <div class="d-flex align-items-center mb-2">
                                                <div class="color-indicator bg-success me-2" style="width: 20px; height: 20px;"></div>
                                                <span>Hadir Tepat Waktu</span>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-6">
                                            <div class="d-flex align-items-center mb-2">
                                                <div class="color-indicator bg-warning me-2" style="width: 20px; height: 20px;"></div>
                                                <span>Terlambat</span>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-6">
                                            <div class="d-flex align-items-center mb-2">
                                                <div class="color-indicator bg-danger me-2" style="width: 20px; height: 20px;"></div>
                                                <span>Tidak Hadir</span>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-6">
                                            <div class="d-flex align-items-center mb-2">
                                                <div class="color-indicator bg-info me-2" style="width: 20px; height: 20px;"></div>
                                                <span>Izin</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Weekly Chart -->
                <div class="row mb-4 chart-container d-none" id="weekly-chart">
                    <div class="col-12">
                        <div class="card card-dashboard">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="bi bi-calendar-week me-2"></i>
                                    Kehadiran Minggu Ini
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="chart-wrapper">
                                    <canvas id="weeklyAttendanceChart" height="100"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Distribution Chart -->
                <div class="row mb-4 chart-container d-none" id="distribution-chart">
                    <div class="col-12">
                        <div class="card card-dashboard">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="bi bi-pie-chart me-2"></i>
                                    Distribusi Kehadiran (30 Hari Terakhir)
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="chart-wrapper">
                                            <canvas id="distributionChart" height="250"></canvas>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Status</th>
                                                        <th>Jumlah Hari</th>
                                                        <th>Persentase</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $totalDays = array_sum($statusDistribution);
                                                    @endphp
                                                    @foreach($statusDistribution as $status => $count)
                                                        @php
                                                            $percentage = $totalDays > 0 ? round(($count / $totalDays) * 100, 1) : 0;
                                                        @endphp
                                                        <tr>
                                                            <td>
                                                                @if($status == 'Hadir Tepat Waktu')
                                                                    <span class="badge bg-success">{{ $status }}</span>
                                                                @elseif($status == 'Terlambat')
                                                                    <span class="badge bg-warning">{{ $status }}</span>
                                                                @elseif($status == 'Izin')
                                                                    <span class="badge bg-info">{{ $status }}</span>
                                                                @else
                                                                    <span class="badge bg-danger">{{ $status }}</span>
                                                                @endif
                                                            </td>
                                                            <td>{{ $count }} hari</td>
                                                            <td>
                                                                <div class="progress" style="height: 20px;">
                                                                    <div class="progress-bar 
                                                                        @if($status == 'Hadir Tepat Waktu') bg-success
                                                                        @elseif($status == 'Terlambat') bg-warning
                                                                        @elseif($status == 'Izin') bg-info
                                                                        @else bg-danger
                                                                        @endif" 
                                                                        role="progressbar" 
                                                                        style="width: {{ $percentage }}%"
                                                                        aria-valuenow="{{ $percentage }}" 
                                                                        aria-valuemin="0" 
                                                                        aria-valuemax="100">
                                                                        {{ $percentage }}%
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Trend Chart -->
                <div class="row mb-4 chart-container d-none" id="trend-chart">
                    <div class="col-12">
                        <div class="card card-dashboard">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="bi bi-graph-up me-2"></i>
                                    Tren Kehadiran (6 Bulan Terakhir)
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="chart-wrapper">
                                    <canvas id="trendChart" height="100"></canvas>
                                </div>
                                <div class="mt-3">
                                    <div class="alert alert-info">
                                        <i class="bi bi-info-circle me-2"></i>
                                        <strong>Analisis Tren:</strong> Grafik menunjukkan persentase kehadiran per bulan. 
                                        Target ideal adalah di atas 90%.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Summary Cards -->
                <div class="row mt-4">
                    <div class="col-md-4 mb-3">
                        <div class="card card-dashboard">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="bi bi-trophy me-2"></i>
                                    Rekor Terbaik
                                </h6>
                            </div>
                            <div class="card-body">
                                @php
                                    $bestMonth = max($attendanceTrend['data']);
                                    $bestMonthIndex = array_search($bestMonth, $attendanceTrend['data']);
                                    $bestMonthName = $attendanceTrend['labels'][$bestMonthIndex] ?? 'Tidak ada data';
                                @endphp
                                <div class="text-center">
                                    <h1 class="display-4 text-success">{{ $bestMonth }}%</h1>
                                    <p class="text-muted">Bulan {{ $bestMonthName }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <div class="card card-dashboard">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    Perlu Perhatian
                                </h6>
                            </div>
                            <div class="card-body">
                                @php
                                    $worstMonth = min($attendanceTrend['data']);
                                    $worstMonthIndex = array_search($worstMonth, $attendanceTrend['data']);
                                    $worstMonthName = $attendanceTrend['labels'][$worstMonthIndex] ?? 'Tidak ada data';
                                @endphp
                                <div class="text-center">
                                    <h1 class="display-4 text-danger">{{ $worstMonth }}%</h1>
                                    <p class="text-muted">Bulan {{ $worstMonthName }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <div class="card card-dashboard">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="bi bi-calculator me-2"></i>
                                    Rata-rata
                                </h6>
                            </div>
                            <div class="card-body">
                                @php
                                    $average = count($attendanceTrend['data']) > 0 ? 
                                        round(array_sum($attendanceTrend['data']) / count($attendanceTrend['data']), 1) : 0;
                                @endphp
                                <div class="text-center">
                                    <h1 class="display-4 text-primary">{{ $average }}%</h1>
                                    <p class="text-muted">6 Bulan Terakhir</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card card-dashboard">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-lightbulb me-2"></i>
                    Tips Meningkatkan Kehadiran
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="alert alert-success">
                            <h6 class="alert-heading">Yang Sudah Baik</h6>
                            <ul class="mb-0">
                                <li>Pastikan anak tidur cukup (8-10 jam)</li>
                                <li>Siapkan perlengkapan sekolah malam sebelumnya</li>
                                <li>Buat rutinitas pagi yang konsisten</li>
                                <li>Berikan sarapan yang bergizi</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="alert alert-warning">
                            <h6 class="alert-heading">Yang Perlu Diperbaiki</h6>
                            <ul class="mb-0">
                                <li>Ajukan izin minimal 1 hari sebelumnya jika sakit</li>
                                <li>Pastikan anak berangkat lebih awal untuk menghindari terlambat</li>
                                <li>Komunikasikan masalah kesehatan dengan guru</li>
                                <li>Pantau perkembangan melalui grafik ini secara rutin</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Chart Type Selector
document.querySelectorAll('[data-chart-type]').forEach(button => {
    button.addEventListener('click', function() {
        const chartType = this.getAttribute('data-chart-type');
        
        // Update active button
        document.querySelectorAll('[data-chart-type]').forEach(btn => {
            btn.classList.remove('active');
        });
        this.classList.add('active');
        
        // Show selected chart
        document.querySelectorAll('.chart-container').forEach(container => {
            container.classList.add('d-none');
        });
        document.getElementById(`${chartType}-chart`).classList.remove('d-none');
    });
});

// Initialize Monthly Chart
const monthlyCtx = document.getElementById('monthlyAttendanceChart').getContext('2d');
const monthlyChart = new Chart(monthlyCtx, {
    type: 'bar',
    data: {
        labels: @json($monthlyData['labels']),
        datasets: [
            {
                label: 'Hadir Tepat Waktu',
                data: @json($monthlyData['present']),
                backgroundColor: '#28a745',
                borderColor: '#28a745',
                borderWidth: 1
            },
            {
                label: 'Terlambat',
                data: @json($monthlyData['late']),
                backgroundColor: '#ffc107',
                borderColor: '#ffc107',
                borderWidth: 1
            },
            {
                label: 'Tidak Hadir',
                data: @json($monthlyData['absent']),
                backgroundColor: '#dc3545',
                borderColor: '#dc3545',
                borderWidth: 1
            },
            {
                label: 'Izin',
                data: @json($monthlyData['permission']),
                backgroundColor: '#17a2b8',
                borderColor: '#17a2b8',
                borderWidth: 1
            }
        ]
    },
    options: {
        responsive: true,
        scales: {
            x: {
                stacked: true,
                title: {
                    display: true,
                    text: 'Hari dalam Bulan'
                }
            },
            y: {
                stacked: true,
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Jumlah'
                },
                ticks: {
                    stepSize: 1
                }
            }
        },
        plugins: {
            legend: {
                position: 'top',
            },
            tooltip: {
                mode: 'index',
                intersect: false
            }
        }
    }
});

// Initialize Weekly Chart
const weeklyCtx = document.getElementById('weeklyAttendanceChart').getContext('2d');
const weeklyChart = new Chart(weeklyCtx, {
    type: 'bar',
    data: {
        labels: @json($weeklyData['labels']),
        datasets: [
            {
                label: 'Hadir Tepat Waktu',
                data: @json($weeklyData['present']),
                backgroundColor: '#28a745',
                borderColor: '#28a745',
                borderWidth: 1
            },
            {
                label: 'Terlambat',
                data: @json($weeklyData['late']),
                backgroundColor: '#ffc107',
                borderColor: '#ffc107',
                borderWidth: 1
            },
            {
                label: 'Tidak Hadir',
                data: @json($weeklyData['absent']),
                backgroundColor: '#dc3545',
                borderColor: '#dc3545',
                borderWidth: 1
            },
            {
                label: 'Izin',
                data: @json($weeklyData['permission']),
                backgroundColor: '#17a2b8',
                borderColor: '#17a2b8',
                borderWidth: 1
            }
        ]
    },
    options: {
        responsive: true,
        scales: {
            x: {
                stacked: true,
                title: {
                    display: true,
                    text: 'Hari dalam Minggu'
                }
            },
            y: {
                stacked: true,
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Jumlah'
                },
                ticks: {
                    stepSize: 1
                }
            }
        },
        plugins: {
            legend: {
                position: 'top',
            },
            tooltip: {
                mode: 'index',
                intersect: false
            }
        }
    }
});

// Initialize Distribution Chart
const distributionCtx = document.getElementById('distributionChart').getContext('2d');
const distributionChart = new Chart(distributionCtx, {
    type: 'pie',
    data: {
        labels: @json(array_keys($statusDistribution)),
        datasets: [{
            data: @json(array_values($statusDistribution)),
            backgroundColor: [
                '#28a745', // Hadir Tepat Waktu
                '#ffc107', // Terlambat
                '#17a2b8', // Izin
                '#dc3545'  // Tidak Hadir
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'right',
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        const label = context.label || '';
                        const value = context.raw || 0;
                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                        const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                        return `${label}: ${value} hari (${percentage}%)`;
                    }
                }
            }
        }
    }
});

// Initialize Trend Chart
const trendCtx = document.getElementById('trendChart').getContext('2d');
const trendChart = new Chart(trendCtx, {
    type: 'line',
    data: {
        labels: @json($attendanceTrend['labels']),
        datasets: [{
            label: 'Persentase Kehadiran',
            data: @json($attendanceTrend['data']),
            backgroundColor: 'rgba(40, 167, 69, 0.2)',
            borderColor: '#28a745',
            borderWidth: 2,
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                max: 100,
                title: {
                    display: true,
                    text: 'Persentase (%)'
                },
                ticks: {
                    callback: function(value) {
                        return value + '%';
                    }
                }
            },
            x: {
                title: {
                    display: true,
                    text: 'Bulan'
                }
            }
        },
        plugins: {
            legend: {
                position: 'top',
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return `${context.dataset.label}: ${context.raw}%`;
                    }
                }
            }
        }
    }
});
</script>
@endsection
