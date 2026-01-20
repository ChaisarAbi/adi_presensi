@extends('layouts.app')

@section('title', 'Absensi Anak')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-dashboard">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-calendar-check me-2"></i>
                    Absensi Anak
                </h5>
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
                            <strong>Informasi:</strong> Anda sedang melihat absensi untuk 
                            <strong>{{ $student->nama }}</strong> (Kelas: {{ $student->kelas }}, NIS: {{ $student->nis }})
                        </div>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card card-statistic bg-success text-white">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="bi bi-check-circle display-6"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-0">{{ $stats['hadir_days'] }}</h5>
                                        <p class="mb-0">Hadir</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card card-statistic bg-info text-white">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="bi bi-envelope-paper display-6"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-0">{{ $stats['izin_days'] }}</h5>
                                        <p class="mb-0">Izin</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card card-statistic bg-danger text-white">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="bi bi-x-circle display-6"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-0">{{ $stats['tidak_hadir_days'] }}</h5>
                                        <p class="mb-0">Tidak Hadir</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card card-statistic bg-primary text-white">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="bi bi-percent display-6"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-0">{{ $stats['attendance_rate'] }}%</h5>
                                        <p class="mb-0">Tingkat Kehadiran</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Attendance (Last 7 Days) -->
                <div class="card card-dashboard mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="bi bi-clock-history me-2"></i>
                            Kehadiran 7 Hari Terakhir
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($recentAttendances as $date => $dayAttendances)
                                @php
                                    $carbonDate = \Carbon\Carbon::parse($date);
                                    $dayName = $carbonDate->locale('id')->dayName;
                                    $formattedDate = $carbonDate->format('d/m/Y');
                                    $attendance = $dayAttendances->first();
                                @endphp
                                <div class="col-md-4 col-sm-6 mb-3">
                                    <div class="card attendance-day-card 
                                        @if($attendance && $attendance->status == 'Hadir Masuk')
                                            border-success
                                        @elseif($attendance && $attendance->status == 'Hadir Pulang')
                                            border-info
                                        @elseif($attendance && $attendance->status == 'Izin')
                                            border-info
                                        @elseif($attendance && $attendance->status == 'Tidak Hadir')
                                            border-danger
                                        @else
                                            border-secondary
                                        @endif">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="mb-1">{{ $dayName }}</h6>
                                                    <p class="text-muted mb-1">{{ $formattedDate }}</p>
                                                </div>
                                                <div class="text-end">
                                                    @if($attendance)
                                                        @if($attendance->status == 'Hadir Masuk')
                                                            <span class="badge bg-success">Hadir</span>
                                                            <p class="mb-0 small">{{ $attendance->waktu }}</p>
                                                        @elseif($attendance->status == 'Hadir Pulang')
                                                            <span class="badge bg-info">Pulang</span>
                                                            <p class="mb-0 small">{{ $attendance->waktu }}</p>
                                                        @elseif($attendance->status == 'Izin')
                                                            <span class="badge bg-info">Izin</span>
                                                        @elseif($attendance->status == 'Tidak Hadir')
                                                            <span class="badge bg-danger">Tidak Hadir</span>
                                                        @else
                                                            <span class="badge bg-secondary">{{ $attendance->status }}</span>
                                                        @endif
                                                    @else
                                                        <span class="badge bg-secondary">Belum Absen</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Attendance Table -->
                <div class="card card-dashboard">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">
                            <i class="bi bi-list-ul me-2"></i>
                            Riwayat Absensi (Semester Ini)
                        </h6>
                        <div>
                            <a href="{{ route('ortu.charts.index') }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-bar-chart me-1"></i> Lihat Grafik
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Tanggal</th>
                                        <th>Hari</th>
                                        <th>Waktu</th>
                                        <th>Status</th>
                                        <th>Keterangan</th>
                                        <th>Di-scan Oleh</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($attendances as $attendance)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ \Carbon\Carbon::parse($attendance->tanggal)->format('d/m/Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($attendance->tanggal)->locale('id')->dayName }}</td>
                                            <td>{{ $attendance->waktu }}</td>
                                            <td>
                                                @if($attendance->status == 'Hadir Masuk')
                                                    <span class="badge bg-success">Hadir</span>
                                                @elseif($attendance->status == 'Hadir Pulang')
                                                    <span class="badge bg-info">Pulang</span>
                                                @elseif($attendance->status == 'Izin')
                                                    <span class="badge bg-info">Izin</span>
                                                @elseif($attendance->status == 'Tidak Hadir')
                                                    <span class="badge bg-danger">Tidak Hadir</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ $attendance->status }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($attendance->status == 'Hadir Masuk')
                                                    <span class="text-success">Hadir masuk</span>
                                                @elseif($attendance->status == 'Hadir Pulang')
                                                    <span class="text-info">Hadir pulang</span>
                                                @elseif($attendance->status == 'Izin')
                                                    <span class="text-info">Dengan izin</span>
                                                @elseif($attendance->status == 'Tidak Hadir')
                                                    <span class="text-danger">Tanpa keterangan</span>
                                                @else
                                                    <span class="text-muted">{{ $attendance->status }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $attendance->scannedBy->name ?? 'Sistem' }}
                                            </td>
                                            <td>
                                                <a href="{{ route('ortu.attendance.show', $attendance->id) }}" 
                                                   class="btn btn-sm btn-info">
                                                    <i class="bi bi-eye"></i> Detail
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-4">
                                                <div class="text-muted">
                                                    <i class="bi bi-calendar-x" style="font-size: 3rem;"></i>
                                                    <p class="mt-2">Belum ada data absensi</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if($attendances->hasPages())
                            <div class="d-flex justify-content-center mt-3">
                                {{ $attendances->links() }}
                            </div>
                        @endif
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
                    <i class="bi bi-info-circle me-2"></i>
                    Informasi Absensi
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="alert alert-info mb-3">
                            <h6 class="alert-heading">Keterangan Status</h6>
                            <ul class="mb-0">
                                <li><span class="badge bg-success">Hadir</span> - Hadir masuk</li>
                                <li><span class="badge bg-info">Pulang</span> - Hadir pulang</li>
                                <li><span class="badge bg-info">Izin</span> - Tidak hadir dengan izin</li>
                                <li><span class="badge bg-danger">Tidak Hadir</span> - Tidak hadir tanpa keterangan</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="alert alert-warning mb-0">
                            <h6 class="alert-heading">Catatan Penting</h6>
                            <ul class="mb-0">
                                <li>Data absensi diupdate secara real-time oleh guru</li>
                                <li>Jika ada ketidaksesuaian, hubungi guru/wali kelas</li>
                                <li>Izin harus diajukan minimal 1 hari sebelumnya</li>
                                <li>Data berdasarkan semester ini (6 bulan terakhir)</li>
                                <li><strong>Persentase kehadiran dihitung berdasarkan hari sekolah aktif (Senin-Jumat, kecuali hari libur)</strong></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card-statistic {
    border-radius: 10px;
    transition: transform 0.3s;
}
.card-statistic:hover {
    transform: translateY(-5px);
}
.attendance-day-card {
    transition: transform 0.3s;
}
.attendance-day-card:hover {
    transform: translateY(-3px);
}
</style>
@endsection
