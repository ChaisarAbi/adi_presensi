@extends('layouts.app')

@section('title', 'Buat Flag Baru')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-dashboard">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-plus-circle me-2"></i>
                    Buat Flag Baru
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
                            <strong>Informasi:</strong> Anda akan membuat flag untuk 
                            <strong>{{ $student->nama }}</strong> (Kelas: {{ $student->kelas }}, NIS: {{ $student->nis }})
                        </div>
                    </div>
                </div>

                <form action="{{ route('ortu.flags.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="keterangan" class="form-label">Keterangan Flag <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                                      id="keterangan" name="keterangan" rows="5" 
                                      placeholder="Tuliskan keterangan flag secara lengkap dan jelas..." 
                                      required>{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Maksimal 500 karakter. Jelaskan secara detail mengapa Anda membuat flag ini.</small>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card card-dashboard">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="bi bi-check-circle me-2"></i>
                                        Contoh Keterangan yang Baik
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <ul class="mb-0">
                                        <li>"Anak belum pulang padahal sudah jam 16:30, padahal jam pulang 15:00"</li>
                                        <li>"Anak mengeluh sakit perut di sekolah, mohon dipantau"</li>
                                        <li>"Ada kejadian bullying yang dialami anak di sekolah"</li>
                                        <li>"Anak tidak membawa bekal hari ini, mohon bantuan"</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card card-dashboard">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="bi bi-exclamation-triangle me-2"></i>
                                        Contoh Keterangan yang Kurang Baik
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <ul class="mb-0">
                                        <li>"Anak belum pulang" (tanpa keterangan waktu)</li>
                                        <li>"Ada masalah" (tidak spesifik)</li>
                                        <li>"Tolong bantu" (tanpa konteks)</li>
                                        <li>"Flag" (tanpa alasan jelas)</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('ortu.flags.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left me-1"></i> Kembali
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-send me-1"></i> Buat Flag
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
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
                    Informasi Penting
                </h6>
            </div>
            <div class="card-body">
                <div class="alert alert-warning mb-0">
                    <h6 class="alert-heading">Perhatian!</h6>
                    <p class="mb-2">Sebelum membuat flag, pastikan:</p>
                    <ul class="mb-0">
                        <li>Flag hanya untuk keperluan penting dan mendesak</li>
                        <li>Keterangan ditulis dengan lengkap dan jelas</li>
                        <li>Flag akan langsung diterima oleh guru/wali kelas</li>
                        <li>Guru akan menindaklanjuti dalam waktu 1x24 jam</li>
                        <li>Anda dapat membatalkan flag yang masih "Aktif"</li>
                        <li>Guru akan memberikan feedback melalui sistem</li>
                    </ul>
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
                    <i class="bi bi-clock-history me-2"></i>
                    Waktu Respon Guru
                </h6>
            </div>
            <div class="card-body">
                <div class="alert alert-info mb-0">
                    <h6 class="alert-heading">Estimasi Waktu Respon</h6>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-center p-3">
                                <i class="bi bi-exclamation-triangle display-4 text-danger"></i>
                                <h5 class="mt-2">Darurat</h5>
                                <p class="mb-0">1-2 jam</p>
                                <small class="text-muted">(kecelakaan, sakit parah)</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-3">
                                <i class="bi bi-clock display-4 text-warning"></i>
                                <h5 class="mt-2">Penting</h5>
                                <p class="mb-0">4-6 jam</p>
                                <small class="text-muted">(belum pulang, masalah serius)</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-3">
                                <i class="bi bi-info-circle display-4 text-primary"></i>
                                <h5 class="mt-2">Informasi</h5>
                                <p class="mb-0">24 jam</p>
                                <small class="text-muted">(informasi, koordinasi)</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Character counter
    const textarea = document.getElementById('keterangan');
    const counter = document.createElement('small');
    counter.className = 'text-muted d-block mt-1';
    counter.textContent = '0/500 karakter';
    
    textarea.parentNode.appendChild(counter);
    
    textarea.addEventListener('input', function() {
        const length = this.value.length;
        counter.textContent = `${length}/500 karakter`;
        
        if (length > 500) {
            counter.classList.remove('text-muted');
            counter.classList.add('text-danger');
        } else {
            counter.classList.remove('text-danger');
            counter.classList.add('text-muted');
        }
    });
</script>
@endsection
