@extends('layouts.app')

@section('title', 'Siswa Belum Pulang')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-exclamation-triangle me-2"></i>Siswa Belum Pulang ({{ \Carbon\Carbon::today()->translatedFormat('d F Y') }})</h5>
                    <div>
                        <a href="{{ route('guru.attendance.scanner') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-qr-code-scan me-1"></i> Scan Absensi
                        </a>
                        <a href="{{ route('guru.attendance.today') }}" class="btn btn-success btn-sm">
                            <i class="bi bi-calendar-check me-1"></i> Absensi Hari Ini
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($students->count() > 0)
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-circle me-2"></i>
                        <strong>Perhatian!</strong> Terdapat {{ $students->count() }} siswa yang belum melakukan absensi pulang.
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Siswa</th>
                                    <th>NIS</th>
                                    <th>Kelas</th>
                                    <th>Jam Masuk</th>
                                    <th>Jam Pulang</th>
                                    <th>Waktu Masuk</th>
                                    <th>Durasi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $student)
                                @php
                                    $attendanceMasuk = $student->attendances->firstWhere('status', 'Hadir Masuk');
                                    $jamMasuk = \Carbon\Carbon::parse($student->classSchedule->jam_masuk);
                                    $jamPulang = \Carbon\Carbon::parse($student->classSchedule->jam_pulang);
                                    $now = \Carbon\Carbon::now();
                                    $waktuMasuk = $attendanceMasuk ? \Carbon\Carbon::parse($attendanceMasuk->waktu) : null;
                                    $durasi = $waktuMasuk ? $now->diff($waktuMasuk)->format('%h jam %i menit') : '-';
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <strong>{{ $student->nama }}</strong>
                                    </td>
                                    <td>{{ $student->nis }}</td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            {{ $student->classSchedule->kelas }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">{{ $jamMasuk->format('H:i') }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-danger">{{ $jamPulang->format('H:i') }}</span>
                                    </td>
                                    <td>
                                        @if($waktuMasuk)
                                            <span class="badge bg-dark">{{ $waktuMasuk->format('H:i') }}</span>
                                        @else
                                            <span class="badge bg-secondary">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($waktuMasuk)
                                            @if($now->greaterThan($jamPulang))
                                                <span class="badge bg-danger">
                                                    {{ $durasi }} <i class="bi bi-clock-history ms-1"></i>
                                                </span>
                                            @else
                                                <span class="badge bg-warning">
                                                    {{ $durasi }}
                                                </span>
                                            @endif
                                        @else
                                            <span class="badge bg-secondary">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" 
                                                onclick="scanStudent('{{ $student->barcode }}')"
                                                data-bs-toggle="tooltip" 
                                                title="Scan absensi pulang">
                                            <i class="bi bi-qr-code"></i> Scan
                                        </button>
                                        <a href="{{ route('admin.students.show', $student->id) }}" 
                                           class="btn btn-sm btn-outline-info"
                                           data-bs-toggle="tooltip"
                                           title="Lihat detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        <div class="card border-warning">
                            <div class="card-header bg-warning text-dark">
                                <h6 class="mb-0"><i class="bi bi-bell me-2"></i>Notifikasi Orang Tua</h6>
                            </div>
                            <div class="card-body">
                                <p class="mb-3">Anda dapat mengirim notifikasi kepada orang tua siswa yang belum pulang:</p>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-warning btn-sm" onclick="notifyAllParents()">
                                        <i class="bi bi-bell me-1"></i> Notifikasi Semua Orang Tua
                                    </button>
                                    <button class="btn btn-danger btn-sm" onclick="flagAllStudents()">
                                        <i class="bi bi-flag me-1"></i> Flag Semua Siswa
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <div class="mb-3">
                            <i class="bi bi-check-circle text-success" style="font-size: 4rem;"></i>
                        </div>
                        <h4 class="text-success">Semua siswa sudah pulang!</h4>
                        <p class="text-muted">Tidak ada siswa yang belum melakukan absensi pulang hari ini.</p>
                        <a href="{{ route('guru.attendance.scanner') }}" class="btn btn-primary mt-3">
                            <i class="bi bi-qr-code-scan me-1"></i> Kembali ke Scanner
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function scanStudent(barcode) {
        // Simulate scanning
        const resultContainer = document.createElement('div');
        resultContainer.innerHTML = `
            <div class="text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Memproses barcode: <strong>${barcode}</strong></p>
            </div>
        `;
        
        // Show modal or toast
        showToast('Memproses absensi pulang...', 'info');
        
        // Send scan request
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
                showToast(data.message, 'success');
                // Reload page after 2 seconds
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            } else {
                showToast(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Terjadi kesalahan', 'error');
        });
    }

    function notifyAllParents() {
        if (confirm('Kirim notifikasi ke semua orang tua siswa yang belum pulang?')) {
            showToast('Mengirim notifikasi...', 'info');
            // Simulate API call
            setTimeout(() => {
                showToast('Notifikasi berhasil dikirim ke orang tua', 'success');
            }, 1500);
        }
    }

    function flagAllStudents() {
        if (confirm('Flag semua siswa yang belum pulang? Ini akan menandai siswa sebagai "perlu perhatian".')) {
            showToast('Memproses flag siswa...', 'info');
            // Simulate API call
            setTimeout(() => {
                showToast('Semua siswa berhasil di-flag', 'success');
            }, 1500);
        }
    }

    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-bg-${type} border-0`;
        toast.setAttribute('role', 'alert');
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;
        
        const container = document.getElementById('toastContainer') || (() => {
            const div = document.createElement('div');
            div.id = 'toastContainer';
            div.className = 'toast-container position-fixed bottom-0 end-0 p-3';
            document.body.appendChild(div);
            return div;
        })();
        
        container.appendChild(toast);
        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();
        
        toast.addEventListener('hidden.bs.toast', () => {
            toast.remove();
        });
    }

    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush
