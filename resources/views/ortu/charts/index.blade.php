@extends('layouts.app')

@section('title', 'Distribusi Kehadiran Anak')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-dashboard">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-pie-chart me-2"></i>
                    Distribusi Kehadiran Anak
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
                            <strong>Informasi:</strong> Anda sedang melihat distribusi kehadiran untuk 
                            <strong>{{ $student->nama }}</strong> (Kelas: {{ $student->kelas }}, NIS: {{ $student->nis }})
                            <br>
                            <small class="text-muted">Data berdasarkan 30 hari terakhir</small>
                        </div>
                    </div>
                </div>

                <!-- Distribution Chart -->
                <div class="row mb-4">
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
                                                <tfoot>
                                                    <tr class="table-light">
                                                        <td><strong>Total</strong></td>
                                                        <td><strong>{{ $totalDays }} hari</strong></td>
                                                        <td><strong>100%</strong></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Summary Cards -->
                <div class="row mt-4">
                    @php
                        $hadirTepatWaktu = $statusDistribution['Hadir Tepat Waktu'] ?? 0;
                        $terlambat = $statusDistribution['Terlambat'] ?? 0;
                        $izin = $statusDistribution['Izin'] ?? 0;
                        $tidakHadir = $statusDistribution['Tidak Hadir'] ?? 0;
                        
                        $totalHadir = $hadirTepatWaktu + $terlambat;
                        $persentaseHadir = $totalDays > 0 ? round(($totalHadir / $totalDays) * 100, 1) : 0;
                        $persentaseIzin = $totalDays > 0 ? round(($izin / $totalDays) * 100, 1) : 0;
                        $persentaseTidakHadir = $totalDays > 0 ? round(($tidakHadir / $totalDays) * 100, 1) : 0;
                    @endphp
                    
                    <div class="col-md-4 mb-3">
                        <div class="card card-dashboard border-start border-success border-4">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <div class="text-success fw-bold text-uppercase mb-1">Kehadiran</div>
                                        <div class="h2 mb-0">{{ $totalHadir }} hari</div>
                                        <div class="text-muted">{{ $persentaseHadir }}% dari total</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="bi bi-check-circle card-icon text-success" style="font-size: 2.5rem;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <div class="card card-dashboard border-start border-info border-4">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <div class="text-info fw-bold text-uppercase mb-1">Izin</div>
                                        <div class="h2 mb-0">{{ $izin }} hari</div>
                                        <div class="text-muted">{{ $persentaseIzin }}% dari total</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="bi bi-envelope-paper card-icon text-info" style="font-size: 2.5rem;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <div class="card card-dashboard border-start border-danger border-4">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <div class="text-danger fw-bold text-uppercase mb-1">Tidak Hadir</div>
                                        <div class="h2 mb-0">{{ $tidakHadir }} hari</div>
                                        <div class="text-muted">{{ $persentaseTidakHadir }}% dari total</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="bi bi-x-circle card-icon text-danger" style="font-size: 2.5rem;"></i>
                                    </div>
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
</script>
@endsection
