@extends('layouts.app')

@section('title', 'Ajukan Izin Siswa')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-dashboard">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-plus-circle me-2"></i>
                    Ajukan Izin Siswa
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
                            <strong>Informasi:</strong> Anda akan mengajukan izin untuk 
                            <strong>{{ $student->nama }}</strong> (Kelas: {{ $student->kelas }}, NIS: {{ $student->nis }})
                        </div>
                    </div>
                </div>

                <form action="{{ route('ortu.permissions.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tanggal" class="form-label">Tanggal Izin <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('tanggal') is-invalid @enderror" 
                                   id="tanggal" name="tanggal" 
                                   value="{{ old('tanggal', date('Y-m-d')) }}"
                                   min="{{ date('Y-m-d') }}" 
                                   max="{{ date('Y-m-d', strtotime('+30 days')) }}"
                                   required>
                            @error('tanggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Tanggal izin tidak boleh lebih dari 30 hari ke depan</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="foto_bukti" class="form-label">Foto Bukti <span class="text-danger">*</span></label>
                            <input type="file" class="form-control @error('foto_bukti') is-invalid @enderror" 
                                   id="foto_bukti" name="foto_bukti" accept="image/*" required>
                            @error('foto_bukti')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Format: JPG, PNG, GIF (maksimal 2MB)</small>
                            
                            <div class="mt-2">
                                <img id="preview" src="#" alt="Preview" class="img-fluid rounded d-none" 
                                     style="max-height: 200px;">
                            </div>
                        </div>

                        <div class="col-12 mb-3">
                            <label for="alasan" class="form-label">Alasan Izin <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('alasan') is-invalid @enderror" 
                                      id="alasan" name="alasan" rows="4" 
                                      placeholder="Tuliskan alasan izin secara lengkap..." 
                                      required>{{ old('alasan') }}</textarea>
                            @error('alasan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Maksimal 500 karakter</small>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card card-dashboard">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="bi bi-check-circle me-2"></i>
                                        Contoh Alasan yang Baik
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <ul class="mb-0">
                                        <li>"Sakit demam tinggi, ada surat dokter terlampir"</li>
                                        <li>"Ada acara keluarga yang tidak bisa ditinggalkan"</li>
                                        <li>"Periksa ke dokter gigi sesuai jadwal"</li>
                                        <li>"Mengikuti lomba/kompetisi sekolah"</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card card-dashboard">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="bi bi-exclamation-triangle me-2"></i>
                                        Contoh Alasan yang Kurang Baik
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <ul class="mb-0">
                                        <li>"Tidak masuk" (terlalu singkat)</li>
                                        <li>"Ada keperluan" (tidak spesifik)</li>
                                        <li>"Sakit" (tanpa keterangan)</li>
                                        <li>"Izin" (tanpa alasan jelas)</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('ortu.permissions.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left me-1"></i> Kembali
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-send me-1"></i> Ajukan Izin
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
                    <p class="mb-2">Sebelum mengajukan izin, pastikan:</p>
                    <ul class="mb-0">
                        <li>Foto bukti jelas dan dapat dibaca</li>
                        <li>Alasan izin ditulis dengan lengkap dan jelas</li>
                        <li>Tanggal izin sesuai dengan kebutuhan</li>
                        <li>Izin akan diverifikasi oleh guru/wali kelas</li>
                        <li>Anda dapat membatalkan izin yang masih "Menunggu"</li>
                        <li>Status verifikasi akan dikirimkan melalui sistem</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Preview foto
    document.getElementById('foto_bukti').addEventListener('change', function(e) {
        const preview = document.getElementById('preview');
        const file = e.target.files[0];
        
        if (file) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('d-none');
            }
            
            reader.readAsDataURL(file);
        } else {
            preview.src = '#';
            preview.classList.add('d-none');
        }
    });

    // Set min date to today
    document.getElementById('tanggal').min = new Date().toISOString().split('T')[0];
    
    // Set max date to 30 days from today
    const maxDate = new Date();
    maxDate.setDate(maxDate.getDate() + 30);
    document.getElementById('tanggal').max = maxDate.toISOString().split('T')[0];
</script>
@endsection
