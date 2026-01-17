@extends('layouts.app')

@section('title', 'Dashboard Guru')

@section('content')
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
                        <div class="text-success fw-bold text-uppercase mb-1">Absensi Masuk</div>
                        <div class="h2 mb-0">{{ $stats['attendance_masuk'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-box-arrow-in-right card-icon text-success"></i>
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
                        <div class="text-info fw-bold text-uppercase mb-1">Absensi Pulang</div>
                        <div class="h2 mb-0">{{ $stats['attendance_pulang'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-box-arrow-right card-icon text-info"></i>
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
                        <div class="text-warning fw-bold text-uppercase mb-1">Total Scan Hari Ini</div>
                        <div class="h2 mb-0">{{ $stats['attendance_today'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-qr-code-scan card-icon text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Scan Barcode Section -->
    <div class="col-lg-5 mb-4">
        <div class="card card-dashboard">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-qr-code-scan me-2"></i>Scan Barcode</h5>
            </div>
            <div class="card-body text-center">
                <div class="mb-4">
                    <div class="scan-placeholder bg-light rounded-3 p-5 mb-3">
                        <i class="bi bi-camera display-1 text-muted"></i>
                        <p class="mt-3 text-muted">Kamera siap untuk scan</p>
                    </div>
                    <a href="{{ route('guru.attendance.scanner') }}" class="btn btn-primary btn-lg w-100 py-3">
                        <i class="bi bi-camera-video me-2"></i> Aktifkan Kamera
                    </a>
                </div>
                <div class="input-group mb-3">
                    <input type="text" class="form-control form-control-lg" id="manualBarcodeInput" placeholder="Masukkan kode barcode manual">
                    <button class="btn btn-success" type="button" id="manualSubmitBtn">
                        <i class="bi bi-check-circle"></i> Submit
                    </button>
                </div>
                <small class="text-muted">Scan barcode siswa untuk absensi masuk/pulang</small>
            </div>
        </div>
    </div>

    <!-- Recent Scans -->
    <div class="col-lg-7 mb-4">
        <div class="card card-dashboard">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Scan Terbaru Anda</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nama Siswa</th>
                                <th>Waktu</th>
                                <th>Status</th>
                                <th>Kelas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recent_scans as $scan)
                            <tr>
                                <td>{{ $scan->student->nama ?? 'N/A' }}</td>
                                <td>{{ \Carbon\Carbon::parse($scan->waktu)->format('H:i') }}</td>
                                <td>
                                    @if($scan->status == 'Hadir Masuk')
                                        <span class="badge bg-success">Masuk</span>
                                    @elseif($scan->status == 'Hadir Pulang')
                                        <span class="badge bg-info">Pulang</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $scan->status }}</span>
                                    @endif
                                </td>
                                <td>{{ $scan->student->classSchedule->kelas ?? 'N/A' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">Belum ada scan hari ini</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Students Belum Pulang -->
    <div class="col-12">
        <div class="card card-dashboard">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-exclamation-triangle me-2"></i>Siswa Belum Pulang</h5>
                <span class="badge bg-warning">{{ $students_belum_pulang->count() }} siswa</span>
            </div>
            <div class="card-body">
                <div class="row">
                    @forelse($students_belum_pulang as $student)
                    <div class="col-md-4 col-6 mb-3">
                        <div class="card border-warning">
                            <div class="card-body">
                                <h6 class="card-title">{{ $student->nama }}</h6>
                                <p class="card-text mb-1">
                                    <small class="text-muted">Kelas: {{ $student->classSchedule->kelas ?? 'N/A' }}</small>
                                </p>
                                <p class="card-text mb-1">
                                    <small class="text-muted">NIS: {{ $student->nis }}</small>
                                </p>
                                @if($student->attendances->where('status', 'Hadir Masuk')->count() > 0)
                                <p class="card-text mb-0">
                                    <small class="text-success">
                                        <i class="bi bi-check-circle me-1"></i>
                                        Sudah masuk: {{ \Carbon\Carbon::parse($student->attendances->first()->waktu)->format('H:i') }}
                                    </small>
                                </p>
                                @else
                                <p class="card-text mb-0">
                                    <small class="text-danger">
                                        <i class="bi bi-x-circle me-1"></i>
                                        Belum masuk
                                    </small>
                                </p>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        <p class="text-center text-muted mb-0">Semua siswa sudah pulang hari ini</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <!-- Quick Actions -->
    <div class="col-12">
        <div class="card card-dashboard">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-lightning me-2"></i>Aksi Cepat</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3 col-6">
                        <a href="{{ route('guru.attendance.scanner') }}" class="btn btn-primary w-100 py-3">
                            <i class="bi bi-qr-code-scan display-6 d-block mb-2"></i>
                            Scan Barcode
                        </a>
                    </div>
                    <div class="col-md-3 col-6">
                        <a href="{{ route('guru.attendance.today') }}" class="btn btn-success w-100 py-3">
                            <i class="bi bi-list-check display-6 d-block mb-2"></i>
                            Daftar Absensi
                        </a>
                    </div>
                    <div class="col-md-3 col-6">
                        <a href="{{ route('guru.permissions.index') }}" class="btn btn-info w-100 py-3">
                            <i class="bi bi-check-circle display-6 d-block mb-2"></i>
                            Verifikasi Izin
                        </a>
                    </div>
                    <div class="col-md-3 col-6">
                        <a href="{{ route('guru.flags.index') }}" class="btn btn-warning w-100 py-3">
                            <i class="bi bi-flag display-6 d-block mb-2"></i>
                            Lihat Flagging
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Handle manual barcode submission on dashboard
    document.addEventListener('DOMContentLoaded', function() {
        const manualSubmitBtn = document.getElementById('manualSubmitBtn');
        const manualBarcodeInput = document.getElementById('manualBarcodeInput');
        
        if (manualSubmitBtn && manualBarcodeInput) {
            manualSubmitBtn.addEventListener('click', function() {
                const barcode = manualBarcodeInput.value.trim();
                
                if (!barcode) {
                    alert('Silakan masukkan kode barcode');
                    return;
                }
                
                // Show loading state
                manualSubmitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Memproses...';
                manualSubmitBtn.disabled = true;
                
                // Send request to scan endpoint
                fetch('{{ route("guru.attendance.scan") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ barcode: barcode })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('✅ ' + data.message);
                        manualBarcodeInput.value = '';
                        
                        // Reload page to update stats
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        alert('❌ ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat memproses barcode');
                })
                .finally(() => {
                    // Reset button state
                    manualSubmitBtn.innerHTML = '<i class="bi bi-check-circle"></i> Submit';
                    manualSubmitBtn.disabled = false;
                });
            });
            
            // Allow Enter key to submit
            manualBarcodeInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    manualSubmitBtn.click();
                }
            });
        }
        
    });
</script>
@endpush
