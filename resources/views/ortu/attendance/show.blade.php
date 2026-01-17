@extends('layouts.app')

@section('title', 'Detail Absensi')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-dashboard">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-eye me-2"></i>
                    Detail Absensi
                </h5>
                <div>
                    <a href="{{ route('ortu.attendance.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="alert alert-info mb-4">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-info-circle me-2" style="font-size: 1.2rem;"></i>
                        <div>
                            <strong>Informasi:</strong> Detail absensi untuk 
                            <strong>{{ $student->nama }}</strong> (Kelas: {{ $student->kelas }}, NIS: {{ $student->nis }})
                        </div>
                    </div>
                </div>

                <!-- Attendance Details -->
                <div class="row">
                    <div class="col-md-8">
                        <div class="card card-dashboard mb-4">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="bi bi-calendar-check me-2"></i>
                                    Informasi Absensi
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-muted">Tanggal</label>
                                        <div class="form-control bg-light">
                                            {{ \Carbon\Carbon::parse($attendance->tanggal)->locale('id')->dayName }}, 
                                            {{ \Carbon\Carbon::parse($attendance->tanggal)->format('d F Y') }}
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-muted">Waktu</label>
                                        <div class="form-control bg-light">
                                            {{ $attendance->waktu }}
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-muted">Status</label>
                                        <div class="form-control bg-light">
                                            @if($attendance->status == 'Hadir Masuk')
                                                @if(strtotime($attendance->waktu) > strtotime('07:00:00'))
                                                    <span class="badge bg-warning">Terlambat</span>
                                                @else
                                                    <span class="badge bg-success">Hadir</span>
                                                @endif
                                            @elseif($attendance->status == 'Izin')
                                                <span class="badge bg-info">Izin</span>
                                            @else
                                                <span class="badge bg-danger">Tidak Hadir</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-muted">Di-scan Oleh</label>
                                        <div class="form-control bg-light">
                                            {{ $attendance->scannedBy->name ?? 'Sistem' }}
                                        </div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label text-muted">Keterangan</label>
                                        <div class="form-control bg-light" style="min-height: 100px;">
                                            @if($attendance->status == 'Hadir Masuk')
                                                @if(strtotime($attendance->waktu) > strtotime('07:00:00'))
                                                    <span class="text-warning">
                                                        <i class="bi bi-clock-history me-1"></i>
                                                        Terlambat {{ \Carbon\Carbon::parse($attendance->waktu)->diffInMinutes(\Carbon\Carbon::parse('07:00:00')) }} menit
                                                    </span>
                                                @else
                                                    <span class="text-success">
                                                        <i class="bi bi-check-circle me-1"></i>
                                                        Hadir tepat waktu
                                                    </span>
                                                @endif
                                            @elseif($attendance->status == 'Izin')
                                                <span class="text-info">
                                                    <i class="bi bi-envelope-paper me-1"></i>
                                                    Tidak hadir dengan izin
                                                </span>
                                            @else
                                                <span class="text-danger">
                                                    <i class="bi bi-x-circle me-1"></i>
                                                    Tidak hadir tanpa keterangan
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <!-- Student Info -->
                        <div class="card card-dashboard mb-4">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="bi bi-person-circle me-2"></i>
                                    Informasi Siswa
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="text-center mb-3">
                                    <div class="mb-3">
                                        <i class="bi bi-person-badge" style="font-size: 3rem; color: #667eea;"></i>
                                    </div>
                                    <h5 class="mb-1">{{ $student->nama }}</h5>
                                    <p class="text-muted mb-1">NIS: {{ $student->nis }}</p>
                                    <p class="text-muted mb-3">Kelas: {{ $student->kelas }}</p>
                                </div>
                                
                                <div class="list-group list-group-flush">
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>Barcode</span>
                                        <span class="badge bg-primary">{{ $student->barcode }}</span>
                                    </div>
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>Status</span>
                                        <span class="badge bg-success">Aktif</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Stats -->
                        <div class="card card-dashboard">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="bi bi-bar-chart me-2"></i>
                                    Statistik Hari Ini
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="text-center">
                                    @php
                                        $today = \Carbon\Carbon::today();
                                        $todayAttendance = \App\Models\Attendance::where('student_id', $student->id)
                                            ->whereDate('tanggal', $today)
                                            ->first();
                                    @endphp
                                    
                                    @if($todayAttendance)
                                        <div class="mb-3">
                                            @if($todayAttendance->status == 'Hadir Masuk')
                                                @if(strtotime($todayAttendance->waktu) > strtotime('07:00:00'))
                                                    <div class="display-4 text-warning">
                                                        <i class="bi bi-clock-history"></i>
                                                    </div>
                                                    <h5 class="mt-2">Terlambat</h5>
                                                    <p class="text-muted">Hari ini</p>
                                                @else
                                                    <div class="display-4 text-success">
                                                        <i class="bi bi-check-circle"></i>
                                                    </div>
                                                    <h5 class="mt-2">Hadir</h5>
                                                    <p class="text-muted">Hari ini</p>
                                                @endif
                                            @elseif($todayAttendance->status == 'Izin')
                                                <div class="display-4 text-info">
                                                    <i class="bi bi-envelope-paper"></i>
                                                </div>
                                                <h5 class="mt-2">Izin</h5>
                                                <p class="text-muted">Hari ini</p>
                                            @else
                                                <div class="display-4 text-danger">
                                                    <i class="bi bi-x-circle"></i>
                                                </div>
                                                <h5 class="mt-2">Tidak Hadir</h5>
                                                <p class="text-muted">Hari ini</p>
                                            @endif
                                        </div>
                                    @else
                                        <div class="mb-3">
                                            <div class="display-4 text-secondary">
                                                <i class="bi bi-question-circle"></i>
                                            </div>
                                            <h5 class="mt-2">Belum Absen</h5>
                                            <p class="text-muted">Hari ini</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card card-dashboard">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="bi bi-info-circle me-2"></i>
                                    Informasi Tambahan
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="alert alert-info">
                                            <h6 class="alert-heading">
                                                <i class="bi bi-clock-history me-2"></i>
                                                Jam Sekolah
                                            </h6>
                                            <ul class="mb-0">
                                                <li>Jam Masuk: 07:00 WIB</li>
                                                <li>Jam Pulang: 13:00 WIB</li>
                                                <li>Toleransi Keterlambatan: 15 menit</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="alert alert-warning">
                                            <h6 class="alert-heading">
                                                <i class="bi bi-exclamation-triangle me-2"></i>
                                                Catatan Penting
                                            </h6>
                                            <ul class="mb-0">
                                                <li>Jika ada ketidaksesuaian, hubungi guru/wali kelas</li>
                                                <li>Izin harus diajukan minimal 1 hari sebelumnya</li>
                                                <li>Terlambat lebih dari 3x dalam seminggu akan mendapat pemberitahuan</li>
                                            </ul>
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
</div>
@endsection
