@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
@if($holidayInfo['is_holiday'])
<div class="alert alert-warning alert-dismissible fade show" role="alert" id="holidayAlert">
    <div class="d-flex align-items-center">
        <i class="bi bi-calendar-x me-2" style="font-size: 1.5rem;"></i>
        <div>
            <strong>Hari Ini Libur!</strong> {{ $holidayInfo['keterangan'] }}. 
            <span class="d-block mt-1 small">Tidak ada kegiatan sekolah. Absensi tidak diperlukan.</span>
        </div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="row">
    <!-- Statistik Cards -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card card-dashboard border-start border-primary border-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="text-primary fw-bold text-uppercase mb-1">Total Siswa</div>
                        <div class="h2 mb-0">{{ $stats['total_students'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-people card-icon text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card card-dashboard border-start border-success border-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="text-success fw-bold text-uppercase mb-1">Total Guru</div>
                        <div class="h2 mb-0">{{ $stats['total_guru'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-person-badge card-icon text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card card-dashboard border-start border-info border-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="text-info fw-bold text-uppercase mb-1">Total Orang Tua</div>
                        <div class="h2 mb-0">{{ $stats['total_ortu'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-house-door card-icon text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card card-dashboard border-start border-warning border-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="text-warning fw-bold text-uppercase mb-1">Absensi Hari Ini</div>
                        <div class="h2 mb-0">{{ $stats['attendance_today'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-calendar-check card-icon text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Attendance -->
    <div class="col-lg-8 mb-4">
        <div class="card card-dashboard">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Absensi Terbaru Hari Ini</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nama Siswa</th>
                                <th>Kelas</th>
                                <th>Waktu</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recent_attendances as $attendance)
                            <tr>
                                <td>{{ $attendance->student->nama ?? 'N/A' }}</td>
                                <td>{{ $attendance->student->classSchedule->kelas ?? 'N/A' }}</td>
                                <td>{{ \Carbon\Carbon::parse($attendance->waktu)->format('H:i') }}</td>
                                <td>
                                    @if($attendance->status == 'Hadir Masuk')
                                        <span class="badge bg-success">Hadir Masuk</span>
                                    @elseif($attendance->status == 'Hadir Pulang')
                                        <span class="badge bg-info">Hadir Pulang</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $attendance->status }}</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">Belum ada absensi hari ini</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Class Distribution -->
    <div class="col-lg-4 mb-4">
        <div class="card card-dashboard">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-pie-chart me-2"></i>Distribusi Kelas</h5>
            </div>
            <div class="card-body">
                <div class="chart-container" style="height: 250px;">
                    <canvas id="classChart"></canvas>
                </div>
                <div class="mt-3">
                    @foreach($class_distribution as $dist)
                    <div class="d-flex justify-content-between mb-2">
                        <span>{{ $dist->classSchedule->kelas ?? 'Unknown' }}</span>
                        <span class="fw-bold">{{ $dist->total }} siswa</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Quick Actions -->
    <div class="col-12">
        <div class="card card-dashboard">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-lightning me-2"></i>Aksi Cepat</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3 col-6">
                        <a href="{{ route('admin.students.create') }}" class="btn btn-primary w-100 py-3">
                            <i class="bi bi-plus-circle display-6 d-block mb-2"></i>
                            Tambah Siswa
                        </a>
                    </div>
                    <div class="col-md-3 col-6">
                        <a href="{{ route('admin.students.index') }}" class="btn btn-success w-100 py-3">
                            <i class="bi bi-qr-code-scan display-6 d-block mb-2"></i>
                            Generate Barcode
                        </a>
                    </div>
                    <div class="col-md-3 col-6">
                        <a href="{{ route('admin.permissions.index') }}" class="btn btn-info w-100 py-3">
                            <i class="bi bi-clipboard-check display-6 d-block mb-2"></i>
                            Manajemen Izin
                        </a>
                    </div>
                    <div class="col-md-3 col-6">
                        <a href="{{ route('admin.flags.index') }}" class="btn btn-warning w-100 py-3">
                            <i class="bi bi-flag display-6 d-block mb-2"></i>
                            Flagging
                        </a>
                    </div>
                </div>
                
                <!-- Second Row of Quick Actions -->
                <div class="row g-3 mt-3">
                    <div class="col-md-3 col-6">
                        <a href="{{ route('admin.reports.index') }}" class="btn btn-danger w-100 py-3">
                            <i class="bi bi-file-earmark-pdf display-6 d-block mb-2"></i>
                            Export PDF
                        </a>
                    </div>
                    <div class="col-md-3 col-6">
                        <a href="{{ route('admin.gurus.index') }}" class="btn btn-secondary w-100 py-3">
                            <i class="bi bi-person-badge display-6 d-block mb-2"></i>
                            Data Guru
                        </a>
                    </div>
                    <div class="col-md-3 col-6">
                        <a href="{{ route('admin.ortus.index') }}" class="btn btn-dark w-100 py-3">
                            <i class="bi bi-house-door display-6 d-block mb-2"></i>
                            Data Ortu
                        </a>
                    </div>
                    <div class="col-md-3 col-6">
                        <a href="{{ route('admin.schedules.index') }}" class="btn btn-light w-100 py-3 border">
                            <i class="bi bi-calendar-week display-6 d-block mb-2"></i>
                            Jadwal Kelas
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Class Distribution Chart
        const classCtx = document.getElementById('classChart').getContext('2d');
        const classLabels = @json($class_distribution->pluck('classSchedule.kelas'));
        const classData = @json($class_distribution->pluck('total'));
        
        const colors = [
            '#667eea', '#764ba2', '#f093fb', '#f5576c',
            '#4facfe', '#00f2fe', '#43e97b', '#38f9d7'
        ];

        new Chart(classCtx, {
            type: 'doughnut',
            data: {
                labels: classLabels,
                datasets: [{
                    data: classData,
                    backgroundColor: colors,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
