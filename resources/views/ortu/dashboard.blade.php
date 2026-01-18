@extends('layouts.app')

@section('title', 'Dashboard Orang Tua')

@section('content')
@if(!$student)
<div class="alert alert-warning">
    <i class="bi bi-exclamation-triangle me-2"></i>
    Anda belum terhubung dengan data siswa. Silakan hubungi admin.
</div>
@else
<div class="row">
    <!-- Student Info -->
    <div class="col-lg-4 mb-4">
        <div class="card card-dashboard">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-person-circle me-2"></i>Informasi Anak</h5>
            </div>
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="bi bi-person-bounding-box display-1 text-primary"></i>
                </div>
                <h4 class="mb-1">{{ $student->nama }}</h4>
                <p class="text-muted mb-2">NIS: {{ $student->nis }}</p>
                <p class="mb-2">
                    <i class="bi bi-book me-1"></i>
                    Kelas: {{ $student->classSchedule->kelas ?? 'N/A' }}
                </p>
                <p class="mb-3">
                    <i class="bi bi-clock me-1"></i>
                    Jadwal: {{ $student->classSchedule->jam_masuk_formatted ?? 'N/A' }} - {{ $student->classSchedule->jam_pulang_formatted ?? 'N/A' }}
                </p>
                <div class="alert {{ $stats['today_status'] == 'Belum Absen' ? 'alert-warning' : ($stats['today_status'] == 'Hadir Masuk' ? 'alert-success' : 'alert-info') }}">
                    <strong>Status Hari Ini:</strong> {{ $stats['today_status'] }}
                </div>
                
                <!-- Barcode Download Section -->
                <div class="mt-4 border-top pt-3">
                    <h6 class="mb-2"><i class="bi bi-qr-code me-1"></i> Barcode Absensi</h6>
                    <p class="small text-muted mb-3">
                        Download barcode QR untuk absensi anak Anda. Barcode ini digunakan oleh guru untuk scan absensi masuk dan pulang.
                    </p>
                    @if($student->barcode)
                        <div class="d-grid gap-2">
                            <a href="{{ route('ortu.barcode.download', $student->id) }}" class="btn btn-success">
                                <i class="bi bi-download me-1"></i> Download Barcode
                            </a>
                            <!-- Tombol cetak sementara dinonaktifkan -->
                            <!-- <a href="{{ route('ortu.barcode.print', $student->id) }}" class="btn btn-outline-primary" target="_blank">
                                <i class="bi bi-printer me-1"></i> Cetak Barcode
                            </a> -->
                        </div>
                        <div class="mt-2 small text-muted">
                            <i class="bi bi-info-circle me-1"></i>
                            Simpan barcode di HP Anda atau cetak untuk dibawa anak ke sekolah.
                        </div>
                    @else
                        <div class="alert alert-warning mb-0">
                            <i class="bi bi-exclamation-triangle me-1"></i>
                            Barcode belum digenerate oleh admin. Silakan hubungi admin sekolah.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Cards -->
    <div class="col-lg-8 mb-4">
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card card-dashboard border-start border-primary border-4">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="text-primary fw-bold text-uppercase mb-1">Total Kehadiran</div>
                                <div class="h2 mb-0">{{ $stats['total_attendance'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-calendar-check card-icon text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card card-dashboard border-start border-success border-4">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="text-success fw-bold text-uppercase mb-1">Hadir Bulan Ini</div>
                                <div class="h2 mb-0">{{ $stats['attendance_this_month'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-check-circle card-icon text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card card-dashboard border-start border-info border-4">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="text-info fw-bold text-uppercase mb-1">Total Izin</div>
                                <div class="h2 mb-0">{{ $stats['izin_count'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-envelope-paper card-icon text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card card-dashboard border-start border-warning border-4">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="text-warning fw-bold text-uppercase mb-1">Status Hari Ini</div>
                                <div class="h3 mb-0">
                                    @if($stats['today_status'] == 'Hadir Masuk')
                                        <span class="text-success">Hadir Masuk</span>
                                    @elseif($stats['today_status'] == 'Hadir Pulang')
                                        <span class="text-info">Hadir Pulang</span>
                                    @elseif($stats['today_status'] == 'Belum Absen')
                                        <span class="text-warning">Belum Absen</span>
                                    @else
                                        <span class="text-secondary">{{ $stats['today_status'] }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-clock-history card-icon text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Attendance -->
    <div class="col-lg-7 mb-4">
        <div class="card card-dashboard">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Riwayat Absensi Terbaru</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Waktu</th>
                                <th>Status</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recent_attendances as $attendance)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($attendance->tanggal)->format('d/m/Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($attendance->waktu)->format('H:i') }}</td>
                                <td>
                                    @if($attendance->status == 'Hadir Masuk')
                                        <span class="badge bg-success">Hadir Masuk</span>
                                    @elseif($attendance->status == 'Hadir Pulang')
                                        <span class="badge bg-info">Hadir Pulang</span>
                                    @elseif($attendance->status == 'Izin')
                                        <span class="badge bg-warning">Izin</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $attendance->status }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($attendance->status == 'Hadir Masuk')
                                        <small class="text-muted">Absensi masuk</small>
                                    @elseif($attendance->status == 'Hadir Pulang')
                                        <small class="text-muted">Absensi pulang</small>
                                    @else
                                        <small class="text-muted">-</small>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">Belum ada riwayat absensi</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Permissions -->
    <div class="col-lg-5 mb-4">
        <div class="card card-dashboard">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-envelope-paper me-2"></i>Pengajuan Izin Terbaru</h5>
            </div>
            <div class="card-body">
                @forelse($permissions as $permission)
                <div class="card border mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1">{{ \Carbon\Carbon::parse($permission->created_at)->format('d/m/Y') }}</h6>
                                <p class="mb-1 small">{{ Str::limit($permission->alasan, 50) }}</p>
                            </div>
                            <div>
                                @if($permission->status == 'Pending')
                                    <span class="badge bg-warning">Pending</span>
                                @elseif($permission->status == 'Disetujui')
                                    <span class="badge bg-success">Disetujui</span>
                                @else
                                    <span class="badge bg-danger">Ditolak</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-center text-muted mb-0">Belum ada pengajuan izin</p>
                @endforelse
                
                <div class="text-center mt-3">
                    <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                        <a href="{{ route('ortu.permissions.create') }}" class="btn btn-primary me-md-2 mb-2">
                            <i class="bi bi-plus-circle me-1"></i> Ajukan Izin Baru
                        </a>
                        <a href="{{ route('ortu.permissions.index') }}" class="btn btn-outline-primary mb-2">
                            <i class="bi bi-clock-history me-1"></i> Lihat Riwayat Izin
                        </a>
                    </div>
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
                        <a href="{{ route('ortu.attendance.index') }}" class="btn btn-primary w-100 py-3">
                            <i class="bi bi-calendar-check display-6 d-block mb-2"></i>
                            Lihat Absensi
                        </a>
                    </div>
                    <div class="col-md-3 col-6">
                        <a href="{{ route('ortu.charts.index') }}" class="btn btn-success w-100 py-3">
                            <i class="bi bi-pie-chart display-6 d-block mb-2"></i>
                            Distribusi Kehadiran
                        </a>
                    </div>
                    <div class="col-md-3 col-6">
                        <a href="{{ route('ortu.permissions.create') }}" class="btn btn-info w-100 py-3">
                            <i class="bi bi-envelope-paper display-6 d-block mb-2"></i>
                            Ajukan Izin
                        </a>
                    </div>
                    <div class="col-md-3 col-6">
                        <a href="{{ route('ortu.flags.create') }}" class="btn btn-warning w-100 py-3">
                            <i class="bi bi-flag display-6 d-block mb-2"></i>
                            Flag Anak
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Flagging Section -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card card-dashboard border-warning">
            <div class="card-header bg-warning text-white">
                <h5 class="mb-0"><i class="bi bi-flag me-2"></i>Flagging - Anak Belum Pulang</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Jika anak Anda belum pulang setelah jam pulang, Anda dapat melakukan flagging.
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="form-label">Keterangan</label>
                            <textarea class="form-control" rows="3" placeholder="Contoh: Anak belum pulang padahal sudah jam 17:00"></textarea>
                        </div>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button class="btn btn-warning w-100 py-3">
                            <i class="bi bi-flag me-2"></i> Flag Sekarang
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
