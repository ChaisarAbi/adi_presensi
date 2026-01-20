@extends('layouts.app')

@section('title', 'Edit Hari Libur')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-dashboard">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-calendar-check me-2"></i>
                    Edit Hari Libur
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.holidays.update', $holiday->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tanggal" class="form-label">Tanggal Libur <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('tanggal') is-invalid @enderror" 
                                       id="tanggal" name="tanggal" 
                                       value="{{ old('tanggal', $holiday->tanggal->format('Y-m-d')) }}" required>
                                @error('tanggal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Pilih tanggal libur</div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="keterangan" class="form-label">Keterangan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('keterangan') is-invalid @enderror" 
                                       id="keterangan" name="keterangan" 
                                       value="{{ old('keterangan', $holiday->keterangan) }}" 
                                       placeholder="Contoh: Libur Nasional Hari Raya Nyepi" required>
                                @error('keterangan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Deskripsi singkat tentang libur</div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info mb-4">
                        <h6 class="alert-heading">Informasi:</h6>
                        <ul class="mb-0">
                            <li>Hari libur akan dikeluarkan dari perhitungan hari sekolah aktif</li>
                            <li>Pastikan tanggal sudah benar</li>
                            <li>Libur berlaku untuk semua kelas</li>
                        </ul>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.holidays.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> Perbarui Hari Libur
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Set min date to today (optional)
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('tanggal').min = today;
    });
</script>
@endpush
