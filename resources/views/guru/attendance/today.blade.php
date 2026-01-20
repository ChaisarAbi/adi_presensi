@extends('layouts.app')

@section('title', 'Absensi Hari Ini')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-calendar-check me-2"></i>Absensi Hari Ini ({{ \Carbon\Carbon::today()->translatedFormat('l, d F Y') }})</h5>
                    <div>
                        <a href="{{ route('guru.attendance.scanner') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-qr-code-scan me-1"></i> Scan Absensi
                        </a>
                        <a href="{{ route('guru.attendance.belum-pulang') }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-exclamation-triangle me-1"></i> Belum Pulang
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card border-success">
                                <div class="card-body text-center">
                                    <h6 class="text-muted mb-1">Hadir Masuk</h6>
                                    <h3 class="text-success">{{ $attendances->where('status', 'Hadir Masuk')->count() }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-info">
                                <div class="card-body text-center">
                                    <h6 class="text-muted mb-1">Hadir Pulang</h6>
                                    <h3 class="text-info">{{ $attendances->where('status', 'Hadir Pulang')->count() }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-primary">
                                <div class="card-body text-center">
                                    <h6 class="text-muted mb-1">Izin</h6>
                                    <h3 class="text-primary">{{ $attendances->where('status', 'Izin')->count() }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-secondary">
                                <div class="card-body text-center">
                                    <h6 class="text-muted mb-1">Belum Scan</h6>
                                    <h3 class="text-secondary">{{ $notScannedStudents->count() }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card border-danger">
                                <div class="card-body text-center">
                                    <h6 class="text-muted mb-1">Total Absensi</h6>
                                    <h3 class="text-danger">{{ $attendances->count() }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-warning">
                                <div class="card-body text-center">
                                    <h6 class="text-muted mb-1">Tidak Masuk</h6>
                                    <h3 class="text-warning">
                                        @php
                                            // Hitung siswa dengan status Tidak Hadir saja
                                            $tidakMasukCount = $attendances->where('status', 'Tidak Hadir')->count();
                                        @endphp
                                        {{ $tidakMasukCount }}
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Siswa</th>
                                    <th>NIS</th>
                                    <th>Kelas</th>
                                    <th>Waktu</th>
                                    <th>Status</th>
                                    <th>Tipe</th>
                                    <th>Scan Oleh</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($attendances as $attendance)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <strong>{{ $attendance->student->nama }}</strong>
                                    </td>
                                    <td>{{ $attendance->student->nis }}</td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            {{ $attendance->student->classSchedule->kelas ?? '-' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-dark">{{ $attendance->waktu }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'Hadir Masuk' => 'success',
                                                'Hadir Pulang' => 'info',
                                                'Izin' => 'primary',
                                                'Tidak Hadir' => 'danger'
                                            ];
                                            $color = $statusColors[$attendance->status] ?? 'secondary';
                                        @endphp
                                        <span class="badge bg-{{ $color }}">
                                            {{ $attendance->status }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($attendance->status === 'Hadir Masuk')
                                            <span class="badge bg-success">Masuk</span>
                                        @elseif($attendance->status === 'Hadir Pulang')
                                            <span class="badge bg-info">Pulang</span>
                                        @else
                                            <span class="badge bg-secondary">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $attendance->scanner->name ?? 'System' }}
                                        </small>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        <i class="bi bi-calendar-x me-2"></i>Belum ada absensi hari ini
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Students who haven't been scanned -->
                    @if($notScannedStudents->count() > 0)
                    <div class="row mt-5">
                        <div class="col-12">
                            <div class="card border-secondary">
                                <div class="card-header bg-secondary text-white">
                                    <h6 class="mb-0"><i class="bi bi-person-x me-2"></i>Siswa Belum Di-scan Hari Ini ({{ $notScannedStudents->count() }})</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-hover">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Siswa</th>
                                                    <th>NIS</th>
                                                    <th>Kelas</th>
                                                    <th>Status</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($notScannedStudents as $student)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $student->nama }}</td>
                                                    <td>{{ $student->nis }}</td>
                                                    <td>
                                                        <span class="badge bg-secondary">
                                                            {{ $student->classSchedule->kelas ?? '-' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-warning">Belum Absen</span>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('guru.attendance.manual') }}" class="btn btn-sm btn-outline-primary">
                                                            <i class="bi bi-keyboard me-1"></i> Input Manual
                                                        </a>
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
                    @endif

                    <!-- Students with Izin -->
                    @php
                        $izinAttendances = $attendances->where('status', 'Izin');
                    @endphp
                    @if($izinAttendances->count() > 0)
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card border-primary">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="mb-0"><i class="bi bi-envelope-paper me-2"></i>Siswa Izin Hari Ini ({{ $izinAttendances->count() }})</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-hover">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Siswa</th>
                                                    <th>NIS</th>
                                                    <th>Kelas</th>
                                                    <th>Waktu</th>
                                                    <th>Scan Oleh</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($izinAttendances as $attendance)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $attendance->student->nama }}</td>
                                                    <td>{{ $attendance->student->nis }}</td>
                                                    <td>
                                                        <span class="badge bg-secondary">
                                                            {{ $attendance->student->classSchedule->kelas ?? '-' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-dark">{{ $attendance->waktu }}</span>
                                                    </td>
                                                    <td>
                                                        <small class="text-muted">
                                                            {{ $attendance->scanner->name ?? 'System' }}
                                                        </small>
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
                    @endif

                    <!-- Students with Tidak Hadir -->
                    @php
                        $tidakHadirAttendances = $attendances->where('status', 'Tidak Hadir');
                    @endphp
                    @if($tidakHadirAttendances->count() > 0)
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card border-danger">
                                <div class="card-header bg-danger text-white">
                                    <h6 class="mb-0"><i class="bi bi-person-x me-2"></i>Siswa Tidak Hadir Hari Ini ({{ $tidakHadirAttendances->count() }})</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-hover">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Siswa</th>
                                                    <th>NIS</th>
                                                    <th>Kelas</th>
                                                    <th>Waktu</th>
                                                    <th>Scan Oleh</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($tidakHadirAttendances as $attendance)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $attendance->student->nama }}</td>
                                                    <td>{{ $attendance->student->nis }}</td>
                                                    <td>
                                                        <span class="badge bg-secondary">
                                                            {{ $attendance->student->classSchedule->kelas ?? '-' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-dark">{{ $attendance->waktu }}</span>
                                                    </td>
                                                    <td>
                                                        <small class="text-muted">
                                                            {{ $attendance->scanner->name ?? 'System' }}
                                                        </small>
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
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
