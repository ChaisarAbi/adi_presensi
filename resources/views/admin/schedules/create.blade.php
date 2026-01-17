@extends('layouts.app')

@section('title', 'Tambah Jadwal Kelas')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-dashboard">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-calendar-plus me-2"></i>
                    Tambah Jadwal Kelas
                </h5>
                <div>
                    <a href="{{ route('admin.schedules.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.schedules.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="kelas" class="form-label">Kelas <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('kelas') is-invalid @enderror" 
                                   id="kelas" name="kelas" value="{{ old('kelas') }}" placeholder="Contoh: Kelas 1, Kelas 2" required>
                            @error('kelas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="jam_masuk" class="form-label">Jam Masuk <span class="text-danger">*</span></label>
                            <input type="time" class="form-control @error('jam_masuk') is-invalid @enderror" 
                                   id="jam_masuk" name="jam_masuk" value="{{ old('jam_masuk') }}" required>
                            @error('jam_masuk')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="jam_pulang" class="form-label">Jam Pulang <span class="text-danger">*</span></label>
                            <input type="time" class="form-control @error('jam_pulang') is-invalid @enderror" 
                                   id="jam_pulang" name="jam_pulang" value="{{ old('jam_pulang') }}" required>
                            @error('jam_pulang')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> Simpan
                        </button>
                        <button type="reset" class="btn btn-secondary">
                            <i class="bi bi-arrow-clockwise me-1"></i> Reset
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
