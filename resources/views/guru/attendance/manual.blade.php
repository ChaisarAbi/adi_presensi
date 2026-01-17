@extends('layouts.app')

@section('title', 'Absensi Manual')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-keyboard me-2"></i>Absensi Manual</h5>
                    <div>
                        <a href="{{ route('guru.attendance.scanner') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-qr-code-scan me-1"></i> Scanner
                        </a>
                        <a href="{{ route('guru.attendance.today') }}" class="btn btn-success btn-sm">
                            <i class="bi bi-calendar-check me-1"></i> Hari Ini
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card border-primary">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="mb-0"><i class="bi bi-plus-circle me-2"></i>Input Absensi Manual</h6>
                                </div>
                                <div class="card-body">
                                    @if(session('success'))
                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                        </div>
                                    @endif

                                    @if(session('error'))
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                        </div>
                                    @endif

                                    <form method="POST" action="{{ route('guru.attendance.store-manual') }}">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="student_id" class="form-label">Pilih Siswa</label>
                                            <select class="form-select" id="student_id" name="student_id" required>
                                                <option value="">-- Pilih Siswa --</option>
                                                @foreach($students as $student)
                                                <option value="{{ $student->id }}">
                                                    {{ $student->nama }} ({{ $student->nis }}) - {{ $student->classSchedule->kelas }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Tipe Absensi</label>
                                            <div class="d-flex gap-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="type" 
                                                           id="type_masuk" value="masuk" checked>
                                                    <label class="form-check-label" for="type_masuk">
                                                        <span class="badge bg-success">Masuk</span>
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="type" 
                                                           id="type_pulang" value="pulang">
                                                    <label class="form-check-label" for="type_pulang">
                                                        <span class="badge bg-info">Pulang</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="waktu" class="form-label">Waktu</label>
                                            <input type="time" class="form-control" id="waktu" name="waktu" 
                                                   value="{{ date('H:i') }}" required>
                                            <small class="text-muted">Format: HH:MM (24 jam)</small>
                                        </div>

                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="confirm" required>
                                                <label class="form-check-label" for="confirm">
                                                    Saya yakin data yang diinput sudah benar
                                                </label>
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="bi bi-save me-1"></i> Simpan Absensi
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="card border-info">
                                <div class="card-header bg-info text-white">
                                    <h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>Informasi & Panduan</h6>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-info">
                                        <h6><i class="bi bi-lightbulb me-2"></i>Kapan menggunakan absensi manual?</h6>
                                        <ul class="mb-0">
                                            <li>Scanner barcode tidak berfungsi</li>
                                            <li>Barcode siswa rusak/tidak terbaca</li>
                                            <li>Perbaikan data absensi yang salah</li>
                                            <li>Siswa lupa membawa kartu barcode</li>
                                        </ul>
                                    </div>

                                    <div class="alert alert-warning">
                                        <h6><i class="bi bi-exclamation-triangle me-2"></i>Perhatian!</h6>
                                        <ul class="mb-0">
                                            <li>Pastikan data siswa sudah benar</li>
                                            <li>Periksa kembali waktu absensi</li>
                                            <li>Absensi manual akan tercatat dengan nama Anda</li>
                                            <li>Data tidak dapat diubah setelah disimpan</li>
                                        </ul>
                                    </div>

                                    <div class="mt-4">
                                        <h6><i class="bi bi-clock-history me-2"></i>Statistik Hari Ini</h6>
                                        <div class="row text-center">
                                            <div class="col-6">
                                                <div class="p-3 border rounded">
                                                    <small class="text-muted d-block">Total Siswa</small>
                                                    <h4 class="mb-0">{{ $students->count() }}</h4>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="p-3 border rounded">
                                                    <small class="text-muted d-block">Jam Sekarang</small>
                                                    <h4 class="mb-0" id="currentTime">{{ date('H:i') }}</h4>
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
                            <div class="card border-secondary">
                                <div class="card-header bg-secondary text-white">
                                    <h6 class="mb-0"><i class="bi bi-list-check me-2"></i>Daftar Siswa</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Nama</th>
                                                    <th>NIS</th>
                                                    <th>Kelas</th>
                                                    <th>Jam Masuk</th>
                                                    <th>Jam Pulang</th>
                                                    <th>Status Hari Ini</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($students->take(10) as $student)
                                                @php
                                                    $today = \Carbon\Carbon::today()->toDateString();
                                                    $attendanceToday = $student->attendances->where('tanggal', $today)->first();
                                                @endphp
                                                <tr>
                                                    <td>{{ $student->nama }}</td>
                                                    <td>{{ $student->nis }}</td>
                                                    <td>
                                                        <span class="badge bg-secondary">
                                                            {{ $student->classSchedule->kelas }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-success">
                                                            {{ \Carbon\Carbon::parse($student->classSchedule->jam_masuk)->format('H:i') }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-danger">
                                                            {{ \Carbon\Carbon::parse($student->classSchedule->jam_pulang)->format('H:i') }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        @if($attendanceToday)
                                                            @php
                                                                $statusColors = [
                                                                    'Hadir Masuk' => 'success',
                                                                    'Hadir Pulang' => 'info',
                                                                    'Terlambat' => 'warning',
                                                                    'Izin' => 'primary',
                                                                    'Tidak Hadir' => 'danger'
                                                                ];
                                                                $color = $statusColors[$attendanceToday->status] ?? 'secondary';
                                                            @endphp
                                                            <span class="badge bg-{{ $color }}">
                                                                {{ $attendanceToday->status }}
                                                            </span>
                                                        @else
                                                            <span class="badge bg-secondary">Belum Absen</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="text-center mt-2">
                                        <small class="text-muted">Menampilkan 10 dari {{ $students->count() }} siswa</small>
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

@push('scripts')
<script>
    // Update current time
    function updateTime() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('id-ID', { 
            hour12: false,
            hour: '2-digit',
            minute: '2-digit'
        });
        document.getElementById('currentTime').textContent = timeString;
    }
    setInterval(updateTime, 1000);

    // Auto-select student based on URL parameter
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const studentId = urlParams.get('student_id');
        if (studentId) {
            document.getElementById('student_id').value = studentId;
        }
    });
</script>
@endpush
