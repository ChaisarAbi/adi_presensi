@extends('layouts.app')

@section('title', 'Detail Siswa')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-person-circle me-2"></i>Informasi Siswa</h5>
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="bi bi-person-bounding-box display-1 text-primary"></i>
                    </div>
                    <h4 class="mb-1">{{ $student->nama }}</h4>
                    <p class="text-muted mb-2">NIS: {{ $student->nis }}</p>
                    <p class="mb-2">
                        <i class="bi bi-book me-1"></i>
                        Kelas: <span class="badge bg-info">{{ $student->classSchedule->kelas ?? 'N/A' }}</span>
                    </p>
                    <p class="mb-3">
                        <i class="bi bi-clock me-1"></i>
                        Jadwal: {{ $student->classSchedule->jam_masuk_formatted ?? 'N/A' }} - {{ $student->classSchedule->jam_pulang_formatted ?? 'N/A' }}
                    </p>
                    
                    <div class="alert alert-warning mb-3">
                        <i class="bi bi-qr-code me-2"></i>
                        <strong>Barcode:</strong> <code class="fs-5">{{ $student->barcode }}</code>
                        <a href="{{ route('admin.students.generate-barcode', $student->id) }}" class="btn btn-sm btn-outline-warning ms-2" onclick="return confirm('Generate barcode baru? Barcode lama akan diganti.')">
                            <i class="bi bi-arrow-clockwise"></i> Generate Baru
                        </a>
                    </div>

                    @if($student->ortu)
                    <div class="alert alert-info">
                        <i class="bi bi-person-badge me-2"></i>
                        <strong>Orang Tua:</strong> {{ $student->ortu->name }}<br>
                        <small class="text-muted">{{ $student->ortu->email }}</small>
                    </div>
                    @else
                    <div class="alert alert-secondary">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Belum terhubung dengan orang tua
                    </div>
                    @endif

                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.students.edit', $student->id) }}" class="btn btn-warning">
                            <i class="bi bi-pencil me-1"></i> Edit Data
                        </a>
                        <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card mb-4">
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
                                @forelse($student->attendances->take(10) as $attendance)
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

            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-graph-up me-2"></i>Statistik Kehadiran</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4 mb-3">
                            <div class="card border-primary">
                                <div class="card-body">
                                    <h2 class="text-primary">{{ $student->attendances->where('status', 'Hadir Masuk')->count() }}</h2>
                                    <p class="mb-0 text-muted">Total Hadir Masuk</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card border-success">
                                <div class="card-body">
                                    <h2 class="text-success">{{ $student->attendances->where('status', 'Hadir Pulang')->count() }}</h2>
                                    <p class="mb-0 text-muted">Total Hadir Pulang</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card border-warning">
                                <div class="card-body">
                                    <h2 class="text-warning">{{ $student->attendances->where('status', 'Izin')->count() }}</h2>
                                    <p class="mb-0 text-muted">Total Izin</p>
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
