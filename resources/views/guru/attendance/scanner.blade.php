@extends('layouts.app')

@section('title', 'Scan Absensi')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-qr-code-scan me-2"></i>Scan Barcode Absensi</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6 mb-4">
                            <div class="card border-primary">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="mb-0"><i class="bi bi-camera me-2"></i>Scanner Kamera</h6>
                                </div>
                                <div class="card-body text-center">
                                    <div id="reader" style="width: 100%; min-height: 300px;"></div>
                                    <div class="mt-3">
                                        <button id="startScanner" class="btn btn-success btn-sm">
                                            <i class="bi bi-play-circle me-1"></i> Mulai Scanner
                                        </button>
                                        <button id="stopScanner" class="btn btn-danger btn-sm" disabled>
                                            <i class="bi bi-stop-circle me-1"></i> Stop Scanner
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 mb-4">
                            <div class="card border-info">
                                <div class="card-header bg-info text-white">
                                    <h6 class="mb-0"><i class="bi bi-keyboard me-2"></i>Input Manual</h6>
                                </div>
                                <div class="card-body">
                                    <form id="manualForm">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="barcodeInput" class="form-label">Kode Barcode</label>
                                            <input type="text" class="form-control" id="barcodeInput" 
                                                   placeholder="Masukkan kode barcode siswa" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="bi bi-check-circle me-1"></i> Proses Absensi
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <div class="card border-warning mt-4">
                                <div class="card-header bg-warning text-dark">
                                    <h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>Informasi</h6>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-info mb-2">
                                        <i class="bi bi-clock me-2"></i>
                                        <strong>Jam Sekarang:</strong> <span id="currentTime">{{ now()->format('H:i:s') }}</span>
                                    </div>
                                    <div class="alert alert-light">
                                        <h6><i class="bi bi-lightbulb me-2"></i>Petunjuk:</h6>
                                        <ul class="mb-0">
                                            <li>Arahkan kamera ke barcode siswa</li>
                                            <li>Scanner akan otomatis membaca barcode</li>
                                            <li>Atau masukkan kode barcode secara manual</li>
                                            <li>Sistem akan menentukan absensi masuk/pulang</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card border-success">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0"><i class="bi bi-check-circle me-2"></i>Hasil Scan Terakhir</h6>
                                </div>
                                <div class="card-body">
                                    <div id="resultContainer" class="text-center">
                                        <p class="text-muted mb-0">Belum ada hasil scan</p>
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
<!-- html5-qrcode library -->
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    let html5QrCode;
    let isScanning = false;

    // Update current time
    function updateTime() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('id-ID', { hour12: false });
        document.getElementById('currentTime').textContent = timeString;
    }
    setInterval(updateTime, 1000);

    // Initialize scanner
    document.getElementById('startScanner').addEventListener('click', function() {
        if (!isScanning) {
            html5QrCode = new Html5Qrcode("reader");
            
            const config = { 
                fps: 10, 
                qrbox: { width: 250, height: 250 },
                aspectRatio: 1.0
            };

            html5QrCode.start(
                { facingMode: "environment" },
                config,
                onScanSuccess,
                onScanError
            ).then(() => {
                isScanning = true;
                document.getElementById('startScanner').disabled = true;
                document.getElementById('stopScanner').disabled = false;
                showToast('Scanner berhasil diaktifkan', 'success');
            }).catch(err => {
                console.error(err);
                showToast('Gagal mengaktifkan scanner: ' + err, 'error');
            });
        }
    });

    // Stop scanner
    document.getElementById('stopScanner').addEventListener('click', function() {
        if (isScanning && html5QrCode) {
            html5QrCode.stop().then(() => {
                isScanning = false;
                document.getElementById('startScanner').disabled = false;
                document.getElementById('stopScanner').disabled = true;
                showToast('Scanner dihentikan', 'info');
            }).catch(err => {
                console.error(err);
            });
        }
    });

    // Handle scan success
    function onScanSuccess(decodedText) {
        if (isScanning) {
            html5QrCode.stop();
            isScanning = false;
            document.getElementById('startScanner').disabled = false;
            document.getElementById('stopScanner').disabled = true;
            
            processBarcode(decodedText);
        }
    }

    // Handle scan error
    function onScanError(error) {
        // Optional: handle scan errors
        console.warn(`Scan error: ${error}`);
    }

    // Process barcode (manual or scanned)
    function processBarcode(barcode) {
        const resultContainer = document.getElementById('resultContainer');
        resultContainer.innerHTML = `
            <div class="text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Memproses barcode: <strong>${barcode}</strong></p>
            </div>
        `;

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
                resultContainer.innerHTML = `
                    <div class="alert alert-success">
                        <h5><i class="bi bi-check-circle me-2"></i>${data.message}</h5>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Siswa:</strong> ${data.data.student.nama}</p>
                                <p class="mb-1"><strong>NIS:</strong> ${data.data.student.nis}</p>
                                <p class="mb-1"><strong>Kelas:</strong> ${data.data.student.class_schedule.kelas}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Waktu:</strong> ${data.data.attendance.waktu}</p>
                                <p class="mb-1"><strong>Status:</strong> 
                                    <span class="badge ${data.data.type === 'masuk' ? 'bg-success' : 'bg-info'}">
                                        ${data.data.attendance.status}
                                    </span>
                                </p>
                                <p class="mb-1"><strong>Tipe:</strong> ${data.data.type === 'masuk' ? 'Masuk' : 'Pulang'}</p>
                            </div>
                        </div>
                    </div>
                `;
                
                // Play success sound
                playSound('success');
                
                // Auto-restart scanner after 3 seconds
                setTimeout(() => {
                    if (!isScanning) {
                        document.getElementById('startScanner').click();
                    }
                }, 3000);
            } else {
                resultContainer.innerHTML = `
                    <div class="alert alert-danger">
                        <h5><i class="bi bi-exclamation-triangle me-2"></i>${data.message}</h5>
                        <p class="mb-0">Silakan coba lagi.</p>
                    </div>
                `;
                playSound('error');
                
                // Auto-restart scanner after 3 seconds
                setTimeout(() => {
                    if (!isScanning) {
                        document.getElementById('startScanner').click();
                    }
                }, 3000);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            resultContainer.innerHTML = `
                <div class="alert alert-danger">
                    <h5><i class="bi bi-exclamation-triangle me-2"></i>Terjadi kesalahan</h5>
                    <p class="mb-0">${error.message}</p>
                </div>
            `;
            playSound('error');
        });
    }

    // Handle manual form submission
    document.getElementById('manualForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const barcode = document.getElementById('barcodeInput').value.trim();
        if (barcode) {
            processBarcode(barcode);
            document.getElementById('barcodeInput').value = '';
        }
    });

    // Sound functions
    function playSound(type) {
        const audio = new Audio(type === 'success' 
            ? 'https://assets.mixkit.co/sfx/preview/mixkit-correct-answer-tone-2870.mp3'
            : 'https://assets.mixkit.co/sfx/preview/mixkit-wrong-answer-fail-notification-946.mp3'
        );
        audio.volume = 0.3;
        audio.play().catch(e => console.log('Audio play failed:', e));
    }

    // Toast notification
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

    // Auto-start scanner on page load
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(() => {
            if (!isScanning) {
                document.getElementById('startScanner').click();
            }
        }, 1000);
    });
</script>
@endpush
